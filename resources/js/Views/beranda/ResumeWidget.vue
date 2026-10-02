<template>
    <div class="max-w-full w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:grid-cols-4">

            <div class="flex bg-gray-100 text-gray-700 rounded-md shadow">
                <div class="bg-teal-400 w-4 rounded-l-md">

                </div>
                <div class="flex items-center justify-between w-full p-4">

                    <div>
                        <span class="text-2xl font-semibold">{{ jml_capture }}</span>
                        <span class="inline-block px-2 py-px ml-2 text-xs text-teal-600 bg-green-100 rounded-md">
                            Record
                        </span>
                        <h6
                            class="text-[13px] pt-1 font-[550] leading-none tracking-wider text-gray-700 dark:text-primary-light">
                            Data Belum Diverifikasi
                        </h6>
                        <!-- <span class="inline-block px-2 py-px ml-2 text-xs text-green-500 bg-green-100 rounded-md">
                        Capture
                    </span> -->
                    </div>
                    <div>
                        <span>
                            <svg class="w-12 h-12 text-teal-600 dark:text-primary-dark" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z" />
                            </svg>

                        </span>
                    </div>
                </div>
            </div>

            <div class="flex bg-gray-100 text-gray-700 rounded-md shadow">
                <div class="bg-rose-400 w-4 rounded-l-md">

                </div>
                <div class="flex items-center justify-between w-full p-4">

                    <div>
                        <span class="text-2xl font-semibold">{{ jml_sdh_verif }}</span>
                        <span class="inline-block px-2 py-px ml-2 text-xs text-rose-600 bg-rose-100 rounded-md">
                            Record
                        </span>
                        <h6
                            class="text-[13px] pt-1 font-[550] leading-none tracking-wider text-gray-700 dark:text-primary-light">
                            Data Sudah DIverifikasi
                        </h6>
                        <!-- <span class="inline-block px-2 py-px ml-2 text-xs text-green-500 bg-green-100 rounded-md">
                        Capture
                    </span> -->
                    </div>
                    <div>
                        <span>
                            <svg class="w-12 h-12 text-rose-600 dark:text-primary-dark" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                            </svg>

                        </span>
                    </div>
                </div>
            </div>

            <div class="flex bg-gray-100 text-gray-700 rounded-md shadow">
                <div class="bg-yellow-400 w-4 rounded-l-md">

                </div>
                <div class="flex items-center justify-between w-full p-4">

                    <div>
                        <span class="text-2xl font-semibold">{{ jml_lhr }}</span>
                        <span class="inline-block px-2 py-px ml-2 text-xs text-yellow-600 bg-yellow-100 rounded-md">
                            Record
                        </span>
                        <h6
                            class="text-[13px] pt-1 font-[550] leading-none tracking-wider text-gray-700 dark:text-primary-light">
                            Total Deteksi LHR Hari Ini
                        </h6>
                        <!-- <span class="inline-block px-2 py-px ml-2 text-xs text-green-500 bg-green-100 rounded-md">
                        Capture
                    </span> -->
                    </div>
                    <div>
                        <span>
                            <svg class="w-12 h-12 text-yellow-600 dark:text-primary-dark" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                            </svg>

                        </span>
                    </div>
                </div>
            </div>

            <div class="flex bg-gray-100 text-gray-700 rounded-md shadow">
                <div class="bg-blue-400 w-4 rounded-l-md">

                </div>
                <div class="flex items-center justify-between w-full p-4">

                    <div>
                        <span class="text-2xl font-semibold">{{ jml_wim }}</span>
                        <span class="inline-block px-2 py-px ml-2 text-xs text-blue-600 bg-blue-100 rounded-md">
                            Record
                        </span>
                        <h6
                            class="text-[13px] pt-1 font-[550] leading-none tracking-wider text-gray-700 dark:text-primary-light">
                            Total Deteksi WIM Hari Ini
                        </h6>
                        <!-- <span class="inline-block px-2 py-px ml-2 text-xs text-green-500 bg-green-100 rounded-md">
                        Capture
                    </span> -->
                    </div>
                    <div>
                        <span>
                            <svg class="w-12 h-12 text-blue-600 dark:text-primary-dark" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                            </svg>

                        </span>
                    </div>
                </div>
            </div>


        </div>

    </div>
</template>
<script>
import moment from 'moment';

export default {
    name: "ResumeWidget",
    data() {
        return {
            bulanIni: moment().format('MMMM'),
            bulanLalu: moment().subtract(1, 'month').format('MMMM'),
        };
    },
    props: {
        jml_capture: {
            type: Number,
            default: 0
        },
        jml_sdh_verif: {
            type: Number,
            default: 0
        },
        jml_pelanggaran_now: {
            type: Number,
            default: 0
        },
        jml_pelanggaran_lastnow: {
            type: Number,
            default: 0
        },
        jml_pelanggaran_hari_ini: {
            type: Number,
            default: 0
        },
        jml_archive_now: {
            type: Number,
            default: 0
        },
        jml_archive_hari_ini: {
            type: Number,
            default: 0
        },
        jml_lhr: {
            type: Number,
            default: 0
        },
        jml_wim: {
            type: Number,
            default: 0
        }
    }
};
</script>
