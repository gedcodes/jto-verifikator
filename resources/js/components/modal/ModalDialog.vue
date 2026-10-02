<template>
    <transition name="fade">
        <div class="min-w-screen h-screen animated fadeIn faster fixed left-0 top-0 flex justify-center items-center inset-0 z-10 outline-none focus:outline-none"
            id="staticBackdrop" v-if="open">
            <div class="absolute bg-black opacity-80 inset-0 z-0" @click="handleBackdropClick"></div>

            <div class="absolute max-h-full" :class="maxWidth">
                <div class="container bg-white overflow-hidden md:rounded">
                    <div
                        class="px-4 py-2 leading-none flex justify-between items-center font-medium text-sm bg-gray-100 border-b select-none">
                        <h3>{{ title }}</h3>
                        <div @click="close" class="text-2xl hover:text-gray-600 cursor-pointer">
                            &#215;
                        </div>
                    </div>

                    <div class="max-h-full px-2 py-2">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    data() {
        return {
            open: true,
        };
    },
    props: {
        title: {
            type: String,
            default: "",
        },
        header: {
            type: String,
            required: false,
            default: "",
        },
        width: {
            type: String,
            default: "w-screen",
            validator: (value) =>
                ["xs", "sm", "md", "lg", "full"].indexOf(value) !== -1,
        },
        static: {
            type: Boolean,
            default: false,
        },
    },
    methods: {
        close() {
            this.open = false;
            this.$emit("close");
        },
        handleBackdropClick() {
            if (!this.static) {
                this.close();
            }
        },
    },
    computed: {
        maxWidth() {
            switch (this.width) {
                case "xs":
                    return "w-3/12 top-20 z-10";
                case "sm":
                    return "w-5/12 top-20 z-10";
                case "md":
                    return "w-8/12 top-10 z-10";
                case "lg":
                    return "w-11/12 top-10 z-10";
                case "full":
                    return "w-full top-5 z-10";
            }
        },
    },
    mounted() {
        const onEscape = (e) => {
            if (e.key === "Esc" || e.key === "Escape") {
                this.close();
            }
        };

        document.addEventListener("keydown", onEscape);

        // this.$once("hook:beforeDestroy", () => {
        //     document.removeEventListener("keydown", onEscape);
        // });
    },
    beforeDestroy() {
        document.removeEventListener("keydown", this.onEscape);
    },
};
</script>
<style>
.fade-enter {
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 500ms ease-out;
}

.fade-leave-to {
    opacity: 0;
}
</style>
