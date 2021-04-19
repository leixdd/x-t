import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        user: {},    
        token: ""
    },

    getters: {
        getUser: state => state.user,
        getAccessToken: state => state.user
    },

    mutations: {
        setUser(state, data) {
            state.user = data;
            console.log(state);
        },
        setAccessToken(state, token) {
            state.token = token
        }
    },

    actions: {
        
    }
    
});


export default store