window.Vue = require('vue').default;

import VueRouter from 'vue-router'

Vue.use(VueRouter)

let routes = [
    {
        path:  '/messages',
        component: require('./components/auth/Index.vue').default
    },

];

const router = new VueRouter({
    mode: 'history',
    routes
});

export default router