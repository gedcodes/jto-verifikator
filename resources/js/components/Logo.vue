<template>
    <div class="flex items-center space-x-2 mr-2 pt-8 sm:justify-start sm:pt-0 text-xl text-gray-800 font-bold">
        <img class="w-40 md:w-48 lg:w-56 md:h-9 lg:h-10 min-w-full md:min-w-0 items-center" :src="atms" />
        <!-- <span class="text-white text-2xl font-poppins">Verifikator JTO</span> -->
    </div>
</template>

<script>
import atms from "../../../public/images/logo_verifikator.png";
export default {
    data() {
        return {
            atms,
        };
    },
};
</script>
