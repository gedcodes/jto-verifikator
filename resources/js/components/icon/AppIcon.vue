<template>
    <svg :class="classes" :viewBox="viewbox" :fill="fill" stroke="none" aria-hidden="true" :disabled="disabled">
        <path :d="path" />
    </svg>
</template>

<script>
import icons from "./icons.json";

export default {
    name: "AppIcon",
    props: {
        name: Object,
        classes: {
            type: String,
            default: "h-4 w-4",
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        fill: {
            type: String,
            default: "teal",
        },
    },
    computed: {
        path() {
            return icons[this.name.name].path;
        },
        viewbox() {
            return icons[this.name.name].viewBox;
        },
        // classes() {
        //     return {
        //         "fill-current text-gray-100 cursor-not-allowed hover:text-gray-200 h-4 w-4":
        //             this.disabled === true,
        //         "fill-current text-teal-600 hover:text-teal-800 h-4 w-4":
        //             this.disabled === false,
        //     };
        // },
    },
};
</script>
