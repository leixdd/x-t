<template>
  <v-app>
    <v-navigation-drawer v-if="isUserLoggedIn"
      v-model="drawer"
      app
      color="blue-grey darken-3"
    >

       <v-list-item class="px-2">
        <v-list-item-avatar>
          <v-img src="https://randomuser.me/api/portraits/lego/5.jpg"></v-img>
        </v-list-item-avatar>

        <v-list-item-title class="white--text">{{ this.$store.getters.getUser.fullname }}</v-list-item-title>
      </v-list-item>

      <v-divider></v-divider>

      <v-list>
        <v-list-item-content class="aerolink-background-color white--text pl-4">
          <v-list-item-title >Navigation</v-list-item-title>
        </v-list-item-content>
        <template>
          
          <v-list-item
            link
            dark
            to="/tasks"
            exact
            exact-active-class="activeEx"
            v-if="isUserLoggedIn.role === 1"
          >
            <v-list-item-content>
              <v-list-item-title><v-icon color="#4CAF50">mdi-clipboard-list-outline</v-icon> <strong class="overline">Task Tracker </strong> </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item
            link
            dark
            to="/timeTrack"
            exact
            exact-active-class="activeEx"
            v-if="isUserLoggedIn.role === 1"
          >
            <v-list-item-content>
              <v-list-item-title><v-icon color="#4CAF50">mdi-av-timer</v-icon> <strong class="overline">Time Tracker</strong> </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item
            link
            dark
            to="/admins"
            exact
            exact-active-class="activeEx"
            v-if="isUserLoggedIn.role === 0"
          >
            <v-list-item-content>
              <v-list-item-title><v-icon color="#4CAF50">mdi-account-group</v-icon>&nbsp;<strong class="overline">Admin List</strong> </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item
            link
            dark
            to="/students"
            exact
            exact-active-class="activeEx"
            v-if="isUserLoggedIn.role === 0"
          >
            <v-list-item-content>
              <v-list-item-title><v-icon color="#4CAF50">mdi-account-group-outline</v-icon>&nbsp;<strong class="overline">Student List</strong> </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

        </template>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar v-if="isUserLoggedIn" app dense flat color="green accent-4" dark  >
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
        <router-view></router-view>
    </v-main>
  </v-app>
</template>

<style>

.activeEx {
    color: #4CAF50 !important;
    font-weight: bold !important;
}

</style>
<script>
  export default {
    name: 'Template',
    data: () => ({
      cards: ['Today', 'Yesterday'],
      drawer: null,
      links: [
        ['mdi-inbox-arrow-down', 'Inbox'],
        ['mdi-send', 'Send'],
        ['mdi-delete', 'Trash'],
        ['mdi-alert-octagon', 'Spam'],
      ],
      online_indicator: "blue-grey darken-1",
      getChatUser:""
    }),
    computed: {
      isUserLoggedIn() {
          return this.$store.getters.getUser;
      },
    },
    watch: {
        isUserLoggedIn : function (v) {
          if(v) {
            this.$store.dispatch('fetchUser', window.location.href.split('/').pop());         
          }
        }
    },
    mounted() {
    },
    methods: {
        logout(){
          localStorage.removeItem("token_");
          this.$store.commit("setUser", false);
          this.$router.push({ path: '/' });
        }
    }
  }
</script>
