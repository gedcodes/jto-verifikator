<template>
    <div class="max-w-screen-sm mx-auto text-gray-700 font-thin">
        <div class="flex items-center justify-start h-screen">
            <div class="flex-1">
                <div class="flex justify-center">
                    <img class="w-14 h-16 md:min-w-0 items-center" :src="atms" />
                    <div class="items-right text-4xl font-bold text-center mb-2 p-4">
                        Verifikator JTO
                    </div>
                </div>
                <div class="shadow-md w-auto">
                    <div class="p-4 bg-white rounded-lg">
                        <div class="p-4 text-2xl text-center font-semibold">
                            Lupa Password ?
                        </div>

                        <success v-if="success" :content="success" @close="success = null" />
                        <errors v-if="errors" :content="errors" @close="errors = null" />

                        <form class="md:w-10/12 md:p-4 w-full mx-auto" @submit.prevent="send">
                            <div class="w-full my-1 py-2 sm:flex sm:items-center sm:justify-between">
                                <label for="Email" class="sm:w-6/12">Email</label>
                                <span class="sm:w-6/12 md:w-full">
                                    <TextInput v-model="email" required id="email" type="email" placeholder="Email"
                                        name="email" />
                                </span>
                                <!-- <input
                                type="email"
                                v-model="email"
                                name="email"
                                class="border border-gray-300 bg-white sm:w-8/12 w-full p-2 mt-3 sm:mt-0 focus:outline-none rounded-sm"
                            /> -->
                            </div>
                            <div class="w-full my-1 py-2 sm:flex sm:items-center sm:justify-end">
                                <div class="sm:w-8/12 w-full flex justify-between items-center mt-3 sm:mt-0">
                                    <!-- <div
                                    v-if="busy"
                                    class="flex justify-center items-center p-2 px-6 rounded-sm text-white bg-blue-500 hover:bg-blue-600"
                                >
                                    <circle-svg class="w-6 h-6" />
                                </div> -->
                                    <div v-if="busy"
                                        class="flex justify-center items-center p-2 px-6 rounded-lg text-gray-800 bg-white">
                                        <circle-svg stroke="#1e293b" class="w-6 h-6" />
                                    </div>
                                    <!-- <button
                                        v-else
                                        type="submit"
                                        class="py-2 px-4 rounded-lg font-poppins text-sm text-white bg-gray-700 hover:bg-gray-800"
                                    >
                                        Submit
                                    </button> -->
                                    <SubmitButton v-else title="Reset Password" iconLeft>
                                        <PaperAirplaneIcon class="h-4 w-4 text-white" />
                                    </SubmitButton>
                                </div>
                            </div>
                            <div class="p-4 font-extralight text-xs text-center">
                                Hubungi kami jika anda menemukan masalah pada
                                lupa password. Klik
                                <router-link :to="{ name: 'login' }" class="text-sm text-blue-600 hover:underline">
                                    <span>kembali</span>
                                </router-link>
                                ke halaman login
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import atms from "@/images/logo.png";
import { PaperAirplaneIcon } from "@heroicons/vue/outline";
import axios from "axios";
import Success from "../components/Success.vue";
import Errors from "../components/Errors.vue";
import CircleSvg from "../components/CircleSvg.vue";
import SubmitButton from "@/js/components/form/SubmitButton";
import TextInput from "@/js/components/form/TextInput";
export default {
    components: {
        Success,
        PaperAirplaneIcon,
        Errors,
        CircleSvg,
        Success,
        TextInput,
        SubmitButton,
    },
    data() {
        return {
            email: "",
            errors: null,
            success: "",
            busy: false,
            atms,
        };
    },

    methods: {
        async send() {
            this.busy = true;
            this.errors = null;
            this.success = "";
            await axios
                .post("/api/forgot-password", { email: this.email })
                .then((res) => {
                    this.success = res.data.msg;
                })
                .catch((err) => {
                    this.errors = err.response.data;
                });
            this.busy = false;
        },
    },
};
</script>
