<script setup>
import { toRef } from 'vue';
import { useField } from 'vee-validate';

const props = defineProps({
    type: {
        type: String,
        default: 'text',
    },
    name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
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
        <label :for="name">{{ label }}</label>
        <input
            :name="name"
            :id="name"
            :type="type"
            :value="inputValue"
            :placeholder="placeholder"
            @input="handleChange"
            @blur="handleBlur"
            class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" />

        <p class="help-message" v-show="errorMessage || !meta.valid">
            {{ errorMessage }}
        </p>
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
    border-color: var(--error-bg-color);
    color: var(--error-color);
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
