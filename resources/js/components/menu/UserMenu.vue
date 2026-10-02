<template>
    <div class="hidden md:block">
        <div class="ml-4 flex items-center md:ml-6">
            <button
                type="button"
                class="bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
            >
                <span class="sr-only">View notifications</span>
                <BellIcon class="h-6 w-6" aria-hidden="true" />
            </button>

            <!-- Profile dropdown -->
            <Menu as="div" class="ml-3 relative">
                <div>
                    <MenuButton
                        class="max-w-xs bg-gray-800 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                    >
                        <span class="sr-only">Open user menu</span>
                        <img
                            class="h-8 w-8 rounded-full"
                            :src="user.imageUrl"
                            alt=""
                        />
                    </MenuButton>
                </div>
                <transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <MenuItems
                        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                    >
                        <MenuItem
                            v-for="item in ListMenuItem.userNavigation"
                            :key="item.name"
                            v-slot="{ active }"
                        >
                            <a
                                :href="item.href"
                                :class="[
                                    active ? 'bg-gray-100' : '',
                                    'block px-4 py-2 text-sm text-gray-700',
                                ]"
                                >{{ item.name }}</a
                            >
                        </MenuItem>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </div>
    <div class="-mr-2 flex md:hidden">
        <!-- Mobile menu button -->
        <DisclosureButton
            class="bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
        >
            <span class="sr-only">Open main menu</span>
            <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
            <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
        </DisclosureButton>
    </div>
</template>
<script>
import Logo from "@/js/components/Logo";
import ListMenuItem from "@/js/components/menu/ListMenuItem.json";
import PanelMenu from "@/js/components/menu/PanelMenu";
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

// const user = {
//     name: "Tom Cook",
//     email: "tom@example.com",
//     imageUrl:
//         "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
// };
export default {
    // created: function () {
    //     if (this.$store.getters.user) {
    //         let self = this;
    //         window.addEventListener("click", function (e) {
    //             if (!self.$refs.dropMenu.contains(e.target)) {
    //                 self.drop = false;
    //             }
    //         });
    //     }
    // },

    components: {
        Logo,
        PanelMenu,
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
        PanelMenu,
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
