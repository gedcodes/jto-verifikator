<template>
    <Disclosure as="nav" class="bg-blue-600" v-slot="{ open }">
        <div class="max-w-full w-full mx-auto px-4 py-1 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-12">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <!-- <img
                            class="h-8 w-8"
                            src="https://tailwindui.com/img/logos/workflow-mark.svg?color=indigo&shade=500"
                            alt="Workflow"
                        /> -->
                        <div class="hidden md:block">
                            <Logo />
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-8 flex items-baseline space-x-4">
                            <div v-for="item in ListMenuItem.navigation" :key="item.name">
                                <Menu as="div" v-if="item.push" class="relative z-50">
                                    <div>
                                        <MenuButton :class="[isActive ? 'text-gray-300 bg-blue-500' : '',
                                        item.current
                                            ? 'bg-blue-700 text-white'
                                            : 'text-white hover:bg-blue-500 hover:text-white',
                                            'px-3 py-2 rounded-md text-sm font-medium',
                                        ]">
                                            {{ item.name }}
                                        </MenuButton>
                                    </div>
                                    <transition enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95">
                                        <MenuItems
                                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <router-link v-for="items in item.children" :key="items.name"
                                                :to="{ name: items.href }" :class="[
                                                    items.current
                                                        ? 'bg-gray-100 hover:bg-gray-100 cursor-pointer'
                                                        : '',
                                                    'block px-4 py-2 text-sm text-blue-500 hover:bg-gray-100 cursor-pointer',
                                                ]">
                                                {{ items.name }}
                                            </router-link>
                                        </MenuItems>
                                    </transition>
                                </Menu>
                                <router-link v-else :to="{ name: item.href }" :class="[
                                    item.current
                                        ? 'bg-blue-700 text-white'
                                        : 'text-white hover:bg-blue-500 hover:text-white',
                                    'px-3 py-2 rounded-md text-sm font-medium',
                                ]" active-class="text-gray-300 bg-blue-500" :aria-current="
    item.current ? 'page' : undefined
">
                                    {{ item.name }}
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- <button type="button"
                            class="bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                        </button> -->

                        <!-- Profile dropdown -->
                        <Menu as="div" class="ml-3 relative z-50">
                            <div>
                                <MenuButton
                                    class="max-w-xs bg-blue-600 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-600 focus:ring-white">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" :src="avatar" alt="" />
                                </MenuButton>
                            </div>
                            <transition enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95">
                                <MenuItems
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <!-- <router-link v-for="item in ListMenuItem.userNavigation" :key="item.name"
                                        :to="{ name: item.href }" :class="[
                                            item.current
                                                ? 'bg-gray-100 hover:bg-gray-100 cursor-pointer'
                                                : '',
                                            'block px-4 py-2 text-sm text-blue-500 hover:bg-gray-100 cursor-pointer',
                                        ]">
                                        {{ item.name }} -->
                                    <!-- <MenuItem
                                            :href="href"
                                            @click="navigate"
                                            v-slot="{ active }"
                                        >
                                            <a
                                                :class="
                                                    active
                                                        ? 'bg-gray-100 text-gray-900'
                                                        : 'text-gray-700'
                                                "
                                                class="flex justify-between w-full px-4 py-2 text-sm leading-5 text-left"
                                                >{{ item.name }}</a
                                            >
                                        </MenuItem> -->
                                    <!-- </router-link> -->
                                    <!-- <MenuItem
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
                                    </MenuItem> -->

                                    <MenuItem v-slot="{ active }">
                                    <a @click="logout" :class="[
                                        active
                                            ? 'bg-gray-100 cursor-pointer'
                                            : '',
                                        'block px-4 py-2 text-sm text-blue-500 cursor-pointer',
                                    ]">Logout</a>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    <!-- Mobile menu button -->
                    <div class="inline-flex">
                        <div class="text-base my-4 font-bold leading-none text-white">
                            JTO Verifikator
                        </div>
                    </div>
                    <DisclosureButton
                        class="bg-blue-600 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-800 focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                        <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>
            </div>
        </div>

        <DisclosurePanel class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <router-link v-for="item in ListMenuItem.navigation" :key="item.name" :to="{ name: item.href }" :class="[
                    item.current
                        ? 'bg-blue-700 text-white'
                        : 'text-gray-300 hover:bg-blue-500 hover:text-white',
                    'block px-3 py-2 rounded-md text-base font-medium',
                ]" :aria-current="item.current ? 'page' : undefined">
                    <DisclosureButton>{{ item.name }}</DisclosureButton>
                </router-link>
            </div>
            <div class="pt-4 pb-3 border-t border-blue-500">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" :src="user.imageUrl" alt="" />
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium leading-none text-white">
                            {{ user.name }}
                        </div>
                        <div class="text-sm font-medium leading-none text-gray-400">
                            {{ user.email }}
                        </div>
                    </div>
                    <!-- <button type="button"
                        class="ml-auto bg-gray-800 flex-shrink-0 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                        <span class="sr-only">View notifications</span>
                        <BellIcon class="h-6 w-6" aria-hidden="true" />
                    </button> -->
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <!-- <router-link v-for="item in ListMenuItem.userNavigation" :key="item.name" :to="{ name: item.href }"
                        :class="[
                            item.current
                                ? 'bg-blue-700 text-white'
                                : 'text-gray-300 hover:bg-blue-500 hover:text-white',
                            'block px-3 py-2 rounded-md text-base font-medium',
                        ]" :aria-current="item.current ? 'page' : undefined">
                        <DisclosureButton>{{ item.name }}</DisclosureButton>
                    </router-link> -->
                    <!-- <DisclosureButton
                        v-for="item in ListMenuItem.userNavigation"
                        :key="item.name"
                        as="a"
                        :href="item.href"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700"
                        >{{ item.name }}</DisclosureButton
                    > -->
                    <DisclosureButton @click="logout"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-blue-500">
                        Logout</DisclosureButton>
                </div>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>
<script>
import Logo from "@/js/components/Logo";
import avatar from "@/images/avatar.png";
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
            avatar,
        };
    },
    computed: {
        isActive() {
            return this.$route.name === 'arsipverifikasi' || this.$route.name === 'arsippelanggaran';
        },
        user() {
            const userData = this.$store.getters.user;
            if (userData) {
                this.userProfil.name = userData?.name;
                this.userProfil.email = userData?.email;
            }
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
