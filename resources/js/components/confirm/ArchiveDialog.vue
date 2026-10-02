<template>
    <transition name="fade">
        <div v-if="isVisible"
            class="min-w-screen h-screen animated fadeIn faster fixed left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none"
            id="confirmModal">
            <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
            <div class="w-full max-w-lg p-5 relative mx-auto my-auto rounded-xl shadow-lg bg-white">
                <!--content-->
                <div v-if="busy"
                    class="flex flex-col text-center justify-center items-center content-center p-2 px-6 rounded-lg bg-white text-bold">
                    <div>
                        <circle-svg stroke="#1e293b" class="w-12 h-12 text-center" />
                    </div>
                    <p>Tunggu hingga proses selesai</p>
                </div>
                <div v-else class="">
                    <!--body-->
                    <errors v-if="errorsArchive" :content="errorsArchive" @close="errorsArchive = null" />
                    <div class="text-center p-5 flex-auto justify-center">
                        <h2 class="text-xl font-bold py-4">{{ title }}</h2>
                        <p class="text-sm text-gray-500 px-8">
                            {{ message }}
                        </p>

                    </div>
                    <div>
                        <label class="form-label block mb-1 text-gray-600 font-poppins" for="label"><span
                                class="text-red-600">*</span>
                            <span class="ml-1">Keterangan</span></label>
                        <input name="keterangan" id="keterangan" type="text" v-model="keterangan"
                            placeholder="Masukan Keterangan"
                            class="px-4 py-2 text-sm leading-normal sm:block w-full text-gray-800 bg-white font-sans rounded-md appearance-none outline-none border border-gray-300 focus:border-blue-600 focus:font-normal focus:shadow"
                            :class="{
                                'border-red-400': errorMessage
                            }" />
                        <p class="text-red-600 mt-1 text-xs" v-show="errorMessage">
                            {{ errorMessage }}
                        </p>
                    </div>
                    <!--footer-->
                    <div class="p-3 mt-2 text-center items-center space-x-4 md:block">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <m-button id="btnCancel" outline name="btnCancel" :title="cancelButton"
                                    class="mb-2 md:mb-0 bg-white px-5 py-4 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-md hover:shadow-lg hover:bg-gray-100"
                                    @click="_cancel">
                                </m-button>
                            </div>

                            <div class="flex w-full justify-end items-end content-end space-x-2">
                                <m-button id="btnHapus" icon name="btnSimpan" color="red" :title="okButton"
                                    iconName="archive"
                                    class="mb-2 md:mb-0 bg-red-500 border border-red-500 px-8 py-4 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-red-600"
                                    @click="_confirm">
                                </m-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
<script>
import CircleSvg from "@/js/components/CircleSvg.vue";
import MButton from "@/js/components/form/MButton.vue";
import Errors from "@/js/components/Errors.vue";
export default {
    name: "ArchiveDialog",
    components: {
        MButton,
        CircleSvg,
        Errors,
    },
    data: () => ({
        errors: null,
        isVisible: false,
        busy: false,
        // Parameters that change depending on the type of dialogue
        title: undefined,
        message: undefined, // Main text content
        okButton: undefined, // Text for confirm button; leave it empty because we don't know what we're using it for
        cancelButton: "Batal", // text for cancel button

        // Private variables
        resolvePromise: undefined,
        rejectPromise: undefined,
    }),
    methods: {
        open() {
            this.isVisible = true;
        },
        close() {
            this.busy = false;
            this.isVisible = false;
        },
        show() {
            this.open();

            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve;
                this.rejectPromise = reject;
            });
        },
        showBusy(isBusy = false) {
            this.busy = isBusy;
        },
        showError(error) {
            this.errors = {
                message: error,
            };
        },
        _confirm() {
            // this.close();
            this.resolvePromise(true);
        },
        _cancel() {
            this.resolvePromise(false);
            this.close();
        },
    },
};
</script>
