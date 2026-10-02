<template>
    <footer class="text-center mt-4 lg:text-left text-gray-600">
        <div class="text-center p-2 bg-gray-50 topshadow">
            <span> {{ title }}</span>{{ " " }}
            <a class="text-gray-600 font-semibold" :href="href" target="blank">{{ caption }}</a>
        </div>
    </footer>
</template>
<script>
export default {
    name: "Footer",
    props: {
        title: {
            type: String,
            default: "© 2022 Copyright",
        },
        href: {
            type: String,
            default: "https://jto.dephub.go.id/",
        },
        caption: {
            type: String,
            default: "Jembatan Timbang Online",
        },
    },
};
</script>
<style scoped>
.topshadow {
    -webkit-box-shadow: 0px -2px 3px rgb(229 231 235);
    -moz-box-shadow: 0px -2px 3px rgb(229 231 235);
    box-shadow: 0px -2px 3px rgb(229 231 235);
}
</style>
