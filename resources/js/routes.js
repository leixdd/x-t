window.Vue = require('vue').default;

import VueRouter from 'vue-router'
import store from './store';
Vue.use(VueRouter)

let routes = [

    {
        path:  '/',
        component: require('./components/Login.vue').default,
        meta: {
            needsAuth: false
        }
    },
    {
        path:  '/login',
        component: require('./components/Login.vue').default,
        meta: {
            needsAuth: false
        }
    },
    {
        path:  '/messages',
        component: require('./components/auth/Index.vue').default,
        meta: {
            needsAuth: true
        }
    },

];

const router = new VueRouter({
    mode: 'history',
    routes
});

const reset_token =  () => {
    localStorage.removeItem("token_");
}

router.beforeEach((to, from, next) => {

    let isLoggedIn = false;

    if(localStorage.getItem('token_')) {
        //if token exists, store it in vuex
        //TODO: add vuex here setting the token 
        isLoggedIn = true;
        
    } 


    //if the route has a meta that needs to be auth
    if(to.matched.some(record => record.meta.needsAuth)) {

        if(!isLoggedIn) {
            reset_token();
            next({
                path: 'login' //could be '/'
            })
        } else {

            //You could execute an axios call here to re validate the user with the token attatched to its authorization header.
            //for now i will not re validate. 
            //revalidation prevents tampering of token
            next();
        }
    } else {
        //if the route doesn't need auth, but the user was logged in we will bring the user to default auth page.

        if(isLoggedIn) {
            next({
                path: 'messages' //could be '/'
            })
        }else {
            reset_token();
            next(); // if the user was not logged, just let them to browse the routes that doesn't need auth
        }
    }
})

export default router