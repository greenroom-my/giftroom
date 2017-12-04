export default class API {
    constructor() {
        this.basePath = 'http://127.0.0.1:8000/api/';
        this.endpoints = {
            authLogin: 'user/login',
            authRegister: 'user/register',
            roomCreate: 'room',
            roomJoin: 'room/join'
        }
    }

    getEndpointURL(key) {
        return this.basePath + this.endpoints[key];
    }
}