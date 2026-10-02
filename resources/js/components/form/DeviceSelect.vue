<template>
    <Field :name="name" :id="name" v-model="selectedOption">
        <label v-if="labelTitle" class="form-label block mb-1 text-gray-600 font-poppins text-sm" :for="name"><span
                v-if="requiredSelect" class="text-red-600">*</span>
            <span class="ml-1">{{ labelTitle }}</span></label>
        <div class="relative">
            <v-select v-model="selectedOption" :options="optionsDevice" label="label" :placeholder="placeholder"
                :multiple="multiple" :searchable="searchable"
                class="style-chooser py-0 h-10 leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md appearance-none outline-none border border-gray-300 focus:border-blue-600 focus:font-normal focus:shadow"
                :class="[
                    {
                        'text-xs': inputTextSize == 'xs',
                        'text-sm': inputTextSize == 'sm',
                        'text-md': inputTextSize == 'md',
                        'text-lg': inputTextSize == 'lg',
                    },
                ]">
            </v-select>
        </div>
    </Field>
</template>

<script>
import { useField, Field, ErrorMessage } from "vee-validate";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import DeviceService from "@/js/service/DeviceService.js";

export default {
    name: "deviceservice",
    components: {
        vSelect,
        useField,
        Field,
        ErrorMessage,
    },
    data() {
        return {
            optionsDevice: [],
            selectedOption: null
        }
    },
    props: {
        selectedField: {
            type: Object,
            default: {},
        },
        modelValue: {
            type: Object,
        },
        label: {
            type: String,
            default: ''
        },
        placeholder: {
            type: String,
            default: 'Select an option'
        },
        multiple: {
            type: Boolean,
            default: false
        },
        searchable: {
            type: Boolean,
            default: false
        },
        inputTextSize: {
            type: String,
            default: "sm",
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
    },
    watch: {
        selectedField(newValue) {
            this.selectedOption = newValue
        },
        selectedOption(newOption) {
            // console.log(newOption);
            this.$emit('update:modelValue', newOption)
        }
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
    created() {
        this.getData();
        if (this.selectedField) {
            this.inputValueSelect = this.selectedField.value;
        };
    },
    emits: ["update:modelValue", "selectOption", "doSelected"],
    methods: {
        getData() {
            DeviceService.getAll()
                .then((response) => {
                    // console.log(response)
                    if (response.success) {
                        if (response.data) {
                            for (var row of response.data) {
                                this.optionsDevice.push({
                                    label: row.nama,
                                    value: row.id
                                })
                            }
                        } else {
                            this.optionsDevice = [];
                        }
                    }
                })
                .catch((err) => {
                    this.optionsDevice = [];
                });
        },
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
        withPopper(dropdownList, component, { width }) {
            dropdownList.style.width = width;

            const popper = createPopper(component.$refs.toggle, dropdownList, {
                placement: "bottom",
            });
            return () => popper.destroy();
        },
    }
}
</script>
