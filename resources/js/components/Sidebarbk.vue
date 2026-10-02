<template>
    <aside
        id="sidebar"
        class="bg-gray-800 text-gray-100 md:w-64 w-3/4 space-y-6 pt-6 px-0 absolute inset-y-0 left-0 transform md:relative md:translate-x-0 transition duration-200 ease-in-out md:flex md:flex-col md:justify-between overflow-y-auto"
        data-dev-hint="sidebar; px-0 for frameless; px-2 for visually inset the navigation"
    >
        <div :class="[classes]">
            <div class="mb-10">
                <div
                    v-if="!this.$store.getters.g_sideBarOpen"
                    class="font-semibold text-gray-500 text-sm drop-shadow-sm"
                >
                    {{ titleSide }}
                </div>
                <button
                    :class="[
                        {
                            'right-2 top-2': !this.$store.getters.g_sideBarOpen,
                        },
                        'absolute right-3 top-2',
                    ]"
                    @click="ToggleMenu"
                >
                    <ArrowCircleLeftIcon
                        v-if="!this.$store.getters.g_sideBarOpen"
                        class="block h-8 w-8"
                        aria-hidden="true"
                    />
                    <ArrowCircleRightIcon
                        v-else
                        class="block h-8 w-8"
                        aria-hidden="true"
                    />
                </button>
            </div>

            <div class="flex flex-col space-y-2">
                <!-- {{ groupItem.data }} -->
                <div v-if="!groupItem.data.length">Menu Tidak Tersedia</div>
                <div v-for="group in groupItem.data" :key="group.menuId">
                    <div
                        class="flex items-center text-[11px] text-gray-400 mt-2"
                    >
                        <div class="flex-none w-8 h-6">
                            <rightArrowDoubleIcon class="h-4 w-4" />
                        </div>
                        <div
                            v-if="!this.$store.getters.g_sideBarOpen"
                            class="grow h-6"
                        >
                            {{ group.groupTitle }}
                        </div>
                    </div>
                    <nav
                        v-for="(item, index) in group.subMenu"
                        :key="index"
                        class="text-[11px]"
                    >
                        <!-- {{ index }} {{ item.name }} -->
                        <!-- <ul>
                        <li v-for="(item, key, index) in item" :key="index">
                            {{ item }} - {{ index }}
                        </li>
                    </ul> -->
                        <router-link
                            :key="item.subMenudId"
                            :to="{ name: item.href }"
                            :class="[
                                {
                                    'p-0 py-2 hover:bg-gray-50':
                                        this.$store.getters.g_sideBarOpen,
                                },
                                'flex items-center rounded-lg space-x-2 p-2 transition duration-200 hover:bg-bluegrey-200 hover:text-gray-900',
                            ]"
                            active-class="text-white bg-bluegrey-400"
                        >
                            <div class="flex-none w-8">
                                <Tooltip
                                    class="font-semibold"
                                    v-if="this.$store.getters.g_sideBarOpen"
                                    position="right"
                                    :tooltipText="item.name"
                                >
                                    <app-icon :name="{ name: item.icon }" />
                                </Tooltip>
                                <app-icon v-else :name="{ name: item.icon }" />
                            </div>
                            <div
                                v-if="!this.$store.getters.g_sideBarOpen"
                                class="grow"
                            >
                                <span>{{ item.name }}</span>
                            </div>
                        </router-link>
                    </nav>
                </div>
            </div>
        </div>
    </aside>
</template>

<script>
import AppIcon from "@/js/components/icon/AppIcon.vue";
import { rightArrowDoubleIcon } from "@/js/components/icon";
import Tooltip from "@/js/components/utils/Tooltip.vue";
import {
    ArrowCircleLeftIcon,
    MenuIcon,
    ArrowCircleRightIcon,
} from "@heroicons/vue/outline";
export default {
    name: "sidebar",
    components: {
        AppIcon,
        rightArrowDoubleIcon,
        ArrowCircleLeftIcon,
        MenuIcon,
        ArrowCircleRightIcon,
        Tooltip,
    },
    data() {
        return {
            open: this.$store.getters.g_sideCollaps,
            data: [],
            tooltipShow: false,
        };
    },
    props: {
        titleSide: {
            type: String,
            default: "Main Menu",
        },
        groupItem: {
            type: Object,
            default: [{}],
        },
        dataItem: {
            type: Object,
            default: [{}],
        },
    },
    methods: {
        ToggleMenu() {
            // this.open = !this.open;
            this.$store.commit("toggleSideBar");
        },
        isMobile() {
            if (
                /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
                    navigator.userAgent
                )
            ) {
                return true;
            } else {
                return false;
            }
        },
    },
    computed: {
        classes() {
            return {
                "bg-white rounded-r-lg h-screen text-gray-700 p-5 w-64 duration-300 relative shadow-md":
                    !this.$store.getters.g_sideBarOpen, // this.open === true,
                "bg-white rounded-r-lg h-screen text-gray-700 p-5 w-14 duration-300 relative shadow-md":
                    this.$store.getters.g_sideBarOpen, // this.open === false,
            };
        },
        // showSideBar() {
        //     this.$store.getters.g_sideBarOpen;
        // },
    },
};
</script>
<style scoped>
#sidebar {
    --tw-translate-x: -100%;
}
#menu-close-icon {
    display: none;
}

#menu-open:checked ~ #sidebar {
    --tw-translate-x: 0;
}
#menu-open:checked ~ * #mobile-menu-button {
    background-color: rgba(31, 41, 55, var(--tw-bg-opacity));
}
#menu-open:checked ~ * #menu-open-icon {
    display: none;
}
#menu-open:checked ~ * #menu-close-icon {
    display: block;
}

@media (min-width: 768px) {
    #sidebar {
        --tw-translate-x: 0;
    }
}
</style>
