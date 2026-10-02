<template>
    <!-- <Field :name="name" v-slot="{ field }" v-model="selected">
        <label
            v-if="labelTitle"
            class="form-label block mb-1 text-gray-600 font-poppins text-sm"
            :for="name"
            ><span v-if="required" class="text-red-600">*</span>
            <span class="ml-1">{{ labelTitle }}</span></label
        >
        <v-select
            v-model="selected"
            :label="labelSelect"
            :reduce="(option) => option.value"
            :options="options"
            v-bind="field"
            class="style-chooser py-0 h-10 leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md appearance-none outline-none"
            :class="[
                {
                    'text-xs': inputTextSize == 'xs',
                    'text-sm': inputTextSize == 'sm',
                    'text-md': inputTextSize == 'md',
                    'text-lg': inputTextSize == 'lg',
                },
                classes,
            ]"
        ></v-select>
    </Field> -->

    <!-- <Field :name="name" v-slot="{ field }" v-model="selectedField">
        <label class="form-label block mb-1 text-gray-600 font-poppins text-sm"
            ><span class="text-red-600">*</span>
            <span class="ml-1">{{ labelTitle }}</span></label
        >
        <v-select
            v-model="selected"
            :label="options?.label"
            :value="selectedField"
            :reduce="(option) => option.value"
            :options="options"
            v-bind="field"
            class="py-0 h-10 leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md appearance-none outline-none"
        ></v-select>
    </Field> -->
    <!-- <v-select :options="options" :label="labelSelect" v-model="selected">
        <template #search="{ attributes, events }">
            <input
                :name="name"
                :value="selected"
                class="vs__search"
                :required="!selected"
                @input="handleChange"
                @blur="handleBlur"
                v-bind="attributes"
                v-on="events"
            />
            {{ selected }}
        </template>
    </v-select> -->
    <!-- <Field :name="name" v-slot="{ field }" v-model="selectedField"> -->
    <label
        v-if="labelTitle"
        class="form-label block mb-1 text-gray-600 font-poppins text-sm"
        :for="name"
        ><span v-if="requiredSelect" class="text-red-600">*</span>
        <span class="ml-1">{{ labelTitle }}</span></label
    >
    <div
        class="relative"
        :class="{ 'has-error': !!errorMessage, success: meta.valid }"
    >
        <v-select
            :inputId="name"
            :placeholder="placeholder"
            :options="options"
            :filterable="filterable"
            :multiple="multiple"
            :clearable="clearable"
            v-model="selected"
            v-bind="$attrs"
            :reduce="reduce"
            @option:selected="setSelected"
            @input="setSelected"
            class="style-chooser py-0 h-10 leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md appearance-none outline-none"
            :class="[
                {
                    'border-red-400': errorMessage,
                    'pl-12': leftIcon === true,
                    'text-center': inputCenter == true,
                    'text-xs': inputTextSize == 'xs',
                    'text-sm': inputTextSize == 'sm',
                    'text-md': inputTextSize == 'md',
                    'text-lg': inputTextSize == 'lg',
                },
                classes,
            ]"
        >
        </v-select>

        <input
            type="hidden"
            :name="name"
            :value="inputValueSelect"
            @input="handleChange"
            @blur="handleBlur"
        />
        <p
            v-if="inputInfo"
            class="font-thin text-[10px] text-gray-500 italic drop-shadow-sm"
        >
            {{ inputInfo }}
        </p>
        <p class="text-red-600 mt-1 text-xs" v-show="errorMessage">
            {{ errorMessage }}
        </p>
        <svg
            class="absolute text-red-600 fill-current"
            style="top: 8px; right: 48px"
            v-if="errorMessage"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
        >
            <path
                d="M11.953,2C6.465,2,2,6.486,2,12s4.486,10,10,10s10-4.486,10-10S17.493,2,11.953,2z M13,17h-2v-2h2V17z M13,13h-2V7h2V13z"
            />
        </svg>
    </div>
    <!-- </Field> -->
</template>
<script>
import { useField, Field, ErrorMessage } from "vee-validate";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    name: "MSelect",
    components: { useField, Field, ErrorMessage, vSelect },
    data() {
        return {
            valSelected: "",
        };
    },
    props: {
        type: {
            type: String,
            default: "text",
        },
        name: {
            type: String,
            required: true,
        },
        requiredSelect: {
            type: Boolean,
            default: false,
        },
        labelTitle: {
            type: String,
            required: false,
        },
        labelSelect: {
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
        clearable: {
            type: Boolean,
            default: !0,
        },
        inputCenter: {
            type: Boolean,
            default: false,
        },
        inputTextSize: {
            type: String,
            default: "sm",
        },
        autofocus: {
            type: String,
        },
        options: {
            type: Array,
            default: [],
        },
        filterable: {
            type: Boolean,
            default: true,
        },
        multiple: {
            type: Boolean,
            default: false,
        },
        selected: {
            type: Object,
            default: {},
        },
        selectedField: {
            type: String,
        },
        vbindVal: {
            type: Object,
            default: null,
        },
        reduce: {
            type: Function,
            default: (option) => option,
        },
        modelValue: {
            type: String,
        },
    },
    setup(props) {
        const {
            value: inputValueSelect,
            errorMessage,
            handleBlur,
            handleChange,
            meta,
        } = useField(props.name, undefined, {
            initialValue: props.value,
        });
        // const onInput = (event) => {
        //     handleChange(event, true);
        //     emit("update:value", event.target.value);
        // };

        // const onChangeEmit = (e) => {
        //     console.log(e);
        //     emit("selectChange", e);
        // };

        return {
            handleChange,
            handleBlur,
            errorMessage,
            inputValueSelect,
            meta,
        };
    },
    emits: ["update:modelValue", "selectOption", "doSelected"],
    methods: {
        setSelected(data) {
            console.log(data);
            this.inputValueSelect = data.value.toString();
            this.$emit("selectOption", data);
        },
        valueSelected(val) {
            console.log("doSelected", val);
            this.$emit("doSelected", this.inputValueSelect);
        },
        onClear(val) {
            console.log(val);
            this.clearable = false;
        },
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
    created() {
        if (this.selected.value) {
            this.inputValueSelect = this.selected.value;
        }
    },
};
</script>
<style>
.style-chooser .vs__font-size {
    font-size: 11px;
}
.style-chooser .vs__search::placeholder {
    color: #a9a9a9;
    font-weight: lighter;
    font-size: 12px;
}
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {
    margin-top: 2px;
    border: none;
    color: #394066;
    line-height: 1.5;
}
.style-chooser .vs__clear,
.style-chooser .vs__open-indicator {
    fill: #394066;
}
</style>
