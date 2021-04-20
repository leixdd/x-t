import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        user: {},    
        token: "",
        online_users: [],
        offline_users: []
    },

    getters: {
        getUser: state => state.user,
        getAccessToken: state => state.user,
        getOnlineUsers: state => state.online_users,
        getOfflineUsers: state => state.offline_users
    },

    mutations: {
        setUser(state, data) {
            state.user = data;
        },
        setAccessToken(state, token) {
            state.token = token
        },
        setOnlineUsers(state, data) {
            state.online_users = data
        },
        setOfflineUsers(state, data) {
            state.offline_users = data
        }
    },

    actions: {
        fetchOnlineUsers({ commit }){
            return axios.get('/api/online-users').then(res  => {
               commit("setOnlineUsers",res.data)
            }).catch(err => {
                console.log(err);
            })
            
        },
        fetchOfflineUsers({ commit }){
            return axios.get('/api/offline-users').then(res  => {
               commit("setOfflineUsers",res.data)
            }).catch(err => {
                console.log(err);
            })
            
        }
    }
    
});


export default store