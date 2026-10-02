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
                    <errors v-if="errors" :content="errors" @close="errors = null" />
                    <div class="text-center p-5 flex-auto justify-center">
                        <!-- <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 -m-1 flex items-center text-red-500 mx-auto"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-16 h-16 flex items-center text-red-500 mx-auto"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg> -->
                        <h2 class="text-xl font-bold py-4">{{ title }}</h2>
                        <p class="text-sm text-gray-500 px-8">
                            {{ message }}
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
                                <m-button id="btnHapus" icon name="btnSimpan" color="yellow" :title="okButton"
                                    iconName="print"
                                    class="mb-2 md:mb-0 bg-yellow-600 border border-yellow-600 px-8 py-4 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-yellow-700"
                                    @click="_confirm">
                                </m-button>
                            </div>
                        </div>
                        <!-- <button
                            class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-md hover:shadow-lg hover:bg-gray-100"
                            @click="_cancel"
                        >
                            {{ cancelButton }}
                        </button> -->
                        <!-- <m-button
                            icon
                            color="red"
                            :title="okButton"
                            size="sm"
                            iconName="trash"
                            class="mb-2 md:mb-0 bg-red-500 border border-red-500 px-6 py-4 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-red-600"
                            @click="_confirm"
                        >
                        </m-button> -->
                        <!-- <button
                            class="mb-2 md:mb-0 bg-red-500 border border-red-500 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-red-600"
                            @click="_confirm"
                        >
                            {{ okButton }}
                        </button> -->
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
    name: "PrintDialog",
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
        show(opts = {}) {
            this.title = opts.title;
            this.message = opts.message;
            this.okButton = opts.okButton;
            if (opts.cancelButton) {
                this.cancelButton = opts.cancelButton;
            }

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
            this.close();
            this.resolvePromise(false);
        },
    },
};
</script>
