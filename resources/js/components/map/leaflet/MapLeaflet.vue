<template>
    <div id="mapContainer" class="h-full w-full">
        <l-map
            :ref="ref"
            :zoom="zoom"
            :min-zoom="minZoom"
            :max-zoom="maxZoom"
            :center="center"
            @click="click"
        >
            <l-tile-layer
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            ></l-tile-layer>
            <l-control-layers />
            <l-marker :lat-lng="markers">
                <!-- draggable @moveend="log('moveend')" -->
                <!-- <l-tooltip> lol </l-tooltip> -->
            </l-marker>

            <!-- <l-marker :lat-lng="[47.41322, -1.219482]">
                        <l-icon :icon-url="iconUrl" :icon-size="iconSize" />
                    </l-marker>

                    <l-marker
                        :lat-lng="[50, 50]"
                        draggable
                        @moveend="log('moveend')"
                    >
                        <l-popup> lol </l-popup>
                    </l-marker> -->
        </l-map>
    </div>
</template>
<script>
import "leaflet/dist/leaflet.css";
import {
    LMap,
    LIcon,
    LTileLayer,
    LMarker,
    LControlLayers,
    LTooltip,
    LPopup,
    LPolyline,
    LPolygon,
    LRectangle,
} from "@vue-leaflet/vue-leaflet";

export default {
    name: "MapLeaflet",
    components: {
        LMap,
        LIcon,
        LTileLayer,
        LMarker,
        LControlLayers,
        LTooltip,
        LPopup,
        LPolyline,
        LPolygon,
        LRectangle,
    },
    props: {
        ref: {
            type: String,
            default: "map",
        },
        zoom: {
            type: Number,
            default: 12,
        },
        minZoom: {
            type: Number,
            default: 2,
        },
        maxZoom: {
            type: Number,
            default: 19,
        },
        iconWidth: {
            type: Number,
            default: 25,
        },
        iconHeight: {
            type: Number,
            default: 40,
        },
        center: {
            type: Array,
            default: [0, 0],
        },
        click: {
            type: Function,
            default: (e) => e,
        },
        markers: {
            type: Array,
            default: [],
        },
    },
};
</script>
