<template>
    <div>
        <div class="max-w-full w-full mx-auto py-2 px-4 sm:px-6 lg:px-8 mb-3 shadow bg-white">
            <h2 class="text-xl tracking-tight font-bold text-gray-900">Data WIM</h2>
        </div>
        <div class="max-w-full w-full mx-auto px-0 sm:px-4 lg:px-6 my-2">
            <div class="bg-white shadow-md rounded-t-md mt-2">
                <div class="max-w-full mx-auto px-2 py-2">
                    <div class="flex flex-row space-x-2">
                        <filter-icon class="h-5 w-5" />
                        <div class="font-bold text-base">
                            Filter Data
                        </div>
                    </div>
                    <Form @submit="onFilter">
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <m-select-2 v-model="interval" :selectedField="interval" name="interval"
                                    :options="optionInterval" labelTitle="Interval" placeholder="Pilih Interval"
                                    inputInfo="Pilih Interval" inputTextSize="sm" />
                            </div>
                            <div class="flex items-center pl-4 border border-gray-200 rounded h-11 mt-5">
                                <input id="auto" type="checkbox" name="auto" v-model="auto"
                                    class="w-4 h-4 text-blue-600 bg-gray-200 border-gray-400 focus:ring-blue-500 focus:ring-2">
                                <label for="bordered-radio-1"
                                    class="w-full py-4 ml-2 text-sm font-medium text-gray-700">Reload Data Otomatis</label>
                            </div>
                            <div v-show="isJam" class="col-span-2 grid grid-cols-3 gap-4">
                                <div>
                                    <Field name="tgl_dari" id="tgl_dari" v-model="tgl_dari">
                                        <label class="form-label block mb-1 text-gray-600 font-poppins text-sm">
                                            <span class="ml-1">Tanggal</span>
                                        </label>
                                        <Datepicker v-model="tgl_dari" name="tgl_dari" format="dd-MM-yyyy"
                                            model-type="yyyy-MM-dd" :max-date="dateNow" autoApply />
                                    </Field>
                                </div>
                                <div>
                                    <Field name="jam_dari" id="jam_dari" v-model="jam_dari">
                                        <label class="form-label block mb-1 text-gray-600 font-poppins text-sm">
                                            <span class="ml-1">Jam Dari</span>
                                        </label>
                                        <Datepicker v-model="jam_dari" time-picker />
                                    </Field>
                                </div>
                                <div>
                                    <Field name="jam_sampai" id="jam_sampai" v-model="jam_sampai">
                                        <label class="form-label block mb-1 text-gray-600 font-poppins text-sm">
                                            <span class="ml-1">Jam Dari</span>
                                        </label>
                                        <Datepicker v-model="jam_sampai" time-picker />
                                    </Field>
                                </div>
                            </div>
                            <div v-show="isTanggal" class="col-span-2 grid grid-cols-2 gap-4">
                                <div>
                                    <Field name="tgl_dari" id="tgl_dari" v-model="tgl_dari">
                                        <label class="form-label block mb-1 text-gray-600 font-poppins text-sm">
                                            <span class="ml-1">Tanggal Dari</span>
                                        </label>
                                        <Datepicker v-model="tgl_dari" name="tgl_dari" format="dd-MM-yyyy"
                                            model-type="yyyy-MM-dd" :max-date="tgl_sampai" autoApply />
                                    </Field>
                                </div>
                                <div>
                                    <Field name="tgl_sampai" id="tgl_sampai" v-model="tgl_sampai">
                                        <label class="form-label block mb-1 text-gray-600 font-poppins text-sm">
                                            <span class="ml-1">Tanggal Sampai</span>
                                        </label>
                                        <Datepicker v-model="tgl_sampai" name="tgl_sampai" format="dd-MM-yyyy"
                                            model-type="yyyy-MM-dd" :max-date="new Date()" :min-date="tgl_dari" autoApply />
                                    </Field>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 mb-2">

                            <div class="md:flex justify-between">
                                <div class="flex flex-row space-x-4 items-end mb-2 md:mb-0">
                                </div>
                                <div class="flex flex-row space-x-2 justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center py-2 px-4 text-white bg-blue-600 rounded-md hover:bg-blue-500 shadow hover:shadow-lg focus:outline-none focus:shadow-outline cursor-pointer">
                                        <filter-icon class="h-5 w-5 mr-1" /> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </Form>
                </div>
            </div>
            <div class="block overflow-y-auto h-[600px] mt-1">
                <div v-if="loading" class="flex justify-center items-center my-10 rounded-lg text-gray-800 bg-white">
                    <circle-svg stroke="#0071fe" class="w-10 h-10" />
                </div>
                <div v-else>
                    <div v-if="items.length > 0" class="flex flex-row flex-wrap">

                        <div v-for="(items, index) in items" :key="index"
                            class="basis-1/2 md:basis-1/4 lg:basis-1/5 p-2 mb-1">
                            <div v-if="items.is_status == 1" class="border-b-4 border-green-500">
                                <img :src="items.foto_depan_url" alt="image" class="h-[170px] w-full object-fill">
                            </div>
                            <div v-else-if="items.is_status == 2" class="border-b-4 border-blue-500">
                                <img :src="items.foto_depan_url" alt="image" class="h-[170px] w-full object-fill">
                            </div>
                            <div v-else class="border-b-4 border-red-500">
                                <img :src="items.foto_depan_url" alt="image" class="h-[170px] w-full object-fill">
                            </div>
                            <div class="flex flex-wrap justify-between mt-1">
                                <div class="text-sm sm:text-sm text-gray-600">
                                    {{ formatDate(items.tgl_penimbangan) }}
                                </div>
                                <div class="text-sm sm:text-sm text-gray-600 font-semibold">
                                    WIM
                                </div>
                            </div>
                            <div class="text-sm sm:text-base text-gray-600 font-semibold mb-1">
                                {{ items.no_kendaraan }}
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <!-- <input class="w-5 h-5 cursor-pointer" type="checkbox" :checked="true" :value="items"
                                        v-model="itemsSelected" @click="selected(items)"> Pilih -->
                                </div>
                                <Image :src="items.foto_plat_no_url" classes="w-full h-full" height="h-14" />
                            </div>

                        </div>
                        <div v-if="loadingSelected"
                            class="flex justify-center items-center ml-4 mt-2 text-gray-600 bg-white w-52 h-[170px]">
                            <circle-svg stroke="#0071fe" class="w-10 h-10" />
                        </div>
                    </div>
                    <div v-else class="flex justify-center mt-10">
                        Tidak Ada Data
                    </div>
                </div>
            </div>
            <page class="border-t" :total-pages="totalPages" :total="total" :per-page="perPage" :current-page="currentPage"
                :has-more-pages="hasMorePages" @pagechanged="showMore">
                <template #rows>
                    <div class="flex flex-row items-center">
                        <label class="mr-2 text-sm">Rows Per Page</label>
                        <v-select v-model="perPage" :options="optionLimit" :reduce="(option) => option.value"
                            :clearable="false" :filterable="false"
                            class="h-full w-20 bg-white border shadow-sm placeholder-slate-400 block rounded-md text-sm">
                        </v-select>
                    </div>
                </template>
            </page>
            <Gambar v-show="visible" @close="onHide">
                <template v-slot:gambar>
                    <zoom-on-hover :img-normal="imageUrl" :img-zoom="imageUrl" :scale="5"></zoom-on-hover>
                </template>
            </Gambar>
        </div>
    </div>
</template>

<script>

import { ref } from "vue";
import { Field, Form, useForm } from "vee-validate";
import * as Yup from "yup";
import { SaveIcon, TrashIcon, ArchiveIcon, PencilAltIcon, SearchIcon, XIcon, FilterIcon, ArrowsExpandIcon } from "@heroicons/vue/outline";
import Datepicker from '@vuepic/vue-datepicker';
import ZoomOnHover from "../../components/lightbox/zoomOnHover";
import '@vuepic/vue-datepicker/dist/main.css';
import moment from 'moment';
import { isNull, values } from 'lodash';
moment.locale('id');
import CaptureService from "../../service/CaptureService";
import WimService from "../../service/WimService";
import ShiftService from "../../service/ShiftService";
import ReguService from "../../service/ReguService";
import PasalService from "../../service/PasalService";
import JenisPelanggaranService from '../../service/JenisPelanggaranService';
import KendaraanService from '../../service/KendaraanService';
import PelanggaranService from '../../service/PelanggaranService';
import MSelect2 from '../../components/form/MSelect2.vue';
import Gambar from '../../components/lightbox/Gambar';
// import Verifikator from './Verifikator';
import ConfirmDialog from "@/js/components/confirm/ConfirmDialog.vue";
import PrintDialog from "@/js/components/confirm/PrintDialog.vue";
import AppIcon from "@/js/components/icon/AppIcon.vue";
import CircleSvg from "@/js/components/CircleSvg.vue";
import Errors from "@/js/components/Errors.vue";
import Alert from "@/js/components/Alert.vue";
import MButton from "@/js/components/form/MButton.vue";
import MInput from "@/js/components/form/MInput";
import MToggle from "@/js/components/form/MToggle.vue";
import Toggle from "@/js/components/button/Toggle";
import DeviceSelect from "@/js/components/form/DeviceSelect";
import { VueperSlides, VueperSlide } from 'vueperslides';
import 'vueperslides/dist/vueperslides.css';
import Page from '../../components/Page.vue';
import VSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import Image from "../../components/Image.vue";
import Print from '../../components/Print';

export default {
    components: {
        Datepicker,
        PencilAltIcon,
        SearchIcon,
        TrashIcon,
        FilterIcon,
        XIcon,
        ArrowsExpandIcon,
        ArchiveIcon,
        ZoomOnHover,
        MSelect2,
        Gambar,
        ZoomOnHover,
        ConfirmDialog,
        PrintDialog,
        AppIcon,
        CircleSvg,
        Errors,
        Alert,
        MButton,
        MInput,
        MToggle,
        Form,
        Toggle,
        SaveIcon,
        Field,
        VueperSlides,
        VueperSlide,
        Page,
        VSelect,
        DeviceSelect,
        Image,
    },
    data() {
        return {
            error: null,
            errors: null,
            success: false,
            regSuccess: null,
            busy: false,
            errArr: [],
            date: moment().format("YYYY-MM-DD"),
            tgl_dari: moment().format("YYYY-MM-DD"),
            tgl_sampai: moment().format("YYYY-MM-DD"),
            jam_dari: ref({
                hours: new Date().getHours(),
                minutes: new Date().getMinutes()
            }),
            jam_sampai: ref({
                hours: new Date().getHours(),
                minutes: new Date().getMinutes()
            }),
            dateNow: moment().format("YYYY-MM-DD"),
            tglNow: moment().format("DD-mm-YYYY"),
            isVerif: true,
            loading: true,
            loadingVerif: false,
            loadingSelected: false,
            items: [],
            items2: [],
            itemsSelected: [],
            itemsSelected2: [],
            itemsPlat: [],
            imagePlat: [],
            itemsJP: [],
            urlImg: "http://127.0.0.1:8000/images/",
            visible: false,
            imageUrl: "",
            visibleCard: true,
            visibleVerif: false,
            activeSelected: null,
            indexOfActive: 0,
            indexOfActive2: 4,
            values: {
                no_kendaraan: "",
                jenis_pelanggaran: null,
                pasal: null,
                no_uji: null,
                tgl_uji: null,
                tgl_masa_berlaku: null,
                nama_pemilik: null,
                alamat_pemilik: null,
                jbi_uji: null,
                mst_uji: null,
                jenis_kendaraan_id: null,
                jenis_kendaraan: null,
                sumbu_id: null,
                sumbu: null,
                kategori_kepemilikan_id: null,
                kepemilikan: null,
                device_id: null,
                keterangan: null,
                berat_timbang: 0,
                lebih_berat: 0,
                prosen_lebih: 0,
                panjang_ukur: 0,
                panjang_uji: 0,
                panjang_toleransi: 0,
                lebih_panjang: 0,
                lebar_ukur: 0,
                lebar_uji: 0,
                lebar_toleransi: 0,
                lebih_lebar: 0,
                tinggi_ukur: 0,
                tinggi_uji: 0,
                tinggi_toleransi: 0,
                lebih_tinggi: 0,
                foh_ukur: 0,
                foh_uji: 0,
                foh_toleransi: 0,
                lebih_foh: 0,
                roh_ukur: 0,
                roh_uji: 0,
                roh_toleransi: 0,
                lebih_roh: 0,
            },
            optionShift: [],
            optionRegu: [],
            optionJenisPelanggaran: [],
            optionPasal: [],
            shift: null,
            regu: null,
            is_active: false,
            isDisable: true,
            errorShift: false,
            errorRegu: false,
            isBlue: false,
            kode_uppkb: process.env.MIX_KODE_UPPKB,
            slides: [],
            device: null,
            page: 1,
            totalPages: 0,
            total: 0,
            perPage: 25,
            currentPage: 1,
            offset: 1,
            hasMorePages: true,
            isRed: false,
            optionLimit: [
                {
                    label: 25,
                    value: 25
                },
                {
                    label: 50,
                    value: 50
                },
                {
                    label: 100,
                    value: 100
                },
            ],
            picked: '',
            isTampil: false,
            perangkat: null,
            interval: null,
            optionInterval: [
                {
                    label: 'Jam',
                    value: '1'
                },
                {
                    label: 'Tanggal',
                    value: '2'
                }
            ],
            isJam: false,
            isTanggal: false,
            totalAntrian: 0,
            isSink: true,
            loadingSink: false,
            auto: true,
            intervalId: null,
        }
    },
    watch: {
        // perangkat: function (val) {
        //     console.log(val);
        // },
        picked: function (val) {
            if (val == '1') {
                this.values.keterangan = 'Data Blue Tidak Tersedia';
            } else {
                this.values.keterangan = 'Nomor Kendaraan Buram';
            }
        },
        date: function () {
            this.page = 1;
            this.currentPage = 1;
            this.offset = 1;
            this.getData();
        },
        perPage: function () {
            this.page = 1;
            this.currentPage = 1;
            this.offset = 1;
            this.getData();
        },
        is_active: function (val) {
            //console.log(val);
            if (val) {
                this.schema = Yup.object().shape({
                    no_kendaraan: Yup.string().required("Nomor Kendaraan Harus Diisi").nullable(),
                    jenis_pelanggaran: Yup.array().required("Jenis Pelanggaran Harus Diisi").nullable(),
                    pasal: Yup.array().required("Pasal Harus Diisi").nullable(),
                });
                this.isDisable = false;
            } else {
                this.schema = Yup.object().shape({
                    // no_kendaraan: Yup.string().required("Nomor Kendaraan Harus Diisi").nullable(),
                    keterangan: Yup.string().required("Keterangan Harus Diisi").nullable(),
                });
                this.isDisable = true;
            }
        },
        interval: function (val) {
            // console.log(val)
            if (val !== null) {
                if (val.value === '1') {
                    this.isJam = true;
                    this.isTanggal = false;
                } else {
                    this.isJam = false;
                    this.isTanggal = true;
                }
            } else {
                this.isJam = false;
                this.isTanggal = false;
            }
        },
        auto: function (val) {
            console.log(val);
            if (val) {
                this.startInterval();
            } else {
                this.stopInterval();
            }
        },
    },

    computed: {
        user() {
            return this.$store.getters.user
        },
        uppkb() {
            return this.$store.getters.uppkb
        },
        noPlat() {
            return localStorage.getItem('pathUrl') + "/images/noimage.png";
        }
    },
    created() {
        this.getData();
        this.startInterval();
    },
    destroyed() {
        this.stopInterval();
    },
    methods: {
        showMore(page) {
            this.page = page;
            this.currentPage = page;
            this.offset = this.page === 1 ? 1 : this.perPage * page - this.perPage + 1;
            this.getData();
        },
        formatDate(val) {
            return moment(val).format('DD-MM-YYYY HH:mm:ss');
        },
        changeDateFormat(val) {
            return moment(val).format('DD-MM-YYYY');
        },
        slider(v) {
            var item = []
            if (v.img_url !== null) {
                item.push(v.img_url);
            }
            if (v.img2_url !== null) {
                item.push(v.img2_url);
            }
            if (v.img3_url !== null) {
                item.push(v.img3_url);
            }
            if (v.img4_url !== null) {
                item.push(v.img4_url);
            }
            if (item.length > 0) {
                var tampil = '<vueper-slides :touchable="false" fixed-height="200px"><vueper-slide v-for="(items, index) in item" :key="index" :image="items"></vueper-slide></vueper-slides>'
            } else {
                var tampil = '<img v-else :src="items.img_url" class="w-full h-[200px]">'
            }
            return tampil;
        },
        filter() {
            // this.page = 1;
            // this.currentPage = 1;
            // this.offset = 1;
            // this.getData();
            // console.log('disable');
        },
        onFilter(val) {
            // console.log({ val });
            this.page = 1;
            this.currentPage = 1;
            this.offset = 1;
            this.getData();
        },

        startInterval() { // Memuat data pertama kali
            this.intervalId = setInterval(() => {
                this.getData();
            }, 60000); // Pembaruan setiap 30 detik (30 * 1000)
        },
        stopInterval() {
            clearInterval(this.intervalId); // Menghentikan interval
        },
        // async getSinkDeteksi() {
        //     await CaptureService.sinkDeteksi().then((res) => {
        //         console.log(res);
        //     })
        //         .catch((err) => {
        //             console.log(err);
        //         });
        // },

        getData() {
            // console.log(this.jam_dari.hours + ':' + this.jam_dari.minutes + ':00');
            this.loading = true;
            this.items = [];
            this.items2 = [];
            this.itemsSelected = [];
            this.itemsSelected2 = [];
            this.itemsPlat = [];
            this.imagePlat = [];
            WimService.getPagination({
                page: this.offset,
                limit: this.perPage,
                tgl_dari: this.tgl_dari,
                tgl_sampai: this.tgl_sampai,
                // device: this.perangkat ? this.perangkat.value : null,
                interval: this.interval ? this.interval.value : null,
                jam_dari: this.jam_dari.hours.toString() + ':' + this.jam_dari.minutes.toString() + ':00',
                jam_sampai: this.jam_sampai.hours.toString() + ':' + this.jam_sampai.minutes.toString() + ':00',
            })
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            // console.log(response);
                            this.items = response.data;
                            this.total = response.meta.total;
                            this.totalPages = response.meta.totalPages;
                            if (response.meta.totalPages > 1) {
                                this.hasMorePages = true;
                            } else {
                                this.hasMorePages = false;
                            }

                            this.loading = false;
                            //console.log(localStorage.getItem('urlJTO'));
                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                        this.items = [];
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                    this.items = [];
                });
        },

        getShift() {
            //console.log(this.itemsSelected.length);
            //this.loading = true;
            this.optionShift = [];
            ShiftService.getAllActive()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            //console.log(response);
                            // response.data.filter((x) => moment(x.tgl_capture).format("YYYY-MM-DD") === this.date && x.is_verifikasi === false).forEach((val) => {
                            //     this.items.push(val);
                            // })
                            for (var row of response.data) {
                                this.optionShift.push({
                                    label: row.nama,
                                    value: row.id,
                                });
                            }
                            // sthis.items = response.data;
                            //this.loading = false;
                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                        this.optionShift = [];
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                    this.optionShift = [];
                });
        },

        getRegu() {
            //console.log(this.itemsSelected.length);
            //this.loading = true;
            this.optionRegu = [];
            ReguService.getAllActive()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            //console.log(response);
                            // response.data.filter((x) => moment(x.tgl_capture).format("YYYY-MM-DD") === this.date && x.is_verifikasi === false).forEach((val) => {
                            //     this.items.push(val);
                            // })
                            for (var row of response.data) {
                                this.optionRegu.push({
                                    label: row.nama,
                                    value: row.id,
                                });
                            }
                            // sthis.items = response.data;
                            //this.loading = false;
                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                        this.optionRegu = [];
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                    this.optionRegu = [];
                });
        },

        getJP() {
            //console.log(this.itemsSelected.length);
            //this.loading = true;
            this.optionJenisPelanggaran = [];
            JenisPelanggaranService.getAll()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            //console.log(response.data);
                            // response.data.filter((x) => moment(x.tgl_capture).format("YYYY-MM-DD") === this.date && x.is_verifikasi === false).forEach((val) => {
                            //     this.items.push(val);
                            // })
                            for (var row of response.data) {
                                this.optionJenisPelanggaran.push({
                                    label: row.nama,
                                    value: row.id,
                                    kode: row.kode,
                                });
                            }
                            // sthis.items = response.data;
                            //this.loading = false;
                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                        this.optionJenisPelanggaran = [];
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                    this.optionJenisPelanggaran = [];
                });
        },

        getPasal() {
            //console.log(this.itemsSelected.length);
            //this.loading = true;
            this.optionPasal = [];
            PasalService.getAllActive()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            //console.log(response);
                            // response.data.filter((x) => moment(x.tgl_capture).format("YYYY-MM-DD") === this.date && x.is_verifikasi === false).forEach((val) => {
                            //     this.items.push(val);
                            // })
                            for (var row of response.data) {
                                this.optionPasal.push({
                                    label: row.pasal,
                                    value: row.id,
                                    no_pasal: row.no_pasal,
                                });
                            }
                            // sthis.items = response.data;
                            //this.loading = false;
                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                        this.optionPasal = [];
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                    this.optionPasal = [];
                });
        },

        updateItem(nokend) {
            this.itemsSelected.filter((val) => val.no_kendaraan === nokend).forEach((c) => {
                this.items.filter((v) => v.no_kendaraan === c.no_kendaraan).forEach((d) => {
                    if (c.id === d.id) {

                    } else {
                        this.itemsSelected.push(d);
                    }
                })
            })

        },

        openArchive() {
            this.isTampil = true;
            this.isBlue = false;
            this.is_active = false;
            this.picked = '2';
            this.values.keterangan = 'Nomor Kendaraan Buram';
            console.log(this.schema);
        },

        getNokend(no_kendaraan) {
            //console.log(this.itemsSelected.length);
            this.isTampil = true;
            this.loadingVerif = true;
            this.errors = null

            if (this.itemsSelected[0].no_kendaraan === no_kendaraan) {

            } else {
                this.items.filter((v) => v.no_kendaraan === no_kendaraan).forEach((d) => {
                    if (this.itemsSelected.filter((val) => val.id === d.id).length > 0) {
                        this.itemsSelected = this.itemsSelected;
                        this.itemsPlat = this.itemsPlat;
                    } else {
                        if (d.is_plat) {
                            this.itemsPlat.push(d);
                        } else {
                            this.itemsSelected.push(d);
                        }
                    }
                })
            }
            var nokend = no_kendaraan.replace(/[`~!@#$%^&*()_|+\-=?;:\s'",.<>\{\}\[\]\\\/]/gi, "").toUpperCase();
            this.values.no_kendaraan = nokend;
            KendaraanService.getUjiBerkala({
                nokend: nokend
            })
                .then((response) => {
                    //console.log(response);
                    if (response.data) {
                        // console.log(response.data);
                        //console.log(process.env.VUE_APP_API_URL_JTO);
                        this.is_active = true;
                        var jp = this.optionJenisPelanggaran.filter((v) => v.kode === 'RLL');
                        this.selectedJP(jp);
                        //var ps = this.optionPasal.filter((v) => v.no_pasal === 287);

                        //this.selectedPasal(ps);
                        this.errors = null;
                        //this.values.pasal = ps;
                        // response.data.filter((x) => moment(x.tgl_capture).format("YYYY-MM-DD") === this.date && x.is_verifikasi === false).forEach((val) => {
                        //     this.items.push(val);
                        // })
                        var row = response.data
                        this.values.no_uji = row.no_uji;
                        this.values.tgl_uji = row.tanggal_uji;
                        this.values.tgl_masa_berlaku = row.masa_berlaku_uji;
                        this.values.nama_pemilik = row.nama_pemilik;
                        this.values.alamat_pemilik = row.alamat_pemilik;
                        this.values.jbi_uji = row.jbi;
                        this.values.mst_uji = row.mst;
                        this.values.jenis_kendaraan_id = row.jenis_kendaraan_id;
                        this.values.jenis_kendaraan = row.jenis_kend;
                        this.values.sumbu_id = row.sumbu_id;
                        this.values.sumbu = row.konfigurasi_sumbu;
                        this.values.kategori_kepemilikan_id = row.kepemilikan_id;
                        this.values.kepemilikan = row.kepemilikan_val;
                        this.values.panjang_uji = row.panjang_utama;
                        this.values.lebar_uji = row.lebar_utama;
                        this.values.tinggi_uji = row.tinggi_utama;
                        this.values.foh_uji = row.julur_depan;
                        this.values.roh_uji = row.julur_belakang;
                        //this.values.device_id = null;

                        if (moment(row.masa_berlaku_uji).format("YYYY-MM-DD") < this.dateNow) {
                            this.isRed = true;
                        } else {
                            this.isRed = false;
                        }
                        //console.log(this.dateNow)

                        // sthis.items = response.data;
                        this.findPelanggaran(row, false);
                    } else {
                        this.isBlue = false;
                        this.is_active = false;
                        this.picked = '1';
                        this.values.keterangan = 'Data Blue Tidak Tersedia';
                        this.errors = {
                            message: 'Req. Data Uji Berkala Gagal. Periksa Kembali No Kendaraan',
                        };
                        //this.items = [];
                        this.loadingVerif = false;
                    }
                })
                .catch((err) => {
                    this.isBlue = false;
                    this.is_active = false;
                    this.errors = err.response.data;
                    //this.items = [];
                });
        },

        findPelanggaran(row, isFirst) {
            var dataPlg = {
                kode_uppkb: this.uppkb.kode,
                no_kendaraan: row.no_reg_kend,
                masa_berlaku: row.masa_berlaku_uji,
                berat_timbang: Number(this.values.berat_timbang),
                jbi_uji: Number(row.jbi),
                panjang_uji: Number(row.panjang_utama),
                lebar_uji: Number(row.lebar_utama),
                tinggi_uji: Number(row.tinggi_utama),
                foh_uji: Number(row.julur_depan),
                roh_uji: Number(row.julur_belakang),
                panjang_ukur: Number(this.values.panjang_ukur),
                lebar_ukur: Number(this.values.lebar_ukur),
                tinggi_ukur: Number(this.values.tinggi_ukur),
                foh_ukur: Number(this.values.foh_ukur),
                roh_ukur: Number(this.values.roh_ukur),
                dokumen: "12,14,13,15"
            };
            //console.log(dataPlg);
            KendaraanService.findPelanggaran(dataPlg)
                .then((response) => {
                    //console.log(response);
                    if (response.success) {
                        //console.log(response.data);
                        response.data.forEach((v) => {
                            this.values.lebih_berat = v.kelebihan_berat;
                            this.values.prosen_lebih = v.prosen_kelebihan_berat;
                            this.values.panjang_toleransi = v.dimensi.toleransi_panjang;
                            this.values.lebar_toleransi = v.dimensi.toleransi_lebar;
                            this.values.tinggi_toleransi = v.dimensi.toleransi_tinggi;
                            this.values.foh_toleransi = v.dimensi.toleransi_foh;
                            this.values.roh_toleransi = v.dimensi.toleransi_roh;
                            this.values.lebih_panjang = v.dimensi.kelebihan_panjang < 0 ? 0 : v.dimensi.kelebihan_panjang;
                            this.values.lebih_lebar = v.dimensi.kelebihan_lebar < 0 ? 0 : v.dimensi.kelebihan_lebar;
                            this.values.lebih_tinggi = v.dimensi.kelebihan_tinggi < 0 ? 0 : v.dimensi.kelebihan_tinggi;
                            this.values.lebih_foh = v.dimensi.kelebihan_foh < 0 ? 0 : v.dimensi.kelebihan_foh;
                            this.values.lebih_roh = v.dimensi.kelebihan_roh < 0 ? 0 : v.dimensi.kelebihan_roh;

                            v.pelanggaran.forEach((val) => {
                                var jp = this.optionJenisPelanggaran.filter((v) => v.label === val.label).forEach((r) => {
                                    this.values.jenis_pelanggaran.push(r);
                                });
                            })
                        });


                        this.isBlue = true;
                        this.loadingVerif = false;
                    } else {
                        if (isFirst) {
                            this.isTampil = false;
                        } else {
                            this.isBlue = false;
                            this.errors = {
                                message: 'Req. Data Uji Berkala Gagal. Periksa Kembali No Kendaraan',
                            };
                            //this.items = [];
                            this.loadingVerif = false;
                        }
                    }
                })
                .catch((err) => {
                    this.isBlue = false;
                    this.errors = {
                        message: 'Req. Data Uji Berkala Gagal. Periksa Kembali No Kendaraan',
                    };
                    //this.items = [];
                    this.loadingVerif = false;
                    //this.items = [];
                });
        },

        randomString(length) {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        },

        onInvalidSubmit({ values, errors, results }) {
            // console.log("VALUES : ", values, "ERRORS : ", errors);
            const submitBtn = document.querySelector(".submit-btn");
            submitBtn.classList.add("invalid");
            setTimeout(() => {
                submitBtn.classList.remove("invalid");
            }, 1000);
        },

        async onArchive(values) {
            var no_ref = this.randomString(15);
            var tanggal = moment().format("YYYY-MM-DD");
            var tanggaljam = moment().format();

            this.busy = true;
            this.errors = null;

            var dataCapture = [];
            this.itemsSelected.forEach((val) => {
                dataCapture.push(val.id);
            });

            this.itemsPlat.forEach((val) => {
                dataCapture.push(val.id);
            });
            //console.log(dataCapture);

            var dataPelanggaran = {
                keterangan: values.keterangan,
                keterangan_id: this.picked,
                capture: dataCapture.toString(),
            };

            await PelanggaranService.archive(dataPelanggaran)
                .then((res) => {
                    //console.log(res);
                    if (res.success) {
                        //console.log(res);

                        this.success = res.success;
                        this.busy = false;
                        this.resetForm();
                        this.hideVerif();
                        this.getData();
                    } else {
                        this.busy = false;
                        // resetForm(this.dataItem);
                        const errResp = JSON.parse(
                            JSON.stringify(res.data.data, null, 2)
                        );
                        let text = "";
                        let i = 0;
                        for (let x in errResp) {
                            if (i == 0) {
                                text += `${errResp[x]} `;
                            }
                            i++;
                        }
                        this.errors = {
                            message: text,
                        };
                    }
                })
        },

        async onSubmit(value) {
            var no_ref = this.randomString(15);
            var tanggal = moment().format("YYYY-MM-DD");
            var tanggaljam = moment().format();

            this.busy = true;
            this.errors = null;

            var dataCapture = [];
            var dataDetailPelanggaran = [];
            var dataDetailPasal = [];

            this.itemsSelected.forEach((val) => {
                dataCapture.push(val.id);
            });

            this.itemsPlat.forEach((val) => {
                dataCapture.push(val.id);
            });

            if (this.is_active) {
                if (!isNull(this.values.jenis_pelanggaran)) {
                    this.values.jenis_pelanggaran.forEach((val) => {
                        dataDetailPelanggaran.push(val.value);
                    })
                }
                if (!isNull(this.values.pasal)) {
                    this.values.pasal.forEach((val) => {
                        dataDetailPasal.push(val.value);
                    })
                }
            } else {
                dataDetailPelanggaran = [];
                dataDetailPasal = []
            }

            var dataPelanggaran = {
                tgl_pelanggaran: tanggaljam,
                no_ref: no_ref,
                regu_id: this.regu.value,
                shift_id: this.shift.value,
                no_kendaraan: this.values.no_kendaraan,
                no_uji: this.values.no_uji,
                tgl_uji: this.values.tgl_uji,
                tgl_masa_berlaku: moment(this.values.tgl_masa_berlaku).format("YYYY-MM-DD"),
                nama_pemilik: this.values.nama_pemilik,
                alamat_pemilik: this.values.alamat_pemilik,
                jbi_uji: this.values.jbi_uji,
                mst_uji: this.values.mst_uji,
                jenis_kendaraan_id: this.values.jenis_kendaraan_id,
                jenis_kendaraan: this.values.jenis_kendaraan,
                sumbu_id: this.values.sumbu_id,
                sumbu: this.values.sumbu,
                kategori_kepemilikan_id: this.values.kategori_kepemilikan_id,
                berat_timbang: Number(this.values.berat_timbang),
                kelebihan_berat: Number(this.values.lebih_berat),
                prosen_lebih: Number(this.values.prosen_lebih),
                panjang_ukur: Number(this.values.panjang_ukur),
                panjang_utama: Number(this.values.panjang_uji),
                panjang_toleransi: Number(this.values.panjang_toleransi),
                panjang_lebih: Number(this.values.lebih_panjang),
                lebar_ukur: Number(this.values.lebar_ukur),
                lebar_utama: Number(this.values.lebar_uji),
                lebar_toleransi: Number(this.values.lebar_toleransi),
                lebar_lebih: Number(this.values.lebih_lebar),
                tinggi_ukur: Number(this.values.tinggi_ukur),
                tinggi_utama: Number(this.values.tinggi_uji),
                tinggi_toleransi: Number(this.values.tinggi_toleransi),
                tinggi_lebih: Number(this.values.lebih_tinggi),
                foh_ukur: Number(this.values.foh_ukur),
                foh_utama: Number(this.values.foh_uji),
                foh_toleransi: Number(this.values.foh_toleransi),
                foh_lebih: Number(this.values.lebih_foh),
                roh_ukur: Number(this.values.roh_ukur),
                roh_utama: Number(this.values.roh_uji),
                roh_toleransi: Number(this.values.roh_toleransi),
                roh_lebih: Number(this.values.lebih_roh),
                device_id: this.values.device_id.id,
                petugas_id: this.user.petugas_id,
                tgl_capture: this.itemsSelected[0].tgl_capture,
                capture: dataCapture.toString(),
                pelanggaran: dataDetailPelanggaran.toString(),
                pasal: dataDetailPasal.toString(),
            };

            // console.log(dataPelanggaran);

            await PelanggaranService.create(dataPelanggaran)
                .then((res) => {
                    //console.log(res);
                    if (res.success) {
                        //console.log(res);
                        this.success = res.success;
                        this.busy = false;
                        this.resetForm();
                        this.hideVerif();
                        this.getData();
                        this.print(res.data.id, res.data.no_kendaraan, res.data.tgl_pelanggaran);
                        // alert(JSON.stringify(data, null, 2));
                    } else {
                        this.busy = false;
                        // resetForm(this.dataItem);
                        const errResp = JSON.parse(
                            JSON.stringify(res.data.data, null, 2)
                        );
                        let text = "";
                        let i = 0;
                        for (let x in errResp) {
                            if (i == 0) {
                                text += `${errResp[x]} `;
                            }
                            i++;
                        }
                        this.errors = {
                            message: text,
                        };
                    }
                })
        },

        async print(id, noken, tgl) {
            try {
                const ok = await this.$refs.printDialogue.show({
                    title: "Bukti Pelanggaran",
                    message: "Apakah anda akan mendownload data ?",
                    okButton: "YA",
                    cancelButton: "TIDAK",
                });
                // If you throw an error, the method will terminate here unless you surround it wil try/catch
                if (ok) {
                    this.$refs.printDialogue.showBusy(true);
                    await PelanggaranService.exportOne({
                        id: id
                    })
                        .then((response) => {
                            //console.log(response);
                            Print.openPrintWindow(response);

                            // const url = window.URL.createObjectURL(new Blob([response]));
                            // const link = document.createElement('a');
                            // link.href = url;
                            // link.setAttribute('download', 'bukti_pelanggaran_' + noken + '_' + tgl + '.pdf');
                            // document.body.appendChild(link);
                            // link.click();
                            this.$refs.printDialogue.close();
                        })
                }
            } catch (err) {
                this.$refs.printDialogue.showError(err);
            }
        },

        selectedInterval(val) {
            // console.log(val);
        },

        selectedShift(val) {
            // console.log(val);
            localStorage.setItem("shift", JSON.stringify(val));
            this.$store.dispatch("shift", val);
            this.shift = val;
            this.errorShift = false;
            //console.log(this.shift);
        },

        selectedRegu(val) {
            //console.log(val);
            localStorage.setItem("regu", JSON.stringify(val));
            this.$store.dispatch("regu", val);
            this.regu = val;
            this.errorRegu = false;
            //console.log(this.regu);
        },

        selectedPasal(val) {
            //console.log(val);
            this.values.pasal = val;
        },

        deselectedPasal(val) {
            //console.log(val);
            const index = this.values.pasal.indexOf(val);
            //console.log(index);
            if (index > -1) {
                this.values.pasal.splice(index, 1);
            }

            if (this.values.pasal.length < 1) {
                this.values.pasal = null;
            }
            //console.log(this.values.pasal)
        },

        selectedJP(val) {
            //console.log(val);
            this.values.jenis_pelanggaran = val;
            //console.log(this.values.jenis_pelanggaran);
        },

        deselectedJP(val) {
            //console.log(val);
            const index = this.values.jenis_pelanggaran.indexOf(val);
            //console.log(index);
            if (index > -1) {
                this.values.jenis_pelanggaran.splice(index, 1);
            }

            if (this.values.jenis_pelanggaran.length < 1) {
                this.values.jenis_pelanggaran = null;
            }
            //console.log(this.values.jenis_pelanggaran)
        },

        changeActive(index) {
            // console.log(index);
            //console.log(this.itemSelected[0].no_kendaraan);
            this.activeSelected = this.itemsSelected[index].img_url;
            this.indexOfActive = index;
            this.indexOfActive2 = 0;
        },

        changeActive2(index, i) {
            // console.log(index);
            //console.log(this.itemSelected[0].no_kendaraan);
            this.activeSelected = this.itemsSelected[index].image[i];
            this.indexOfActive2 = i + 5;
            this.indexOfActive = 5;
        },

        changeActive3(index, i) {
            // console.log(index);
            //console.log(this.itemSelected[0].no_kendaraan);
            this.activeSelected = this.itemsSelected[index].image[i];
            this.indexOfActive2 = i + 4;
            this.indexOfActive = 5;
        },

        trashSelected(index) {
            this.itemsSelected.splice(index, 1);
            if (this.itemsSelected.length < 1) {
                this.hideVerif();
            } else {
                this.activeSelected = this.itemsSelected[0].img_url;
            }
            this.indexOfActive = 0;
        },

        verifNokend() {
            //console.log(this.values.no_kendaraan);
        },

        selected(val) {
            // console.log(val);
            if (this.itemsSelected.length < 1) {
                this.items2 = [];
                this.items2.push(val);
                this.loadingSelected = true;
                if (val.no_kendaraan) {
                    //console.log(val.tgl_capture, val.no_kendaraan, val.id)
                    CaptureService.getSelected({
                        tanggal: val.tgl_capture,
                        nokend: val.no_kendaraan,
                        id: val.id
                    }).then((res) => {
                        // console.log(res);
                        if (res.success) {
                            var data = [];
                            for (var row of res.data) {
                                var imgs = [];
                                var imgsPlat = [];
                                if (row.img_url !== null) {
                                    imgs.push(row.img_url)
                                }
                                if (row.img2_url !== null) {
                                    imgs.push(row.img2_url)
                                }
                                if (row.img3_url !== null) {
                                    imgs.push(row.img3_url)
                                }
                                if (row.img4_url !== null) {
                                    imgs.push(row.img4_url)
                                }

                                if (row.img_plat_depan_url !== null) {
                                    imgsPlat.push(row.img_plat_depan_url)
                                }
                                if (row.img_plat_belakang_url !== null) {
                                    imgsPlat.push(row.img_plat_belakang_url)
                                }

                                if (row.is_plat === false) {

                                    // this.itemsSelected.push({
                                    //     ...row,
                                    //     image: imgs,
                                    //     imagePlat: imgsPlat
                                    // });
                                    data.push({
                                        ...row,
                                        image: imgs,
                                        imagePlat: imgsPlat
                                    });
                                }

                            };

                            res.data.filter((v) => v.device_id === 1).map((val) => {
                                this.values.device_id = val.device_id;
                                if (val.berat_timbang > 0) {
                                    this.values.berat_timbang = val.berat_timbang;
                                } else {
                                    this.values.berat_timbang = 0;
                                }

                                if (val.panjang_ukur > 0) {
                                    this.values.panjang_ukur = val.panjang_ukur;
                                } else {
                                    this.values.panjang_ukur = 0;
                                }

                                if (val.lebar_ukur > 0) {
                                    this.values.lebar_ukur = val.lebar_ukur;
                                } else {
                                    this.values.lebar_ukur = 0;
                                }

                                if (val.tinggi_ukur > 0) {
                                    this.values.tinggi_ukur = val.tinggi_ukur;
                                } else {
                                    this.values.tinggi_ukur = 0;
                                }

                                if (val.foh_ukur > 0) {
                                    this.values.foh_ukur = val.foh_ukur;
                                } else {
                                    this.values.foh_ukur = 0;
                                }

                                if (val.roh_ukur > 0) {
                                    this.values.roh_ukur = val.roh_ukur;
                                } else {
                                    this.values.roh_ukur = 0;
                                }
                            });

                            this.items2 = data;
                            this.itemsSelected = data;
                            // console.log(this.items2);
                            // console.log(this.itemsSelected);
                            if (this.itemsSelected[0].img_url) {
                                this.activeSelected = this.itemsSelected[0].img_url;
                            } else {
                                this.activeSelected = this.itemsSelected[1].img_url;
                            }
                            this.values.no_kendaraan = this.itemsSelected[0].no_kendaraan;
                            this.values.device_id = this.itemsSelected[0].device;
                            this.device = this.itemsSelected[0].device.nama;
                            this.isVerif = false;
                            this.loadingSelected = false;
                        }
                    })
                } else {
                    CaptureService.getSelected({
                        tanggal: val.tgl_capture,
                        nokend: val.no_kendaraan,
                        id: val.id
                    }).then((res) => {
                        // console.log(res);
                        if (res.success) {
                            var data = [];
                            for (var row of res.data) {
                                var imgs = [];
                                var imgsPlat = [];
                                if (row.img_url !== null) {
                                    imgs.push(row.img_url)
                                }
                                if (row.img2_url !== null) {
                                    imgs.push(row.img2_url)
                                }
                                if (row.img3_url !== null) {
                                    imgs.push(row.img3_url)
                                }
                                if (row.img4_url !== null) {
                                    imgs.push(row.img4_url)
                                }

                                if (row.img_plat_depan_url !== null) {
                                    imgsPlat.push(row.img_plat_depan_url)
                                }
                                if (row.img_plat_belakang_url !== null) {
                                    imgsPlat.push(row.img_plat_belakang_url)
                                }

                                if (row.is_plat === false) {

                                    this.itemsSelected.push({
                                        ...row,
                                        image: imgs,
                                        imagePlat: imgsPlat
                                    });
                                    data.push({
                                        ...row,
                                        image: imgs,
                                        imagePlat: imgsPlat
                                    });
                                }


                            };

                            res.data.filter((v) => v.device_id === 1).map((val) => {
                                this.values.device_id = val.device_id;
                                if (val.berat_timbang > 0) {
                                    this.values.berat_timbang = val.berat_timbang;
                                } else {
                                    this.values.berat_timbang = 0;
                                }

                                if (val.panjang_ukur > 0) {
                                    this.values.panjang_ukur = val.panjang_ukur;
                                } else {
                                    this.values.panjang_ukur = 0;
                                }

                                if (val.lebar_ukur > 0) {
                                    this.values.lebar_ukur = val.lebar_ukur;
                                } else {
                                    this.values.lebar_ukur = 0;
                                }

                                if (val.tinggi_ukur > 0) {
                                    this.values.tinggi_ukur = val.tinggi_ukur;
                                } else {
                                    this.values.tinggi_ukur = 0;
                                }

                                if (val.foh_ukur > 0) {
                                    this.values.foh_ukur = val.foh_ukur;
                                } else {
                                    this.values.foh_ukur = 0;
                                }

                                if (val.roh_ukur > 0) {
                                    this.values.roh_ukur = val.roh_ukur;
                                } else {
                                    this.values.roh_ukur = 0;
                                }
                            });

                            // console.log(this.imagePlat);
                            // console.log(this.itemsSelected);
                            this.items2 = data;
                            //this.itemsSelected2 = data;
                            if (this.itemsSelected[0].img_url) {
                                this.activeSelected = this.itemsSelected[0].img_url;
                            } else {
                                this.activeSelected = this.itemsSelected[1].img_url;
                            }
                            this.values.no_kendaraan = this.itemsSelected[0].no_kendaraan;
                            this.values.device_id = this.itemsSelected[0].device;
                            this.device = this.itemsSelected[0].device.nama;
                            this.isVerif = false;
                            this.loadingSelected = false;
                        }
                    });
                }
            } else {
                if (this.itemsSelected.length < 2) {
                    this.items2 = [];
                    this.items.forEach((d) => {
                        if (d.is_plat === false) {
                            this.items2.push(d);
                        }

                    });
                    this.itemsSelected = [];
                    this.itemsPlat = [];
                    this.no_kendaraan = "";
                    this.activeSelected = "";
                    this.values.device_id = null;
                    this.values.berat_timbang = 0;
                    this.values.panjang_ukur = 0;
                    this.values.lebar_ukur = 0;
                    this.values.tinggi_ukur = 0;
                    this.values.foh_ukur = 0;
                    this.values.roh_ukur = 0;
                    this.device = null;
                    this.isVerif = true;
                } else {
                    var i = this.itemsSelected.indexOf(val);
                    // console.log(i);
                    var ii = this.items2.indexOf(val);
                    // console.log(ii);
                    // this.itemsSelected.splice(i, 1);
                    this.items2.splice(ii, 1);
                    this.itemsSelected = this.items2;
                    // console.log(this.itemsSelected);
                    if (this.itemsSelected[0].img_url) {
                        this.activeSelected = this.itemsSelected[0].img_url;
                    } else {
                        this.activeSelected = this.itemsSelected[1].img_url;
                    }
                    this.values.no_kendaraan = this.itemsSelected[0].no_kendaraan;
                    this.values.device_id = this.itemsSelected[0].device;
                    this.device = this.itemsSelected[0].device.nama;

                    this.itemsSelected.filter((v) => v.device_id === 1).map((val) => {
                        this.values.device_id = val.device_id;
                        if (val.berat_timbang > 0) {
                            this.values.berat_timbang = val.berat_timbang;
                        } else {
                            this.values.berat_timbang = 0;
                        }

                        if (val.panjang_ukur > 0) {
                            this.values.panjang_ukur = val.panjang_ukur;
                        } else {
                            this.values.panjang_ukur = 0;
                        }

                        if (val.lebar_ukur > 0) {
                            this.values.lebar_ukur = val.lebar_ukur;
                        } else {
                            this.values.lebar_ukur = 0;
                        }

                        if (val.tinggi_ukur > 0) {
                            this.values.tinggi_ukur = val.tinggi_ukur;
                        } else {
                            this.values.tinggi_ukur = 0;
                        }

                        if (val.foh_ukur > 0) {
                            this.values.foh_ukur = val.foh_ukur;
                        } else {
                            this.values.foh_ukur = 0;
                        }

                        if (val.roh_ukur > 0) {
                            this.values.roh_ukur = val.roh_ukur;
                        } else {
                            this.values.roh_ukur = 0;
                        }
                    });
                }
            };
            console.log(this.values);
        },

        onShow(val) {
            //console.log(val);
            this.imageUrl = val.img_url;
            this.visible = true;
        },

        onHide() {
            this.visible = false;
        },

        verif() {
            this.isTampil = false;

            if (this.shift === null) {
                this.errorShift = true;
            } else {
                this.errorShift = false;
            }

            if (this.regu === null) {
                this.errorRegu = true;
            } else {
                this.errorRegu = false;
            }

            if (this.shift === null || this.regu === null) {
                //this.errorShift = true;
            } else {
                if (this.values.no_kendaraan) {
                    KendaraanService.getByNokend({
                        tanggal: this.itemsSelected[0].tgl_capture,
                        nokend: this.itemsSelected[0].no_kendaraan,
                    }).then((res) => {
                        // console.log(res);
                        this.isTampil = true;
                        if (res.success) {

                            this.is_active = true;
                            var jp = this.optionJenisPelanggaran.filter((v) => v.kode === 'RLL');
                            this.selectedJP(jp);
                            this.errors = null;

                            var row = res.data;
                            this.values.no_uji = row.no_uji;
                            this.values.tgl_uji = row.tgl_uji;
                            this.values.tgl_masa_berlaku = row.tgl_masa_berlaku;
                            this.values.nama_pemilik = row.nama_pemilik;
                            this.values.alamat_pemilik = row.alamat_pemilik;
                            this.values.jbi_uji = row.jbi_uji;
                            this.values.mst_uji = row.mst_uji;
                            this.values.jenis_kendaraan_id = row.jenis_kendaraan_id;
                            this.values.jenis_kendaraan = row.jenis_kendaraan;
                            this.values.sumbu_id = row.sumbu_id;
                            this.values.sumbu = row.sumbu;
                            this.values.kategori_kepemilikan_id = row.kategori_kepemilikan_id;
                            this.values.kepemilikan = row.kategori_kepemilikan_id ? row.kategori_kepemilikan_id == 1 ? 'PERSEORANGAN' : 'PERSEORANGAN' : '';
                            this.values.panjang_uji = row.panjang_utama;
                            this.values.lebar_uji = row.lebar_utama;
                            this.values.tinggi_uji = row.tinggi_utama;
                            this.values.foh_uji = row.foh_utama;
                            this.values.roh_uji = row.roh_utama;

                            if (moment(row.tgl_masa_berlaku).format("YYYY-MM-DD") < this.dateNow) {
                                this.isRed = true;
                            } else {
                                this.isRed = false;
                            }
                            var row = {
                                no_reg_kend: row.no_kendaraan,
                                masa_berlaku_uji: row.tgl_masa_berlaku,
                                jbi: row.jbi_uji,
                                panjang_utama: row.panjang_utama,
                                lebar_utama: row.lebar_utama,
                                tinggi_utama: row.tinggi_utama,
                                julur_depan: row.foh_utama,
                                julur_belakang: row.roh_utama,
                            }
                            this.findPelanggaran(row, true);
                        } else {
                            this.isTampil = false;
                            this.isBlue = false;
                            this.is_active = false;
                            this.picked = '1';
                            this.values.keterangan = 'Data Blue Tidak Tersedia';
                            this.loadingVerif = false;
                        }
                    })
                }
                this.visibleCard = false;
                this.visibleVerif = true;
            }

            //this.$router.replace({ name: "verifikator" });
        },

        hideVerif() {
            this.visibleCard = true;
            this.visibleVerif = false;
            this.activeSelected = null;
            this.indexOfActive = 0;
            this.indexOfActive2 = 4;
            this.itemsSelected = [];
            this.itemsSelected2 = [];
            this.itemsPlat = [];
            this.imagePlat = [];
            this.items2 = [];
            this.isTampil = false;
            this.items.forEach((d) => {
                if (d.is_plat === false) {
                    this.items2.push(d);
                }
            });
            // this.items.filter((value) => value.is_plat === false).forEach((d) => {
            //     this.items2.push(d);
            // });
            this.isVerif = true;
            this.is_active = false;
            this.errors = null;
            this.isBlue = false;
            this.values.berat_timbang = 0;
            this.values.panjang_ukur = 0;
            this.values.lebar_ukur = 0;
            this.values.tinggi_ukur = 0;
            this.values.foh_ukur = 0;
            this.values.roh_ukur = 0;
        },

        resetForm() {
            this.values = {
                no_kendaraan: "",
                jenis_pelanggaran: null,
                pasal: null,
                no_uji: null,
                tgl_uji: null,
                tgl_masa_berlaku: null,
                nama_pemilik: null,
                alamat_pemilik: null,
                jbi_uji: null,
                mst_uji: null,
                jenis_kendaraan_id: null,
                jenis_kendaraan: null,
                sumbu_id: null,
                sumbu: null,
                kategori_kepemilikan_id: null,
                kepemilikan: null,
                device_id: null,
                keterangan: null,
            }
            this.is_active = false;
        },

        async hapus() {
            try {
                const ok = await this.$refs.confirmDialogue.show({
                    title: "Hapus " + this.itemsSelected.length + " Data",
                    message: "Anda yakin akan menghapus data ?",
                    okButton: "YA",
                    cancelButton: "TIDAK",
                });
                // If you throw an error, the method will terminate here unless you surround it wil try/catch
                if (ok) {
                    let id = [];
                    this.$refs.confirmDialogue.showBusy(true);
                    if (this.itemsSelected.key != undefined) {
                        id.push(this.itemsSelected.id);
                    } else {
                        // console.log(Object.keys(item).length);
                        for (var i = 0; i < this.itemsSelected.length; i++) {
                            id.push(this.itemsSelected[i].id);
                        }
                    }

                    if (id.length > 0) {
                        CaptureService.force(id).then((res) => {
                            if (res.success) {
                                this.success = res.success;
                                this.getData();
                                this.$refs.confirmDialogue.close();
                            } else {
                                this.$refs.confirmDialogue.showBusy(
                                    res.data.success
                                );
                                this.$refs.confirmDialogue.showError(
                                    res.data.message
                                );
                            }
                        });
                    } else {
                        this.$refs.table.setError("ID Tidak Tersedia");
                    }
                }
            } catch (err) {
                this.$refs.confirmDialogue.showError(err);
            }
        },
    },

}
</script>

<style>
.thumbnails {
    margin: auto;
    max-width: 300px;
}

.thumbnails .vueperslide {
    box-sizing: border-box;
    border: 1px solid #fff;
    transition: 0.3s ease-in-out;
    opacity: 0.7;
    cursor: pointer;
}

.thumbnails .vueperslide--active {
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.3);
    opacity: 1;
    border-color: #000;
}
</style>
