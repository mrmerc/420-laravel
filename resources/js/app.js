require('./bootstrap');

import Vue from 'vue'

import vuetify from '@/plugins/vuetify'

import Notifications from 'vue-notification'
Vue.use(Notifications)

import { router } from './router'
import store from './store'
import App from '@/components/Layout/App'

Vue.config.productionTip = false

export default new Vue({
    el: '#app',
    router,
    store,
    vuetify,
    render: h => h(App)
})
