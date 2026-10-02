<template>
    <div v-if="showData">
        <div class="max-w-full w-full mx-auto py-3 px-4 sm:px-6 lg:px-8 mb-3 shadow bg-white">
            <h2 class="text-xl tracking-tight font-bold text-gray-900">Data Pelanggaran</h2>
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
                    <Form @submit="filter">
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <m-select-2 v-model="jenis_pelanggaran" :selectedField="jenis_pelanggaran"
                                    name="jenis_pelanggaran" :options="optionJenisPelanggaran"
                                    labelTitle="Jenis Pelanggaran" placeholder="Pilih Jenis Pelanggaran"
                                    inputTextSize="xs" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <m-select-2 v-model="shift" :selectedField="shift" name="shift" :options="optionShift"
                                        labelTitle="Shift" placeholder="Pilih Shift" inputTextSize="xs" />
                                </div>
                                <div>
                                    <m-select-2 v-model="regu" :selectedField="regu" name="regu" :options="optionRegu"
                                        labelTitle="Regu" placeholder="Pilih Regu" inputTextSize="xs" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label block mb-1 text-gray-600 font-poppins text-sm">
                                        <span class="ml-1">Dari</span>
                                    </label>
                                    <Datepicker v-model="tgl_dari" format="dd-MM-yyyy" model-type="yyyy-MM-dd"
                                        :max-date="tgl_sampai" autoApply />
                                </div>
                                <div>
                                    <label class="form-label block mb-1 text-gray-600 font-poppins text-sm">
                                        <span class="ml-1">Sampai</span>
                                    </label>
                                    <Datepicker v-model="tgl_sampai" format="dd-MM-yyyy" model-type="yyyy-MM-dd"
                                        :max-date="new Date()" :min-date="tgl_dari" autoApply />
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 mb-2">
                            <div class="flex flex-row space-x-2 justify-end">
                                <button type="submit"
                                    class="inline-flex items-center justify-center py-2 px-4 text-white bg-blue-600 rounded-md hover:bg-blue-500 shadow hover:shadow-lg focus:outline-none focus:shadow-outline cursor-pointer">
                                    <filter-icon class="h-5 w-5 mr-1" /> Filter
                                </button>
                                <div v-if="busyPrint"
                                    class="flex justify-center items-center content-end p-1 px-6 rounded-md bg-yellow-600 text-bold shadow">
                                    <circle-svg stroke="#ffffff" class="w-6 h-6" />
                                </div>
                                <div v-else @click="printpdf"
                                    class="inline-flex items-center justify-center py-2 px-4 text-white bg-yellow-600 rounded-md hover:bg-yellow-500 shadow hover:shadow-lg focus:outline-none focus:shadow-outline cursor-pointer">
                                    <printer-icon class="h-5 w-5 mr-1" /> Print
                                </div>
                                <div v-if="busyPdf"
                                    class="flex justify-center items-center content-end p-1 px-6 rounded-md bg-red-600 text-bold shadow">
                                    <circle-svg stroke="#ffffff" class="w-6 h-6" />
                                </div>
                                <div v-else @click="exportpdf"
                                    class="inline-flex items-center justify-center py-1 px-4 text-white bg-red-600 rounded-md hover:bg-red-500 shadow hover:shadow-lg focus:outline-none focus:shadow-outline cursor-pointer">
                                    <DocumentDownloadIcon class="h-5 w-5 mr-1" /> PDF
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
            <pelanggaran-crud ref="table" titleCrud="PELANGGARAN" :loading="loading" :headers="headers" :items="items"
                textSize="sm" :checboxColWidth="30" @print-item="doPrint" @view-item="doEdit" @delete-item="dialog">
            </pelanggaran-crud>
            <div v-if="totalPages > 0" class="bg-white shadow-md rounded-b-md px-4 py-3">
                <Page :total-pages="totalPages" :total="total" :per-page="perPage" :current-page="currentPage"
                    :has-more-pages="currentPage < totalPages" @pagechanged="onPageChanged">
                    <template #rows>
                        <select v-model="perPage" @change="onPerPageChanged"
                            class="rounded-sm border border-gray-100 px-3 py-2 text-gray-600 text-sm">
                            <option :value="10">10</option>
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                            <option :value="100">100</option>
                        </select>
                    </template>
                </Page>
            </div>
        </div>
    </div>
    <PelanggaranDetail v-if="showModal" v-on:close="closeModal" :dataItem="dataEdit" @print-item="doPrint">
    </PelanggaranDetail>

    <confirm-dialog ref="confirmDialogue"></confirm-dialog>
    <transition name="fade">
        <div v-if="isVisible"
            class="min-w-screen h-screen animated fadeIn faster fixed left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none"
            id="confirmModal">
            <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
            <div class="w-full max-w-lg p-5 relative mx-auto my-auto rounded-xl shadow-lg bg-white">
                <!--content-->
                <div v-if="busy"
                    class="flex flex-col text-center justify-center items-center content-center p-2 px-6 rounded-lg bg-white text-bold">
                    <div>
                        <circle-svg stroke="#1e293b" class="w-12 h-12 text-center" />
                    </div>
                    <p>Tunggu hingga proses selesai</p>
                </div>
                <div v-else class="">
                    <!--body-->
                    <errors v-if="errorsArchive" :content="errorsArchive" @close="errorsArchive = null" />
                    <div class="text-center p-5 flex-auto justify-center">
                        <h2 class="text-xl font-bold py-4">{{ title }}</h2>
                        <p class="text-sm text-gray-500 px-8">
                            {{ message }}
                        </p>

                    </div>
                    <div>
                        <label class="form-label block mb-1 text-gray-600 font-poppins" for="label"><span
                                class="text-red-600">*</span>
                            <span class="ml-1">Keterangan</span></label>
                        <input name="keterangan" id="keterangan" type="text" v-model="keterangan"
                            placeholder="Masukan Keterangan"
                            class="px-4 py-2 text-sm leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md appearance-none outline-none border border-gray-300 focus:border-blue-600 focus:font-normal focus:shadow"
                            :class="{
                                'border-red-400': errorMessage
                            }" />
                        <p class="text-red-600 mt-1 text-xs" v-show="errorMessage">
                            {{ errorMessage }}
                        </p>
                    </div>
                    <!--footer-->
                    <div class="p-3 mt-2 text-center items-center space-x-4 md:block">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <m-button id="btnCancel" outline name="btnCancel" :title="cancelButton"
                                    class="mb-2 md:mb-0 bg-white px-5 py-4 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-md hover:shadow-lg hover:bg-gray-100"
                                    @click="doCancel">
                                </m-button>
                            </div>

                            <div class="flex w-full justify-end items-end content-end space-x-2">
                                <m-button id="btnHapus" icon name="btnSimpan" color="red" :title="okButton"
                                    iconName="archive"
                                    class="mb-2 md:mb-0 bg-red-500 border border-red-500 px-8 py-4 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-red-600"
                                    @click="doArchive">
                                </m-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
<script>
import { FilterIcon, TrashIcon, PencilIcon, PencilAltIcon, PrinterIcon, DocumentTextIcon, DocumentDownloadIcon } from "@heroicons/vue/outline";
import AppIcon from "@/js/components/icon/AppIcon.vue";
import { Form, Field, ErrorMessage, FieldArray } from "vee-validate";
import * as Yup from "yup";
import excel from "@/js/components/icon/excel.vue";
import DeviceService from "@/js/service/DeviceService.js";
import PelanggaranService from "@/js/service/PelanggaranService.js";
import PelanggaranCrud from "./PelanggaranCrud.vue";
import MCrud from "@/js/components/crud/MCrud.vue";
import ModalDialog from "@/js/components/modal/ModalDialog.vue";
import ConfirmDialog from "@/js/components/confirm/ConfirmDialog.vue";
import ArchiveDialog from "@/js/components/confirm/ArchiveDialog.vue";
import MButton from "@/js/components/form/MButton.vue";
import ShiftService from "../../service/ShiftService";
import ReguService from "../../service/ReguService";
import JenisPelanggaranService from '../../service/JenisPelanggaranService';
import PelanggaranDetail from "./PelanggaranDetail.vue";
import Datepicker from '@vuepic/vue-datepicker';
import moment from 'moment';
import MInput from '../../components/form/MInput.vue'
import Errors from "@/js/components/Errors.vue";
import CircleSvg from "@/js/components/CircleSvg.vue";
import MSelect2 from '../../components/form/MSelect2.vue';
import Print from '../../components/Print';
import Page from '../../components/Page.vue';

export default {
    components: {
        MCrud,
        AppIcon,
        ModalDialog,
        MButton,
        ConfirmDialog,
        PelanggaranCrud,
        PelanggaranDetail,
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
        DocumentTextIcon,
        DocumentDownloadIcon,
        Page
    },
    data() {
        const schema = Yup.object().shape({
            jenis_pelanggaran: Yup.object(),
            shift: Yup.object(),
            regu: Yup.object(),
        });
        return {
            schema,
            errors: null,
            errorsArchive: null,
            headers: [
                { text: "AKSI", width: 60, value: "operation" },
                {
                    text: "CAPTURE",
                    value: "photo",
                    width: 80,
                },
                {
                    text: "WAKTU",
                    value: "tgl_pelanggaran",
                    width: 80,
                    sortable: true,
                },
                {
                    text: "DEVICE",
                    value: "nm_device",
                    width: 80,
                },
                {
                    text: "NO KENDARAAN",
                    value: "no_kendaraan",
                    width: 80,
                },
                {
                    text: "MASA BERLAKU",
                    value: "tgl_masa_berlaku",
                    width: 80,
                },
                {
                    text: "JENIS KENDARAAN",
                    value: "jenis_kendaraan",
                    width: 100,
                },
                {
                    text: "PELANGGARAN",
                    value: "detail_pelanggaran",
                    width: 120,
                },
                {
                    text: "PASAL",
                    value: "detail_pasal",
                    width: 200,
                },
                {
                    text: "OPERATOR",
                    value: "nama_petugas",
                    width: 80,
                },
            ],
            items: [],
            loading: false,
            showModal: false,
            titleDialog: "Konfirmasi",
            dataEdit: {},
            showData: true,
            tgl_dari: moment().format('YYYY-MM-DD'),
            tgl_sampai: moment().format('YYYY-MM-DD'),
            no_kendaraan: null,
            keterangan: null,
            errorMessage: null,
            busy: false,
            title: "Konfirmasi",
            message: "Anda yakin ingin menyimpan data ke archive ?",
            okButton: "YA",
            cancelButton: "TIDAK",
            isVisible: false,
            itemArchive: [],
            optionShift: [],
            optionRegu: [],
            optionJenisPelanggaran: [],
            jenis_pelanggaran: null,
            shift: null,
            regu: null,
            busyPrint: false,
            busyExport: false,
            busyPdf: false,
            currentPage: 1,
            perPage: 25,
            total: 0,
            totalPages: 0,
            serverOptions: {
                page: 1,
                rowsPerPage: 25,
                sortBy: 'tgl_pelanggaran',
                sortType: 'desc'
            }
        };
    },
    watch: {
        keterangan: function (val) {
            if (val !== '' || val !== null) {
                this.errorMessage = null
            }
        }
    },
    created() {
        this.getData();
        this.getJP();
        this.getShift();
        this.getRegu();
    },
    emits: ["data-table"],
    methods: {
        async getData() {
            this.items = [];
            this.loading = true;
            await PelanggaranService.getAllActive({
                tgl_dari: this.tgl_dari,
                tgl_sampai: this.tgl_sampai,
                jenis_pelanggaran: this.jenis_pelanggaran ? this.jenis_pelanggaran.value : null,
                shift: this.shift ? this.shift.value : null,
                regu: this.regu ? this.regu.value : null,
                page: this.currentPage,
                limit: this.perPage,
            })
                .then((response) => {
                    console.log(response)
                    if (response.success) {
                        if (response.data) {
                            this.items = [];
                            for (var row of response.data) {
                                var petugas = "";
                                if (row.petugas === null) {
                                    petugas = row.created_by.nama_lengkap;
                                } else {
                                    petugas = row.petugas.nama;
                                }

                                this.items.push({
                                    ...row,
                                    nama_petugas: petugas,
                                    nama_device: row.device.nama,
                                });
                            }
                            
                            // Update pagination info dari meta
                            if (response.meta) {
                                this.total = response.meta.total || 0;
                                this.totalPages = response.meta.totalPages || 0;
                                this.currentPage = response.meta.page || 1;
                                this.perPage = response.meta.limit || 25;
                            }
                            
                            this.loading = false;
                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                        this.items = [];
                        this.total = 0;
                        this.totalPages = 0;
                        this.loading = false;
                    }
                })
                .catch((err) => {
                    this.items = [];
                    this.total = 0;
                    this.totalPages = 0;
                    this.loading = false;
                });

        },
        getShift() {
            this.optionShift = [];
            ShiftService.getAllActive()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            for (var row of response.data) {
                                this.optionShift.push({
                                    label: row.nama,
                                    value: row.id,
                                });
                            }
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
            this.optionRegu = [];
            ReguService.getAllActive()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            for (var row of response.data) {
                                this.optionRegu.push({
                                    label: row.nama,
                                    value: row.id,
                                });
                            }
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
            this.optionJenisPelanggaran = [];
            JenisPelanggaranService.getAll()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            for (var row of response.data) {
                                this.optionJenisPelanggaran.push({
                                    label: row.nama,
                                    value: row.id,
                                    kode: row.kode,
                                });
                            }
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

        filter(val) {
            // Reset ke page 1 saat filter berubah
            this.currentPage = 1;
            this.getData();
        },
        
        onPageChanged(page) {
            this.currentPage = page;
            this.getData();
        },
        
        onPerPageChanged() {
            // Reset ke page 1 saat perPage berubah
            this.currentPage = 1;
            this.getData();
        },

        async printpdf() {
            this.busyPrint = true;
            var data = {
                ispdf: 0,
                tgl_dari: this.tgl_dari,
                tgl_sampai: this.tgl_sampai,
                jenis_pelanggaran: this.jenis_pelanggaran ? this.jenis_pelanggaran.value : null,
                shift: this.shift ? this.shift.value : null,
                regu: this.regu ? this.regu.value : null,
            }

            await PelanggaranService.laporanpdf(data)
                .then((res) => {
                    this.busyPrint = false;
                    Print.openPrintWindow(res);
                })
                .catch((err) => {
                    this.busyPrint = false;
                    console.log(err);
                })
        },

        async exportpdf() {
            this.busyPdf = true;
            var data = {
                ispdf: 1,
                tgl_dari: this.tgl_dari,
                tgl_sampai: this.tgl_sampai,
                jenis_pelanggaran: this.jenis_pelanggaran ? this.jenis_pelanggaran.value : null,
                shift: this.shift ? this.shift.value : null,
                regu: this.regu ? this.regu.value : null,
            }

            await PelanggaranService.laporanpdf(data)
                .then((res) => {
                    this.busyPdf = false;
                    window.open(res.data.url_file, '_blank');
                })
                .catch((err) => {
                    this.busyPdf = false;
                    console.log(err);
                })
        },

        async exportexcel() {
            this.busyExport = true;
            var data = {
                tgl_dari: this.tgl_dari,
                tgl_sampai: this.tgl_sampai,
                jenis_pelanggaran: this.jenis_pelanggaran ? this.jenis_pelanggaran.value : null,
                shift: this.shift ? this.shift.value : null,
                regu: this.regu ? this.regu.value : null,
            }

            await PelanggaranService.laporanexcel(data)
                .then((res) => {
                    this.busyExport = false;
                    window.open(res.data.url_file, '_blank');

                })
                .catch((err) => {
                    this.busyExport = false;
                    console.log(err);
                })
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
        },
        async doPrint(item) {
            console.log(item);
            await PelanggaranService.exportOne({
                id: item.id,
                kuppkb: item.kode_uppkb,
            })
                .then((response) => {

                    Print.openPrintWindow(response);

                })
        },

        dialog(item) {
            if (Array.isArray(item)) {
                this.itemArchive = item;
            } else {
                this.itemArchive.push(item);
            }
            this.isVisible = true;
        },

        doCancel() {
            this.itemArchive = [];
            this.keterangan = null;
            this.errorMessage = null;
            this.isVisible = false;
        },

        async doArchive() {
            console.log(this.itemArchive);
            if (this.keterangan) {
                let id = [];
                this.busy = true

                this.itemArchive.forEach((v) => {
                    id.push(v.id);
                })

                console.log(id);

                if (id.length > 0) {

                    PelanggaranService.archiveArr({
                        id: id.toString(),
                        keterangan: this.keterangan,
                    }).then((res) => {
                        if (res.success) {
                            this.success = res.success;
                            this.getData();
                            this.busy = false;
                            this.doCancel();
                        } else {
                            this.busy = res.data.success;
                            this.errorsArchive = res.data.message;
                        }
                    });
                } else {
                    this.$refs.table.setError("ID Tidak Tersedia");
                }
            } else {
                this.errorMessage = 'Keterangan harap diisi';
            }
        },

        selectedShift(val) {
            console.log(val);
            this.shift = val;
        },

        selectedRegu(val) {
            console.log(val);
            this.regu = val;
        },

        selectedJP(val) {
            console.log(val);
            this.jenis_pelanggaran = val;
        },

        deselectedJP(val) {
            console.log(val);
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
        resetForm() {
            this.dataEdit = {
                id: null,
                kementerian_id: null,
                bptd_id: null,
                provinsi_id: null,
                kota_kab_id: null,
                jenis_perangkat_id: null,
                vendor_id: null,
                gateway_route: null,
                deskripsi: null,
                kementerian: null,
                vendor: null,
                jenisperangkat: null,
                bptd: null,
                provinsi: null,
                kotakab: null,
                is_active: true,
            };
        },
    },
};
</script>
