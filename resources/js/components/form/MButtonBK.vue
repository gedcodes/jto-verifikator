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
        >
            <!-- <slot name="mButtonSlot"></slot> -->
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
    >
        <!-- <slot name="mButtonSlot"></slot> -->
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
    },
};
</script>
