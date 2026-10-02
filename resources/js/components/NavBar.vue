<template>
    <nav class="bg-white border-b border-gray-200 p-4 mb-4">
        <div class="container mx-auto flex justify-center md:justify-between">
            <router-link :to="{ name: 'beranda' }"
                class="hidden md:flex text-xl font-semibold place-items-center space-x-2">
                <span>Fortify Vue SPA</span>
            </router-link>
            <ul class="list flex items-center text-gray-500 text-sm font-semibold">
                <li>
                    <router-link :to="{ name: 'beranda' }"
                        class="hover:text-gray-700 p-2 rounded flex items-center space-x-2">
                        <span>Home</span>
                    </router-link>
                </li>
                <!-- <li>
                    <router-link
                        :to="{ name: 'user' }"
                        class="hover:text-gray-700 p-2 rounded flex items-center space-x-2 font-semibold"
                    >
                        <span>{{ user?.name }}</span>
                    </router-link>
                </li> -->
                <li class="ml-4">
                    <button @click="logout" class="hover:text-gray-700 p-2 rounded flex items-center space-x-2">
                        <span>logout</span>
                    </button>
                </li>
            </ul>
        </div>
    </nav>
</template>
<script>
import { CogIcon, LogoutIcon, ChevronDownIcon } from "@heroicons/vue/outline";

export default {
    // created: function () {
    //     if (this.$store.getters.user) {
    //         let self = this;
    //         window.addEventListener("click", function (e) {
    //             if (!self.$refs.dropMenu.contains(e.target)) {
    //                 self.drop = false;
    //             }
    //         });
    //     }
    // },

    // components: { CogIcon, LogoutIcon, ChevronDownIcon },
    // data() {
    //     return {
    //         drop: false,
    //     };
    // },
    computed: {
        user() {
            return this.$store.getters.user;
        },
    },
    methods: {
        async logout() {
            await this.$store.dispatch("logout");
            this.$router.push({ name: "login" });
        },
    },
};
</script>
