<template>
    <component
        class="btn font-medium flex items-center cursor-pointer border shadow hover:shadow-lg focus:outline-none focus:shadow-outline"
        :is="buttonType"
        :href="href"
        :type="type"
        :disabled="disabled"
        :id="id"
        :name="name"
    >
        <slot></slot>
    </component>
</template>
<script>
export default {
    props: {
        href: {
            required: false,
            type: String,
            default: null,
        },
        type: {
            type: String,
            default: "button", //button, submit
        },
        id: {
            type: String,
            required: false,
        },
        name: {
            type: String,
            required: false,
        },
        color: {
            type: String,
            default: "teal",
        },
        size: {
            type: String,
            default: "md", //sm, md, lg
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        outline: Boolean,
        icon: Boolean,
        round: Boolean,
    },
    computed: {
        // colorClasses() {
        //     const color = this.color;

        //     const baseClasses = `bg-${color}-600 text-${color}-50 border-${color}-200 hover:bg-${color}-800 hover:border-${color}-200 hover:text-white`;
        //     const outlineClasses = `border-${color}-300 bg-white hover:bg-${color}-100 text-${color}-600 hover:text-${color}-400 hover:border-${color}-200`;

        //     return this.outline ? outlineClasses : baseClasses;
        // },
        // sizeClasses() {
        //     const isIcon = this.icon;
        //     const sizeMappings = {
        //         xs: `h-6 text-sm ${isIcon ? "px-1" : "px-2"}`,
        //         sm: `h-8 text-sm ${isIcon ? "px-2" : "px-4"}`,
        //         md: `h-10 ${isIcon ? "px-3" : "px-6"}`,
        //         lg: `text-lg h-12 ${isIcon ? "px-4" : "px-12"}`,
        //     };

        //     return sizeMappings[this.size] || sizeMappings.md;
        // },
        // btnClasses() {
        //     const borderRadiusClasses = this.round ? "rounded-full" : "rounded";
        //     if (this.disabled) {
        //         return `bg-gray-200 text-gray-500 cursor-not-allowed hover:bg-gray-200 shadow-none font-semibold hover:shadow-none ${this.sizeClasses} ${borderRadiusClasses}`;
        //     }
        //     return `${this.colorClasses} ${this.sizeClasses} ${borderRadiusClasses}`;
        // },
        buttonType() {
            if (this.href) {
                return "a";
            } else {
                return "button";
            }
        },
    },
};
</script>
