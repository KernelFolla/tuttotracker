import React from 'react'
import {Link} from 'react-router'
import {connect} from 'react-redux'
import {logout} from '../actions/user'


const mapStateToProps = (state) => {
    return {
        isLogged: !!state.user.token,
        user: state.user
    };
}

const mapDispatchToProps = (dispatch) => {
    return {logout: () => logout(dispatch)};
}

function menu(isLogged, logout) {
    if (isLogged) {
        return (
            <ul className="list-inline">
                <li>Menu:</li>
                <li><Link to="/">Home</Link></li>
                <li><Link to="/app/tracker">{'Tracker'}</Link></li>
                <li><Link to="/app/admin">{'Admin'}</Link></li>
                <li>
                    <button onClick={() => logout()}>Logout</button>
                </li>
            </ul>
        )
    } else {
        return (
            <ul className="list-inline">
                <li>Menu:</li>
                <li><Link to="/">Home</Link></li>
                <li><Link to="/app/login">Login</Link></li>
                <li><Link to="/app/signup">Signup</Link></li>
            </ul>
        )
    }
}

class AppComponent extends React.Component{
    render() {
        let children = this.props.children;
        let addMenu = menu(
            this.props.isLogged,
            this.props.logout
        );
        return (
            <div>
                <header>
                    {addMenu}
                </header>
                <div style={{ marginTop: '1.5em' }}>{children}</div>
            </div>
        );
    }
}

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(AppComponent)
