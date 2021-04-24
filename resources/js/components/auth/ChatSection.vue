<template>
    <v-app>
        <sidebar></sidebar>
        <v-card outlined>
          <v-card-title>
              <v-icon :color="online_indicator">mdi-circle-medium</v-icon>
              User name {{ chat_user }}
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text class="message-body">
                <div id="messages">
                  <v-list-item
                    v-for="message in messages"
                    :key="message.key"
                    class="mt-5"
                  >
                    <v-list-item-content
                      :class="{ 'green accent-4 message-out': message.user === 'You', 'teal darken-2 message-in': message.user !== 'You' }"
                      class="message white--text pa-sm-2" 
                    >
                      <v-list-item-title 
                        v-text="message.body"
                      > 
                      </v-list-item-title>
                      <p :class="{ 'date-message-out': message.user === 'You', 'date-message-in': message.user !== 'You' }">
                        <span> {{ message.created_at }} </span>
                      </p>
                    </v-list-item-content>
                  </v-list-item> 
                </div>
          </v-card-text>
        </v-card>
        <v-footer
        app
        color="transparent"
        inset
        >
            <v-textarea
              v-model="message"
              background-color="grey lighten-1"
              dense
              flat
              rows="2"
              hide-details
              rounded
              solo
              :append-outer-icon="'mdi-send'"
              @click:append-outer="sendMessage"
            >
                <template v-slot:prepend>
                    <emoji-picker @emoji="insert" :search="search">
                        <div
                        class="emoji-invoker"
                        slot="emoji-invoker"
                        slot-scope="{ events: { click: clickEvent } }"
                        @click.stop="clickEvent"
                        >
                        <v-icon>mdi-emoticon-happy</v-icon>
                        </div>
                        <div slot="emoji-picker" slot-scope="{ emojis, insert, display }">
                        <div class="emoji-picker">
                            <div class="emoji-picker__search">
                            <input type="text" v-model="search" v-focus>
                            </div>
                            <div>
                            <div v-for="(emojiGroup, category) in emojis" :key="category">
                                <h5>{{ category }}</h5>
                                <div class="emojis">
                                <span
                                    v-for="(emoji, emojiName) in emojiGroup"
                                    :key="emojiName"
                                    @click="insert(emoji)"
                                    :title="emojiName"
                                >{{ emoji }}</span>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </emoji-picker>
                </template>
            </v-textarea>
        </v-footer>
    </v-app>
</template>
<script>
  export default {
    data: () => ({
      message: 'Test',
      search: '',
      messages: [
        {
          'key' : 1,
          'body': "test"
        },
        {
          'key' : 2,
          'body': "test12"
        }
      
      ]
    }),

    computed: {
      chat_user() {
        return this.$store.getters.getChatUser;
      },
    },
    mounted(){
      this.$store.dispatch('fetchUser', window.location.href.split('/').pop());
    },
    methods: {
      insert(emoji) {
        this.message += emoji
      },
      sendMessage () {
        this.clearMessage()
      },
      clearMessage () {
        this.message = ''
      },
    },
  }
</script>