<template>
    <v-app>
    <div class="flex-container">
        <v-container>
            <v-card
                class="mx-auto login-box mt-5"
                outlined
            >
                <v-container>
                    <center>
                      <img src="/images/login.svg" width="50%">
                      <h3>Login</h3>
                    </center>
                    <v-row>
                        <v-col cols="12" sm="12" md="12" lg="12">
                            <form @submit.prevent="login" @keydown="form.onKeydown($event)"> 
                                <v-text-field
                                    v-model="form.username"
                                    color="success"
                                    label="Username"
                                    :class="{ 'is-invalid': form.errors.has('username') }"
                                >
                                </v-text-field>

                                <v-text-field
                                    v-model="form.password"
                                    color="success"
                                    type="password"
                                    label="Password"
                                    :class="{ 'is-invalid': form.errors.has('password') }"
                                ></v-text-field>
                                    
                                <has-error :form="form" field="username" class="red--text"></has-error>
                                <has-error :form="form" field="password" class="red--text"></has-error>
                                <has-error :form="form" field="credentials-failed" class="red--text"></has-error>

                                <br>
                                <v-btn
                                    color="success"
                                    class="mr-4"
                                    block
                                    depressed
                                    :disabled="form.busy"
                                    type="submit" 
                                    >
                                    Login
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
import Vue from 'vue'
import { Form, HasError, AlertError } from 'vform'

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

    export default {
        title: 'Login',
        data: () => ({
            form: new Form({
               username: "",
               password: "",
            })
        }),
        methods: {
            login(){
                this.form.post('api/login')
                .then((response) => {
                 this.$store.commit(
                    "setAccessToken",
                    `Bearer ${response.data.access_token}`
                  );

                  localStorage.setItem(
                    "token_",
                    `Bearer ${response.data.access_token}`
                  );

                  axios.defaults.headers.common[
                    "Authorization"
                  ] = localStorage.getItem("token_");

                 this.$router.push({ path: '/messages' }); 
                // window.location.href = '/messages';
                })
                .catch((error) => {
                    this.form.errors.set('credentials-failed', 'Wrong login credentials')
                });
            }
        }
    }

</script>