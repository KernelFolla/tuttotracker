import React from 'react'
import {Link} from 'react-router'
import {connect} from 'react-redux'
import {logout} from '../actions/user'


const mapStateToProps = (state) => {
    return {
        isLogged: !!state.user.token,
        username: state.user.data ? state.user.data.username : null
    };
}

const mapDispatchToProps = (dispatch) => {
    return {logout: () => logout(dispatch)};
}

function menu(props) {
    if (props.isLogged) {
        return (
            <ul className="list-inline">
                <li>Logged as {props.username}:</li>
                <li><Link to="/">Home</Link></li>
                <li><Link to="/app/tracker">Tracker</Link></li>
                <li>
                    <button onClick={() => props.logout()}>Logout</button>
                </li>
            </ul>
        )
    } else {
        return (
            <ul className="list-inline">
                <li>Anonymous:</li>
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
            this.props
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
