import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)
import cookies from 'browser-cookies';

const authToken = localStorage.getItem('token');
if (authToken) {
	Vue.axios.defaults.headers.common['Authorization'] = 'Bearer ' + authToken;
}

Vue.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
Vue.axios.defaults.headers.common['X-CSRF-TOKEN'] = cookies.get('XSRF-TOKEN');

const API_URL_V1 = '/api/v1'

export function login(params) {
	return Vue.axios.post(`${ API_URL_V1 }/login/google`, params);
}

export function self() {
	return Vue.axios.get(`${ API_URL_V1 }/user/self`);
}