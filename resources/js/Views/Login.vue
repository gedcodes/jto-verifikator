<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-slate-600 font-light font-sans">
        <div class="login-box py-2 md:py-6 px-2 md:px-4 mx-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center space-x-1 text-xl text-gray-800 font-bold">
                        <img class="w-96 min-w-full md:min-w-0 items-center" :src="atms" />
                    </div>
                    <div class="w-full sm:max-w-md mt-6 px-6 py-4">
                        <div class="grid grid-cols-1 gap-0">
                            <div class="items-right text-3xl font-bold text-center mb-2 p-4">
                                Login
                            </div>
                        </div>
                        <div class="block w-full my-2" v-if="errors">
                            <Errors type="danger" :content="errors" @close="errors = null" />
                        </div>
                        <form @submit.prevent="onSubmit">
                            <div>
                                <TextInput v-model="email" keyInput="email" required label="Email" id="email" name="email"
                                    type="email" class="text-sm" placeholder="Masukkan Email"
                                    :errors="errors && errors.email">
                                </TextInput>
                            </div>
                            <div class="mt-4">
                                <PasswordInput v-model="password" required label="Password" id="password" type="password"
                                    placeholder="Password" name="password" />
                            </div>
                            <div class="block mt-4">
                                <label for="remember" class="inline-flex items-center">
                                    <input v-model="remember" id="remember" type="checkbox"
                                        class="rounded border border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        name="remember" />
                                    <span class="ml-2 text-sm text-gray-500">Remember me</span>
                                </label>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <div></div>
                                <div v-if="busy"
                                    class="flex justify-center items-center p-2 px-6 rounded-lg text-gray-800 bg-white">
                                    <circle-svg stroke="#1e293b" class="w-6 h-6" />
                                </div>

                                <m-button v-else id="btnLogin" icon name="btnLogin" color="cyan" title="Login"
                                    iconName="signin" class="submit-btn h-10 px-8 text-white bg-[#56b8f5] items-end"
                                    type="submit">
                                </m-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div
        class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 p-4 bg-slate-600 font-light font-sans">
        <div class="flex items-center space-x-1 pt-8 sm:justify-start sm:pt-0 text-xl text-gray-800 font-bold">
            <img class="w-96 min-w-full md:min-w-0 items-center" :src="atms" />
        </div>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-md">
            <div class="grid grid-cols-1 gap-0">
                <div class="items-right text-3xl font-bold text-center mb-2 p-4">
                    Login
                </div>
            </div>
            <div class="block w-full my-2" v-if="errors">
                <Errors type="danger" :content="errors" @close="errors = null" />
            </div>
            <form @submit.prevent="onSubmit">
                <div>
                    <TextInput v-model="email" keyInput="email" required label="Email" id="email" name="email"
                        type="email" class="text-sm" placeholder="Masukkan Email" :errors="errors && errors.email">
                    </TextInput>
                </div>
                <div class="mt-4">
                    <PasswordInput v-model="password" required label="Password" id="password" type="password"
                        placeholder="Password" name="password" />
                </div>
                <div class="block mt-4">
                    <label for="remember" class="inline-flex items-center">
                        <input v-model="remember" id="remember" type="checkbox"
                            class="rounded border border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="remember" />
                        <span class="ml-2 text-sm text-gray-500">Remember me</span>
                    </label>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <div></div>
                    <div v-if="busy"
                        class="flex justify-center items-center p-2 px-6 rounded-lg text-gray-800 bg-white">
                        <circle-svg stroke="#1e293b" class="w-6 h-6" />
                    </div>

                    <m-button v-else id="btnLogin" icon name="btnLogin" color="green" title="Login" iconName="signin"
                        class="submit-btn h-10 px-8 text-white items-end" type="submit">
                    </m-button>
                </div>
            </form>
        </div>
    </div> -->
</template>

<script>
import { Form } from "vee-validate";
import * as Yup from "yup";
import Logo from "@/js/components/Logo";
import atms from "../../../public/images/logo_verifikator2.png";
import { AnnotationIcon, LoginIcon, XIcon } from "@heroicons/vue/outline";
import CircleSvg from "@/js/components/CircleSvg.vue";
import Errors from "@/js/components/Errors.vue";
import TextInput from "@/js/components/form/TextInput";
import MButton from "@/js/components/form/MButton.vue";
import MInput from "@/js/components/form/MInput";
import PasswordInput from "@/js/components/form/PasswordInput";
import SubmitButton from "@/js/components/form/SubmitButton";
export default {
    components: {
        Logo,
        LoginIcon,
        AnnotationIcon,
        XIcon,
        CircleSvg,
        Errors,
        TextInput,
        MInput,
        MButton,
        PasswordInput,
        SubmitButton,
    },
    data() {
        return {
            email: "",
            password: "",
            errors: null,
            remember: null,
            busy: false,
            atms,
        };
    },
    setup() {
        const schema = Yup.object().shape({
            email: Yup.string()
                .email("Format Email Salah")
                .required("Kode Harus Diisi"),
            password: Yup.string()
                .min(5, "Min. 3 Karakter")
                .required("Nama Harus Diisi"),
        });

        return {
            schema,
        };
    },
    methods: {
        async onSubmit(values) {
            this.busy = true;
            this.errors = null;
            try {
                await this.$store.dispatch("login", {
                    email: this.email,
                    password: this.password,
                });

                this.$router.replace({ name: "beranda" });
            } catch (e) {
                console.log(e);
                // await this.$store.dispatch("logout");
                var message = {
                    message: e.data.error
                }
                this.errors = message;
            }
            this.busy = false;
        },
        onInvalidSubmit() {
            const submitBtn = document.querySelector(".submit-btn");
            submitBtn.classList.add("invalid");
            setTimeout(() => {
                submitBtn.classList.remove("invalid");
            }, 1000);
        },
    },
};
</script>

<style>
.login-box {
    background: url(/images/login-form-bg.png);
    background-size: cover;
    background-position: center;
    -webkit-box-shadow: 0 2px 60px -5px rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 60px -5px rgba(0, 0, 0, 0.1);
}
</style>
