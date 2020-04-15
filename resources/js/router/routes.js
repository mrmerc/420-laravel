import Login from '@/components/Auth/Login.vue'
import Authenticated from '@/components/Auth/Authenticated.vue'
import Chat from '@/components/Chat'

export default [
	{
		path: '/login',
		name: 'Login',
		component: Login,
		meta: { guest: true }
	},
	{
		path: '/login/callback',
		name: 'Authenticated',
		component: Authenticated,
		meta: { guest: true }
	},
	{
		path: '/chat',
		name: 'Chat',
		component: Chat
	}
]