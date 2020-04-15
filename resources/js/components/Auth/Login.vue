<template>
  <v-container fill-height fluid>
    <v-row align="center" justify="center">
			<v-btn
				color="light-green darken-3"
				class="white--text"
				:loading="btnLoading"
				@click.prevent="login"
			>Sign in with Google</v-btn>
    </v-row>
  </v-container>
</template>

<script>
import hello from 'hellojs'
export default {
	name: 'Login',

	data() {
		return {
			google_id: '305503831759-137uno0m9qub0roprbo7maitgg0rio9t.apps.googleusercontent.com',
			network: 'google',
			btnLoading: false
		}
	},

	mounted() {
		hello.init({ google: this.google_id })
	},

	beforeDestroy() {
		this.btnLoading = false;
	},

	methods: {
		async login() {
			try {
				this.btnLoading = true;
				await hello.login(this.network, { 
					display: 'page', 
					scope: 'email', 
					redirect_uri: '/login/callback', 
					page_uri: '' 
				})
			} catch (error) {
				this.$notify({
          group: "api",
          type: "error",
          text: error
        });
			} finally {
				this.btnLoading = false;
			}
		}
	}
}
</script>

<style>

</style>