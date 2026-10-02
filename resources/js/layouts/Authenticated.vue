<template>
    <div v-if="user">
        <navigation></navigation>

        <!-- <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl tracking-tight font-bold text-gray-900">
                Dashboard
            </h1>
        </div>
    </header> -->

        <main>
            <div>
                <router-view v-slot="{ Component, route }">
                    <transition name="fade" mode="out-in">
                        <div :key="route.name">
                            <component :is="Component"></component>
                        </div>
                        <!-- <component :is="Component" :key="route.path" /> -->
                    </transition>
                </router-view>
                <!-- <router-view></router-view> -->
            </div>
        </main>

        <Footer></Footer>
    </div>
</template>
<script>
import Navigation from "@/js/components/Navigation";
import Footer from "@/js/components/layout/Footer";
export default {
    components: { Navigation, Footer },
    computed: {
        async user() {
            //if (this.$store?.getters?.user?.email_verified_at) {
            //console.log(this.$store?.getters?.user);
            return this.$store?.getters?.user;
            // } else {
            //     await this.$store.dispatch("logout");
            //     this.$router.push({ name: "login" });
            // }
        },
    },
};
</script>
