<template>
    <div class="border font-semibold  p-4  flex items-center justify-between">
        <div class="flex">
            <UserIcon class="h-5 w-5 mr-3" />
            Profile
        </div>
        <div class="text-gray-600 text-sm font-medium">
            {{ `Register Date : ${register_date} ` }}
        </div>

    </div>

    <div class="p-4 bg-white">

        <Success v-if="success" :content="success" @close="success = null" />

        <Errors v-if="errors" :content="errors" @close="errors = null" />
        <!-- <div v-if="error" class="md:w-10/12 md:p-2 w-full mx-auto text-sm text-red-500 text-white text-center">
                        {{error}}
                    </div> -->
        <div class="md:w-10/12 md:p-4 w-full mx-auto text-gray-600 text-base">
            <div class="grid grid-cols-3 gap-4 my-3">
                <div class="col-span-1 w-full">
                    <div class=""> Username </div>
                </div>
                <div class="col-span-2 w-full">
                    <div class=""> {{ username }} </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 my-3">
                <div class="col-span-1">
                    <div class=""> Email </div>
                </div>
                <div class="col-span-2">
                    <div class=""> {{ email }} </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 my-3">
                <div class="col-span-1">
                    <div class=""> Grup </div>
                </div>
                <div class="col-span-2">
                    <div class=""> {{ role }} </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 my-3">
                <div class="col-span-1">
                    <div class=""> Nama Lengkap </div>
                </div>
                <div class="col-span-2">
                    <div class=""> {{ name }} </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 my-3">
                <div class="col-span-1">
                    <div class=""> Kontak Person </div>
                </div>
                <div class="col-span-2">
                    <div class=""> {{ kontak }} </div>
                </div>
            </div>
        </div>


    </div>
</template>


<script>
import { XIcon } from '@heroicons/vue/solid';
import { UserIcon } from '@heroicons/vue/outline';
import Errors from '../components/Errors.vue';
import Success from '../components/Success.vue';
import moment from 'moment'
import CircleSvg from '../components/CircleSvg.vue';
import RoleService from '../service/RoleService';
export default {
    components: {
        XIcon,
        Errors,
        Success,
        CircleSvg,
        UserIcon
    },
    data() {
        return {
            email: '',
            name: '',
            username: '',
            kontak: '',
            errors: null,
            success: '',
            busy: false,
            register_date: '',
            role: '',

        }
    },
    computed: {
        user() {
            return this.$store.getters.user
        },
        verified() {
            return this.$store.getters.verified
        },
    },

    created() {
        this.getRole();
    },

    methods: {
        async update() {
            this.busy = true;
            this.errors = null
            this.success = ''
            try {
                await this.$store.dispatch('profile', { 'email': this.email, 'name': this.name })
                this.success = 'profile updated successfully !'
            }
            catch (e) {
                this.errors = e.data
            };
            this.busy = false;

        },
        moment: function () {
            return moment();
        },

        async getRole() {
            await RoleService.getById(this.user.role_id)
                .then((res) => {
                    console.log(res);
                    this.role = res.data.nama;
                })
                .catch((err) => {
                    console.log(err);
                })
        }
    },


    mounted() {
        this.name = this.user.nama_lengkap;
        this.email = this.user.email;
        this.username = this.user.username;
        this.kontak = this.user.kontak_person;
        this.register_date = moment(this.user.register_date).format('Do MMMM YYYY');
    },


}
</script>
