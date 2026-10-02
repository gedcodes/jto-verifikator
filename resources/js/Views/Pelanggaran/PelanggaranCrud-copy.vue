<template>
    <nav class="bg-white shadow-md rounded-t-md mt-2">
        <div class="max-w-full mx-auto">
            <div class="flex flex-row flex-wrap justify-center items-center content-center space-x-0 space-y-0">
                <div
                    class="flex flex-row flex-wrap w-full xl:w-3/5 2xl:w-3/5 px-2 justify-start items-center content-center space-x-1">

                    <!-- <m-button id="btnDel" name="btnDel" icon outline color="red" title="Archive" tooltipText="Archive"
                        tooltipPos="top" iconName="archive" @click="deleteItem()">
                    </m-button> -->
                </div>
                <div class="w-full xl:w-2/5 2xl:w-2/5 p-1 m-auto">
                    <TextInput leftIcon v-model="searchValue" required id="search" type="text" placeholder="Cari"
                        name="search" class="w-full border border-gray-300 focus:border-gray-500">
                        <template #iconLeft>
                            <app-icon class="h-6 mt-3" fill="gray" :name="{ name: 'search' }" />
                        </template>
                    </TextInput>
                </div>
            </div>
        </div>
    </nav>
    <div class="block h-full text-xs">
        <errors v-if="errors" :content="errors" @close="errors = null" class="m-1" />
        <easy-data-table v-model:items-selected="itemsSelected" :fixed-checkbox="fixedCheckbox"
            :checkbox-column-width="checboxColWidth" buttons-pagination :headers="headers" :items="items" border-cell
            :table-class-name="setTableFontSize(textSize)" header-text-direction="center" :table-height="485"
            :search-value="searchValue" :theme-color="themeColor" :loading="loading">
            <template #item-logo_url="{ logo_url }">
                <div class="logo_url-wrapper text-center">
                    <img class="avator" :src="logo_url" alt="" />
                </div>
            </template>
            <template #item-tgl_pelanggaran="{ tgl_pelanggaran }">
                <div class="operation-wrapper">
                    {{ formatDate(tgl_pelanggaran) }}
                </div>
            </template>
            <template #item-nm_device="{ detailcapture }">
                <div class="operation-wrapper">
                    {{ deviceName(detailcapture) }}
                </div>
            </template>
            <template #item-tgl_masa_berlaku="{ tgl_masa_berlaku }">
                <div class="operation-wrapper">
                    {{ moveDate(tgl_masa_berlaku) }}
                </div>
            </template>
            <template #item-photo="{ detailcapture }">
                <div class="flex justify-center items-center">
                    <img class="w-20 m-2 rounded" :src="detailcapture[0].img_url" alt="" />
                </div>
            </template>
            <template #item-operation="item">
                <div class="operation-wrapper text-center">
                    <m-button icon outline size="xs" class="text-cyan-500" color="cyan" tooltipText="View" tooltipPos="top"
                        iconName="eye" @click="viewItem(item)">
                    </m-button>

                    <m-button icon outline class="text-yellow-600" color="yellow" size="xs" tooltipText="Print"
                        tooltipPos="top" iconName="print" @click="generatePDF(item)">
                    </m-button>

                    <!-- <m-button icon outline color="red" size="xs" tooltipText="Archive" tooltipPos="top" iconName="archive"
                        @click="deleteItem(item)">
                    </m-button> -->
                </div>
            </template>
            <template #item-is_active="{ is_active }">
                <div class="operation-wrapper text-center">
                    <m-toggle :name="uniq" :selected="is_active" :tooltipText="is_active ? 'Aktif' : 'Tidak Aktif'">
                    </m-toggle>
                </div>
            </template>
        </easy-data-table>
    </div>
</template>
<script>
import { ref } from "vue";
import AppIcon from "@/js/components/icon/AppIcon.vue";
import MButton from "@/js/components/form/MButton.vue";
import MToggle from "@/js/components/form/MToggle.vue";
import TextInput from "@/js/components/form/TextInput";
import Errors from "@/js/components/Errors.vue";
import Tooltip from "@/js/components/utils/Tooltip.vue";
import PrintPdf from "./PrintPdf.vue";
import moment from "moment";
import Image from "../../components/Image.vue";

export default {
    name: "mcrud",
    components: {
        AppIcon,
        MButton,
        EasyDataTable: window["vue3-easy-data-table"],
        Errors,
        TextInput,
        MToggle,
        Tooltip,
        MToggle,
        PrintPdf,
        Image,
    },
    data() {
        return {
            errors: null,
            searchValue: ref(),
            itemsSelected: ref([]),
            themeColor: "#15803d",
            showModal: false,
            titleDialog: "Konfirmasi",
            uniq: "chk" + new Date().getTime(),
        };
    },
    props: {
        titleCrud: {
            type: String,
            default: "SETUP DATA",
        },
        slotName: {
            type: String,
        },
        headers: {
            type: Object,
            required: true,
        },
        items: {
            type: Object,
            default: [],
        },
        loading: {
            type: Boolean,
            default: false,
        },
        textSize: {
            type: String,
            default: "lg",
        },
        checboxColWidth: {
            type: Number,
            default: 20,
        },
        fixedCheckbox: {
            type: Boolean,
            default: true,
        },
        isViewDetail: {
            type: Boolean,
            default: false,
        },
    },
    emits: ["view-item", "add-item", "edit-item", "delete-item", "print-item"],
    methods: {
        deviceName(arr) {
            var data = [];
            arr.map((val) => {
                if (!val.is_plat) {
                    if (val.device_id) {
                        data.push(val.device_id == 1 ? "WIM" : "LHR");
                    }
                }
            })
            return data.length > 1 ? data.join(", ") : data.toString();
        },
        formatDate(val) {
            return moment(val).format('DD-MM-YYYY HH:mm:ss');
        },
        moveDate(val) {
            return moment(val).format('DD-MM-YYYY');
        },
        generatePDF(item) {
            if (item == undefined) {
                if (this.itemsSelected.length < 1) {
                    this.errors = {
                        message: "Silahkan Pilih Data Yang Akan Di Print",
                    };
                    // this.showModal = false;
                } else {
                    // console.log(JSON.parse(JSON.stringify(this.itemsSelected)));
                    this.errors = null;
                    this.$emit(
                        "print-item",
                        JSON.parse(JSON.stringify(this.itemsSelected))
                    );
                    // this.showModal = !this.showModal;
                }
            } else {
                // console.log(item);
                this.errors = null;
                // this.showModal = !this.showModal;

                this.$emit("print-item", item);
            }
        },
        viewItem(item) {
            // this.titleDialog = "TAMBAH DATA";
            // this.showModal = !this.showModal;
            this.$emit("view-item", item);
        },
        addItem() {
            // this.titleDialog = "TAMBAH DATA";
            // this.showModal = !this.showModal;
            this.$emit("add-item");
        },
        editItem(item) {
            // this.titleDialog = "UBAH DATA";
            if (item == undefined) {
                if (this.itemsSelected.length != 1) {
                    this.errors = {
                        message:
                            "Silahkan Pilih Salah Satu Data Yang Akan Di Ubah",
                    };
                    // this.showModal = false;
                } else {
                    // console.log(this.itemsSelected[0].nama);
                    this.errors = null;
                    this.$emit(
                        "edit-item",
                        JSON.parse(JSON.stringify(this.itemsSelected[0]))
                    );
                    // this.showModal = !this.showModal;
                }
            } else {
                // console.log(item);
                this.errors = null;
                // this.showModal = !this.showModal;
                this.$emit("edit-item", item);
            }
        },
        deleteItem(item) {
            if (item == undefined) {
                if (this.itemsSelected.length < 1) {
                    this.errors = {
                        message: "Silahkan Pilih Data Yang Akan Di Hapus",
                    };
                    // this.showModal = false;
                } else {
                    // console.log(JSON.parse(JSON.stringify(this.itemsSelected)));
                    this.errors = null;
                    this.$emit(
                        "delete-item",
                        JSON.parse(JSON.stringify(this.itemsSelected))
                    );
                    // this.showModal = !this.showModal;
                }
            } else {
                // console.log(item);
                this.errors = null;
                // this.showModal = !this.showModal;

                this.$emit("delete-item", item);
            }

            // this.titleDialog = "KONFIRMASI";
            // if (item == undefined) {
            //     if (this.itemsSelected.length != 1) {
            //         this.errors = {
            //             message: "Silahkan Pilih Data Yang Akan Di Hapus",
            //         };
            //         this.showModal = false;
            //     } else {
            //         console.log(this.itemsSelected[0].nama);
            //         this.errors = null;
            //         this.showModal = !this.showModal;
            //     }
            // } else {
            //     console.log(item);
            //     this.errors = null;
            //     this.showModal = !this.showModal;
            // }
        },
        setError(error = null) {
            this.errors = {
                message: error,
            };
        },
        setTableFontSize(size) {
            let tableFontSize = "customize-table";
            switch (size) {
                case "xs":
                    tableFontSize = "customize-table-xs";
                    break;
                case "sm":
                    tableFontSize = "customize-table-sm";
                    break;
                case "md":
                    tableFontSize = "customize-table-md";
                    break;
                default:
                    tableFontSize = "customize-table";
                    break;
            }
            return tableFontSize;
        },
    },
    mounted() {
        // this.setTableFontSize();
        // let tableFontSize = "customize-table";
        // switch (this.textSize) {
        //     case "xs":
        //         tableFontSize = "customize-table-xs";
        //         break;
        //     case "sm":
        //         tableFontSize = "customize-table-sm";
        //         break;
        //     case "md":
        //         tableFontSize = "customize-table-md";
        //         break;
        //     default:
        //         tableFontSize = "customize-table";
        //         break;
        // }
        // return tableFontSize;
    },
};
</script>
<style scope>
.customize-table {
    --easy-table-border: none;
    --easy-table-header-background-color: #f3f4f6;
    --easy-table-buttons-pagination-border: 1px solid #e0e0e0;
}

.customize-table-xs {
    --easy-table-border: none;
    --easy-table-header-background-color: #f3f4f6;
    --easy-table-buttons-pagination-border: 1px solid #e0e0e0;
    --easy-table-body-row-font-size: 9px;
    --easy-table-header-font-size: 9px;
}

.customize-table-sm {
    --easy-table-border: none;
    --easy-table-header-background-color: #f3f4f6;
    --easy-table-buttons-pagination-border: 1px solid #e0e0e0;
    --easy-table-body-row-font-size: 10px;
    --easy-table-header-font-size: 10px;
}

.customize-table-md {
    --easy-table-border: none;
    --easy-table-header-background-color: #f3f4f6;
    --easy-table-buttons-pagination-border: 1px solid #e0e0e0;
    --easy-table-body-row-font-size: 11px;
    --easy-table-header-font-size: 11px;
}

.player-wrapper {
    padding: 5px;
    display: flex;
    align-items: center;
    justify-items: center;
}

.avator {
    margin-right: 10px;
    display: inline-block;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 10%);
}
</style>
