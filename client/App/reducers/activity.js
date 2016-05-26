import * as constants from '../constants'

function activityAddUpdate(state = {}, {type, payload}) {
    const initialState = {
        startSuccess: false,
        stopSuccess: false,
        isFetchingStart: false,
        isFetchingStop: false,
        errors: null,
        data: null
    }

    if (type === constants.ACTIVITY_START_ATTEMPT) {
        return Object.assign({}, initialState, payload);
    } else if (type === constants.ACTIVITY_START_SUCCESS) {
        return Object.assign({}, initialState, payload);
    } else if (type === constants.ACTIVITY_START_FAILED) {
        return Object.assign({}, initialState, payload);
    } else if (type === constants.ACTIVITY_STOP_ATTEMPT) {
        return Object.assign({}, initialState, payload);
    } else if (type === constants.ACTIVITY_STOP_SUCCESS) {
        return Object.assign({}, initialState, payload);
    } else if (type === constants.ACTIVITY_STOP_FAILED) {
        return Object.assign({}, initialState, payload);
    }
    return Object.assign({}, initialState);
}

function activityListUpdate(state, {type, payload}) {
    if (type === constants.ACTIVITY_STOP_SUCCESS) {
        let items = state.items.slice();
        items.push(payload.data);
        return Object.assign({}, state, {items: items});
    }

    return state;
}

export default function activity(state = {
    adder: {},
    list: {items: []}
}, action) {
    console.log(state.adder);
    return Object.assign({}, state, {
        adder: activityAddUpdate(state.adder, action),
        list: activityListUpdate(state.list, action)
    });
}