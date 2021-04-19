<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      color="blue-grey darken-3"
    >
      <v-sheet
        color="blue-grey darken-3"
        class="pa-4"
        dark
      >
        <v-avatar
          class="mb-4"
          color="grey darken-1"
          size="64"
        ></v-avatar>

        <div>john@vuetifyjs.com</div>
      </v-sheet>

      <v-divider></v-divider>

      <v-list>
        <v-list-item
          v-for="n in 5"
          :key="n"
          link
          dark
        >
          <v-list-item-content>
            <v-list-item-title>Item {{ n }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      {{ this.$store.getters.getUser}}
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
    mounted() {
    //  this.user();
    },
    methods: {
        user(){
          axios.get('api/user').then(user  => {
            console.log(user);
          })
        },
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