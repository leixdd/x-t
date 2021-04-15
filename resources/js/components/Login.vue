<template>
    <v-app>
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
                        <v-col cols="12" sm="12" md="12" lg="12">
                            <form> 
                                <v-text-field
                                    v-model="username"
                                    label="Username"
                                ></v-text-field>
                                <v-text-field
                                    v-model="password"
                                    label="Password"
                                ></v-text-field><br>
                                <v-btn
                                    color="success"
                                    class="mr-4"
                                    block
                                    depressed
                                    >
                                    Submit
                                </v-btn>
                            </form>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-container>
    </div>
    </v-app>
</template>

<script>

    export default {
        title: 'Aerolink | Login',
        data: () => ({
            username: "",
            password: ""
        }),
        methods: {
            submit(){

            }
        }
    }

</script>