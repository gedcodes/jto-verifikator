<template>
    <div v-if="noImages">
        <zoom-on-hover :img-normal="images" :img-zoom="images" :scale="scale" :height="height"></zoom-on-hover>
    </div>
    <div v-else>
        <img :src="images" :alt="alt" :class="classes">
    </div>
    <!-- <img :src="images" :alt="alt" :class="classes"> -->
</template>

<script>
import ZoomOnHover from "../components/lightbox/zoomOnHover.vue";
export default {
    name: 'Image',
    components: {
        ZoomOnHover,
    },
    data() {
        return {
            noimages: false,
        };
    },
    props: {
        src: {
            type: String,
            default: ''
        },
        alt: {
            type: String,
            default: 'Foto'
        },
        classes: {
            type: String,
            default: ''
        },
        height: {
            type: String,
            default: ''
        },
        scale: {
            type: Number,
            default: 2
        },
    },
    computed: {
        noPlat() {
            return localStorage.getItem('pathUrl') + "/images/noimage.png";
        },
        noImages() {
            if (this.src) {
                return true;
            };

            return false;
        },
        images() {
            if (this.src) {
                return this.src;
            };

            return this.noPlat;
        }
    }
    // mounted() {
    //     this.images = this.src || this.noPlat;
    // },
}
</script>

<style></style>
