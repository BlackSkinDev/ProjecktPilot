import {Api,publicApi} from "./Api";


const ENDPOINT = 'auth';
export default {

    register(userData){
        return publicApi.post(`${ENDPOINT}/register`,userData)
    },
    login(credentials){
        return publicApi.post(`${ENDPOINT}/login`,credentials)
    },
    logout(){
        return Api.post(`${ENDPOINT}/logout`)
    },
    getAuthUser(){
        return Api.get(`${ENDPOINT}/user`)
    },





}
