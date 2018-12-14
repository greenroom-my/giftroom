export default class API {
    constructor() {
        this.basePath = 'http://127.0.0.1:8000/api/';
        this.endpoints = {
            authLogin: 'user/login',
            authRegister: 'user/register',
            roomCreate: 'room',
            roomInfo: 'room/{name}',
            roomInvite: 'room/{name}/invites',
            roomJoin: 'room/{name}/join',
            roomMatch: 'room/{name}/match',
            wishList: 'room/{name}/my-wish-list',
            match: 'room/{name}/my-match',
        }
    }

    getEndpointURL(key, params = null) {
        let url = this.basePath + this.endpoints[key];
        if (params && Array.isArray(params))
            for (let param of params) {
                let key = Object.keys(param)[0];
                let searchString = `{${key}}`;
                url = url.replace(searchString, param[key]);
            }
        return url;
    }
}