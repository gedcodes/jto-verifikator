<template>
    <div class="max-w-screen-sm mx-auto text-gray-700 font-thin">
        <div class="flex items-center justify-center h-screen">
            <div class="flex-1">
                <div class="shadow-md w-auto">
                    <div class="p-4 bg-white rounded-lg">
                        <div class="p-4 text-2xl text-center font-semibold">
                            Reset Password Anda
                        </div>

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

                        <form
                            @submit.prevent="reset"
                            class="md:w-10/12 md:p-4 w-full mx-auto"
                        >
                            <div
                                class="border w-full my-1 py-2 sm:flex sm:items-center sm:justify-between"
                            >
                                <!-- <label for="Password" class="w-4/12">
                                    Password
                                </label>
                                <input
                                    type="password"
                                    name="password"
                                    v-model="password"
                                    class="border border-gray-300 bg-white sm:w-8/12 w-full p-2 mt-3 sm:mt-0 focus:outline-none rounded-sm"
                                /> -->
                                <label for="Password" class="sm:w-6/12">
                                    Password
                                </label>
                                <PasswordInput
                                    v-model="password"
                                    required
                                    id="password"
                                    type="password"
                                    placeholder="Password"
                                    name="password"
                                    class="sm:w-6/12 md:w-full"
                                />
                            </div>
                            <div
                                class="w-full my-1 py-2 sm:flex sm:items-center sm:justify-between"
                            >
                                <label for="Password confirm" class="w-6/12">
                                    Ulangi Password
                                </label>
                                <PasswordInput
                                    v-model="password_confirmation"
                                    required
                                    id="password_confirmation"
                                    type="password"
                                    placeholder="Ulangi Password"
                                    name="password_confirmation"
                                    class="sm:w-6/12 md:w-full"
                                />
                                <!-- <input
                                    type="password"
                                    name="password_confirmation"
                                    v-model="password_confirmation"
                                    class="border border-gray-300 bg-white sm:w-8/12 w-full p-2 mt-3 sm:mt-0 focus:outline-none rounded-sm"
                                /> -->
                            </div>

                            <div
                                class="w-full my-1 py-2 sm:flex sm:items-center sm:justify-end"
                            >
                                <div
                                    class="sm:w-8/12 w-full flex justify-between items-center mt-3 sm:mt-0"
                                >
                                    <div
                                        v-if="busy"
                                        class="flex justify-center items-center p-2 px-6 rounded-full text-gray-800 bg-white"
                                    >
                                        <circle-svg class="w-6 h-6" />
                                    </div>
                                    <button
                                        v-else
                                        type="submit"
                                        class="py-2 px-4 font-semibold rounded-lg text-white bg-gray-500 hover:bg-gray-600"
                                    >
                                        Reset
                                    </button>
                                </div>
                            </div>
                            <div
                                class="p-4 font-extralight text-sm text-center"
                            >
                                Hubungi kami jika anda menemukan masalah pada
                                reset password
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { XIcon } from "@heroicons/vue/outline";
import Errors from "../components/Errors.vue";
import axios from "axios";
import Success from "../components/Success.vue";
import CircleSvg from "../components/CircleSvg.vue";
import PasswordInput from "@/js/components/form/PasswordInput";
export default {
    components: {
        XIcon,
        Errors,
        Success,
        CircleSvg,
        PasswordInput,
    },
    props: {
        token: {
            required: true,
        },
        email: {
            required: true,
        },
    },

    data() {
        return {
            password: "",
            password_confirmation: "",
            errors: null,
            success: "",
            busy: false,
        };
    },

    methods: {
        async reset() {
            this.busy = true;
            this.errors = null;
            this.success = "";
            await axios
                .post("/api/reset-password", {
                    email: this.email,
                    token: this.token,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                })
                .then((res) => {
                    this.success = res.data.msg + " redirecting ...";
                    setTimeout(() => {
                        this.$router.push({ name: "login" });
                    }, 1000);
                })
                .catch((err) => {
                    this.errors = err.response.data;
                });
            this.busy = false;
        },
    },
    created() {},
};
</script>
