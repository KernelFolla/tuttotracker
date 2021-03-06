import * as constants from '../constants'
import UserManager from '../service/UserManager'
import {reduceSymfonyErrors} from '../utils'

export function login(data, dispatch) {
    dispatch({
        type: constants.USER_LOGIN_ATTEMPT,
        payload: {
            isFetchingLogin: true
        }
    });
    UserManager.login(
        data.username,
        data.password,
        (response) => dispatch(receiveLogin(response)),
    );
}

export function receiveLogin(response) {
    switch (response.status) {
        case 200:
            localStorage.setItem('auth_token', response.data.token);
            return {
                type: constants.USER_LOGIN_SUCCESS,
                payload: {token: response.data.token}
            };
            break;
        case 401:
            return {
                type: constants.USER_LOGIN_FAILED,
                payload: {error: response.data.message}
            };
            break;
        default:
            return {
                type: constants.USER_LOGIN_FAILED,
                payload: {error: 'Unexpected error'}
            };
            break;
    }
}

export function signup(data, dispatch) {
    dispatch({
        type: constants.USER_SIGNUP_ATTEMPT,
        payload: {
            data: data,
            isFetchingSignup: true
        }
    });
    UserManager.signup(
        data,
        (response) => dispatch(receiveSignup(response, data, dispatch)),
    );
}

export function receiveSignup(response, initialData, dispatch) {
    switch (response.status) {
        case 201:
            dispatch({
                type: constants.USER_SIGNUP_SUCCESS,
                payload: {registrationSuccess: true}
            });

            return (dispatch) => {
                return login({
                    username: initialData.username,
                    password: initialData.plainPassword.first
                }, dispatch);
            }
            break;
        case 400:
            return {
                type: constants.USER_SIGNUP_FAILED,
                payload: {errors: reduceSymfonyErrors(response.data)}
            };
            break;
        default:
            return {
                type: constants.USER_SIGNUP_FAILED,
                payload: {
                    errors: [{
                        key: 'main',
                        message: 'Unexpected error, please retry'
                    }]
                }
            };
            break;
    }
}

export function logout(dispatch) {
    localStorage.clear();
    dispatch({
        type: constants.USER_LOGGED_OUT
    });
}
