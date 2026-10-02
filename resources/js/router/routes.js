import Settings from '@/js/Views/Settings.vue';
import Profile from '@/js/Views/Profile.vue';
import Password from '@/js/Views/Password.vue';
import Login from '@/js/Views/Login.vue';
import ForgotPassword from '@/js/Views/ForgotPassword.vue';
import ResetPassword from '@/js/Views/ResetPassword.vue';
import VerifyEmail from '@/js/Views/VerifyEmail.vue';
import Register from '@/js/Views/Register.vue';
import Home from '@/js/Views/Home.vue';
import Welcome from '@/js/Views/Welcome.vue';
import NotFound from '@/js/Views/NotFound.vue';

import Capture from '@/js/Views/Capture/CaptureCard.vue';
import Wim from '@/js/Views/Wim/CaptureCard.vue';

import Pelanggaran from '@/js/Views/Pelanggaran/PelanggaranTable.vue';
import Archive from '@/js/Views/Archive/ArchiveTable.vue';
import ArchiveCard from '@/js/Views/Archive/ArchiveCard.vue';
import ArchivePelanggaran from '@/js/Views/ArchivePelanggaran/ArchiveTable.vue';
import Report from '@/js/Views/Laporan/LaporanTable.vue';

import Authenticated from '@/js/layouts/Authenticated';

export default [
    // {
    //     path: '/',
    //     redirect: 'home',
    // },
    {
        path: '/',
        redirect: 'beranda',
        component: Authenticated,
        // name: 'home',
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: "/beranda",
                name: 'beranda',
                component: Home
            },
            {
                path: "/verifikasi",
                name: 'capture',
                component: Capture,
            },
            {
                path: "/wim",
                name: 'wim',
                component: Wim,
            },
            // {
            //     path: "capture/verifikator",
            //     name: 'verifikator',
            //     component: Verifikator,
            // },
            {
                path: "/pelanggaran",
                name: 'pelanggaran',
                component: Pelanggaran,
            },
            {
                path: "/asrip",
                name: 'archive',
                component: Archive,
            },
            {
                path: "/asripverifikasi",
                name: 'arsipverifikasi',
                component: ArchiveCard,
            },
            {
                path: "/arsippelanggaran",
                name: 'arsippelanggaran',
                component: ArchivePelanggaran,
            },
            {
                path: "/report",
                name: 'report',
                component: Report,
            },
            {
                path: "/settings",
                name: 'settings',
                component: Settings,
                children: [
                    {
                        path: 'profile',
                        component: Profile,
                        name: 'profile',
                    },
                    {
                        path: 'password',
                        component: Password,
                        name: 'password',

                    },
                ]
            },
        ]
    },
    {
        path: '/login',
        component: Login,
        name: 'login',
        meta: {
            isGuest: true
        },
        // children: [
        //     { path: "/register", name: 'register', component: Register },
        //     { path: "/login", name: 'login', component: Login },
        //     { path: "/verify-email/:id/:hash", name: 'verify-email', component: VerifyEmail },
        //     { path: "/forgot-password", name: 'forgot-password', component: ForgotPassword },
        //     {
        //         path: "/reset-password/:token",
        //         name: 'reset-password',
        //         props: route => ({
        //             token: route.params.token,
        //             email: route.query.email
        //         }),
        //         component: ResetPassword
        //     }
        // ]
    },
    {
        path: '/forgot-password',
        component: ForgotPassword,
        name: 'forgot-password',
        meta: {
            isGuest: true
        }
    },
    {
        path: '/reset-password/:token',
        props: route => ({
            token: route.params.token,
            email: route.query.email
        }),
        component: ResetPassword,
        name: 'reset-password',
        meta: {
            isGuest: true
        }
    },
    {
        path: '/register',
        component: Register,
        name: 'register',
        meta: {
            isGuest: true
        }
    },
    {
        path: '/verify-email/:id/:hash',
        props: route => ({
            id: route.params.id,
            hash: route.params.hash
        }),
        component: VerifyEmail,
        name: 'verify-email',

    },
    // {
    //     path: '/settings',
    //     component: Settings,
    //     redirect: {
    //         name: 'profile'
    //     },
    //     name: 'settings',
    //     meta: {
    //         guard: 'auth'
    //     },
    //     children: [{
    //         path: 'profile',
    //         component: Profile,
    //         name: 'profile',
    //         meta: {
    //             guard: 'auth'
    //         },

    //     },
    //     {
    //         path: 'password',
    //         component: Password,
    //         name: 'password',
    //         meta: {
    //             guard: 'auth'
    //         },

    //     },
    //     ]
    // },
    {
        path: '/:pathMatch(.*)*',
        name: '404',
        component: NotFound,
    }
];
