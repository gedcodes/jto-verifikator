import { createRouter, createWebHistory } from 'vue-router'
import routes from './routes.js'
import store from '../store/index'

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {

    if (to.meta.requiresAuth && !store?.getters?.is_login) {
        next({ name: "login" })
    } else if (to.meta.isGuest && store?.getters?.is_login) {
        next({ name: "beranda" })
    } else {
        next();
    }

    //console.log(store.getters.user);
    // if (store.getters.user) {
    //     if (to.matched.some(route => route.meta.guard === 'guest')) next({ name: 'home' })
    //     else next();

    // } else {
    //     if (to.matched.some(route => route.meta.guard === 'auth')) next({ name: 'login' })
    //     else next();
    // }



    // if (store.getters.user) {
    //   if (to.name === 'login' || to.name === 'register') next({ name: 'home' })
    //   else next()
    // } else {

    //   if (to.name !== 'login' && to.name !== 'register') next({ name: 'login' })
    //   else next()
    // }
})

export default router;

