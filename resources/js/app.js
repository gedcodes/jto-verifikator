require('./bootstrap');

import { Vue, createApp } from 'vue';
import App from '@/js/components/App.vue'
import router from '@/js/router';
import store from '@/js/store/index';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import '../css/_tooltip.scss';
import directives from "./directive";
// import the styles
import 'vue-good-table-next/dist/vue-good-table-next.css'
import 'vue-directive-tooltip/dist/vueDirectiveTooltip.css';
axios.defaults.withCredentials = true;
import VueApexCharts from "vue3-apexcharts";


store.dispatch('getUser').then(() => {
    createApp(App)
        .use(router)
        .use(store)
        .use(directives)
        .use(VueApexCharts)
        .component('EasyDataTable', Vue3EasyDataTable)
        .mount("#app");
});

