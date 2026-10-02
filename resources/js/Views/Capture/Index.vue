<template>
    <div>fsfdfdfdfenfm</div>
</template>

<script>

import CaptureCard from './CaptureCard';
import { SaveIcon, TrashIcon, PencilIcon, PencilAltIcon } from "@heroicons/vue/outline";
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import moment from 'moment';
import { isNull } from 'lodash';
moment.locale('id');

export default {
    components: {
        Datepicker,
        PencilAltIcon,
        CaptureCard
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
