<template>
    <Tooltip
        v-if="tooltipText"
        class="font-poppins text-xs m-0 p-0"
        :position="tooltipPos"
        :tooltipText="tooltipText"
    >
        <t-button
            :icon="icon"
            :outline="outline"
            :color="color"
            :size="size"
            :disabled="disabled"
            :id="id"
            :name="name"
            :type="type"
            :class="btnClasses"
        >
            <div v-if="icon && iconPosition == 'left'">
                <span v-if="icon">
                    <app-icon
                        :name="{ name: iconName }"
                        :classes="iconClasses"
                        :fill="fillIconClasses"
                    />
                </span>
                <span v-if="title" :class="titleClasses">{{ title }}</span>
            </div>
            <div v-else>
                <span v-if="title" :class="titleClasses">{{ title }}</span>
                <span v-if="icon">
                    <app-icon
                        :name="{ name: iconName }"
                        :classes="iconClasses"
                        :fill="fillIconClasses"
                    />
                </span>
            </div>
        </t-button>
    </Tooltip>
    <t-button
        v-else
        :icon="icon"
        :outline="outline"
        :color="color"
        :size="size"
        :disabled="disabled"
        :type="type"
        :class="btnClasses"
    >
        <span v-if="icon">
            <app-icon :name="{ name: iconName }" :classes="iconClasses" />
        </span>
        <span v-if="title" :class="titleClasses">{{ title }}</span>
    </t-button>
</template>
<script>
import TButton from "@/js/components/form/TButton.vue";
import Tooltip from "@/js/components/utils/Tooltip.vue";
import AppIcon from "@/js/components/icon/AppIcon.vue";
export default {
    name: "App",
    components: {
        TButton,
        Tooltip,
        AppIcon,
    },
    props: {
        id: {
            type: String,
            required: false,
        },
        name: {
            type: String,
            required: false,
        },
        title: {
            type: String,
        },
        size: {
            type: String,
            default: "sm",
        },
        color: {
            type: String,
            default: "gray",
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        tooltip: {
            type: Boolean,
            default: false,
        },
        tooltipPos: {
            type: String,
            default: "top", //right, left, bottom
        },
        tooltipText: {
            type: String,
        },
        outline: Boolean,
        icon: Boolean,
        iconName: {
            type: String,
            default: "traffic-light",
        },
        iconPosition: {
            type: String,
            default: "left", //left, right
        },
        titleColor: {
            type: String,
            default: "white",
        },
        round: Boolean,
        href: {
            required: false,
            type: String,
            default: null,
        },
        type: {
            type: String,
            default: "button", //button, submit
        },
    },
    computed: {
        colorIconClasses() {
            const color = this.color;
            const baseIconClasses = `fill-current ${
                this.iconPosition == "left" ? "float-left" : "float-right"
            } ${
                this.outline
                    ? `text-${color}-60 hover:text-${color}-800`
                    : `text-${color}-50 hover:text-white`
            } font-bold`;
            const disableIconClasses = `fill-current ${
                this.iconPosition == "left" ? "float-left" : "float-right"
            } font-normal text-${color}-200 hover:text-${color}-200`;
            return this.disabled ? disableIconClasses : baseIconClasses;
        },
        fillIconClasses() {
            return this.color;
        },
        sizeIconClasses() {
            const sizeMappings = {
                xs: `h-3 w-3`,
                sm: `h-4 w-4`,
                md: `h-6 w-6`,
                lg: `h-8 w-8`,
            };

            return sizeMappings[this.size] || sizeMappings.md;
        },
        iconClasses() {
            return `${this.colorIconClasses} ${this.sizeIconClasses}`;
        },
        colorTitleClasses() {
            const color = this.color;
            const titleColor = this.titleColor;
            let baseTitleClasses = `font-semibold text-${color}-600 hover:text-${color}-800`;
            const disableTitleClasses = `font-normal text-${color}-200 hover:text-${color}-200`;

            if (titleColor) {
                baseTitleClasses = `font-semibold ${titleColor}`;
            }

            return this.disabled ? disableTitleClasses : baseTitleClasses;
        },
        sizeTitleClasses() {
            const sizeMappings = {
                xs: `text-xs`,
                sm: `text-xs`,
                md: `text-sm`,
                lg: `text-md`,
            };

            return sizeMappings[this.size] || sizeMappings.md;
        },
        titleClasses() {
            if (this.icon) {
                if (this.iconPosition == "left") {
                    return `float-right ml-2 ${this.colorTitleClasses} ${this.sizeTitleClasses}`;
                }

                return `float-left mr-2 ${this.colorTitleClasses} ${this.sizeTitleClasses}`;
            }

            return `${this.colorTitleClasses} ${this.sizeTitleClasses}`;
        },
        // colorBtnClasses() {
        //     const color = this.color;

        //     const baseBtnClasses = `bg-${color}-100 hover:bg-${color}-300 hover:border-${color}-600`;
        //     const disableBtnClasses = `bg-gray-200 text-gray-500 cursor-not-allowed hover:bg-gray-200`;
        //     return this.disabled ? disableBtnClasses : baseBtnClasses;
        // },
        colorClasses() {
            const color = this.color;
            const baseClasses = `bg-${color}-600 text-${color}-50 border-${color}-200 hover:bg-${color}-800 hover:border-${color}-200 hover:text-white`;
            const outlineClasses = `border-${color}-300 bg-white hover:bg-${color}-100 text-${color}-600 hover:text-${color}-400 hover:border-${color}-200`;

            return this.outline ? outlineClasses : baseClasses;
        },
        sizeClasses() {
            const isIcon = this.icon;
            const sizeMappings = {
                xs: `h-6 text-sm ${
                    isIcon ? "px-1" : "text-center justify-center px-2"
                }`,
                sm: `h-8 text-sm ${
                    isIcon ? "px-2" : "text-center justify-center px-2"
                }`,
                md: `h-10 ${
                    isIcon ? "px-3" : "text-center justify-center px-2"
                }`,
                lg: `text-lg h-12 ${
                    isIcon ? "px-4" : "text-center justify-center px-2"
                }`,
            };

            return sizeMappings[this.size] || sizeMappings.md;
        },
        btnClasses() {
            const borderRadiusClasses = this.round ? "rounded-full" : "rounded";
            if (this.disabled) {
                return `bg-gray-200 text-gray-500 cursor-not-allowed hover:bg-gray-200 shadow-none font-semibold hover:shadow-none ${this.sizeClasses} ${borderRadiusClasses}`;
            }
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
