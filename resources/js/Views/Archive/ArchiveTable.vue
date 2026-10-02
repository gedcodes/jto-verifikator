<template>
    <div v-if="showData">
        <div class="max-w-full w-full mx-auto py-3 px-4 sm:px-6 lg:px-8 mb-3 shadow bg-white">
            <h2 class="text-xl tracking-tight font-bold text-gray-900">Data Archive Verifikasi</h2>
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
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
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
                            </div>
                        </div>
                    </Form>
                </div>
            </div>
            <archive-crud ref="table" titleCrud="PELANGGARAN" :loading="loading" :headers="headers" :items="items"
                textSize="sm" :checboxColWidth="30" @verif-item="doVerif">
            </archive-crud>
        </div>
    </div>
</template>
<script>
import { FilterIcon, TrashIcon, PencilIcon, PencilAltIcon, PrinterIcon } from "@heroicons/vue/outline";
import AppIcon from "@/js/components/icon/AppIcon.vue";
import excel from "@/js/components/icon/excel.vue";
import { Form, Field, ErrorMessage, FieldArray } from "vee-validate";
import PelanggaranService from "@/js/service/PelanggaranService.js";
import ArchiveService from "@/js/service/ArchiveService.js";
import ShiftService from "../../service/ShiftService";
import ReguService from "../../service/ReguService";
import ArchiveCrud from "./ArchiveCrud.vue";
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
import MSelect2 from '../../components/form/MSelect2.vue';

export default {
    components: {
        MCrud,
        AppIcon,
        ModalDialog,
        MButton,
        ConfirmDialog,
        ArchiveCrud,
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
    },
    data() {
        return {
            errors: null,
            errorsArchive: null,
            headers: [
                { text: "AKSI", width: 60, value: "operation" },
                {
                    text: "CAPTURE",
                    width: 80,
                    value: "gambar"
                },
                {
                    text: "WAKTU",
                    value: "tgl_archive",
                    width: 80,
                    sortable: true,
                },
                {
                    text: "DEVICE",
                    value: "nama_device",
                    width: 40,
                    sortable: true,
                },
                {
                    text: "NO KENDARAAN",
                    value: "no_kendaraan",
                    width: 60,
                    sortable: true,
                },
                {
                    text: "KETERANGAN",
                    value: "keterangan",
                    width: 100,
                },
                {
                    text: "OPERATOR",
                    value: "nama_petugas",
                    width: 80,
                    sortable: true,
                },
                // {
                //     text: "STATUS VERIFIKASI",
                //     value: "is_active",
                //     width: 60,
                //     sortable: true,
                // },
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
            shift: null,
            regu: null,
            dataItem: {
                tgl_pelanggaran: null,
                no_ref: '',
                shift_id: null,
                regu_id: null,
                no_kendaraan: "",
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
                tgl_capture: null,
            },
            jenis_pelanggaran: null,
            pasal: null,
        };
    },
    computed: {
        user() {
            return this.$store.getters.user
        },
    },
    created() {
        this.getData();
        this.getShift();
        this.getRegu();
    },
    emits: ["data-table"],
    methods: {
        async getData() {
            this.items = [];
            this.loading = true;
            await ArchiveService.getAll({
                tgl_dari: this.tgl_dari,
                tgl_sampai: this.tgl_sampai,
                shift: this.shift ? this.shift.value : null,
                regu: this.regu ? this.regu.value : null,
            })
                .then((response) => {
                    console.log(response)
                    if (response.success) {
                        if (response.data) {
                            for (var row of response.data) {
                                console.log(row);
                                var petugas = "";
                                if (row.petugas === null) {
                                    petugas = row.created_by.nama_lengkap;
                                } else {
                                    petugas = row.petugas.nama;
                                }

                                //this.items.push(row);
                                this.items.push({
                                    ...row,
                                    nama_petugas: petugas,
                                    nama_device: row.device.nama,
                                });
                            }
                            // sthis.items = response.data;
                            this.loading = false;
                        }
                        //console.log(this.items);
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                        this.items = [];
                    }
                })
                .catch((err) => {
                    //this.errors = err.response.data;
                    this.items = [];
                });

            //console.log(this.items);
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
        filter() {
            // var data = {
            //     no_kendaraan: this.no_kendaraan,
            //     tgl_dari: this.tgl_dari,
            //     tgl_sampai: this.tgl_sampai,
            // }

            // console.log(data);

            this.getData();
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

        doCancel() {
            this.itemArchive = [];
            this.keterangan = null;
            this.errorMessage = null;
            this.isVisible = false;
        },

        async doVerif(item) {
            this.dataItem = {
                tgl_pelanggaran: item.tgl_archive,
                no_ref: item.no_ref,
                shift_id: item.shift_id,
                regu_id: item.regu_id,
                no_kendaraan: item.no_kendaraan,
                no_uji: item.no_uji,
                tgl_uji: item.tgl_uji,
                tgl_masa_berlaku: item.tgl_masa_berlaku,
                nama_pemilik: item.nama_pemilik,
                alamat_pemilik: item.alamat_pemilik,
                jbi_uji: item.jbi_uji,
                mst_uji: item.mst_uji,
                jenis_kendaraan_id: item.jenis_kendaraan_id,
                jenis_kendaraan: item.jenis_kendaraan,
                sumbu_id: item.sumbu_id,
                sumbu: item.sumbu,
                kategori_kepemilikan_id: item.kategori_kepemilikan_id,
                kepemilikan: '',
                device_id: item.device_id,
                keterangan: item.keterangan,
                berat_timbang: item.berat_timbang,
                lebih_berat: 0,
                prosen_lebih: 0,
                panjang_ukur: item.panjang_ukur,
                panjang_uji: 0,
                panjang_toleransi: 0,
                lebih_panjang: 0,
                lebar_ukur: item.lebar_ukur,
                lebar_uji: 0,
                lebar_toleransi: 0,
                lebih_lebar: 0,
                tinggi_ukur: item.tinggi_ukur,
                tinggi_uji: 0,
                tinggi_toleransi: 0,
                lebih_tinggi: 0,
                foh_ukur: item.foh_ukur,
                foh_uji: 0,
                foh_toleransi: 0,
                lebih_foh: 0,
                roh_ukur: item.roh_ukur,
                roh_uji: 0,
                roh_toleransi: 0,
                lebih_roh: 0,
                tgl_capture: item.tgl_capture,
            };
            console.log(this.dataItem);
        },

        async doArchive() {
            console.log(this.itemArchive);
            // try {
            //     const ok = await this.$refs.archiveDialogue.show();
            //     // If you throw an error, the method will terminate here unless you surround it wil try/catch
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
            // } catch (err) {
            //     this.$refs.archiveDialogue.showError(err);
            // }
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
