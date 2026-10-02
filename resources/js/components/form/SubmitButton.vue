<template>
    <TButton
        type="submit"
        class="mx-0 p-4 text-center justify-center text-white bg-bluegrey-600 hover:bg-bluegrey-800 hover:text-white hover:border-bluegrey-900 focus:outline-none focus:shadow-outline"
    >
        <span v-if="iconLeft" class="pr-2"><slot></slot></span>
        <span>{{ title }}</span>
        <span v-if="iconRight" class="pl-2"><slot></slot></span>
    </TButton>
</template>
<script>
import TButton from "@/js/components/form/TButton.vue";

export default {
    name: "submit-button",
    components: {
        TButton,
    },
    props: {
        outline: Boolean,
        iconLeft: Boolean,
        iconRight: Boolean,
        round: Boolean,
        title: {
            type: String,
            default: "Submit",
        },
    },
};
</script>
