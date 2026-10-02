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
                    <div v-if="regSuccess" class="p-4 bg-white rounded-md border border-gray-100">
                        <div class="rounded-lg py-5 px-6 mb-4 text-base text-green-700" role="alert">
                            <h4 class="text-2xl font-medium leading-tight mb-2">
                                {{ regSuccess }}
                            </h4>
                            <p class="mb-4">
                                Silahkan buka email anda dan ikuti petunjuk yang
                                terdapat pada email anda.
                            </p>
                            <hr class="border-green-600 opacity-30" />
                            <p class="mt-4 mb-0 text-xs">
                                Hubungi kami jika anda menemukan masalah pada
                                pendaftaran anda. Klik
                                <router-link :to="{ name: 'login' }" class="text-sm text-blue-600 hover:underline">
                                    <span>Login</span>
                                </router-link>
                                untuk menuju ke halaman login atau klik
                                <a class="text-sm text-blue-600 hover:underline" :href="
                                    $router.resolve({ name: 'register' })
                                        .href
                                ">daftar</a>
                                <!-- <router-link
                                    :to="{ name: 'register' }"
                                    class="text-sm text-blue-600 hover:underline"
                                >
                                    <span>daftar</span>
                                </router-link> -->
                                untuk kembali melakukan pendaftaran
                            </p>
                        </div>
                    </div>
                    <div v-else class="p-0 bg-white rounded-md border border-gray-100">
                        <div class="p-4 text-2xl text-center font-semibold">
                            Pendaftaran Akun
                        </div>

                        <errors v-if="errors" :content="errors" @close="errors = null" />

                        <form @submit.prevent="register" class="md:w-12/12 p-2 md:p-4 w-full mx-auto">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <TextInput v-model="name" label="Nama" required id="name" type="name"
                                        placeholder="Nama" name="name" />
                                </div>
                                <div>
                                    <TextInput v-model="email" label="Email" required id="email" type="email"
                                        placeholder="Email" name="email" inputInfo="Gunakan Format Email" />
                                </div>
                                <div>
                                    <PasswordInput v-model="password" label="Password" required type="password"
                                        id="password" placeholder="Password" name="password" />
                                </div>
                                <div>
                                    <PasswordInput v-model="password_confirmation" label="Ulangi Password" required
                                        type="password" id="password_confirmation" placeholder="Ulangi Password"
                                        name="password_confirmation" inputInfo="Harus Sama Dengan Password" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mt-4">
                                <div>
                                    <div v-if="busy"
                                        class="flex justify-center items-center p-2 px-6 rounded-lg text-gray-800 bg-white">
                                        <circle-svg stroke="#1e293b" class="w-6 h-6" />
                                    </div>
                                    <SubmitButton v-else title="Daftar" class="h-10 w-full md:w-[120px] text-center"
                                        iconLeft>
                                        <app-icon :name="{ name: 'address-card' }" />
                                    </SubmitButton>
                                </div>
                                <div class="font-thin text-xs">
                                    <div class="mt-2">
                                        Jika anda sudah memiliki akun, silahkan
                                        klik
                                        <router-link :to="{ name: 'login' }"
                                            class="text-sm text-blue-500 hover:text-blue-600 underline">
                                            Login
                                        </router-link>
                                    </div>
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
import atms from "@/images/logo.png";
import AppIcon from "@/js/components/icon/AppIcon.vue";
import { XIcon } from "@heroicons/vue/outline";
import CircleSvg from "../components/CircleSvg.vue";
import Errors from "../components/Errors.vue";
import Alert from "../components/Alert.vue";
import TextInput from "@/js/components/form/TextInput";
import PasswordInput from "@/js/components/form/PasswordInput";
import SubmitButton from "@/js/components/form/SubmitButton";
export default {
    components: {
        AppIcon,
        XIcon,
        CircleSvg,
        Errors,
        Alert,
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
            regSuccess: null,
            busy: false,
            atms,
            errArr: [],
        };
    },

    methods: {
        async register() {
            this.busy = true;
            this.errors = null;
            this.success = "";
            this.regSuccess = "";
            this.errArr = [];
            try {
                const registerUser = await this.$store.dispatch("register", {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                });

                const response = registerUser.data;
                console.log("RESPONSE", response.message);
                if (response.success) {
                    this.regSuccess = response.message;
                }
                console.log("registerUser", registerUser);
            } catch (e) {
                if (e.response.status === 422) {
                    this.errors = e.response.data;
                }

                this.regSuccess = "";
            }
            this.busy = false;
        },
    },
};
</script>
