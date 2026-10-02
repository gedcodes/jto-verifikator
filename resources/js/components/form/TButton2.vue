<template>
    <component
        class="btn font-poppins text-sm font-normal flex items-center cursor-pointer border shadow hover:shadow-lg focus:outline-none focus:shadow-outline"
        :is="buttonType"
        :href="href"
        :type="type"
        :class="btnClasses"
    >
        <slot></slot>
    </component>
    <!-- <component
        class="btn font-normal flex items-center cursor-pointer border shadow hover:shadow-lg focus:outline-none focus:shadow-outline"
        :is="buttonType"
        :href="href"
        :type="type"
        :class="btnClasses"
    >
        <slot></slot>
    </component> -->
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
            default: "bluegrey",
        },
        size: {
            type: String,
            default: "md", //sm, md, lg
        },
        outline: Boolean,
        icon: Boolean,
        round: Boolean,
    },
    computed: {
        // inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out
        colorClasses() {
            const color = this.color;
            const baseClasses = `bg-${color}-500 text-${color}-100 border-${color}-200 hover:bg-${color}-700 hover:border-${color}-700 hover:text-white focus:shadow-none`;
            const outlineClasses = `border-${color}-600 bg-white text-${color}-600 hover:bg-${color}-600 hover:border-${color}-600 hover:text-white`;
            return this.outline ? outlineClasses : baseClasses;
        },
        sizeClasses() {
            const isIcon = this.icon;
            const sizeMappings = {
                sm: `h-6 text-sm ${isIcon ? "px-2" : "px-2"}`,
                md: `h-8 ${isIcon ? "px-3" : "px-4"}`,
                lg: `text-lg h-10 ${isIcon ? "px-4" : "px-10"}`,
            };

            return sizeMappings[this.size] || sizeMappings.md;
        },
        btnClasses() {
            const borderRadiusClasses = this.round ? "rounded-full" : "rounded";
            return `${this.colorClasses} ${this.sizeClasses} ${borderRadiusClasses}`;
        },
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
