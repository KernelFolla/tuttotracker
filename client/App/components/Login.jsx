import React, {Component, PropTypes} from 'react'
import {routerActions} from 'react-router-redux'
import {connect} from 'react-redux'
import {Link, browserHistory} from 'react-router'
import {login} from '../actions/user'

const mapStateToProps = (state) => {
    return {user: state.user};
}

const mapDispatchToProps = (dispatch) => {
    return {login: (x) => login(x, dispatch)};
}


class LoginContainer extends Component {
    onClick = (e) => {
        e.preventDefault();
        this.props.login({
            username: this.refs.username.value,
            password: this.refs.password.value
        });
    }


    renderErrors = () => {
        if (this.props.user.error)
            return (
                <div className="alert alert-danger">
                    {this.props.user.error}
                </div>
            );
        else
            return ('');
    }

    render() {
        const label = this.props.user.isFetchingLogin ? 'Loading...' : 'Login';
        return (
            <div className="container">
                <div className="row">
                    <div className="col-sm-6 col-md-4 col-md-offset-4">
                        <h1 className="text-center login-title">Login to continue</h1>
                        <div className="account-wall">
                            <form className="form-signin">
                                <input ref="username" type="text" className="form-control" placeholder="Username"
                                       required autofocus/>
                                <input ref="password" type="password" className="form-control" placeholder="Password"
                                       required/>
                                <button onClick={this.onClick} className="btn btn-lg btn-primary btn-block">
                                    {label}
                                </button>
                                {this.renderErrors()}
                                <span className="clearfix"></span>
                            </form>
                        </div>
                        <Link to="/app/signup" className="text-center new-account">Create an account</Link>
                    </div>
                </div>
            </div>
        )
    }
}

const Login = connect(
    mapStateToProps,
    mapDispatchToProps
)(LoginContainer)

export default Login
