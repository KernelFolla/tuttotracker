import * as constants from '../constants'
import jwt_decode  from 'jwt-decode'

export default function userUpdate(state = {
    token: localStorage.getItem('auth_token')
}, {type, payload}) {
    if (type === constants.USER_LOGIN_ATTEMPT) {
        return Object.assign({}, state, payload);
    } else if (type === constants.USER_LOGIN_SUCCESS) {
        return {
            token: payload.token,
            data: jwt_decode(payload.token)
        };
    } else if (type === constants.USER_LOGIN_FAILED) {
        return {error: payload.error};
    } else if (type === constants.USER_SIGNUP_ATTEMPT) {
        return Object.assign({}, state, payload);
    } else if (type === constants.USER_SIGNUP_SUCCESS) {
        return payload;
    } else if (type === constants.USER_SIGNUP_FAILED) {
        return {errors: payload.errors};
    } else if (type === constants.USER_LOGGED_OUT) {
        return {}
    }
    if (state.token && !state.data) {
        return Object.assign({}, state, {data: jwt_decode(state.token)});
    }
    return state
}
