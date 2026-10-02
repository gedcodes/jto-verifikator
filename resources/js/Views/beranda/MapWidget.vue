<template>
    <div id="mapContainer" class="mx-2 rounded-md" style="height: 75vh">
        <l-map
            ref="map"
            :zoom="4"
            :min-zoom="2"
            :max-zoom="18"
            :center="[lat, lon]"
            @ready="changeCenter()"
        >
            <l-tile-layer
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            ></l-tile-layer>
            <l-control-layers />
            <l-circle-marker
                fill,
                fillColor="#0284c7"
                color="#0284c7"
                :fillOpacity="0.7"
                :radius="6"
                :weight="4"
                v-for="(item, index) in markers"
                :key="'cmarker-' + index"
                :lat-lng="[item.lat, item.lon]"
            ></l-circle-marker>
            <l-marker
                v-for="(item, index) in markers"
                :key="'marker-' + index"
                :lat-lng="[Number(item.lat), Number(item.lon)]"
            >
                <l-popup>
                    <div>
                        <div class="font-bold py-2 border-b w-full">
                            {{ item.nama }}
                        </div>
                        <div class="text-[10px] text-gray-500 font-medium mt-1">
                            <table
                                cellspacing="0"
                                cellpadding="0"
                                border="0"
                                width="100%"
                            >
                                <tr>
                                    <td>
                                        <app-icon
                                            class="text-[9px] h-3"
                                            fill="#cccccc"
                                            :name="{ name: 'map-marker' }"
                                        />
                                    </td>
                                    <td>
                                        <span>
                                            {{ item.lat }}, {{ item.lon }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </l-popup>
            </l-marker>
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
    LCircleMarker,
    LControlLayers,
    LTooltip,
    LPopup,
    LPolyline,
    LPolygon,
    LRectangle,
} from "@vue-leaflet/vue-leaflet";
import AppIcon from "@/js/components/icon/AppIcon.vue";
import LokasiService from "@/js/service/LokasiService.js";
export default {
    name: "RoutingDetail",
    components: {
        AppIcon,
        LMap,
        LIcon,
        LTileLayer,
        LMarker,
        LCircleMarker,
        LControlLayers,
        LTooltip,
        LPopup,
        LPolyline,
        LPolygon,
        LRectangle,
    },
    data() {
        return {
            lat: -2.44565,
            lon: 123.1622375,
            markers: [],
            loading: false,
        };
    },
    props: {
        data: {
            type: Object,
            default: {},
        },
        zoom: {
            type: Number,
            default: 2,
        },
        minZoom: {
            type: Number,
            default: 2,
        },
        maxZoom: {
            type: Number,
            default: 19,
        },
    },
    methods: {
        getData() {
            this.markers = [];
            this.loading = true;
            LokasiService.getAll()
                .then((response) => {
                    if (response.success) {
                        if (response.data) {
                            for (var row of response.data) {
                                this.markers.push(row);
                            }
                            // sthis.items = response.data;
                            this.loading = false;
                        }
                    } else {
                        this.errors = {
                            message: response.message,
                        };
                        this.markers = [];
                    }
                })
                .catch((err) => {
                    this.errors = err.response.data;
                    this.markers = [];
                });
        },
        changeCenter() {
            if (this.markers.length > 0) {
                var bounds = new L.LatLngBounds(this.markers);
                // this.$refs.map.leafletObject.setView(this.markers, 10);
                this.$refs.map.leafletObject.fitBounds(bounds);
            }
        },
    },
    created() {
        this.getData();
    },
};
</script>
