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
                            <form @submit.prevent="login" @keydown="form.onKeydown($event)"> 
                                <v-text-field
                                    v-model="form.username"
                                    label="Username"
                                    :class="{ 'is-invalid': form.errors.has('username') }"
                                >
                                </v-text-field>

                                <v-text-field
                                    v-model="form.password"
                                    label="Password"
                                    :class="{ 'is-invalid': form.errors.has('password') }"
                                ></v-text-field>
                                    
                                <has-error :form="form" field="username"></has-error>
                                <has-error :form="form" field="password"></has-error>

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
        title: 'Aerolink | Login',
        data: () => ({
            form: new Form({
               username: "",
               password: "",
            })
        }),
        methods: {
            // submit(){
            //     axios.post('login', {
            //         username: this.username,
            //         password: this.password
            //     })
            //     .then((response) => {
            //         console.log(response.errors);
            //     })
            //     .catch((error) => {
            //         //  console.log(error.response.data.errors);
            //         this.response = "";
            //         Object.keys(error.response.data.errors).map((key, index) =>{
            //             this.response += String(error.response.data.errors[key]) + "<br>";
            //         })
            //     });
            // }
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

                if(!response.data.error){
                    this.$router.push({ path: '/messages' }); 
                }
              //   
                 // window.location.href = '/messages';
                })
                .catch((error) => {
                });
            }
        }
    }

</script>