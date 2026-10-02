<template>
    <div class="max-w-screen-sm mx-auto text-gray-700 font-thin">
        <div class="flex items-center justify-start h-screen">
            <div class="flex-1">
                <div class="flex justify-center">
                    <div class="p-4 text-2xl text-center font-semibold">
                        <img class="w-96 md:min-w-0 text-center object-center" :src="atms" />
                    </div>
                </div>
                <div class="shadow-md w-auto">
                    <div class="p-4 bg-white rounded-lg">
                        <div class="p-4 text-2xl text-center font-semibold">
                            Pendaftaran Akun
                        </div>

                        <errors v-if="errors" :content="errors" @close="errors = null" />

                        <form @submit.prevent="register" class="md:w-10/12 md:p-4 w-full mx-auto">
                            <div class="w-full my-1 py-2 sm:flex sm:items-center sm:justify-between">
                                <label for="Name" class="w-4/12"> Name </label>
                                <input type="text" name="name" v-model="name"
                                    class="border border-gray-300 bg-white sm:w-8/12 w-full p-2 mt-3 sm:mt-0 focus:outline-none rounded-sm" />
                            </div>
                            <div class="w-full my-1 py-2 sm:flex sm:items-center sm:justify-between">
                                <label for="Email" class="w-4/12">
                                    Email
                                </label>
                                <!-- <input
                                    type="email"
                                    name="email"
                                    v-model="email"
                                    class="border border-gray-300 bg-white sm:w-8/12 w-full p-2 mt-3 sm:mt-0 focus:outline-none rounded-sm"
                                /> -->
                                <TextInput v-model="email" keyInput="email" required label="Email" id="email" name="email"
                                    type="email" class="text-sm" placeholder="Masukkan Email"
                                    :errors="errors && errors.email">
                                </TextInput>
                            </div>
                            <div class="w-full my-1 py-2 sm:flex sm:items-center sm:justify-between">
                                <label for="Password" class="w-4/12">
                                    Password
                                </label>
                                <input type="password" name="password" v-model="password"
                                    class="border border-gray-300 bg-white sm:w-8/12 w-full p-2 mt-3 sm:mt-0 focus:outline-none rounded-sm" />
                            </div>
                            <div class="w-full my-1 py-2 sm:flex sm:items-center sm:justify-between">
                                <label for="Password confirm" class="w-4/12">
                                    Confirm Password
                                </label>
                                <input type="password" name="password_confirmation" v-model="password_confirmation"
                                    class="border border-gray-300 bg-white sm:w-8/12 w-full p-2 mt-3 sm:mt-0 focus:outline-none rounded-sm" />
                            </div>

                            <div class="w-full my-1 py-2 sm:flex sm:items-center sm:justify-end">
                                <div class="sm:w-8/12 w-full flex justify-between items-center mt-3 sm:mt-0">
                                    <div v-if="busy"
                                        class="flex justify-center items-center p-2 px-6 rounded-sm text-white bg-blue-500 hover:bg-blue-600">
                                        <circle-svg class="w-6 h-6" />
                                    </div>
                                    <button v-else type="submit"
                                        class="p-3 rounded-sm text-white bg-blue-500 hover:bg-blue-600">
                                        Register
                                    </button>
                                    <router-link :to="{ name: 'login' }" class="text-sm text-blue-500 hover:underline">
                                        Already a member ? Sing In !
                                    </router-link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import atms from "@/images/atms_logo_2.png";
import { XIcon } from "@heroicons/vue/outline";
import CircleSvg from "../components/CircleSvg.vue";
import Errors from "../components/Errors.vue";
import TextInput from "@/js/components/form/TextInput";
import PasswordInput from "@/js/components/form/PasswordInput";
import SubmitButton from "@/js/components/form/SubmitButton";
export default {
    components: {
        XIcon,
        CircleSvg,
        Errors,
        TextInput,
        PasswordInput,
        SubmitButton,
    },
    data() {
        return {
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
            errors: null,
            busy: false,
            atms,
        };
    },

    methods: {
        async register() {
            this.busy = true;
            this.errors = null;
            this.success = "";
            try {
                await this.$store.dispatch("register", {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                });
                this.$router.push({ name: "beranda" });
            } catch (e) {
                // e.data.errors
                this.errors = e.data;
            }
            this.busy = false;
        },
    },
};
</script>
