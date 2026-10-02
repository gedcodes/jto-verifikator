<template>
    <div>
        <label v-if="label" class="form-label block mb-1 text-gray-600 font-poppins" :for="name" :class="[
            {
                'text-xs': labelTextSize == 'xs',
                'text-sm': labelTextSize == 'sm',
                'text-md': labelTextSize == 'md',
                'text-lg': labelTextSize == 'lg',
            }
        ]"><span v-if="required" class="text-red-600">*</span>
            <span class="ml-1">{{ label }}</span></label>

        <div v-if="type == 'file'" class="relative mt-2" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
            <div class="flex items-center space-x-6">
                <div class="shrink-0">
                    <img v-if="image" class="object-cover w-16 h-16 rounded-full" :src="image" alt="no image" />
                    <img v-else-if="src" class="object-cover w-16 h-16 rounded-full" :src="src" alt="no image" />
                    <img v-else class="object-cover w-16 h-16 rounded-full" src="/storage/images/no-image.png"
                        alt="no image" />
                </div>
                <label class="block">
                    <span class="sr-only">Pilih Gambar</span>
                    <input :name="name" :id="name" :type="type" :placeholder="placeholder" @input="handleChange"
                        @blur="handleBlur"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        @change="onFileChange" />
                </label>
            </div>
        </div>

        <div v-else class="relative" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
            <input :name="name" :id="name" :type="type" :value="inputValue" :placeholder="placeholder"
                @input="handleChange" @blur="handleBlur" @change="handleChange" :readonly="readonly"
                class="px-4 py-1 leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md appearance-none outline-none"
                :class="[
                    {
                        'h-9': height == '9',
                        'h-10': height == '10',
                        'border-red-400': errorMessage || err,
                        'text-red-500': err,
                        'bg-gray-200': readonly,
                        'pl-8': leftIcon === true,
                        'text-center': inputCenter == true,
                        'text-xs': inputTextSize == 'xs',
                        'text-sm': inputTextSize == 'sm',
                        'text-md': inputTextSize == 'md',
                        'text-lg': inputTextSize == 'lg',
                    },
                    classes,
                ]" />
            <p v-if="inputInfo" class="font-thin text-[10px] text-gray-500 italic drop-shadow-sm">
                {{ inputInfo }}
            </p>
            <p class="text-red-600 mt-1 text-xs" v-show="errorMessage">
                {{ errorMessage }}
            </p>
            <svg class="absolute text-red-600 fill-current" style="top: 8px; right: 12px" v-if="errorMessage"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path
                    d="M11.953,2C6.465,2,2,6.486,2,12s4.486,10,10,10s10-4.486,10-10S17.493,2,11.953,2z M13,17h-2v-2h2V17z M13,13h-2V7h2V13z" />
            </svg>
            <div class="absolute left-0 top-0 bottom-0 w-10 block ml-2" v-if="leftIcon">
                <slot name="iconLeft"></slot>
            </div>
            <div class="absolute right-0 top-0 bottom-0 w-10 block mr-0" v-if="rightIcon">
                <slot name="iconRight"></slot>
            </div>
        </div>
    </div>
</template>

<script>
import { useField, Field } from "vee-validate";
export default {
    name: "MInput",
    components: { useField, Field },
    data() {
        return {
            image: "",
        };
    },
    props: {
        type: {
            type: String,
            default: "text",
        },
        value: {
            type: String,
            default: "",
        },
        name: {
            type: String,
            required: true,
        },
        required: {
            type: Boolean,
            default: false,
        },
        label: {
            type: String,
            required: false,
        },
        successMessage: {
            type: String,
            default: "",
        },
        errorMessageSelected: {
            type: String,
            default: "",
        },
        placeholder: {
            type: String,
            default: "",
        },
        src: {
            type: String,
            default: "",
        },
        bordered: {
            type: Boolean,
            default: true,
        },
        leftIcon: {
            type: Boolean,
            default: false,
        },
        rightIcon: {
            type: Boolean,
            default: false,
        },
        inputInfo: {
            type: String,
        },
        inputCenter: {
            type: Boolean,
            default: false,
        },
        height: {
            type: String,
            default: "10",
        },
        inputTextSize: {
            type: String,
            default: "sm",
        },
        labelTextSize: {
            type: String,
            default: "sm",
        },
        autofocus: {
            type: String,
        },
        readonly: {
            type: Boolean,
            default: false,
        },
        err: {
            type: Boolean,
            default: false,
        },
    },
    emits: ["on-preview-image", "input", "update:modelValue"],
    setup(props, { emit }) {
        const {
            value: inputValue,
            errorMessage,
            handleBlur,
            handleChange,
            meta,
        } = useField(props.name, undefined, {
            initialValue: props.value,
        });

        const onInput = (event) => {
            handleChange(event, true);
            emit("update:value", event.target.value);
        };

        const onChange = (event) => {
            const value = event.target.value;
            console.log(value);
            handleChange(value);
            emit("update:modelValue", value);
        };

        return {
            onInput,
            handleChange,
            handleBlur,
            onChange,
            errorMessage,
            inputValue,
            meta,
        };
    },
    methods: {
        onFileChange(e) {
            const file = e.target.files || e.dataTransfer.files;

            if (!file.length) return;
            this.createImage(file[0]);
            // this.src = URL.createObjectURL(file);
            // this.$emit("on-preview-image", file);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        onChangeInput(e) {
            this.$emit("input", e.target.value);
        },
        updateInput(event) {
            this.$emit("update:modelValue", event.target.value);
        },
        valueInput() {
            return this.inputValue;
        },
        // onSearch(search, loading) {
        //     if (search.length) {
        //         loading(true);
        //         this.search(loading, search, this);
        //     }
        // },
        // search: _.debounce((loading, search, vm) => {
        //     fetch(
        //         `https://api.github.com/search/repositories?q=${escape(search)}`
        //     ).then((res) => {
        //         res.json().then((json) => (vm.options = json.items));
        //         loading(false);
        //     });
        // }, 350),
    },
    computed: {
        classes() {
            return {
                "border border-gray-300 focus:border-blue-600 focus:font-normal focus:shadow":
                    this.bordered === true,
                "border bg-bluegrey-400 focus:bg-white":
                    this.bordered === false,
            };
        },
    },
};
</script>
