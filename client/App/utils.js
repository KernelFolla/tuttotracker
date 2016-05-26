import reqwest from 'reqwest';

export function reduceSymfonyErrors(response) {
    let base = [{
        key: 'main',
        message: response.message
    }];
    return reduceSymfonyErrorsInner(response.errors, base)
}

function reduceSymfonyErrorsInner(node, ret = [], append = '') {
    let c = node.children;
    Object.keys(c).forEach((key) => {
        if (c[key].children)
            reduceSymfonyErrorsInner(c[key], ret, key + '.');
        else if (c[key].errors) {
            c[key].errors.forEach((message) => {
                ret.push({
                    key: append + key,
                    message: message
                })
            });
        }
    });
    return ret;
}

function processResponse(resp) {
    let data;
    try {
        data = JSON.parse(resp.responseText);
    } catch (e) {
        data = false;
    }

    return {
        request: resp,
        status: resp.status,
        data: data
    };
}

export function callRest(data, auth) {
    let config = {
        url: data.url,
        type: 'json',
        method: data.method,
        contentType: 'application/json',
        data: JSON.stringify(data.data),
    }
    if (auth) {
        config.headers = {
            Authorization: 'Bearer ' + localStorage.getItem('auth_token')
        }
    }
    // console.log(config);
    try {
        let r = reqwest(config).always(() => data.callback(processResponse(r.request)));
    } catch (e) {
        //console.log(e);
    }
}