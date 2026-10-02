<template>
    <div class="max-w-screen-md mx-auto text-gray-900 font-thin">
        <div class="flex items-center justify-center h-screen">
            <div class="flex-1">
                <div class="p-4 bg-white">
                    <div class="p-4 text-2xl text-center">
                        Verifikasi Alamat Email Anda Sekarang !
                    </div>
                    <div>
                        <success
                            v-if="success"
                            :content="success"
                            @close="success = null"
                        />
                        <errors
                            v-if="errors"
                            :content="errors"
                            @close="errors = null"
                        />
                    </div>
                    <!-- <div v-if="error" class="md:w-10/12 md:p-2 w-full mx-auto text-sm text-red-500 text-white text-center">
                        {{error}}
                    </div> -->

                    <div
                        class="my-1 py-2 sm:w-8/12 md:w-10/12 md:p-4 w-full mx-auto flex justify-center items-center mt-3 sm:mt-0"
                    >
                        <div
                            v-if="loading"
                            class="flex justify-center items-center p-2 px-6 rounded-full text-gray-800 bg-white"
                        >
                            <circle-svg stroke="#1e293b" class="w-6 h-6" />
                        </div>
                        <button
                            v-else
                            @click="error ? resend() : verify()"
                            :class="
                                'p-3 rounded-lg text-white' +
                                (!error
                                    ? ' bg-blue-500 hover:bg-blue-600'
                                    : ' bg-red-400 text-white hover:bg-red-600')
                            "
                        >
                            {{
                                error
                                    ? "Oops ! Resend ?"
                                    : "Klik Untuk Verifikasi"
                            }}
                        </button>
                    </div>
                    <div class="p-4 font-thin text-sm text-center">
                        Hubungi kami jika anda menemukan masalah pada verifikasi
                        alamat email
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { XIcon } from "@heroicons/vue/solid";
import Errors from "../components/Errors.vue";
import Success from "../components/Success.vue";
import CircleSvg from "../components/CircleSvg.vue";
export default {
    components: {
        XIcon,
        Errors,
        Success,
        CircleSvg,
    },
    props: {
        id: {
            required: true,
        },
        hash: {
            required: true,
        },
    },
    data() {
        return {
            errors: null,
            success: "",
            busy: false,
            loading: false,
        };
    },

    methods: {
        async verify() {
            this.busy = true;
            this.errors = null;
            this.success = "";
            await this.$store
                .dispatch("verifyEmail", { id: this.id, hash: this.hash })
                .then((res) => {
                    this.loading = true;
                    this.success = res.data.message
                        ? res.data.message + "..."
                        : " Mengarahkan Halaman ...";
                    setTimeout(() => {
                        this.$router.push({ name: "home" });
                    }, 3000);
                })
                .catch((err) => {
                    this.errors =
                        "Kesalahan Internal ! Coba beberapa saat lagi.";
                });
            this.busy = false;
        },

        resend() {
            this.errors = null;
            this.success = "";
            this.$store
                .dispatch("verifyResend", { id: this.id })
                .then((res) => {
                    this.success = res.data.message + "...";
                    setTimeout(() => {
                        this.$router.push({ name: "home" });
                    }, 3000);
                })
                .catch((err) => {
                    this.errors =
                        "Kesalahan Internal ! Coba beberapa saat lagi";
                    setTimeout(() => {
                        this.$router.push({ name: "home" });
                    }, 3000);
                });
        },
    },
};
</script>
