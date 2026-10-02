<template>
    <!-- ON DEVELOP -->
    <div class="flex justify-center">
        <div>
            <div class="dropdown relative">
                <m-button
                    icon
                    outline
                    size="sm"
                    class="mr-1"
                    color="teal"
                    iconPosition="right"
                    tooltipText="Export"
                    tooltipPos="top"
                    iconName="arrowDown"
                    title="Dropdown"
                    @click="toggleShow"
                >
                </m-button>
                <ul
                    v-show="showMenu"
                    class="dropdown-menu min-w-max absolute bg-white text-base z-50 float-left py-2 list-none text-left rounded-lg shadow-lg mt-1 m-0 bg-clip-padding border-none"
                    aria-labelledby="dropdownMenuButton1ds"
                >
                    <li>
                        <a
                            class="dropdown-item text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-gray-700 hover:bg-gray-100"
                            href="#"
                            >Action</a
                        >
                    </li>
                    <li>
                        <a
                            class="dropdown-item text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-gray-700 hover:bg-gray-100"
                            href="#"
                            >Another action</a
                        >
                    </li>
                    <li>
                        <a
                            class="dropdown-item text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-gray-400 pointer-events-none"
                            href="#"
                            >Something else here</a
                        >
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import MButton from "@/js/components/form/MButton.vue";

export default {
    name: "m-button-dropdown",
    components: {
        MButton,
    },
    data() {
        return {
            showMenu: false,
        };
    },
    methods: {
        toggleShow: function () {
            this.showMenu = !this.showMenu;
        },
    },
};
</script>
