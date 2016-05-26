import React, {Component, PropTypes} from 'react'
import {routerActions} from 'react-router-redux'
import {Link} from 'react-router'
import {connect} from 'react-redux'
import {signup, login} from '../actions/user'

const mapStateToProps = (state) => {
    return {user: state.user};
}

const mapDispatchToProps = (dispatch) => {
    return {
        signup: (x) => signup(x, dispatch),
        login: (x) => login(x, dispatch)
    };
}


class SignupContainer extends Component {
    onClick = (e) => {
        e.preventDefault();
        this.props.signup({
            username: this.refs.username.value,
            email: this.refs.email.value,
            plainPassword: {
                first: this.refs.password1.value,
                second: this.refs.password2.value,
            }
        });
    }


    renderErrors = (fieldName) => {
        let ret = this.props.user.errors;
        if (!ret) {
            return;
        }
        let i = 0;
        ret = ret.reduce(
            function (ret, item) {
                if (item.key == fieldName) {
                    ret.push(<li key={i}>{item.message}</li>);
                    i++;
                }
                return ret;
            }, []
        );
        if (ret.length)
            return (
                <div className="alert alert-danger">
                    <ul>{ret}</ul>
                </div>
            );
        else
            return (<span/>);
    }

    render() {
        const label = (this.props.user.isFetchingSignup ? 'Loading...' :
            (this.props.user.registrationSuccess ? 'Logging in...' : 'Sign up'));

        return (
            <div className="container">
                <div className="row">
                    <div className="col-sm-6 col-md-4 col-md-offset-4">
                        <h1 className="text-center login-title">Sign Up</h1>
                        <div className="account-wall">
                            <form className="form-signup">
                                {this.renderErrors('main')}
                                <input ref="username" type="text" className="form-control" placeholder="Username"
                                       required autofocus/>
                                {this.renderErrors('username')}

                                <input ref="email" type="email" className="form-control" placeholder="E-Mail"
                                       required/>
                                {this.renderErrors('email')}

                                <input ref="password1" type="password" className="form-control" placeholder="Password"
                                       required/>
                                {this.renderErrors('plainPassword')}
                                {this.renderErrors('plainPassword.first')}

                                <input ref="password2" type="password" className="form-control"
                                       placeholder="Repeat Password"
                                       required/>
                                {this.renderErrors('plainPassword.second')}

                                <button onClick={this.onClick} className="btn btn-lg btn-primary btn-block">
                                    {label}
                                </button>
                                <span className="clearfix"></span>
                            </form>
                        </div>
                        <Link to="/app/login" className="text-center new-account">Have an existing account?</Link>
                    </div>
                </div>
            </div>
        )
    }
}


const Signup = connect(
    mapStateToProps,
    mapDispatchToProps
)(SignupContainer)

export default Signup
