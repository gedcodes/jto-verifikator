<template>
    <DisclosurePanel class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <DisclosureButton
                v-for="item in ListMenuItem.navigation"
                :key="item.name"
                as="a"
                :href="item.href"
                :class="[
                    item.current
                        ? 'bg-gray-900 text-white'
                        : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                    'block px-3 py-2 rounded-md text-base font-medium',
                ]"
                :aria-current="item.current ? 'page' : undefined"
                >{{ item.name }}</DisclosureButton
            >
        </div>
        <div class="pt-4 pb-3 border-t border-gray-700">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <img
                        class="h-10 w-10 rounded-full"
                        :src="user.imageUrl"
                        alt=""
                    />
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-white">
                        {{ user.name }}
                    </div>
                    <div class="text-sm font-medium leading-none text-gray-400">
                        {{ user.email }}
                    </div>
                </div>
                <button
                    type="button"
                    class="ml-auto bg-gray-800 flex-shrink-0 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                >
                    <span class="sr-only">View notifications</span>
                    <BellIcon class="h-6 w-6" aria-hidden="true" />
                </button>
            </div>
            <div class="mt-3 px-2 space-y-1">
                <DisclosureButton
                    v-for="item in ListMenuItem.userNavigation"
                    :key="item.name"
                    as="a"
                    :href="item.href"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700"
                    >{{ item.name }}</DisclosureButton
                >
            </div>
        </div>
    </DisclosurePanel>
</template>
<script>
import Logo from "@/js/components/Logo";
import ListMenuItem from "@/js/components/menu/ListMenuItem.json";
import { CogIcon, LogoutIcon, ChevronDownIcon } from "@heroicons/vue/outline";
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from "@headlessui/vue";
import { BellIcon, MenuIcon, XIcon } from "@heroicons/vue/outline";

export default {
    name: "PanelMenu",
    components: {
        Logo,
        CogIcon,
        LogoutIcon,
        ChevronDownIcon,
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
        BellIcon,
        MenuIcon,
        XIcon,
    },
    data() {
        return {
            drop: false,
            userProfil: {
                name: "",
                email: "",
                imageUrl: "",
            },
            ListMenuItem,
        };
    },
    computed: {
        user() {
            const userData = this.$store.getters.user;
            this.userProfil.name = userData.name;
            this.userProfil.email = userData.email;
            return this.$store.getters.user;
        },
    },
    methods: {
        async logout() {
            await this.$store.dispatch("logout");
            this.$router.push({ name: "login" });
        },
    },
};
</script>
