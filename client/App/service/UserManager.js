import { callRest } from '../utils';

class UserManager {
    login(username, password, callback) {
        return callRest({
            method: 'POST',
            url: '/api/login_check',
            data: {
                "_username": username,
                "_password": password
            },
            callback: callback
        });
    }

    signup(data, callback) {
        return callRest({
            method: 'POST',
            url: '/api/v1/users',
            data: data,
            callback: callback
        });
    }
}

export default new UserManager();