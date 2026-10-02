<template>
    <div
        class="max-w-full w-full mx-auto px-0 sm:px-4 lg:px-6 pt-2 py-10 bg-gradient-to-b from-blue-600 to-blue-400 rounded-b">
        <div class="grid grid-cols-1 md:grid-cols-4 grid-flow-row gap-4">
            <div class="md:col-span-1">
                <div class="bg-gray-100 text-gray-700 rounded-md shadow w-full p-4">
                    <div class="mb-3">
                        <div class="flex justify-between items-center mb-2 pb-1 border-b">
                            <div class="text-sm font-[600]">Data Petugas</div>
                            <div>
                                <span class="h-6 w-6">
                                    <svg class="w-8 h-8 text-orange-600" fill="none" viewBox="0 0 122.88 115.48"
                                        stroke="currentColor">
                                        <path class="st0"
                                            d="M39.54,70.85l8.78,25.83l4.42-15.33l-2.17-2.37c-0.98-1.42-1.19-2.67-0.65-3.74c1.17-2.32,3.6-1.89,5.87-1.89 c2.38,0,5.31-0.45,6.05,2.52c0.25,0.99-0.06,2.04-0.76,3.11l-2.17,2.37l4.42,15.33l7.95-25.83c1.71,1.53,4.4,2.7,7.52,3.68 c0.47-0.56,0.97-1.11,1.49-1.63c4.51-4.51,10.75-7.31,17.64-7.31c6.89,0,13.12,2.79,17.64,7.31c4.51,4.51,7.31,10.75,7.31,17.64 s-2.79,13.12-7.31,17.64c-4.51,4.51-10.75,7.31-17.64,7.31c-5.22,0-10.07-1.61-14.08-4.35H6.07c-3.81-0.29-5.75-2.26-6.07-5.67 c0.97-6.57,1.24-15.16,5.24-20.45c1.45-1.92,3.26-3.34,5.26-4.45C16.82,77.04,33.8,76.01,39.54,70.85L39.54,70.85z M85.11,92.67 l4.54-4.76l6.55,6.3l10.2-13.59l5.07,4.09c-2.39,2.96-10.91,14.65-12.77,16.17c-1.53,1.25-3.73,1.25-5.15-0.12L85.11,92.67 L85.11,92.67z M112.95,75.52c-3.84-3.84-9.15-6.22-15.02-6.22c-5.87,0-11.18,2.38-15.02,6.22c-3.84,3.84-6.22,9.15-6.22,15.02 c0,5.87,2.38,11.18,6.22,15.02c3.84,3.84,9.15,6.22,15.02,6.22c5.87,0,11.18-2.38,15.02-6.22c3.84-3.84,6.22-9.15,6.22-15.02 C119.18,84.67,116.8,79.36,112.95,75.52L112.95,75.52z M35.32,33.91c0.68,0.19,1.38,0.21,2.11,0.06l-1.25-9.91 c0.64-2.45,1.62-4.36,2.92-5.76c1.36-1.46,3.06-2.37,5.09-2.76c2.69-0.19,3.5,1.77,6.19,3.49c8.18,5.23,15.1,6.99,25.21,7.12 l-1.25,6.32c-0.17,0.26-0.26,0.58-0.23,0.91c0,0.05,0.01,0.11,0.02,0.16l-0.18,0.9c0.28,0.02,0.55,0.03,0.82,0.02 c0.25,0.15,0.55,0.23,0.87,0.2c1.76-0.16,2.83,0,3.12,0.59c0.4,0.8,0.06,2.43-1.09,5.05l-5.53,9.11c-2.06,3.39-4.14,6.78-6.79,9.25 c-2.54,2.38-5.67,3.96-9.94,3.95c-3.95-0.01-6.93-1.53-9.4-3.78c-2.56-2.34-4.63-5.53-6.59-8.66l-4.91-7.81l0,0l-0.02-0.03 c-1.51-2.25-2.29-4.19-2.33-5.68c-0.01-0.5,0.06-0.94,0.23-1.3c0.15-0.32,0.38-0.59,0.7-0.81C33.62,34.19,34.36,33.98,35.32,33.91 L35.32,33.91L35.32,33.91z M78.98,32.1l0.31-12.72c-0.37-5.17-2.08-9.08-4.81-12.03c-6.69-7.24-19.19-9.1-28.62-5.69 c-1.59,0.57-3.09,1.3-4.46,2.17c-3.89,2.48-7.04,6.09-8.29,10.57c-0.3,1.06-0.5,2.12-0.61,3.18c-0.2,4.46-0.09,9.78,0.23,14.01 c-0.44,0.17-0.84,0.37-1.2,0.61c-0.76,0.51-1.33,1.18-1.7,1.97c-0.35,0.76-0.51,1.62-0.48,2.57c0.06,2.01,0.99,4.47,2.79,7.15 l4.91,7.8c2.07,3.28,4.24,6.64,7.08,9.24c2.94,2.69,6.53,4.51,11.3,4.53c5.11,0.01,8.84-1.88,11.87-4.71 c2.92-2.74,5.12-6.3,7.27-9.85l5.59-9.21c0.03-0.05,0.06-0.11,0.08-0.16l0,0c1.53-3.47,1.86-5.89,1.06-7.49 C80.84,33.05,80.06,32.43,78.98,32.1L78.98,32.1L78.98,32.1z" />
                                    </svg>

                                </span>
                            </div>
                        </div>
                        <div class="text-[13px] font-[550]">Nama : {{ petugas ? petugas.nama : '' }}</div>
                        <div class="text-[13px] font-[550]">Pangkat : {{ petugas ? petugas.pangkat : '' }}</div>
                        <div class="text-[13px] font-[550]">Grup : {{ role ? role.nama : '' }}</div>
                    </div>
                    <!-- <div class="flex items-center justify-between"> -->

                    <div>

                        <h6
                            class="text-[13px] pt-1 font-[550] leading-none tracking-wider text-gray-700 dark:text-primary-light">
                            Data Sudah Diverifikasi Petugas
                        </h6>
                        <span class="text-2xl font-semibold">{{ jml_data_user }}</span>
                        <span class="inline-block px-2 py-px ml-2 text-xs text-orange-600 bg-orange-100 rounded-md">
                            Record
                        </span>

                        <!-- <span class="inline-block px-2 py-px ml-2 text-xs text-green-500 bg-green-100 rounded-md">
                        Capture
                    </span> -->
                    </div>
                    <!-- <div> -->
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
      <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
    </svg> -->

                    <!-- <span>
                                <svg class="w-12 h-12 text-orange-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>

                            </span>
                        </div> -->
                    <!-- </div> -->
                </div>
            </div>
            <div class="md:col-span-3">
                <ResumeWidget :jml_capture="jml_capture" :jml_sdh_verif="jml_sdh_verif"
                    :jml_pelanggaran_now="jml_pelanggaran_now" :jml_pelanggaran_lastnow="jml_pelanggaran_lastnow"
                    :jml_archive_now="jml_archive_now" :jml_pelanggaran_hari_ini="jml_pelanggaran_hari_ini"
                    :jml_archive_hari_ini="jml_archive_hari_ini" :jml_lhr="jml_lhr" :jml_wim="jml_wim" />

                <div class="my-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center justify-between p-4 bg-gray-100 rounded-md dark:bg-darker shadow">
                        <div>
                            <h6
                                class="text-sm font-medium leading-none tracking-wider text-gray-700 dark:text-primary-light">
                                Persentase Pelanggaran Kategori Perusahaan
                            </h6>
                            <span class="text-xl text-gray-700 font-semibold">{{ perusahaan.total }}</span>
                            <span class="inline-block px-2 py-px ml-2 text-xs text-red-500 bg-red-100 rounded-md">
                                Pelanggaran
                            </span>
                        </div>
                        <div>
                            <span class="inline-block font-semibold text-3xl text-red-500 font-sans rounded">
                                {{ perusahaan.persen }}%
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-100 rounded-md dark:bg-darker shadow">
                        <div>
                            <h6
                                class="text-sm font-medium leading-none tracking-wider text-gray-700 dark:text-primary-light">
                                Persentase Pelanggaran Kategori Peseorangan
                            </h6>
                            <span class="text-xl text-gray-700 font-semibold">{{ perseorangan.total }}</span>
                            <span class="inline-block px-2 py-px ml-2 text-xs text-red-500 bg-red-100 rounded-md">
                                Pelanggaran
                            </span>
                        </div>
                        <div>
                            <span class="inline-block font-semibold text-3xl text-red-500 font-sans rounded">
                                {{ perseorangan.persen }}%
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="max-w-full w-full -mt-6 px-4 sm:px-4 lg:px-6">

        <div class="rounded-lg p-2 shadow bg-white">
            <BarChart style="height: 450px;" :chartData="chartData" :title="titleBulan" />
        </div>
    </div>

    <div class="max-w-full w-full h-full px-4 sm:px-4 lg:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="col-span-2">
                <div class="rounded-lg p-2 mt-4 shadow bg-white">
                    <BarChart style="height: 350px;" :chartData="chartDataJam" :title="titleJam" :legend="false" />
                </div>
                <div class="rounded-lg p-2 mt-4 shadow bg-white">
                    <BarChart style="height: 350px;" :chartData="chartDataBerat" :title="titleBerat" :legend="false" />
                </div>
            </div>
            <div class="col-span-1">

                <div class="rounded-lg p-2 mt-4 shadow bg-white">
                    <div class="mt-4 mb-2">
                        <div class="flex justify-center font-normal text-sm">
                            {{ titleJenis }}
                        </div>
                        <div class="flex justify-center font-normal text-sm">
                            {{ judul }}
                        </div>
                    </div>

                    <div class="bg-white">
                        <div v-for="(item, index) in jenis_kend" :key="index"
                            class="grid grid-cols-6 md:grid-cols-8 lg:grid-cols-6 gap-4 mx-2 mt-2 pb-2">
                            <div class="col-span-1 w-12 h-12 rounded-full p-2 flex items-center border" :class="item.color">
                                <img class="w-auto" :src="item.image" alt="" />
                            </div>
                            <div class="col-span-4 md:col-span-6 lg:col-span-4 flex items-center">
                                <div class="font-normal text-base">
                                    {{ item.name }}
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <div class="w-10 bg-gray-50 shadow rounded-2xl flex items-center justify-center">
                                    <div class="font-normal text-base text-gray-800">
                                        {{ item.amount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="max-w-full w-full p-4 sm:p-4 lg:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex justify-center items-center rounded-lg p-2 shadow bg-white">
                <DougnutChart style="height: 400px; width: 400px;" :chartData="chartKpData" />
            </div>
            <div class="rounded-lg p-2 shadow bg-white">
                <BarChart style="height: 400px;" :chartData="chartDataJam" :title="titleJam" />
            </div>
        </div>
    </div>

    <div class="max-w-full w-full p-4 sm:p-4 lg:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-lg p-2 shadow bg-white">
                <BarChart style="height: 400px;" :chartData="chartDataBerat" :title="titleBerat" :legend="false" />
            </div>
            <div class="rounded-lg p-2 shadow bg-white">
                <data-chart style="height: 400px; width: 100%;" :chartData="chartJkData" :title="titleJenis" />
            </div>
        </div>
    </div> -->

    <!-- <div class="max-w-full w-full mx-auto px-0 sm:px-4 lg:px-6 my-2"> -->
    <!-- <canvas id="myChart" class="w-full h-[100px] my-2 mx-4 sm:mx-6 lg:mx-8"></canvas> -->
    <!-- </div> -->

    <!-- <div class="max-w-full w-full mx-auto px-0 sm:px-4 lg:px-6 my-2">
        <div><MapWidget></MapWidget></div>
    </div> -->
</template>

<script>
import MapWidget from "@/js/Views/beranda/MapWidget.vue";
import ResumeWidget from "@/js/Views/beranda/ResumeWidget.vue";
import BarChart from "@/js/Views/beranda/BarChart.vue";
import DougnutChart from "@/js/Views/beranda/DougnutChart.vue";
import DataChart from "@/js/Views/beranda/DataChart.vue";
import BerandaService from "@/js/service/BerandaService.js";
import VueApexCharts from "vue3-apexcharts";
import moment from 'moment';
import { SaveIcon, TrashIcon, PencilIcon, PencilAltIcon, SearchIcon, XIcon, FilterIcon } from "@heroicons/vue/outline";
import Datepicker from '@vuepic/vue-datepicker';
import CircleSvg from "@/js/components/CircleSvg.vue";
//import Chart from 'chart.js/auto';
import ChartDataLabel from 'chartjs-plugin-datalabels';
export default {
    components: {
        MapWidget,
        ResumeWidget,
        apexchart: VueApexCharts,
        Datepicker,
        TrashIcon,
        FilterIcon,
        CircleSvg,
        BarChart,
        DougnutChart,
        DataChart
    },
    computed: {
        user() {
            return this.$store.getters.user;
        },
    },
    data() {
        return {
            items: [],
            loading: false,
            errors: null,
            date: moment().format("YYYY-MM-DD"),
            jml_capture: 0,
            jml_sdh_verif: 0,
            jml_pelanggaran: 0,
            jml_pelanggaran_now: 0,
            jml_pelanggaran_lastnow: 0,
            jml_pelanggaran_hari_ini: 0,
            jml_archive_now: 0,
            jml_archive_hari_ini: 0,
            jml_data_user: 0,
            jml_lhr: 0,
            jml_wim: 0,
            tahun: moment().format('YYYY'),
            tanggal: moment().format('dddd, Do MMMM YYYY'),
            bulanIni: moment().format('MMMM'),
            bulanLalu: moment().subtract(1, 'month').format('MMMM'),
            judul: moment().format('DD MMMM YYYY'),
            options: [],
            bulanDari: {},
            bulanSampai: {},
            chartData: {},
            chartDataJam: {},
            chartJkData: {},
            chartDataBerat: {},
            chartKpData: {},
            titleBulan: 'Data Verifikasi Tahun ' + moment().format('YYYY'),
            titleJenis: 'Data Pelenggaran Perjenis Kendaraan Hari Ini',
            titleKategori: 'Persentase Pelanggaran Perkategori Kepemilikan Bulan Ini',
            titleBerat: 'Data Pelanggaran Kelebihan Muatan Hari Ini - ' + moment().format('DD MMMM YYYY'),
            titleJam: 'Data Pelanggaran Terverifikasi Per Jam - ' + moment().format('dddd, DD MMMM YYYY'),
            opt: {},
            jenis_kend: [],
            kat_pemilik: [],
            perusahaan: {},
            perseorangan: {},
            petugas: null,
            role: null,
        };
    },
    created() {
        this.getResume();
        this.getPerKp();
        this.getPerBulan();
        this.getPerJk();
        this.chartBarPerjam();
        this.getPerBerat();
    },
    methods: {
        getResume() {
            this.loading = true;
            console.log(this.user.petugas_id);
            BerandaService.getResume({
                id: this.user.id,
                petugas_id: this.user.petugas_id,
                role_id: this.user.role_id,
            })
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            var dataList = response.data;
                            console.log(dataList);

                            this.jml_capture = dataList.totalCapture;
                            this.jml_sdh_verif = dataList.totalSudahVerif;
                            this.jml_pelanggaran = dataList.totalPelanggaranAll;
                            this.jml_pelanggaran_now = dataList.totalPelanggaranBulanIni;
                            this.jml_pelanggaran_lastnow = dataList.totalPelanggaranBulanLalu;
                            this.jml_pelanggaran_hari_ini = dataList.totalPelanggaranHariIni;
                            this.jml_archive_now = dataList.totalArchiveBulanIni;
                            this.jml_archive_hari_ini = dataList.totalArchiveHariIni;
                            this.jml_lhr = dataList.totalDeteksiLhr;
                            this.jml_wim = dataList.totalDeteksiWim;
                            this.jml_data_user = dataList.totalDataUser;
                            this.petugas = dataList.petugas;
                            this.role = dataList.role;
                            this.loading = false;
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

        getPerBulan() {
            BerandaService.getPerBulan()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            var dataList = response.data;
                            console.log(dataList);

                            // this.chartDougnut(dataList.chartPerJenisKendaraan);
                            // this.chartBarPerjam(dataList.chartPerJam);

                            this.chartData = {
                                labels: dataList.chartPerBulan.label,
                                datasets: [
                                    {
                                        label: 'Total',
                                        data: dataList.chartPerBulan.total,
                                        backgroundColor: 'rgb(54, 162, 235)',
                                        borderWidth: 1,
                                        order: 2,
                                        type: 'bar',
                                        //barPercentage: 0.3,
                                        barThickness: 50,
                                    },
                                    // {
                                    //     label: 'Verifikasi',
                                    //     data: dataList.chartPerBulan.verifikasi,
                                    //     backgroundColor: 'rgb(75, 192, 192)',
                                    //     borderColor: 'blue',
                                    //     borderWidth: 1,
                                    //     //barPercentage: 0.3,
                                    //     //barThickness: 80,
                                    // },
                                    // {
                                    //     label: 'Archive',
                                    //     data: dataList.chartPerBulan.archive,
                                    //     backgroundColor: 'rgb(255, 99, 132)',
                                    //     borderColor: 'blue',
                                    //     borderWidth: 1,
                                    //     //barPercentage: 0.3,
                                    //     //barThickness: 80,
                                    // },
                                    {
                                        label: 'Terverifikasi',
                                        data: dataList.chartPerBulan.verifikasi,
                                        backgroundColor: 'rgb(255,255,255)',
                                        borderColor: 'rgb(74, 222, 128)',
                                        borderWidth: 3,
                                        pointStyle: 'circle',
                                        pointRadius: 5,
                                        pointHoverRadius: 6,
                                        type: 'line',
                                        order: 0,
                                        tension: 0.2
                                    },
                                    {
                                        label: 'Arsip',
                                        data: dataList.chartPerBulan.archive,
                                        backgroundColor: 'rgb(255,255,255)',
                                        borderColor: 'rgb(255, 99, 132)',
                                        borderWidth: 3,
                                        pointStyle: 'circle',
                                        pointRadius: 5,
                                        pointHoverRadius: 6,
                                        type: 'line',
                                        order: 1,
                                        tension: 0.2
                                    },
                                ]
                            };

                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                });
        },

        chartBarPerjam() {
            BerandaService.getPerJam()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            var val = response.data;
                            //console.log(dataList);

                            // this.chartDougnut(dataList.chartPerJenisKendaraan);
                            // this.chartBarPerjam(dataList.chartPerJam);

                            this.chartDataJam = {
                                labels: val.chartPerJam.label,
                                datasets: [
                                    {
                                        label: 'Pelanggaran',
                                        data: val.chartPerJam.verifikasi,
                                        backgroundColor: 'rgb(54, 162, 235)',
                                        borderWidth: 1,
                                        order: 2,
                                        type: 'bar',
                                        //barPercentage: 0.3,
                                        barThickness: 25,
                                    },
                                ]
                            }

                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                });

        },
        getPerJk() {
            BerandaService.getPerJenisKendaraan()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            var data = response.data;
                            var path = localStorage.getItem('pathUrl');
                            console.log(path);
                            //bg-emerald-400 bg-green-400 bg-red-400 bg-indigo-400 bg-yellow-400 bg-cyan-400 bg-teal-400 bg-sky-400 bg-blue-400 bg-rose-400 bg-pink-400 bg-orange-400 bg-amber-400 bg-lime-400 bg-violet-400 bg-purple-400
                            if (data.length > 0) {
                                for (var row of data) {
                                    this.jenis_kend = [
                                        {
                                            color: 'bg-white',
                                            image: path + '/images/bak_terbuka.png',
                                            name: 'Mobil Barang Bak Terbuka',
                                            amount: row.mobil_barang_bak_terbuka ?? 0
                                        },
                                        {
                                            color: 'bg-white',
                                            image: path + '/images/bak_tertutup.png',
                                            name: 'Mobil Barang Bak Tertutup',
                                            amount: row.mobil_barang_bak_tertutup
                                        },
                                        {
                                            color: 'bg-white',
                                            image: path + '/images/tempelan_bak_terbuka.png',
                                            name: 'Kereta Tempelan Bak Terbuka',
                                            amount: row.kereta_tempelan_bak_terbuka
                                        },
                                        {
                                            color: 'bg-white',
                                            image: path + '/images/penarik.png',
                                            name: 'Mobil Penarik',
                                            amount: row.mobil_penarik
                                        },
                                        {
                                            color: 'bg-white',
                                            image: path + '/images/tangki.png',
                                            name: 'Mobil Tangki',
                                            amount: row.mobil_tangki
                                        },
                                        {
                                            color: 'bg-white',
                                            image: path + '/images/tempelan.png',
                                            name: 'Kereta Tempelan',
                                            amount: row.kereta_tempelan
                                        },
                                        {
                                            color: 'bg-white',
                                            image: path + '/images/khusus.png',
                                            name: 'Kendaraan Khusus',
                                            amount: row.kendaraan_khusus
                                        },
                                        {
                                            color: 'bg-white',
                                            image: path + '/images/gandeng_bak_tertutup.png',
                                            name: 'Kereta Gandeng Bak Tertutup',
                                            amount: row.kereta_gandeng_bak_tertutup
                                        },
                                        {
                                            color: 'bg-white',
                                            image: path + '/images/gandeng_bak_terbuka.png',
                                            name: 'Kereta Gandeng Bak Terbuka',
                                            amount: row.kereta_gandeng_bak_terbuka
                                        },
                                        {
                                            color: 'bg-white',
                                            image: path + '/images/cycle-rickshaw.png',
                                            name: 'Kendaraan Bermotor Roda Tiga',
                                            amount: row.kendaraan_bermotor_roda_tiga
                                        }
                                    ]
                                }
                            } else {
                                this.jenis_kend = [
                                    {
                                        color: 'bg-white',
                                        image: path + '/images/bak_terbuka.png',
                                        name: 'Mobil Barang Bak Terbuka',
                                        amount: 0
                                    },
                                    {
                                        color: 'bg-white',
                                        image: path + '/images/bak_tertutup.png',
                                        name: 'Mobil Barang Bak Tertutup',
                                        amount: 0
                                    },
                                    {
                                        color: 'bg-white',
                                        image: path + '/images/tempelan_bak_terbuka.png',
                                        name: 'Kereta Tempelan Bak Terbuka',
                                        amount: 0
                                    },
                                    {
                                        color: 'bg-white',
                                        image: path + '/images/penarik.png',
                                        name: 'Mobil Penarik',
                                        amount: 0
                                    },
                                    {
                                        color: 'bg-white',
                                        image: path + '/images/tangki.png',
                                        name: 'Mobil Tangki',
                                        amount: 0
                                    },
                                    {
                                        color: 'bg-white',
                                        image: path + '/images/tempelan.png',
                                        name: 'Kereta Tempelan',
                                        amount: 0
                                    },
                                    {
                                        color: 'bg-white',
                                        image: path + '/images/khusus.png',
                                        name: 'Kendaraan Khusus',
                                        amount: 0
                                    },
                                    {
                                        color: 'bg-white',
                                        image: path + '/images/gandeng_bak_tertutup.png',
                                        name: 'Kereta Gandeng Bak Tertutup',
                                        amount: 0
                                    },
                                    {
                                        color: 'bg-white',
                                        image: path + '/images/gandeng_bak_terbuka.png',
                                        name: 'Kereta Gandeng Bak Terbuka',
                                        amount: 0
                                    },
                                    {
                                        color: 'bg-white',
                                        image: path + '/images/cycle-rickshaw.png',
                                        name: 'Kendaraan Bermotor Roda Tiga',
                                        amount: 0
                                    }
                                ]
                                // this.jenis_kend = [
                                //     {
                                //         color: 'bg-yellow-white',
                                //         image: path + '/images/bak_terbuka.png',
                                //         name: 'Mobil Barang Bak Terbuka',
                                //         amount: 0
                                //     },
                                //     {
                                //         color: 'bg-blue-white',
                                //         image: path + '/images/bak_tertutup.png',
                                //         name: 'Mobil Barang Bak Tertutup',
                                //         amount: 0
                                //     },
                                //     {
                                //         color: 'bg-red-white',
                                //         image: path + '/images/tempelan_bak_terbuka.png',
                                //         name: 'Kereta Tempelan Bak Terbuka',
                                //         amount: 0
                                //     },
                                //     {
                                //         color: 'bg-green-white',
                                //         image: path + '/images/penarik.png',
                                //         name: 'Mobil Penarik',
                                //         amount: 0
                                //     },
                                //     {
                                //         color: 'bg-orange-white',
                                //         image: path + '/images/tangki.png',
                                //         name: 'Mobil Tangki',
                                //         amount: 0
                                //     },
                                //     {
                                //         color: 'bg-pink-white',
                                //         image: path + '/images/tempelan.png',
                                //         name: 'Kereta Tempelan',
                                //         amount: 0
                                //     },
                                //     {
                                //         color: 'bg-emerald-white',
                                //         image: path + '/images/khusus.png',
                                //         name: 'Kendaraan Khusus',
                                //         amount: 0
                                //     },
                                //     {
                                //         color: 'bg-sky-white',
                                //         image: path + '/images/gandeng_bak_tertutup.png',
                                //         name: 'Kereta Gandeng Bak Tertutup',
                                //         amount: 0
                                //     },
                                //     {
                                //         color: 'bg-amber-white',
                                //         image: path + '/images/gandeng_bak_terbuka.png',
                                //         name: 'Kereta Gandeng Bak Terbuka',
                                //         amount: 0
                                //     },
                                //     {
                                //         color: 'bg-violet-white',
                                //         image: path + '/images/cycle-rickshaw.png',
                                //         name: 'Kendaraan Bermotor Roda Tiga',
                                //         amount: 0
                                //     }
                                // ]
                            }

                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                });
        },
        // getPerJk() {
        //     BerandaService.getPerJenisKendaraan()
        //         .then((response) => {
        //             if (response.success) {
        //                 if (response.data) {
        //                     var data = response.data;
        //                     //console.log(data);

        //                     this.chartJkData = {
        //                         labels: data.dataPerJenisKendaraan.map((v) => {
        //                             var name = v.jenis_kendaraan.toLowerCase();
        //                             name = name.split(" ")
        //                                 .map(nama =>
        //                                     nama.charAt(0).toUpperCase() +
        //                                     nama.slice(1))
        //                                 .join(" ");
        //                             // var nm = [];
        //                             // name.split(" ").map((v) => {
        //                             //     nm.push(v);
        //                             // });
        //                             return name;
        //                         }),
        //                         datasets: [
        //                             {
        //                                 label: 'Pelanggaran',
        //                                 data: data.chartPerJenisKendaraan.data,
        //                                 backgroundColor: 'rgb(75, 192, 192)',
        //                                 borderWidth: 1,
        //                                 order: 2,
        //                                 type: 'bar',
        //                                 //barPercentage: 0.3,
        //                                 barThickness: 30,
        //                             },
        //                         ]
        //                     };


        //                 }
        //             } else {
        //                 this.errors = {
        //                     message: response.message,
        //                 };
        //             }
        //         })
        //         .catch((err) => {
        //             this.errors = err.response.data;
        //         });
        // },

        getPerBerat() {
            BerandaService.getPerBerat()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            var data = response.data;
                            this.chartDataBerat = {
                                labels: data.chartPerRangeBerat.label,
                                datasets: [
                                    {
                                        label: 'Pelanggaran',
                                        data: data.chartPerRangeBerat.data,
                                        backgroundColor: 'rgb(255, 99, 132)',
                                        borderWidth: 1,
                                        order: 2,
                                        type: 'bar',
                                        //barPercentage: 0.3,
                                        barThickness: 40,
                                    },
                                ]
                            };
                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                });
        },

        getPerKp() {
            BerandaService.getPerKategoriKepemilikan()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            var data = response.data;
                            console.log(data)
                            var total = data.totalAll;
                            var ttlPerusahaan = Math.round(data.dataPerKategoriKepemilikan.perusahaan / total * 100);
                            var ttlPerseorangan = Math.round(data.dataPerKategoriKepemilikan.perseorangan / total * 100);

                            this.perusahaan = {
                                name: 'PERUSAHAAN',
                                total: data.dataPerKategoriKepemilikan.perusahaan ?? 0,
                                persen: isNaN(ttlPerusahaan) ? 0 : ttlPerusahaan,
                            }
                            this.perseorangan = {
                                name: 'PERSEORANGAN',
                                total: data.dataPerKategoriKepemilikan.perseorangan ?? 0,
                                persen: isNaN(ttlPerseorangan) ? 0 : ttlPerseorangan,
                            }

                            // if (data.dataPerKategoriKepemilikan.length > 0) {
                            //     var total = data.totalAll;
                            //     data.dataPerKategoriKepemilikan.forEach((v) => {
                            //         if (v.nama === 'PERUSAHAAN') {
                            //             var raw = v.total / total * 100;
                            //             this.perusahaan = {
                            //                 name: v.nama,
                            //                 total: v.total,
                            //                 persen: Math.round(raw),
                            //             }
                            //         } else {
                            //             var raw = v.total / total * 100;
                            //             this.perseorangan = {
                            //                 name: v.nama,
                            //                 total: v.total,
                            //                 persen: Math.round(raw),
                            //             }
                            //         }
                            //     });
                            // } else {
                            //     this.perusahaan = {
                            //         name: 'PERUSAHAAN',
                            //         total: 0,
                            //         persen: Math.round(0),
                            //     }
                            //     this.perseorangan = {
                            //         name: 'PERSEORANGAN',
                            //         total: 0,
                            //         persen: Math.round(0),
                            //     }
                            // }

                            // this.chartKpData = {
                            //     labels: data.dataPerKategoriKepemilikan.map((v) => {
                            //         var raw = v.total / total * 100;
                            //         var name = v.nama;
                            //         // name = name.split(" ")
                            //         //     .map(nama =>
                            //         //         nama.charAt(0).toUpperCase() +
                            //         //         nama.slice(1))
                            //         //     .join(" ");
                            //         return name + ' : ' + v.total + ' (' + Math.round(raw) + '%)';
                            //     }),
                            //     datasets: [
                            //         {
                            //             label: 'Pelanggaran',
                            //             data: data.dataPerKategoriKepemilikan.map((v) => {
                            //                 var raw = v.total / total * 100;
                            //                 return Math.round(raw);
                            //             }),
                            //             backgroundColor: [
                            //                 'rgb(255, 99, 132)',
                            //                 'rgb(54, 162, 235)',
                            //                 'rgb(255, 159, 64)',
                            //                 'rgb(255, 205, 86)',
                            //                 'rgb(75, 192, 192)',
                            //                 'rgb(153, 102, 255)',
                            //                 'rgb(201, 203, 207)'
                            //             ],
                            //             hoverOffset: 10,
                            //             datalabels: {
                            //                 anchor: 'end'
                            //             }
                            //         },
                            //     ]
                            // };
                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                });
        },

    },
};
</script>

<style>
.chart-container {
    position: relative;
    margin: auto;
    height: 60vh;
    width: 100%;
}

.box {
    font-size: 3em;
    width: 160px;
    height: 160px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    position: relative;
    z-index: 0;
}

.box::after {
    content: "%";
    font-size: .45em;
}

.box::before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: inherit;
    padding: 16px;
    background:
        linear-gradient(#f3f3f3, #f3f3f3) content-box,
        /* this background will be shown if mask is no supported */
        conic-gradient(#3498db 0 var(--p1), red calc(var(--p1) + 0.5%) calc(var(--p1) + var(--p2)), grey 0);
    -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 16px), #fff calc(100% - 15px));
}
</style>
