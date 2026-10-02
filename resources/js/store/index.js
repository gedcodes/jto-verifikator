import axios from 'axios';
import { createStore } from 'vuex'
import sharedMutations from 'vuex-shared-mutations';
import ApiClient from '../utils/APIClient';
import ApiClientJTO from '../utils/APIClientJTO';

export default createStore({
    state() {
        return {
            user: null,
            sideBarOpen: false,
            is_login: false,
            is_logout: true,
            shift: null,
            regu: null,
            uppkb: null,
        }
    },
    getters: {
        g_sideBarOpen(state) {
            return state.sideBarOpen;
        },
        user(state) {
            return state.user;
        },
        uppkb(state) {
            return state.uppkb;
        },
        shift(state) {
            return state.shift;
        },
        regu(state) {
            return state.regu;
        },
        verified(state) {
            if (state.user) return state.user.email_verified_at
            return null
        },
        id(state) {
            if (state.user) return state.user.id
            return null
        },
        is_login(state) {
            return state.is_login;
        },
        is_logout(state) {
            return state.is_logout;
        },
    },
    mutations: {

        setUser(state, payload) {
            state.user = payload;
        },
        setUppkb(state, payload) {
            state.uppkb = payload;
        },
        setShift(state, payload) {
            state.shift = payload;
        },
        setRegu(state, payload) {
            state.regu = payload;
        },
        setLogin(state, payload) {
            state.is_login = payload;
        },
        setLogout(state, payload) {
            state.is_logout = payload;
        },
        toggleSideBar(state) {
            state.sideBarOpen = !state.sideBarOpen;
        }

    },

    actions: {

        async login({ dispatch }, payload) {
            try {
                //await axios.get('/sanctum/csrf-cookie');

                await axios.post('/api/login', payload).then(async(res) => {
                    localStorage.setItem('tokenJTO', `${res.data.data.token_type} ${res.data.data.tokenJTO}`);
                    localStorage.setItem('token', `${res.data.data.token_type} ${res.data.data.access_token}`);
                    localStorage.setItem('urlJTO', `${res.data.data.urljto}`);
                    localStorage.setItem('pathUrl', `${res.data.data.pathUrl}`);
                    // await dispatch('loginJto', payload);
                    return dispatch('getUser');
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

        async register({ commit }, payload) {
            let res = await axios.post('/api/register', payload)
            if (res.status != 200) throw res
            return res
            // try {

            //     await axios.post('/api/register', payload).then((res) => {
            //         console.log('RES', res.data);
            //         commit(res.data);
            //         // return res.data;  // dispatch('login', { 'email': payload.email, 'password': payload.password })
            //     }).catch((err) => {
            //         throw (err.response)
            //     })
            // } catch (e) {
            //     throw (e)
            // }
        },

        async logout({ commit }) {
            await axios.post('/api/logout').then((res) => {
                localStorage.clear();
                localStorage.removeItem("token");
                localStorage.removeItem("tokenJTO");
                localStorage.removeItem("urlJTO");
                localStorage.removeItem("shift");
                localStorage.removeItem("regu");
                commit('setUser', null);
                commit('setRegu', null);
                commit('setShift', null);
                commit('setLogin', false);
                commit('setLogout', true);
            }).catch((err) => {

            })

        },
        async loginJto({ commit }, payload) {
            await ApiClientJTO.post('/v2pb/login', payload).then((res) => {
                    localStorage.setItem('tokenJTO', `Bearer ${res.data.accessToken}`);
                    // return dispatch('getUser');
                }).catch((err) => {
                    throw err.response
                });
        },
        async getUser({ commit }) {

            await ApiClient.withAuth().then(
                (api) => api.get('/user').then((res) => {
                    commit('setUser', res.data.user);
                    commit('setUppkb', res.data.uppkb);
                    if (res.data.status) {
                        commit('setLogin', true);
                        commit('setLogout', false);
                    } else {
                        commit('setLogin', false);
                        commit('setLogout', true);
                    }
                }).catch((err) => {
                    throw err.response
                })
            );
            // await axios.get('/api/user').then((res) => {
            //     commit('setUser', res.data.user);
            //     commit('isLogin', res.data.status);
            // }).catch((err) => {
            //     throw err.response
            // })
        },
        shift({ commit }, payload) {
            commit('setShift', payload);
        },
        regu({ commit }, payload) {
            commit('setRegu', payload);
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

        }

    },
    plugins: [sharedMutations({ predicate: ['setUser', 'setUppkb', 'setShift', 'setRegu'] })],


})
