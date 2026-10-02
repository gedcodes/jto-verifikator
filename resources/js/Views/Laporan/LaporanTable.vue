<template>
    <div v-if="showData">
        <div class="max-w-full w-full mx-auto py-3 px-4 sm:px-6 lg:px-8 mb-3 shadow bg-white">
            <h2 class="text-xl tracking-tight font-bold text-gray-900">Laporan Rekapitulasi Data Pelanggaran</h2>
        </div>
        <div class="max-w-full w-full mx-auto px-0 sm:px-4 lg:px-6 my-2">
            <div class="bg-white shadow-md rounded-t-md mt-2 mb-4">
                <div class="max-w-full mx-auto px-2 py-2">
                    <div class="flex flex-row space-x-2">
                        <filter-icon class="h-5 w-5" />
                        <div class="font-bold text-base">
                            Filter Data
                        </div>
                    </div>
                    <Form @submit="onSubmit" :validation-schema="schema">
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <m-select-2 v-model="jenis" :selectedField="jenis" name="jenis" :options="optionsJenis"
                                    labelTitle="Jenis Laporan" placeholder="Pilih Jenis Laporan" requiredSelect
                                    inputInfo="Pilih Jenis Laporan" inputTextSize="sm" />

                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div v-show="isInterval">
                                    <m-select-2 v-model="interval" :selectedField="interval" name="interval"
                                        :options="optionsInterval" labelTitle="Interval" placeholder="Pilih Interval"
                                        requiredSelect inputInfo="Pilih Interval" inputTextSize="sm" />
                                </div>
                                <div v-show="isBulan">
                                    <Field id="bulan" name="bulan" v-model="bulan" v-slot="{ errorMessage }">
                                        <label class="form-label block mb-1 text-gray-600 font-poppins text-sm">
                                            <span class="text-red-600">*</span>
                                            <span class="ml-1">Bulan</span>
                                        </label>
                                        <div class="relative" :class="{ 'has-error': !!errorMessage }">
                                            <Datepicker v-model="bulan" month-picker placeholder="Pilih Bulan" format="MMM"
                                                model-type="MMM" autoApply model-auto :format-locale="id" locale="id"
                                                :input-class-name="errorMessage ? 'input-invalid' : ''" />
                                            <p class="text-red-600 mt-1 text-xs" v-show="errorMessage">
                                                {{ errorMessage }}
                                            </p>
                                            <svg class="absolute text-red-600 fill-current" style="top: 8px; right: 8px"
                                                v-if="errorMessage" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24">
                                                <path
                                                    d="M11.953,2C6.465,2,2,6.486,2,12s4.486,10,10,10s10-4.486,10-10S17.493,2,11.953,2z M13,17h-2v-2h2V17z M13,13h-2V7h2V13z" />
                                            </svg>
                                        </div>
                                    </Field>
                                </div>
                                <div v-show="isTahun">
                                    <Field id="tahun" name="tahun" v-model="tahun" v-slot="{ errorMessage }">
                                        <label class="form-label block mb-1 text-gray-600 font-poppins text-sm">
                                            <span class="text-red-600">*</span>
                                            <span class="ml-1">Tahun</span>
                                        </label>
                                        <div class="relative" :class="{ 'has-error': !!errorMessage }">
                                            <Datepicker v-model="tahun" year-picker placeholder="Pilih Tahun" format="yyyy"
                                                model-type="yyyy" :max-date="maxYear" :year-range="[2017, 2028]" autoApply
                                                :input-class-name="errorMessage ? 'input-invalid' : ''" />
                                            <p class="text-red-600 mt-1 text-xs" v-show="errorMessage">
                                                {{ errorMessage }}
                                            </p>
                                            <svg class="absolute text-red-600 fill-current" style="top: 8px; right: 8px"
                                                v-if="errorMessage" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24">
                                                <path
                                                    d="M11.953,2C6.465,2,2,6.486,2,12s4.486,10,10,10s10-4.486,10-10S17.493,2,11.953,2z M13,17h-2v-2h2V17z M13,13h-2V7h2V13z" />
                                            </svg>
                                        </div>
                                    </Field>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 mb-2">
                            <div class="flex flex-row space-x-2 justify-end">
                                <!-- <m-button id="btnReset" outline icon iconName="refresh" name="btnReset" color="blue"
                                    title="RESET" class="h-10 px-6" type="reset">
                                </m-button>
                                <m-button id="btnSimpan" icon name="btnSimpan" color="blue" title="Filter"
                                    iconName="filter" class="submit-btn h-10 px-6 text-white items-end" type="submit">
                                </m-button> -->
                                <button type="submit"
                                    class="inline-flex items-center justify-center py-2 px-4 text-white bg-blue-600 rounded-md hover:bg-blue-500 shadow hover:shadow-lg focus:outline-none focus:shadow-outline cursor-pointer">
                                    <filter-icon class="h-5 w-5 mr-1" /> Filter
                                </button>
                                <div v-if="busyPrint"
                                    class="flex justify-center items-center content-end p-1 px-6 rounded-md bg-yellow-600 text-bold shadow">
                                    <circle-svg stroke="#ffffff" class="w-6 h-6" />
                                </div>
                                <div v-else @click="printPDF"
                                    class="inline-flex items-center justify-center py-1 px-4 text-white bg-yellow-600 rounded-md hover:bg-yellow-500 shadow hover:shadow-lg focus:outline-none focus:shadow-outline cursor-pointer">
                                    <printer-icon class="h-5 w-5 mr-1" /> PDF
                                </div>
                                <div v-if="busyExport"
                                    class="flex justify-center items-center content-end p-1 px-6 rounded-md bg-green-600 text-bold shadow">
                                    <circle-svg stroke="#ffffff" class="w-6 h-6" />
                                </div>
                                <div v-else @click="exportexcel"
                                    class="inline-flex items-center justify-center py-2 px-4 text-white bg-green-600 rounded-md hover:bg-green-500 shadow hover:shadow-lg focus:outline-none focus:shadow-outline cursor-pointer">
                                    <svg class="w-5 h-5 text-white mr-1" fill="none" viewBox="0 0 50 50"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z" />
                                    </svg> Excel
                                </div>
                            </div>
                        </div>
                    </Form>
                </div>
            </div>
            <errors v-if="errors" :content="errors" @close="errors = null" />
            <laporan-crud v-if="isData" ref="table" titleCrud="PELANGGARAN" :loading="loading" :headers="headers"
                :items="items" textSize="sm">
            </laporan-crud>
            <div v-else class="my-10">
                <div class="flex items-center justify-center">
                    <img :src="noData" alt="noData" class="w-60" />
                </div>
                <div class="flex items-center justify-center mt-2">
                    <div class="font-semibold text-base">Belum Ada Data Yang Ditampilkan</div>
                </div>
                <div class="flex items-center justify-center mt-2">
                    <div>Silahkan isi filter data sesuai keinginan kemudian klik filter</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { FilterIcon, TrashIcon, PencilIcon, PencilAltIcon, PrinterIcon } from "@heroicons/vue/outline";
import AppIcon from "@/js/components/icon/AppIcon.vue";
import { Form, Field, ErrorMessage, FieldArray } from "vee-validate";
import * as Yup from "yup";
import excel from "@/js/components/icon/excel.vue";
import LaporanService from "@/js/service/LaporanService.js";
import LaporanCrud from "./LaporanCrud.vue";
import MCrud from "@/js/components/crud/MCrud.vue";
import ModalDialog from "@/js/components/modal/ModalDialog.vue";
import ConfirmDialog from "@/js/components/confirm/ConfirmDialog.vue";
import ArchiveDialog from "@/js/components/confirm/ArchiveDialog.vue";
import MButton from "@/js/components/form/MButton.vue";
import Datepicker from '@vuepic/vue-datepicker';
import moment from 'moment';
import MInput from '../../components/form/MInput.vue'
import Errors from "@/js/components/Errors.vue";
import CircleSvg from "@/js/components/CircleSvg.vue";
import MSelect2 from "@/js/components/form/MSelect2";
import { id } from 'date-fns/locale';


export default {
    components: {
        MCrud,
        AppIcon,
        ModalDialog,
        MButton,
        ConfirmDialog,
        LaporanCrud,
        Datepicker,
        TrashIcon,
        PencilAltIcon,
        FilterIcon,
        MInput,
        PrinterIcon,
        excel,
        ArchiveDialog,
        CircleSvg,
        Errors,
        MSelect2,
        Form,
        Field,
        ErrorMessage,
        FieldArray,
    },
    data() {
        const schema = Yup.object().shape({
            jenis: Yup.object()
                .required("Pilih Jenis Laporan")
                .nullable(),
            interval: Yup.object()
                .required("Pilih Interval")
                .nullable(),
            bulan: Yup.object()
                .required("Pilih Bulan")
                .nullable(),
            tahun: Yup.string()
                .required("Pilih Tahun")
                .nullable(),
        });
        return {
            id,
            errors: null,
            errorsArchive: null,
            headers: [],
            items: [],
            loading: false,
            showModal: false,
            titleDialog: "Konfirmasi",
            dataEdit: {},
            showData: true,
            maxYear: moment().add(1, 'years').format('YYYY'),
            no_kendaraan: null,
            busy: false,
            busyPrint: false,
            busyExport: false,
            schema,
            title: "Konfirmasi",
            message: "Anda yakin ingin menyimpan data ke archive ?",
            okButton: "YA",
            cancelButton: "TIDAK",
            isVisible: false,
            itemArchive: [],
            isData: false,
            isInterval: false,
            isBulan: false,
            isTahun: false,
            noData: '',
            jenis: null,
            interval: null,
            bulan: null,
            tahun: null,
            optionsJenis: [
                // {
                //     label: 'Data Pelanggaran All',
                //     value: '0'
                // },
                // {
                //     label: 'Data Pelanggaran & Archive',
                //     value: '1'
                // },
                {
                    label: 'Laporan Data Pelanggaran Perjenis Kendaraan',
                    value: '1'
                },
                // {
                //     label: 'Data Pelanggaran Perkategori Kepemilikan',
                //     value: '3'
                // },
                {
                    label: 'Laporan Data Pelanggaran Kelebihan Muatan',
                    value: '2'
                },
                {
                    label: 'Laporan Data Pelanggaran Perjenis Pelanggaran',
                    value: '3'
                },

            ],
            optionsInterval: [
                {
                    label: 'Bulanan',
                    value: '1'
                },
                {
                    label: 'Tahunan',
                    value: '2'
                }
            ],
            param: {},
        };
    },
    watch: {
        jenis: function (val) {
            if (val !== null) {
                this.isInterval = true;
            } else {
                this.isInterval = false;
                this.interval = null;
            }
        },
        interval: function (val) {
            if (val !== null) {
                if (val.value === '1') {
                    this.isBulan = true;
                    this.isTahun = true;
                    this.schema = Yup.object().shape({
                        jenis: Yup.object()
                            .required("Pilih Jenis Laporan")
                            .nullable(),
                        interval: Yup.object()
                            .required("Pilih Interval")
                            .nullable(),
                        bulan: Yup.object()
                            .required("Pilih Bulan")
                            .nullable(),
                        tahun: Yup.string()
                            .required("Pilih Tahun")
                            .nullable(),
                    });
                } else {
                    this.isBulan = false;
                    this.isTahun = true;
                    this.schema = Yup.object().shape({
                        jenis: Yup.object()
                            .required("Pilih Jenis Laporan")
                            .nullable(),
                        interval: Yup.object()
                            .required("Pilih Interval")
                            .nullable(),
                        tahun: Yup.string()
                            .required("Pilih Tahun")
                            .nullable(),
                    });
                }
            } else {
                this.isBulan = false;
                this.isTahun = false;
                this.bulan = null;
                this.tahun = null;
            }
        }
    },
    created() {
        this.noData = localStorage.getItem('pathUrl') + "/images/no-data.png";
    },
    emits: ["data-table"],
    methods: {
        async getData(payload, jenis) {
            this.isData = true;
            this.headers = [];
            this.items = [];
            this.loading = true;
            this.errors = null;

            if (jenis === '1') {
                await LaporanService.perjeniskendaraan(payload)
                    .then((response) => {
                        //console.log(response)
                        if (response.success) {
                            if (response.data) {

                                this.headers.push(
                                    {
                                        text: "WAKTU",
                                        value: "waktu",
                                        width: 100,
                                        sortable: true,
                                    },
                                    {
                                        text: " TOTAL PELANGGARAN",
                                        value: "jml_pelanggaran",
                                        width: 80,
                                    },
                                    {
                                        text: "KERETA TEMPELAN BAK TERBUKA",
                                        value: "kereta_tempelan_bak_terbuka",
                                        width: 80,
                                    },
                                    {
                                        text: "MOBIL BARANG BAK TERBUKA",
                                        value: "mobil_barang_bak_terbuka",
                                        width: 80,
                                    },
                                    {
                                        text: "MOBIL BARANG BAK TERTUTUP",
                                        value: "mobil_barang_bak_tertutup",
                                        width: 80,
                                    },
                                    {
                                        text: "MOBIL PENARIK",
                                        value: "mobil_penarik",
                                        width: 80,
                                    },
                                    {
                                        text: "MOBIL TANGKI",
                                        value: "mobil_tangki",
                                        width: 80,
                                    },
                                    {
                                        text: "KERETA TEMPELAN",
                                        value: "kereta_tempelan",
                                        width: 80,
                                    },
                                    {
                                        text: "KENDARAAN KHUSUS",
                                        value: "kendaraan_khusus",
                                        width: 80,
                                    },
                                    {
                                        text: "KERETA GANDENG BAK TERBUKA",
                                        value: "kereta_gandeng_bak_tertutup",
                                        width: 80,
                                    },
                                    {
                                        text: "KERETA GANDENG BAK TERTUTUP",
                                        value: "kereta_gandeng_bak_terbuka",
                                        width: 80,
                                    },
                                    {
                                        text: "KENDARAAN BERMOTOR RODA TIGA",
                                        value: "kendaraan_bermotor_roda_tiga",
                                        width: 80,
                                    },
                                )

                                if (payload.interval === '1') {
                                    for (var row of response.data) {
                                        //console.log(row);

                                        this.items.push({
                                            ...row,
                                            waktu: moment(row.waktu).format('DD-MM-YYYY')
                                        });
                                    }
                                } else {
                                    for (var row of response.data) {
                                        //console.log(row);

                                        //this.items.push(row);
                                        this.items.push({
                                            ...row,
                                            waktu: moment(row.tahun + '-' + row.bulan).format('MMMM'),
                                        });
                                    }
                                }

                                // sthis.items = response.data;
                                this.loading = false;
                            }
                            //console.log(this.items);
                        } else {
                            this.errors = {
                                message: response.message,
                            };
                            this.headers = []
                            this.items = [];
                        }
                    })
                    .catch((err) => {
                        this.errors = err.response.data;
                        this.headers = []
                        this.items = [];
                    });
            } else if (jenis === '2') {
                await LaporanService.perberat(payload)
                    .then((response) => {
                        //console.log(response)
                        if (response.success) {
                            if (response.data) {

                                this.headers.push(
                                    {
                                        text: "WAKTU",
                                        value: "waktu",
                                        width: 100,
                                        sortable: true,
                                    },
                                    {
                                        text: "5 s/d 20",
                                        value: "range_5_20",
                                        width: 80,
                                    },
                                    {
                                        text: "21 s/d 40",
                                        value: "range_21_40",
                                        width: 80,
                                    },
                                    {
                                        text: "41 s/d 60",
                                        value: "range_41_60",
                                        width: 80,
                                    },
                                    {
                                        text: "61 s/d 80",
                                        value: "range_61_80",
                                        width: 80,
                                    },
                                    {
                                        text: "81 s/d 100",
                                        value: "range_81_100",
                                        width: 80,
                                    },
                                    {
                                        text: ">100",
                                        value: "range_up_100",
                                        width: 80,
                                    },
                                )

                                if (payload.interval === '1') {
                                    for (var row of response.data) {
                                        //console.log(row);

                                        this.items.push({
                                            ...row,
                                            waktu: moment(row.waktu).format('DD-MM-YYYY')
                                        });
                                    }
                                } else {
                                    for (var row of response.data) {
                                        //console.log(row);

                                        //this.items.push(row);
                                        this.items.push({
                                            ...row,
                                            waktu: moment(row.tahun + '-' + row.bulan).format('MMMM'),
                                        });
                                    }
                                }

                                // sthis.items = response.data;
                                this.loading = false;
                            }
                            //console.log(this.items);
                        } else {
                            this.errors = {
                                message: response.message,
                            };
                            this.headers = []
                            this.items = [];
                        }
                    })
                    .catch((err) => {
                        this.errors = err.response.data;
                        this.headers = []
                        this.items = [];
                    });
            } else {
                await LaporanService.perjenispelanggaran(payload)
                    .then((response) => {
                        //console.log(response)
                        if (response.success) {
                            if (response.data) {

                                this.headers.push(
                                    {
                                        text: "WAKTU",
                                        value: "waktu",
                                        width: 100,
                                        sortable: true,
                                    },
                                    {
                                        text: " DAYA ANGKUT",
                                        value: "daya_angkut",
                                        width: 80,
                                    },
                                    {
                                        text: "DIMENSU",
                                        value: "dimensi",
                                        width: 80,
                                    },
                                    {
                                        text: "PERSYARATAN TEKNIS",
                                        value: "persyaratan_teknis",
                                        width: 80,
                                    },
                                    {
                                        text: "DOKUMEN",
                                        value: "dokumen",
                                        width: 80,
                                    },
                                    {
                                        text: "TATA CARA MUAT",
                                        value: "tata_cara_muat",
                                        width: 80,
                                    },
                                    {
                                        text: "KELAS JALAN",
                                        value: "kelas_jalan",
                                        width: 80,
                                    },
                                    {
                                        text: "RAMBU LALU LINTAS",
                                        value: "rambu_lalu_lintas",
                                        width: 80,
                                    },
                                )

                                if (payload.interval === '1') {
                                    for (var row of response.data) {
                                        //console.log(row);

                                        this.items.push({
                                            ...row,
                                            waktu: moment(row.waktu).format('DD-MM-YYYY')
                                        });
                                    }
                                } else {
                                    for (var row of response.data) {
                                        //console.log(row);

                                        //this.items.push(row);
                                        this.items.push({
                                            ...row,
                                            waktu: moment(row.tahun + '-' + row.bulan).format('MMMM'),
                                        });
                                    }
                                }

                                // sthis.items = response.data;
                                this.loading = false;
                            }
                            //console.log(this.items);
                        } else {
                            this.errors = {
                                message: response.message,
                            };
                            this.headers = []
                            this.items = [];
                        }
                    })
                    .catch((err) => {
                        this.errors = err.response.data;
                        this.headers = []
                        this.items = [];
                    });
            }
        },
        filter() {
            // var data = {
            //     no_kendaraan: this.no_kendaraan,
            //     tgl_dari: this.tgl_dari,
            //     tgl_sampai: this.tgl_sampai,
            // }

            console.log(this.values);

            //this.getData();
        },

        selectedJenis(val) {
            console.log(val);
            //this.jenis = val;
            this.isInterval = true;
        },

        selectedInterval(val) {
            //console.log(val);
            this.interval = val;
            if (val.value === '1') {
                this.isBulan = true;
                this.isTahun = true;
                this.schema = Yup.object().shape({
                    jenis: Yup.object()
                        .required("Pilih Jenis Laporan")
                        .nullable(),
                    interval: Yup.object()
                        .required("Pilih Interval")
                        .nullable(),
                    bulan: Yup.object()
                        .required("Pilih Bulan")
                        .nullable(),
                    tahun: Yup.string()
                        .required("Pilih Tahun")
                        .nullable(),
                });
            } else {
                this.isBulan = false;
                this.isTahun = true;
                this.schema = Yup.object().shape({
                    jenis: Yup.object()
                        .required("Pilih Jenis Laporan")
                        .nullable(),
                    interval: Yup.object()
                        .required("Pilih Interval")
                        .nullable(),
                    tahun: Yup.string()
                        .required("Pilih Tahun")
                        .nullable(),
                });
            }
        },

        onSubmit(val) {
            if (val.interval.value === '1') {
                var payload = {
                    interval: val.interval.value,
                    bulan: val.bulan.month + 1,
                    tahun: val.tahun
                };
                this.param = {
                    jenis: val.jenis.value,
                    interval: val.interval.value,
                    bulan: val.bulan.month + 1,
                    tahun: val.tahun
                };
            } else {
                var payload = {
                    interval: val.interval.value,
                    tahun: val.tahun
                };
                this.param = {
                    jenis: val.jenis.value,
                    interval: val.interval.value,
                    tahun: val.tahun
                };
            }
            var jenis = val.jenis.value;

            this.getData(payload, jenis);

            //console.log(val);
        },
        onInvalidSubmit({ values, errors, results }) {
            // console.log("VALUES : ", values, "ERRORS : ", errors);
            const submitBtn = document.querySelector(".submit-btn");
            submitBtn.classList.add("invalid");
            setTimeout(() => {
                submitBtn.classList.remove("invalid");
            }, 1000);
        },

        resetForm() {
            this.values = {
                jenis: null,
                interval: null,
                bulan: null,
                tahun: null
            };
        },

        printPDF() {
            if (this.isData) {
                this.errors = null;
                this.busyPrint = true;

                LaporanService.print(this.param).then((res) => {
                    this.busyPrint = false;
                    window.open(res.data.url_file, '_blank');
                })
            } else {
                this.errors = {
                    message: 'Harap filter data terlebih dahulu',
                };
            }

        },

        exportexcel() {
            if (this.isData) {
                this.errors = null;
                this.busyExport = true;

                LaporanService.excel(this.param).then((res) => {
                    this.busyExport = false;
                    window.open(res.data.url_file, '_blank');
                })
            } else {
                this.errors = {
                    message: 'Harap filter data terlebih dahulu',
                };
            }

        },

        doTambah(item) {
            this.dataEdit = item;
            this.titleDialog = "TAMBAH DATA";
            this.showModal = !this.showModal;
        },
        doEdit(item) {
            this.dataEdit = null;
            this.dataEdit = item;
            this.showModal = !this.showModal;
            this.showData = !this.showData;
            //console.log(this.dataEdit);
        },

        dialog(item) {
            //console.log(item)
            if (Array.isArray(item)) {
                this.itemArchive = item;
            } else {
                this.itemArchive.push(item);
            }
            this.isVisible = true;
        },
        successPost(val) {
            if (val) {
                this.dataEdit = null;
                this.showModal = !this.showModal;
                this.getData();
            }
        },
        successConfirm(val) {
            if (val) {
                this.titleDialog = "Result";
                this.getData();
            }
        },
        closeModal() {
            this.dataEdit = null;
            this.showModal = !this.showModal;
            this.showData = !this.showData;
        },
    },
};
</script>

<style>
.input-invalid {
    border-color: red;
}
</style>
