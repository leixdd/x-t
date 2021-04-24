
window.Vue = require('vue').default;

Vue.component('app-template', require('./components/auth/Template.vue').default);
Vue.component('chat', require('./components/auth/ChatSection.vue').default);
//Vue.component('sidebar', require('./components/auth/Sidebar.vue').default);