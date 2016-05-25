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
        //     statusCode: {
        //         401: (data) => console.log(data.responseJSON),
        //         400: (data) => {
        //             this.errors = reduceSymfonyErrors(data.responseJSON);
        //             console.log(this.errors);
        //             this.setState({'error': true});
        //         },
        //         201: (data) => {
        //             console.log(data)
        //         }
        //     },
        // });
    }

    logout(){
    }
}

export default new UserManager();