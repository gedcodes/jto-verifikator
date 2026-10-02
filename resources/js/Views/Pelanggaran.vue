<template>
    <div class="max-w-full w-full mx-auto py-2 px-4 sm:px-6 lg:px-8 mb-3 shadow bg-white">
        <h2 class="text-xl tracking-tight font-bold text-gray-900">Pelanggaran</h2>
    </div>
    <div class="max-w-full w-full mx-auto px-0 sm:px-4 lg:px-6 my-4">
        <WidgetsDashboardCard />
        <CaptureTable />
    </div>
</template>

<script>
import WidgetsDashboardCard from "@/js/components/card/WidgetsDashboardCard";
import CaptureTable from "./Capture/CaptureTable";
export default {
    components: {
        WidgetsDashboardCard,
        CaptureTable
    },
};
</script>
