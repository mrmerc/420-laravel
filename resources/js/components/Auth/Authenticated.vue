<template>
	<div>
		<h1>Authenticated</h1>
	</div>
</template>

<script>
export default {
	name: 'Authenticated',

	async mounted() {
		const hash = window.location.hash.substr(1)
		const decoded = decodeURIComponent(hash.replace(/\+/g, ' '))

		const result = decoded.split('&').reduce(function (result, item) {
				const parts = item.split('=')
				result[parts[0]] = parts[1]
				return result
		}, {})

		const params = {
			access_token: result.access_token,
			provider: JSON.parse(result.state).network
		}

		this.$store.dispatch("loginUser", params);
	},
}
</script>
