import {callRest} from '../utils';

class ActivityManager {
    post(data, callback) {
        return callRest({
            method: 'POST',
            url: '/api/v1/activities',
            data: data,
            callback: callback
        },true);
    }

    stop(id, callback) {
        return callRest({
            method: 'PATCH',
            url: '/api/v1/activities/' + id + '/stop',
            callback: callback
        },true);
    }

    get(id, callback) {
        return callRest({
            method: 'GET',
            url: '/api/v1/activities/'+id,
            callback: callback
        },true);
    }
}

export default new ActivityManager();