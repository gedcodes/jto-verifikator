<template>
    <div>
        <div class="lightbox fixed inset-0 flex justify-center items-center">
            <div class="fixed right-0 top-0 text-white cursor-pointer rounded-sm m-2" @click="close">
                <XIcon class="w-8 hover:bg-red-500" />
            </div>
            <div class="lightbox-image">
                <slot name="gambar">
                    <img src="http://via.placeholder.com/350x150" />
                </slot>
            </div>
        </div>
    </div>
</template>

<script>
import { XCircleIcon, XIcon } from "@heroicons/vue/outline";
export default {
    name: 'Gambar',
    methods: {
        close() {
            this.$emit('close');
        },
    },
    components: {
        XCircleIcon,
        XIcon
    },
};
</script>

<style>
.lightbox {
    z-index: 9998;
    display: flex;
    background-color: rgba(0, 0, 0, 0.9);
    transition: opacity 0.3s ease;
}

.lightbox-image img {
    width: auto;
    height: auto;
    max-width: calc(100vh - 50px);
    max-height: calc(100vh - 90px);
    margin-left: 10px;
    margin-right: 10px;

}

.lightbox-image img::before {
    transform: rotate(45deg);
}

.lightbox-image img::after {
    transform: rotate(135deg);
}

.lightbox-image img::hover {
    transform: rotate(270deg);
}
</style>
