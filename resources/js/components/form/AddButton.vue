<template>
    <TButton
        class="mx-0 p-4 text-center justify-center text-white bg-bluegrey-600 hover:bg-bluegrey-800 hover:text-white hover:border-bluegrey-900 focus:outline-none focus:shadow-outline"
    >
        <i v-if="icon" class="far fa-plus mr-1 font-bold" />
        <span v-if="title != 'Tambah'">{{ title }}</span>
    </TButton>
</template>
<script>
import TButton from "@/js/components/form/TButton.vue";

export default {
    name: "App",
    components: {
        TButton,
    },
    props: {
        title: {
            type: String,
            default: "Tambah",
        },
        outline: Boolean,
        icon: Boolean,
        round: Boolean,
    },
};
</script>
