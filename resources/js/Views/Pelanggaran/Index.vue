<template>
    <div class="max-w-full w-full mx-auto py-2 px-4 sm:px-6 lg:px-8 mb-3 shadow bg-white">
        <h2 class="text-xl tracking-tight font-bold text-gray-900">Data Pelanggaran</h2>
    </div>
    <div class="max-w-full w-full mx-auto px-0 sm:px-4 lg:px-6 my-2">
        <PelanggaranTable></PelanggaranTable>
    </div>
</template>

<script>

import PelanggaranTable from './PelanggaranTable';
import { SaveIcon, TrashIcon, PencilIcon, PencilAltIcon } from "@heroicons/vue/outline";
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import moment from 'moment';
moment.locale('id');

export default {
    components: {
        PelanggaranTable,
        Datepicker,
        PencilAltIcon,
    },
    data() {
        return {
            error: null,
            date: moment().format("YYYY-MM-DD"),
            isVerif: true,
            loading: true,
        }
    },
}
</script>
