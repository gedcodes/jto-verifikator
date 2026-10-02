<template>
    <swiper :style="{
      '--swiper-navigation-color': '#fff',
      '--swiper-pagination-color': '#fff',
    }" :loop="true" :spaceBetween="10" :navigation="true" :thumbs="{ swiper: thumbsSwiper }" :modules="modules"
        class="mySwiper2">
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-1.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-2.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-3.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-4.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-5.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-6.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-7.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-8.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-9.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-10.jpg" /></swiper-slide>
    </swiper>
    <swiper @swiper="setThumbsSwiper" :loop="true" :spaceBetween="10" :slidesPerView="6" :freeMode="true"
        :watchSlidesProgress="true" :modules="modules" class="mySwiper">
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-1.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-2.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-3.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-4.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-5.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-6.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-7.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-8.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-9.jpg" /></swiper-slide>
        <swiper-slide><img src="https://swiperjs.com/demos/images/nature-10.jpg" /></swiper-slide>
    </swiper>
    <Swiper @swiper="setThumbsSwiper" :slides-per-view="1" :space-between="50" :modules="modules" navigation
        :pagination="{ clickable: true, dynamicBullets: true }" grab-cursor :preload-images="false" lazy
        :thumbs="{ swiper: thumbsSwiper }">
        <SwiperSlide v-for="n in 20" :key="n" class="customSlide">
            <img :data-src="'https://picsum.photos/id/'+n+'/640/480'" class="swiper-lazy"
                style="width: 640px; max-width: 100%" />
            <div class="swiper-lazy-preloader"></div>
        </SwiperSlide>
    </Swiper>
    <swiper @swiper="setThumbsSwiper" :spaceBetween="50" :slidesPerView="10" :freeMode="true"
        :watchSlidesProgress="true" :modules="modules" class="w-1/2">
        <SwiperSlide v-for="n in 20" :key="n">
            <img :src="'https://picsum.photos/id/'+n+'/640/480'" style="height: 80px" />
        </SwiperSlide>
    </swiper>
</template>
<script>
import { Swiper, SwiperSlide } from 'swiper/vue';

import 'swiper/css';
import "swiper/css/free-mode"
import "swiper/css/navigation"
import "swiper/css/thumbs"
import { FreeMode, Navigation, Thumbs } from 'swiper';


export default {
    components: {
        Swiper,
        SwiperSlide,
    },
    data() {
        return {
            thumbsSwiper: null,
            modules: [FreeMode, Navigation, Thumbs],
        }
    },
    methods: {
        setThumbsSwiper(swiper) {
            this.thumbsSwiper = swiper;
        },
    },
}
</script>

<style>
.mySwiper2 {
    height: 50%;
    width: 50%;
}

.mySwiper {
    height: 20%;
    box-sizing: border-box;
    padding: 10px 0;
}

.mySwiper .swiper-slide {
    width: 25%;
    height: 100%;
    opacity: 0.4;
}

.mySwiper .swiper-slide-thumb-active {
    opacity: 1;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
