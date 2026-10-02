import axios from 'axios';
import { createStore } from 'vuex'
import sharedMutations from 'vuex-shared-mutations';


export default createStore({
    state: {
        user: {
            data: {},
            token: sessionStorage.getItem("TOKEN"),
        },
        dashboard: {
            loading: false,
            data: {}
        },
        notification: {
            show: false,
            type: 'success',
            message: ''
        }
    },
    getters: {},
    actions: {
        async login({ commit }, payload) {
            try {
                await axios.get('/sanctum/csrf-cookie');

                await axios.post('/api/login', payload).then((res) => {
                    // localStorage.setItem('token', `${res.data.token_type} ${res.data.access_token}`);
                    // return dispatch('getUser');
                    commit('setUser', res.data.user);
                    commit('setToken', `${res.data.token_type} ${res.data.access_token}`)
                }).catch((err) => {
                    throw err.response
                });
                // const res = await axios.post('/api/login', payload);

                // if (res.status != 200) throw res;

                // if (res.data.status_code != 200) throw res.data.message;



            } catch (e) {
                throw e
            }

        },

        async register({ dispatch }, payload) {
            try {

                await axios.post('/api/register', payload).then((res) => {
                    return dispatch('login', { 'email': payload.email, 'password': payload.password })
                    // commit('setUser', data.user);
                    // commit('setToken', data.token)
                }).catch((err) => {
                    throw (err.response)
                })
            } catch (e) {
                throw (e)
            }
        },
        async logout({ commit }) {
            await axios.post('/api/logout').then((res) => {
                commit('setUser', null);
            }).catch((err) => {
                throw (err.response);
            })

        },
        async getUser({ commit }) {
            console.log('GET USER');
            await axios.get('/api/user').then((res) => {
                console.log('USER : ', res.data);
                commit('setUser', res.data);
            }).catch((err) => {
                throw err.response
            })
        },
        async profile({ commit }, payload) {
            await axios.patch('/api/profile', payload).then((res) => {
                commit('setUser', res.data.user);
            }).catch((err) => {
                throw err.response
            })
        },
        async password({ commit }, payload) {
            await axios.patch('/api/password', payload).then((res) => {

            }).catch((err) => {
                throw err.response
            })
        },

        async verifyResend({ dispatch }, payload) {
            let res = await axios.post('/api/verify-resend', payload)
            if (res.status != 200) throw res
            return res
        },
        async verifyEmail({ dispatch }, payload) {
            let res = await axios.post('/api/verify-email/' + payload.id + '/' + payload.hash)
            if (res.status != 200) throw res
            dispatch('getUser')
            return res

        },


    },
    mutations: {
        logout: (state) => {
            state.user.token = null;
            state.user.data = {};
            sessionStorage.removeItem("TOKEN");
        },

        setUser: (state, user) => {
            state.user.data = user;
        },
        setToken: (state, token) => {
            state.user.token = token;
            sessionStorage.setItem('TOKEN', token);
        },
        dashboardLoading: (state, loading) => {
            state.dashboard.loading = loading;
        },
        notify: (state, { message, type }) => {
            state.notification.show = true;
            state.notification.type = type;
            state.notification.message = message;
            setTimeout(() => {
                state.notification.show = false;
            }, 3000)
        },
    },

    modules: {},
    // plugins: [sharedMutations({ predicate: ['setUser'] })],
})
