<template>
    <v-app>
        <p>Sample</p>


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
      message: 'Hey!',
      search: '',
    }),

    computed: {
   
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