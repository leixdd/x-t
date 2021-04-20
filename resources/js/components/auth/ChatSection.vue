<template>
    <v-app>
        <p>Sample</p>


        <v-footer
        app
        color="transparent"
        inset
        >
        <v-text-field
            v-model="message"
            background-color="grey lighten-1"
            dense
            flat
            hide-details
            rounded
            solo
            :append-outer-icon="'mdi-send'"
            @click:append-outer="sendMessage"
        ></v-text-field>
        </v-footer>
    </v-app>
</template>
<script>
  export default {
    data: () => ({
      message: 'Hey!',
    }),

    computed: {
   
    },

    methods: {
      sendMessage () {
        this.clearMessage()
      },
      clearMessage () {
        this.message = ''
      },

    },
  }
</script>