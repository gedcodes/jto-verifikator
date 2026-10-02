<template>
    <!-- <div class="flex justify-center">
        <div class="form-check form-switch">
            <input
                class="form-check-input appearance-none w-9 -ml-10 rounded-full float-left h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer shadow-sm"
                type="checkbox"
                role="switch"
                :name="name"
                :id="name"
                v-model="selected"
            />
            <label
                v-if="label"
                class="form-check-label inline-block text-gray-800"
                for="flexSwitchCheckDefault"
                >{{ label }}</label
            >
        </div>
    </div> -->
    <label
        v-if="label"
        :for="name"
        class="form-label mr-4 align-middle text-gray-600 font-poppins text-sm"
        >{{ label }}</label
    >
    <Tooltip
        class="font-poppins text-xs m-0 p-0"
        position="top"
        :tooltipText="checked ? 'Aktif' : 'Tidak Aktif' || tooltipText"
    >
        <div
            class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in"
        >
            <input
                type="checkbox"
                :name="name"
                :id="name"
                class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer"
                :disabled="disabled"
                :checked="checked"
                @change="(e) => onchange(e)"
            />
            <!-- @change="onchange" -->
            <label
                :for="name"
                class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"
            ></label>
        </div>
    </Tooltip>
</template>
<script>
import Tooltip from "@/js/components/utils/Tooltip.vue";
export default {
    components: {
        Tooltip,
    },
    data() {
        return {
            checked: this.selected,
        };
    },
    emits: ["input"],
    props: {
        name: {
            type: String,
            required: true,
        },
        label: {
            type: String,
        },
        selected: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        tooltipText: {
            type: String,
        },
    },
    methods: {
        onchange(e) {
            this.checked = e.target.checked;
            // this.$emit("input", this.checked);
        },
        showOutputValue() {
            return this.checked;
        },
    },
};
</script>
<style scoped>
.toggle-checkbox:checked {
    @apply: right-0 border-green-400;
    right: 0;
    border-color: #68d391;
}
.toggle-checkbox:checked + .toggle-label {
    @apply: bg-green-400;
    background-color: #68d391;
}
</style>
