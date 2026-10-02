<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 p-4 bg-slate-600 font-light font-sans">
        <div class="flex items-center space-x-1 pt-8 sm:justify-start sm:pt-0 text-xl text-gray-800 font-bold">
            <img class="w-8 min-w-full md:min-w-0 items-center" :src="atms" />
        </div>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-md">
            <div class="grid grid-cols-1 gap-0">
                <div class="items-right text-4xl font-bold text-center mb-2 p-4">
                    Login
                </div>
            </div>
            <!-- <p class="text-sm text-center my-2">
                Masukkan akun anda dan klik tombol Login
            </p> -->
            <div class="block w-full my-2" v-if="errors">
                <Errors type="danger" :content="errors" @close="errors = null" />
            </div>
            <!-- <form @submit.prevent="login"> -->
            <Form @submit="onSubmit" :validation-schema="schema" @invalid-submit="onInvalidSubmit">
                <!-- <div
                    v-if="errors"
                    class="bg-red-100 border text-sm border-red-200 text-red-700 px-2 py-2 my-2 rounded relative"
                >
                    <span>{{ errors.message }}</span>
                </div> -->
                <div>
                    <!-- <TextInput
                        v-model="email"
                        keyInput="email"
                        required
                        label="Email"
                        id="email"
                        name="email"
                        type="email"
                        class="text-sm"
                        placeholder="Masukkan Email"
                        :errors="errors && errors.email"
                    >
                    </TextInput> -->
                    <m-input name="email" type="email" label="Email" placeholder="Email" required />
                </div>
                <div class="mt-4">
                    <PasswordInput required label="Password" id="password" type="password" placeholder="Password"
                        name="password" />
                    <!-- <m-input
                        :value="password"
                        name="password"
                        type="password"
                        label="Password"
                        placeholder="Password"
                        required
                    /> -->
                </div>
                <!-- <div class="block mt-4">
                    <label for="remember" class="inline-flex items-center">
                        <input
                            v-model="remember"
                            id="remember"
                            type="checkbox"
                            class="rounded border border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="remember"
                        />
                        <span class="ml-2 text-sm text-gray-500"
                            >Remember me</span
                        >
                    </label>
                </div> -->
                <div class="flex items-center justify-end mt-4">
                    <router-link class="underline mr-4 text-sm text-gray-500 hover:text-gray-800"
                        :to="{ name: 'forgot-password' }">
                        Lupa Password?
                    </router-link>
                    <div v-if="busy"
                        class="flex justify-center items-center p-2 px-6 rounded-lg text-gray-800 bg-white">
                        <circle-svg stroke="#1e293b" class="w-6 h-6" />
                    </div>

                    <!-- <SubmitButton v-else title="Login" class="px-6" iconLeft>
                        <LoginIcon class="h-6 w-6 text-white" />
                    </SubmitButton> -->
                    <m-button id="btnLogin" icon name="btnLogin" color="green" title="Login" iconName="signin"
                        class="submit-btn h-10 px-8 text-white items-end" type="submit">
                    </m-button>
                </div>
            </Form>
            <div class="text-center text-xs mt-4">
                Tidak memiliki akun ? Silahkan klik link
                <router-link class="underline font-poppins text-sm text-blue-500 hover:text-blue-600"
                    :to="{ name: 'register' }">
                    Daftar
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import { Form } from "vee-validate";
import * as Yup from "yup";
import Logo from "@/js/components/Logo";
import atms from "@/images/logo.png";
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
        onSubmit(values) {
            this.busy = true;
            this.errors = null;
            console.log(values);
            alert(JSON.stringify(values, null, 2));
            // try {
            //     await this.$store.dispatch("login", {
            //         email: this.email,
            //         password: this.password,
            //     });

            //     this.$router.replace({ name: "home" });
            // } catch (e) {
            //     // await this.$store.dispatch("logout");
            //     this.errors = e.data;
            // }
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
