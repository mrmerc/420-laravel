<template>
	<notifications group="api">
		<template slot="body" slot-scope="props">
			<v-alert :type="props.item.type" dense>
				<div>{{ props.item.text }}</div>
			</v-alert>
		</template>
	</notifications>
</template>

<script>
export default {
	name: "ApiNotification"
};
</script>