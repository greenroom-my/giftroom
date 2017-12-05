export default class API {
    constructor() {
        this.basePath = 'http://127.0.0.1:8000/api/';
        this.endpoints = {
            authLogin: 'user/login',
            authRegister: 'user/register',
            roomCreate: 'room',
            roomJoin: 'room/join',
            roomInfo: 'room/{name}',
            roomInvite: 'room/{name}/invites',
            wishList: 'room/{name}/my-wish-list',
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