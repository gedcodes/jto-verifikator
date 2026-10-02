<script setup>
import { toRef } from 'vue';
import { useField, Field } from 'vee-validate';

const props = defineProps({
    type: {
        type: String,
        default: 'text',
    },
    value: {
        type: String,
        default: '',
    },
    name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        default: "",
    },
    textVal: {
        type: String,
        default: "",
    },
    successMessage: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    readonly: {
        type: Boolean,
        default: false,
    },
});

// use `toRef` to create reactive references to `name` prop which is passed to `useField`
// this is important because vee-validte needs to know if the field name changes
// https://vee-validate.logaretm.com/v4/guide/composition-api/caveats
const name = toRef(props, 'name');

// we don't provide any rules here because we are using form-level validation
// https://vee-validate.logaretm.com/v4/guide/validation#form-level-validation
const {
    value: inputValue,
    errorMessage,
    handleBlur,
    handleChange,
    meta,
} = useField(name, undefined, {
    initialValue: props.value,
});

</script>

<template>
    <div class="TextInput" :class="{ 'has-error': !!errorMessage }">
        <label :for="name"
            class="after:content-['*'] after:ml-0.5 after:text-red-500 block text-sm font-medium text-slate-700">
            {{ label }}
        </label>
        <div class="relative">
            <Field v-bind="$attrs" v-model="textVal" :name="name" :id="name" :type="type" :placeholder="placeholder"
                @input="handleChange" @blur="handleBlur" :readonly="readonly"
                class="mt-1 px-3 py-2 bg-white border border-slate-300 focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 shadow-sm placeholder-slate-400 block w-full rounded-md sm:text-sm"
                :class="{'border-red-300 focus:outline-none focus:border-red-500 focus:ring-red-500 focus:ring-1': errorMessage, 'bg-gray-100': readonly}" />
            <p class="help-message">
                {{ errorMessage }}
            </p>
            <svg class="absolute text-red-600 fill-current" style="top: 8px; right: 12px" v-if="errorMessage"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path
                    d="M11.953,2C6.465,2,2,6.486,2,12s4.486,10,10,10s10-4.486,10-10S17.493,2,11.953,2z M13,17h-2v-2h2V17z M13,13h-2V7h2V13z" />
            </svg>
        </div>
    </div>
</template>

<style scoped>
.TextInput {
    position: relative;
    margin-bottom: calc(1em * 1.5);
    width: 100%;
}

label {
    display: block;
    margin-bottom: 4px;
    width: 100%;
}

.help-message {
    position: absolute;
    bottom: calc(-1.5 * 1em);
    left: 0;
    margin: 0;
    font-size: 13px;
}

.TextInput.has-error input {
    border-color: var(--error-color);
    /* color: var(--error-color); */
}

.TextInput.has-error input:focus {
    border-color: var(--error-color);
}

.TextInput.has-error .help-message {
    color: var(--error-color);
}

.TextInput.success input {
    background-color: var(--success-bg-color);
    color: var(--success-color);
}

.TextInput.success input:focus {
    border-color: var(--success-color);
}

.TextInput.success .help-message {
    color: var(--success-color);
}
</style>
