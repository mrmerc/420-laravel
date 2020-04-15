import Vue from 'vue'
import Router from 'vue-router'
import routes from './routes'

Vue.use(Router)

export const router = new Router({
	mode: 'history',
	routes: routes,
	scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  },
})

router.beforeEach((to, from, next) => {
	if (to.matched.some(r => !r.meta.guest && !localStorage.getItem('token'))) {
		next('/login');
	} else if (to.matched.some(r => r.meta.guest && localStorage.getItem('token'))) {
		next('/chat');
	} else {
		next();
	}
})