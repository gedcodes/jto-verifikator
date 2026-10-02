<template>
    <nav class="border-t border-gray-200 px-4 flex items-center justify-between sm:px-0" aria-label="Pagination">
        <div class="-mt-px w-0 flex-1 flex">
            <button :disabled="pagination.prev_page_url == null" @click="pageURL(pagination.prev_page_url)"
                class="border-t-2 border-transparent pt-4 pr-1 inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-200">
                <ArrowNarrowLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                Previous
            </button>
        </div>
        <div class="hidden md:-mt-px md:flex">
            <button v-if="current_page - 2 > 0" @click="pageURL(pagination.path + '?page=' + (current_page - 2))"
                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200 border-t-2 pt-4 px-4 inline-flex items-center text-sm font-medium">
                {{ current_page - 2 }} </button>
            <button v-if="pagination.prev_page_url" @click="pageURL(pagination.prev_page_url)"
                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200 border-t-2 pt-4 px-4 inline-flex items-center text-sm font-medium">
                {{ current_page - 1 }} </button>
            <button
                class="border-indigo-500 text-indigo-600 border-t-2 pt-4 px-4 inline-flex items-center text-sm font-medium"
                aria-current="page"> {{ current_page }} </button>
            <button v-if="pagination.next_page_url" @click="pageURL(pagination.next_page_url)"
                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200 border-t-2 pt-4 px-4 inline-flex items-center text-sm font-medium">
                {{ current_page + 1 }} </button>
            <button v-if="current_page + 2 <= last_page"
                @click="pageURL(pagination.path + '?page=' + (current_page + 2))"
                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200 border-t-2 pt-4 px-4 inline-flex items-center text-sm font-medium">
                {{ current_page + 2 }} </button>
        </div>
        <div class="-mt-px w-0 flex-1 flex justify-end">
            <button :disabled="pagination.next_page_url == null" @click="pageURL(pagination.next_page_url)"
                type="button"
                class="border-t-2 border-transparent pt-4 pl-1 inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-200">
                Next
                <ArrowNarrowRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
            </button>
        </div>
    </nav>
</template>
<script>
import {
    ArrowNarrowLeftIcon,
    ArrowNarrowRightIcon,
} from '@heroicons/vue/solid'

export default {
    components: {
        ArrowNarrowLeftIcon,
        ArrowNarrowRightIcon,
    },

    props: ['pagination', 'current_page', 'last_page'],
    methods: {
        pageURL(url) {
            console.log(url)
            this.$parent.getPagination(url)
        }
    }
}
</script>
