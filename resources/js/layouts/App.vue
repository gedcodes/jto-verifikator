<template>
    <router-view :key="$route.path"></router-view>
</template>

<script>
import NavBar from "./NavBar.vue";
import VerifyNotice from "./VerifyNotice.vue";
export default {
    computed: {
        id() {
            return this.$store.getters.id;
        },
        verified() {
            return this.$store.getters.verified;
        },
    },
    components: {
        NavBar,
        VerifyNotice,
    },
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
