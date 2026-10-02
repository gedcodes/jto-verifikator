<template>
    <header
        class="bg-gray-600 text-gray-100 flex justify-between md:hidden"
        data-dev-hint="mobile menu bar"
    >
        <a
            href="#"
            class="block p-4 text-white font-bold whitespace-nowrap truncate"
        >
            {{ titleSide }}
        </a>

        <label
            for="menu-open"
            id="mobile-menu-button"
            class="m-2 p-2 focus:outline-none hover:text-white hover:bg-gray-700 rounded-md"
        >
            <svg
                id="menu-open-icon"
                class="h-6 w-6 transition duration-200 ease-in-out"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
            <svg
                id="menu-close-icon"
                class="h-6 w-6 transition duration-200 ease-in-out"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </label>
    </header>
    <!-- class="bg-gray-800 text-gray-100 md:w-64 w-3/4 space-y-6 pt-6 px-0 absolute inset-y-0 left-0 transform md:relative md:translate-x-0 transition duration-200 ease-in-out md:flex md:flex-col md:justify-between overflow-y-auto" -->

    <aside
        id="sidebar"
        :class="classes"
        data-dev-hint="sidebar; px-0 for frameless; px-2 for visually inset the navigation"
    >
        <div class="flex flex-col space-y-6">
            <div class="toggle mb-4">
                <div
                    v-if="!this.$store.getters.g_sideBarOpen"
                    class="font-semibold text-gray-500 text-sm drop-shadow-sm"
                >
                    {{ titleSide }}
                </div>
                <button
                    :class="[
                        {
                            'right-2 top-3': !this.$store.getters.g_sideBarOpen,
                        },
                        'absolute right-3 top-3',
                    ]"
                    @click="ToggleMenu"
                >
                    <ArrowCircleLeftIcon
                        v-if="!this.$store.getters.g_sideBarOpen"
                        class="block h-8 w-8 xs:hidden"
                        aria-hidden="true"
                    />
                    <ArrowCircleRightIcon
                        v-else
                        class="block h-8 w-8 xs:hidden"
                        aria-hidden="true"
                    />
                </button>
            </div>

            <nav data-dev-hint="main navigation">
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
            </nav>
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
    },
    computed: {
        classes() {
            return {
                "bg-white text-gray-700 md:w-64 w-3/4 space-y-6 p-5 z-10 absolute inset-y-0 left-0 transform md:relative md:translate-x-0 transition duration-200 ease-in-out md:flex md:flex-col md:justify-between overflow-y-auto":
                    !this.$store.getters.g_sideBarOpen, // this.open === true,
                "bg-white rounded-r-lg text-gray-700 p-5 md:w-14 w-1/4 duration-300 z-10 absolute inset-y-0 left-0 transform md:relative md:translate-x-0 transition duration-200 ease-in-out md:flex md:flex-col md:justify-between overflow-y-auto":
                    this.$store.getters.g_sideBarOpen, // this.open === false,
            };
        },
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
#menu-open:checked ~ * .toggle {
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
