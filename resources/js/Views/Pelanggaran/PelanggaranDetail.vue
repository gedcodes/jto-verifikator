<template>
    <transition name="fade">
        <div class="max-w-full w-full mx-auto animated fadeIn faster" v-if="open">
            <div class="flex justify-between items-center py-2 px-4 sm:px-6 lg:px-8 mb-3 shadow bg-gray-100">
                <h2 class="text-xl tracking-tight font-bold text-gray-900">Detail Pelanggaran</h2>
                <div @click="close" class="text-3xl font-bold text-gray-900 hover:text-red-500 cursor-pointer">
                    &#215;
                </div>
            </div>
            <div class="max-h-full px-2 py-2">
                <div class="md:w-12/12 p-2 md:p-2 w-full mx-auto">
                    <div class="grid grid-cols-1 gap-4 text-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm border-b mb-3 pb-2 devide-y">
                            <div class="text-gray-600 uppercase font-medium">
                                <div>
                                    No Ref : {{ dataItem.no_ref }}
                                </div>
                                <div @click="printpdf"
                                    class="inline-flex items-center justify-center py-2 px-4 text-white bg-yellow-600 rounded-md hover:bg-yellow-500 shadow hover:shadow-lg focus:outline-none focus:shadow-outline cursor-pointer">
                                    <printer-icon class="h-5 w-5 mr-1" /> Print
                                </div>
                            </div>
                            <div class="text-gray-600 uppercase font-medium flex md:justify-end">
                                <div>
                                    <div>
                                        Tanggal Capture : {{ tgl_cap }}
                                    </div>
                                    <div>
                                        Tanggal Verifikasi : {{ tgl }}
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="flex justify-end">
                                <img :src="dataItem.qrcode_url" class="w-24" />
                            </div> -->
                        </div>
                        <div>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 text-sm mb-2">
                                <div class="col-span-2 md:border-r md:border-b-0 border-b">
                                    <div class="grid grid-cols-2 gap-2 text-sm mb-4 devide-y">
                                        <div class="text-gray-400 uppercase font-semibold">
                                            DETAIL CAPTURE
                                        </div>
                                        <!-- <div class="flex justify-end text-gray-400 mr-2">{{ tgl_capture }}</div> -->
                                    </div>
                                    <div class="flex flex-wrap justify-center">
                                        <div v-for="(item, index) in capture" :key="index" class="relative mx-2 my-2">
                                            <Image :src="item.img_url" :classes="capture.length > 1 ? 'w-64' : 'w-full'"
                                                :scale="5" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <div class="border-b">
                                        <div class="grid grid-cols-2 gap-2 text-sm md:mb-4 mb-2 devide-y">
                                            <div>
                                                <div class="text-gray-400 uppercase font-semibold mb-2">
                                                    DATA KENDARAAN
                                                </div>
                                                <div v-if="plat.length > 0" class="flex space-x-1">
                                                    <div v-for="(item, index) in plat" :key="index">
                                                        <Image :src="item.img_url" class="w-28" />
                                                    </div>
                                                </div>
                                                <div v-else class="grid grid-cols-2 gap-2">
                                                    <Image :src="noPlat" class="w-full h-full" />
                                                </div>
                                                <div class="text-gray-400 mt-2">
                                                    Device : {{ device.length > 1 ? device.join(", ") : device.toString() }}
                                                </div>
                                            </div>
                                            <div class="flex justify-end">
                                                <img :src="dataItem.qrcode_url" class="w-20" />
                                            </div>
                                        </div>
                                        <table
                                            class="border-separate border border-slate-400 w-full font-poppins text-xs mb-4">
                                            <tbody>
                                                <tr>
                                                    <td class="border border-slate-300 p-2 w-1/5">No Kendaraan</td>
                                                    <td class="border border-slate-300 p-2">
                                                        {{ dataItem?.no_kendaraan }}
                                                    </td>
                                                    <td class="border border-slate-300 p-2 w-1/5">No Uji</td>
                                                    <td class="border border-slate-300 p-2 w-1/5">
                                                        {{ dataItem?.no_uji }}
                                                    </td>
                                                </tr>
                                                <tr class="bg-gray-200">
                                                    <td class="border border-slate-300 p-2 w-1/5">Pemilik</td>
                                                    <td class="border border-slate-300 p-2">
                                                        {{ dataItem?.nama_pemilik }}
                                                    </td>
                                                    <td class="border border-slate-300 p-2 w-1/5">Masa Berlaku</td>
                                                    <td class="border border-slate-300 p-2"
                                                        :class="{ 'text-red-600': dataItem.tgl_masa_berlaku < date }">
                                                        {{ masaBerlaku }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-slate-300 p-2 w-1/4">Alamat</td>
                                                    <td class="border border-slate-300 p-2">
                                                        {{ dataItem?.alamat_pemilik }}
                                                    </td>
                                                    <td class="border border-slate-300 p-2 w-1/4">JBI</td>
                                                    <td class="border border-slate-300 p-2">
                                                        {{ dataItem?.jbi_uji }} Kg
                                                    </td>
                                                </tr>
                                                <tr class="bg-gray-200">
                                                    <td class="border border-slate-300 p-2 w-1/4">Jenis Kendaraan</td>
                                                    <td class="border border-slate-300 p-2">
                                                        {{ dataItem?.jenis_kendaraan }}
                                                    </td>
                                                    <td class="border border-slate-300 p-2 w-1/4">Berat Timbang</td>
                                                    <td class="border border-slate-300 p-2">
                                                        {{ dataItem?.berat_timbang }} Kg
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-slate-300 p-2 w-1/4">Sumbu</td>
                                                    <td class="border border-slate-300 p-2">
                                                        {{ dataItem?.sumbu }}
                                                    </td>
                                                    <td class="border border-slate-300 p-2 w-1/4">Berat Lebih</td>
                                                    <td class="border border-slate-300 p-2"
                                                        :class="{ 'text-red-600': dataItem.kelebihan_berat > 0 }">
                                                        {{ dataItem?.kelebihan_berat }} Kg
                                                    </td>
                                                </tr>
                                                <tr class="bg-gray-200">
                                                    <td class="border border-slate-300 p-2 w-1/4">Kepemilikan</td>
                                                    <td class="border border-slate-300 p-2"
                                                        v-if="dataItem.kategori_kepemilikan_id === 1">PERSEORANGAN</td>
                                                    <td class="border border-slate-300 p-2"
                                                        v-else-if="dataItem.kategori_kepemilikan_id === 2">PERUSAHAAN
                                                    </td>
                                                    <td class="border border-slate-300 p-2" v-else></td>
                                                    <td class="border border-slate-300 p-2 w-1/4">Persentase</td>
                                                    <td class="border border-slate-300 p-2"
                                                        :class="{ 'text-red-600': dataItem.prosen_lebih > 0 }">
                                                        {{ dataItem?.prosen_lebih }} %
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-5 md:border-b devide-y">
                                        <div class="md:border-r border-b md:border-b-0">
                                            <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                                                <div class="text-gray-400 uppercase font-semibold">
                                                    PELANGGARAN
                                                </div>
                                                <div>&nbsp;</div>
                                            </div>
                                            <div v-for="(item, index) in dataItem.detailpelanggaran" :key="index"
                                                class="grid grid-cols-1 text-xs mb-4 devide-y">
                                                <div class="text-gray-500 flex">
                                                    <div class="mr-2 font-semibold">
                                                        -
                                                    </div>
                                                    <div>
                                                        {{ item.deskripsi }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="border-b md:border-b-0">
                                            <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                                                <div class="text-gray-400 uppercase font-semibold">
                                                    PASAL
                                                </div>
                                                <div>&nbsp;</div>
                                            </div>
                                            <div v-for="(item, index) in dataItem.detailpasal" :key="index"
                                                class="grid grid-cols-1 text-xs mb-4 devide-y">
                                                <div class="text-gray-500 flex">
                                                    <div class="mr-2 font-semibold">
                                                        -
                                                    </div>
                                                    <div>
                                                        {{ item.desk_pasal }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
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
import CaptureService from "../../service/CaptureService";
import QrcodeVue from 'qrcode.vue';
import moment from 'moment';
import Image from "../../components/Image.vue";
moment.locale('id');
export default {
    components: {
        QrcodeVue,
        Image,
        PrinterIcon,
    },
    data() {
        return {
            open: true,
            date: moment().format("YYYY-MM-DD"),
            tgl: moment(this.dataItem.tgl_pelanggaran).format("DD-MM-YYYY HH:mm:ss"),
            tgl_cap: moment(this.dataItem.tgl_capture).format("DD-MM-YYYY HH:mm:ss"),
            value: 'https://192.168.5.111:8021/api/verifikasi/pelanggaran/active/exportpdf?id=',
            size: 100,
            capture: [],
            plat: [],
            device: [],
            //tgl_capture: "",
        };
    },
    props: {
        dataItem: {
            type: Object,
            default: {},
        },
    },
    created() {
        this.getCapture();
        this.getPlat();
    },
    emits: ["close", "print-item"],

    methods: {
        printpdf() {
            this.$emit(
                "print-item", this.dataItem
            );
        },
        getCapture() {
            this.dataItem.detailcapture.filter((val) => val.is_plat === false).forEach((e) => {
                this.capture.push(e);
                if (e.device_id) {
                    this.device.push(e.device_id == 1 ? "WIM" : "LHR");
                }
                // else {
                //     this.device.map((v) => {
                //         if (v !== this.dataItem.device.nama) {
                //             this.device.push(this.dataItem.device.nama);
                //         }

                //     });
                // }
            });
            //console.log(this.capture);
        },
        getPlat() {
            this.dataItem.detailcapture.filter((val) => val.is_plat === true).forEach((e) => {
                this.plat.push(e);
                // if (this.tgl_capture === "") {
                //     this.tgl_capture = e.tgl_capture;
                // }
            });
            //console.log(this.plat);
        },
        close() {
            this.open = false;
            this.$emit("close");
        },
    },
    computed: {
        masaBerlaku() {
            return moment(this.dataItem.tgl_masa_berlaku).format('DD-MM-YYYY');
        },
        tgl_capture() {
            return moment(this.capture[0].tgl_capture).format('DD-MM-YYYY HH:mm:ss');
        },
        noPlat() {
            return localStorage.getItem('pathUrl') + "/images/noimage.png";
        }
        // maxWidth() {
        //     switch (this.width) {
        //         case "xs":
        //             return "w-3/12 top-20 z-10";
        //         case "sm":
        //             return "w-5/12 top-20 z-10";
        //         case "md":
        //             return "w-8/12 top-10 z-10";
        //         case "lg":
        //             return "w-11/12 top-10 z-10";
        //         case "full":
        //             return "w-full top-5 z-10";
        //     }
        // },
    },
    mounted() {
        const onEscape = (e) => {
            if (e.key === "Esc" || e.key === "Escape") {
                this.close();
            }
        };

        document.addEventListener("keydown", onEscape);

        // this.$once("hook:beforeDestroy", () => {
        //     document.removeEventListener("keydown", onEscape);
        // });
    },
    beforeDestroy() {
        document.removeEventListener("keydown", this.onEscape);
    },
};
</script>
<style>
.fade-enter {
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 500ms ease-out;
}

.fade-leave-to {
    opacity: 0;
}
</style>
