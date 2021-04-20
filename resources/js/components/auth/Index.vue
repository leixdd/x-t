<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      color="blue-grey darken-3"
    >

       <v-list-item class="px-2">
        <v-list-item-avatar>
          <v-img src="https://randomuser.me/api/portraits/men/85.jpg"></v-img>
        </v-list-item-avatar>

        <v-list-item-title class="white--text">{{ this.$store.getters.getUser.fullname }}</v-list-item-title>
      </v-list-item>

      <v-divider></v-divider>

      <v-list>
        <v-list-item-content class="aerolink-background-color white--text pl-4">
          <v-list-item-title >Online</v-list-item-title>
        </v-list-item-content>
        <template v-if="online_users.length > 0">
          <v-list-item
            v-for="user in online_users"
            :key="user.id"
            link
            dark
          >
            <v-list-item-content>
              <v-list-item-title><v-icon style="font-size:8px" color="green">mdi-circle</v-icon> {{ user.fullname }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
        <template v-else>
            <v-list-item-content>
              <v-list-item-title class="pl-4 white--text">No Users Online</v-list-item-title>
            </v-list-item-content>
        </template>

        <v-list-item-content class="aerolink-background-color white--text pl-4">
          <v-list-item-title >Offline</v-list-item-title>
        </v-list-item-content>
        <template v-if="offline_users.length > 0">
          <v-list-item
            v-for="user in offline_users"
            :key="user.id"
            link
            dark
          >
            <v-list-item-content>
              <v-list-item-title><v-icon style="font-size:8px" color="#78909C">mdi-circle</v-icon> {{ user.fullname }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
        <template v-else>
            <v-list-item-content>
              <v-list-item-title class="pl-4 white--text">No Users Offline</v-list-item-title>
            </v-list-item-content>
        </template>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app dense flat color="green accent-4" dark>
        <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

        <v-spacer></v-spacer>

        <v-btn
            class="ma-2"
            text
            icon
            @click="logout"
        >
            <v-icon>mdi-logout</v-icon>
        </v-btn>
    </v-app-bar>

    <v-main>
      <chat></chat>
    </v-main>
  </v-app>
</template>

<script>
  export default {
    data: () => ({
      cards: ['Today', 'Yesterday'],
      drawer: null,
      links: [
        ['mdi-inbox-arrow-down', 'Inbox'],
        ['mdi-send', 'Send'],
        ['mdi-delete', 'Trash'],
        ['mdi-alert-octagon', 'Spam'],
      ],
    }),
    computed: {
      online_users() {
        return this.$store.getters.getOnlineUsers;
      },
      offline_users() {
        return this.$store.getters.getOfflineUsers;
      },
    },
    mounted() {
      this.$store.dispatch('fetchOnlineUsers');
      this.$store.dispatch('fetchOfflineUsers');
    },
    methods: {
        logout(){
            axios.post('logout')
            .then((response) => {
                console.log(response);
            })
            .catch((error) => {
            });
        }
    }
  }
</script>
