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
        path:  '/home',
        component: require('./components/auth/Index.vue').default,
        meta: {
            needsAuth: true
        }
    },
    {
        path:  '/tasks',
        component: require('./components/auth/Tasks.vue').default,
        meta: {
            needsAuth: true
        }
    },
    {
        path:  '/timeTrack',
        component: require('./components/auth/TimeTrack.vue').default,
        meta: {
            needsAuth: true
        }
    },
    {
        path:  '/students',
        component: require('./components/auth/UserStudent.vue').default,
        meta: {
            needsAuth: true
        }
    },
    {
        path:  '/admins',
        component: require('./components/auth/UserAdmin.vue').default,
        meta: {
            needsAuth: true
        }
    }

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
    store.commit("setUser", false);

    if(localStorage.getItem('token_')) {
        //if token exists, store it in vuex
        //TODO: add vuex here setting the token 
        isLoggedIn = true;
        
    } 


    //if the route has a meta that needs to be auth
    if(to.matched.some(record => record.meta.needsAuth)) {

        if(!isLoggedIn) {
            reset_token();
            store.commit("setUser", false);
            next({
                path: 'login' //could be '/'
            })
        } else {

            //You could execute an axios call here to re validate the user with the token attatched to its authorization header.
            //for now i will not re validate. 
            //revalidation prevents tampering of token
            axios.get('/api/user-data').catch(err => {
                reset_token();
                store.commit("setUser", false);
                next({
                    path: 'login' //could be '/'
                })
            }).then(response => {
                store.commit("setUser", response.data);
            });
            next();
        }
    } else {
        //if the route doesn't need auth, but the user was logged in we will bring the user to default auth page.

        if(isLoggedIn) {
            
            axios.get('/api/user-data').catch(err => {
                reset_token();
                store.commit("setUser", false);
                next({
                    path: 'login' //could be '/'
                })
            }).then(response => {
                store.commit("setUser", response.data);
            });
            next({
                path: 'home' //could be '/'
            })
        }else {
            reset_token();
            store.commit("setUser", false);
            next(); // if the user was not logged, just let them to browse the routes that doesn't need auth
        }
    }
})

export default router