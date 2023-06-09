import axios from 'axios'
import {UNAUTHORIZED_CODE} from "../constants/constants";



// Public API instance with default options
export const publicApi = axios.create({
    baseURL: `${import.meta.env.VITE_API_URL}v1/`,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
});


// Protected API instance with default options
export const Api = axios.create({
    baseURL: `${import.meta.env.VITE_API_URL}v1/`,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
    withCredentials: true // Send cookies with cross-origin requests
});


Api.interceptors.response.use(
    function (response) {
        return response;
    },
    async function (error) {
        const originalRequest = error.config;
        if (error.response.status === UNAUTHORIZED_CODE && !originalRequest._retry) {
            originalRequest._retry = true;
            try {
                const refresh_token = localStorage.getItem('refresh_token');
                await axios.post(`${import.meta.env.VITE_API_URL}v1/auth/refresh-token`, {refresh_token});
                return Api(originalRequest);
            } catch (error) {
                localStorage.removeItem('logged_in');
                localStorage.removeItem('refresh_token');
                window.location="/signin"
                return Promise.reject(error);
            }
        }
        return Promise.reject(error);
    }
);


