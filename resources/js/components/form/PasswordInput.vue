<template>
    <div>
        <label
            v-if="label"
            class="form-label block mb-1 text-gray-700"
            :for="id"
            ><span v-if="required" class="text-red-600">*</span>
            <span class="ml-1">{{ label }}</span></label
        >
        <div class="relative">
            <input
                ref="input"
                v-bind="$attrs"
                v-model="textField"
                :id="id"
                :name="name"
                :placeholder="placeholder"
                :type="inputType"
                :required="required"
                :autofocus="autofocus"
                class="px-2 py-1 h-10 leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md text-left appearance-none outline-none"
                :class="[
                    {
                        'border-red-400': errors,
                        'pl-12': leftIcon === true,
                    },
                    classes,
                ]"
            />
            <span
                class="mt-1 text-xs cursor-pointer"
                v-show="showPwIcon"
                title="Lihat Password"
                @click="switchVisibility"
            >
                <i
                    v-if="errors"
                    class="absolute fill-current fa-regular"
                    :class="[
                        pwIcon
                            ? 'fa-eye fa-lg text-red-600'
                            : 'fa-eye-slash fa-lg text-red-600',
                    ]"
                    style="top: 20px; right: 12px"
                ></i>
                <i
                    v-if="!errors"
                    class="absolute fill-current fa-regular"
                    :class="[pwIcon ? 'fa-eye fa-lg' : 'fa-eye-slash fa-lg']"
                    style="top: 20px; right: 12px"
                ></i>
            </span>
            <p
                v-if="inputInfo"
                class="font-thin text-xs text-gray-400 italic drop-shadow-sm"
            >
                {{ inputInfo }}
            </p>
            <div v-if="errors" class="text-red-600 mt-1 text-xs">
                {{ errors[0] }}
            </div>
            <!-- <svg
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
      </svg> -->
            <div
                class="absolute left-0 top-0 bottom-0 w-10 block ml-2"
                v-if="leftIcon"
            >
                <slot name="iconLeft"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            default() {
                return `text-input-password-id-${Math.random()}`;
            },
        },
        name: {
            type: String,
            default() {
                return `text-input-password-name-${Math.random()}`;
            },
        },
        required: {
            type: Boolean,
            default: false,
        },
        placeholder: {
            type: String,
            value: "",
        },
        type: {
            type: String,
            value: "",
            required: true,
        },
        required: {
            type: Boolean,
            default: false,
        },
        leftIcon: {
            type: Boolean,
            default: false,
        },
        autofocus: String,
        label: String,
        inputInfo: String,
        errors: {
            type: Object,
        },
        bordered: {
            type: Boolean,
            default: true,
        },
    },
    data() {
        return {
            textField: "",
            pwIcon: false,
            inputType: this.type,
        };
    },
    computed: {
        showPwIcon() {
            return this.type === "password";
        },
        classes() {
            return {
                "border-2 border-bluegrey-200 focus:border-bluegrey-400 focus:bg-bluegrey-100 focus:font-medium focus:shadow":
                    this.bordered === true,
                "border-2 bg-bluegrey-400 focus:bg-white":
                    this.bordered === false,
            };
        },
    },
    methods: {
        switchVisibility() {
            this.inputType =
                this.inputType === "password" ? "text" : "password";
            this.$emit("input", {
                type: this.inputType,
            });
            this.pwIcon = this.inputType === "text";
        },
    },
};
</script>
