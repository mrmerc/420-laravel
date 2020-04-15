<template>
	<v-app>
		<!-- <v-navigation-drawer app></v-navigation-drawer> -->

		<!-- <v-app-bar app> </v-app-bar> -->

		<!-- Sizes your content based upon application components -->
		<v-content>
			<ApiNotification />
			<router-view></router-view>
		</v-content>

		<!-- <v-footer app></v-footer> -->
	</v-app>
</template>

<script>
import ApiNotification from "@/components/Notifications/ApiNotification.vue";
export default {
	name: "App",
	components: {
		ApiNotification
	}
};
</script>