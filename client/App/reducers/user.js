import * as constants from '../constants'

export default function userUpdate(state = {}, {type, payload}) {
    if (type === constants.USER_LOGIN_ATTEMPT) {
        return Object.assign({}, state, payload);
    } else if (type === constants.USER_LOGIN_SUCCESS) {
        return {token: payload.token};
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
    return state
}
