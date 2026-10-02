<template>
    <div class="flex space-x-4 flex-row">
        <span class="text-sm font-medium text-slate-700">
            {{ label }}
        </span>
        <div :class="modelValue ? 'bg-green-500' : 'bg-gray-300'" @click="toggle"
            class="w-14 transition-all duration-500 h-6 flex items-center rounded-full p-1 cursor-pointer">

            <div :class="{'translate-x-7': modelValue}"
                class="w-5 h-5 transition-all duration-500 transform bg-white rounded-full shadow-md">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Toggle",
    props: {
        modelValue: {
            type: Boolean,
            default: true,
        },
        label: {
            type: String,
            default: "Aktif Data",
        }
    },
    methods: {
        toggle() {
            this.$emit('update:modelValue', !this.modelValue);
            //console.log(!this.modelValue);
        }
    }
}
// const props = defineProps({
//     modelValue: {
//         type: Boolean
//     }
// })
// const emit = defineEmits(['update:modelValue'])
// function toggle() {
//     emit('update:modelValue', !props.modelValue)
// }
</script>

<style scoped>

</style>
