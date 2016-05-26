import * as constants from '../constants'
import ActivityManager from '../service/ActivityManager'
import {reduceSymfonyErrors} from '../utils'

export function stop(id, dispatch) {
    dispatch({
        type: constants.ACTIVITY_STOP_ATTEMPT,
        payload: {
            isFetchingStop: true
        }
    });
    ActivityManager.stop(
        id,
        (response) => dispatch(receiveStop(response)),
    );
}

function receiveStop(response) {
    switch (response.status) {
        case 200:
            return {
                type: constants.ACTIVITY_STOP_SUCCESS,
                payload: {stopSuccess: true, data: response.data}
            };
        case 401:
            return {
                type: constants.ACTIVITY_STOP_FAILED,
                payload: {errors: response.data.message}
            };
        default:
            return {
                type: constants.ACTIVITY_STOP_FAILED,
                payload: {error: 'Unexpected error'}
            };
    }
}

export function start(data, dispatch) {
    dispatch({
        type: constants.ACTIVITY_START_ATTEMPT,
        payload: {
            data: data,
            isFetchingStart: true
        }
    });
    ActivityManager.post(
        data,
        (response) => dispatch(receiveStart(response)),
    );
}

function receiveStart(response) {
    switch (response.status) {
        case 201:
            return {
                type: constants.ACTIVITY_START_SUCCESS,
                payload: {startSuccess: true, data: response.data}
            };
        case 400:
            return {
                type: constants.ACTIVITY_START_FAILED,
                payload: {errors: reduceSymfonyErrors(response.data)}
            };
        default:
            return {
                type: constants.ACTIVITY_START_FAILED,
                payload: {errors: 'Unexpected error'}
            };
    }
}