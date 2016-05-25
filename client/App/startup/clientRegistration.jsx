require('../../sass/layout.scss');

import { createDevTools } from 'redux-devtools'
import LogMonitor from 'redux-devtools-log-monitor'
import DockMonitor from 'redux-devtools-dock-monitor'

import React from 'react'
import ReactDOM from 'react-dom'
import { createStore, combineReducers, applyMiddleware, compose } from 'redux'
import { Provider } from 'react-redux'
import { Router, Route, IndexRoute, browserHistory } from 'react-router'
import { routerReducer, syncHistoryWithStore, routerActions, routerMiddleware } from 'react-router-redux'
import { UserAuthWrapper } from 'redux-auth-wrapper'
import ReduxThunk from 'redux-thunk'

import * as reducers from '../reducers'
import { App, Home, Tracker, Admin, Login, Signup } from '../components'

const baseHistory = browserHistory
const routingMiddleware = routerMiddleware(baseHistory)
const reducer = combineReducers(Object.assign({}, reducers, {
    routing: routerReducer
}))

const DevTools = createDevTools(
    <DockMonitor toggleVisibilityKey="ctrl-h"
                 changePositionKey="ctrl-q">
        <LogMonitor theme="tomorrow" />
    </DockMonitor>
)

const enhancer = compose(
    applyMiddleware(routingMiddleware, ReduxThunk),
    DevTools.instrument()
)

const store = createStore(reducer, enhancer)
const history = syncHistoryWithStore(baseHistory, store)

const UserIsAuthenticated = UserAuthWrapper({
    authSelector: state => state.user,
    redirectAction: routerActions.replace,
    wrapperDisplayName: 'UserIsAuthenticated',
    predicate: user => user.token,
    failureRedirectPath: '/app/login',
})

const UserIsAnonymous = UserAuthWrapper({
    authSelector: state => state.user,
    redirectAction: routerActions.replace,
    wrapperDisplayName: 'UserIsAnonymous',
    predicate: user => !user.token,
    failureRedirectPath: '/app/tracker',
})

const UserIsAdmin = UserAuthWrapper({
    authSelector: state => state.user,
    redirectAction: routerActions.replace,
    failureRedirectPath: '/',
    wrapperDisplayName: 'UserIsAdmin',
    predicate: user => user.isAdmin,
    allowRedirectBack: false
})

ReactDOM.render(
    <Provider store={store}>
        <div>
            <Router history={history}>
                <Route path="/" component={App}>
                    <IndexRoute component={Home}/>
                    <Route path="app/login" component={UserIsAnonymous(Login)}/>
                    <Route path="app/signup" component={UserIsAnonymous(Signup)}/>
                    <Route path="app/tracker" component={UserIsAuthenticated(Tracker)}/>
                    <Route path="app/admin" component={UserIsAuthenticated(UserIsAdmin(Admin))}/>
                </Route>
            </Router>
            <DevTools />
        </div>
    </Provider>,
    document.getElementById('mount')
)

