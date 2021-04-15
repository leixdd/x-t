<template>
    <div class="login-page flex-container">
        <div class="overlay"></div>
        <v-container>
            <v-card
                class="mx-auto login-box"
                outlined
            >
                <v-container>
                    <center>
                      <img src="/images/aerolink.png">
                      <h3>Aerolink Messenger</h3>
                    </center>
                    <v-row>
                        <v-col sm="12" md="12" lg="12">
                            <form>
                                <v-text-field
                                    v-model="username"
                                ></v-text-field>
                                <v-text-field
                                    v-model="password"
                                ></v-text-field><br>
                                <v-btn @click="submit" color="success" block depressed>Login</v-btn>
                            </form>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-container>
    </div>
</template>

<script>

    export default {
        title: 'Aerolink | Login',
        data: () => ({
            username: "",
            password: ""
        }),
    }

</script>