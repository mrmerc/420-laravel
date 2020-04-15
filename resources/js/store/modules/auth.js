import * as api from '@/api'
import Vue from '../../app.js'
import { router } from '../../router'

export const state = {
	token: null,
  user: null
}

export const actions = {
	async loginUser ({ commit }, params) {
		try {
			const loginResponse = await api.login(params);
			const loginData = loginResponse.data;
			if (!loginData.access_token) { throw new Error('Token is empty') }
			commit('SET_TOKEN', loginData.access_token);
			localStorage.setItem('token', loginData.access_token);
			Vue.$http.defaults.headers.common['Authorization'] = 'Bearer ' + loginData.access_token;

			const selfResponse = await api.self();
			commit('SET_CURRENT_USER', selfResponse.data);
			localStorage.setItem('user', JSON.stringify(selfResponse.data));

			Vue.$notify({ group: 'api', type: 'success', text: 'Logged in' });
			router.push('/chat');
		} catch (err) {
			Vue.$notify({ group: 'api', type: 'error', text: err.message });
		}
	}
} 

export const mutations = {
	SET_TOKEN(state, token) {
		state.token = token
	},
	SET_CURRENT_USER(state, profile) {
		state.user = profile
	}
}