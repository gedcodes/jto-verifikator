<template>
    <div>
        <label
            v-if="label"
            class="form-label block mb-1 text-gray-600 font-poppins"
            :for="id"
            ><span v-if="required" class="text-red-600">*</span>
            <span class="ml-1">{{ label }}</span></label
        >
        <div class="relative">
            <!-- <input
                :id="id"
                :name="name"
                ref="input"
                v-bind="$attrs"
                v-model="textField"
                class="px-1 py-1 h-10 leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md text-left appearance-none outline-none"
                :class="[
                    {
                        'border-red-400': errors,
                        'pl-12': leftIcon === true,
                    },
                    classes,
                ]"
                :type="type"
                :required="required"
                :autofocus="autofocus"
                autocomplete="off"
            /> -->
            <!-- <input
                ref="input"
                v-bind="$attrs"
                v-model="textField"
                :id="id"
                :name="name"
                :placeholder="placeholder"
                :type="type"
                :required="required"
                :autofocus="autofocus"
                :value="value"
                class="px-2 py-1 h-10 leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md text-left appearance-none outline-none"
                :class="[
                    {
                        'border-red-400': errors,
                        'pl-12': leftIcon === true,
                    },
                    classes,
                ]"
            /> -->
            <input
                ref="input"
                v-bind="$attrs"
                v-model="textField"
                :id="id"
                :name="name"
                :placeholder="placeholder"
                :type="type"
                :required="required"
                :autofocus="autofocus"
                class="px-2 py-1 h-10 leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md text-left appearance-none outline-none"
                :class="[
                    {
                        'border-red-400': errors,
                        'pl-8': leftIcon === true,
                    },
                    classes,
                ]"
            />
            <p
                v-if="inputInfo"
                class="font-thin text-[10px] text-gray-500 italic drop-shadow-sm"
            >
                {{ inputInfo }}
            </p>
            <div v-if="errors" class="text-red-600 mt-1 text-xs">
                {{ errors[0] }}
            </div>
            <svg
                class="absolute text-red-600 fill-current"
                style="top: 8px; right: 12px"
                v-if="errors"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
            >
                <path
                    d="M11.953,2C6.465,2,2,6.486,2,12s4.486,10,10,10s10-4.486,10-10S17.493,2,11.953,2z M13,17h-2v-2h2V17z M13,13h-2V7h2V13z"
                />
            </svg>
            <div
                class="absolute left-0 top-0 bottom-0 w-10 block ml-2"
                v-if="leftIcon"
            >
                <slot name="iconLeft"></slot>
            </div>
            <div
                class="absolute right-0 top-0 bottom-0 w-10 block mr-0"
                v-if="rightIcon"
            >
                <slot name="iconRight"></slot>
            </div>
        </div>
    </div>
</template>

<script>
let _uid = 0;
export default {
    name: "TextInput",
    inheritAttrs: false,
    beforeCreate() {
        this._uid = _uid.toString();
        _uid += 1;
    },
    data() {
        return {
            textField: "",
        };
    },
    props: {
        id: {
            type: String,
            default() {
                return `text-input-${Math.random()}`;
            },
        },
        type: {
            type: String,
            default: "text",
        },
        name: {
            type: String,
            default: "text",
        },
        placeholder: {
            type: String,
            value: "",
        },
        required: {
            type: Boolean,
            default: false,
        },
        autofocus: String,
        value: String,
        keyInput: String,
        errorKey: String,
        label: String,
        inputInfo: String,
        errors: {
            type: Object,
        },
        leftIcon: {
            type: Boolean,
            default: false,
        },
        rightIcon: {
            type: Boolean,
            default: false,
        },
        // showPass: {
        //   type: Boolean,
        //   default: false,
        // },
        bordered: {
            type: Boolean,
            default: true,
        },
    },

    methods: {
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end);
        },
        // showPassword() {
        //   if (this.fieldType === "password") {
        //     console.log("Test");
        //     this.fieldType = "text";
        //     this.showPass = true;
        //   } else {
        //     console.log("Test2");

        //     this.fieldType = "password";
        //     this.showPass = false;
        //   }
        // },
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
