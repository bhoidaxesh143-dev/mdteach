<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'

const props = defineProps({
    topics: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/
const search = ref(props.filters?.search || '')

const performSearch = useDebounceFn((value) => {
    router.get('/admin/topic-content', {
        search: value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}, 400)

watch(search, performSearch)

const clearSearch = () => {
    search.value = ''
}

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/
const goBack = () => {
    if (window.history.length > 1) {
        window.history.back()
    } else {
        router.visit('/admin/dashboard')
    }
}
</script>

<template>
<AdminLayout>
<div class="max-w-7xl mx-auto px-4 space-y-6">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Theory Content
            </h1>

            <p class="text-slate-500 mt-1">
                Manage learning / theory content for all topics
            </p>
        </div>

        <button
            @click="goBack"
            class="px-5 py-3 rounded-xl border border-slate-300 hover:bg-slate-50 transition"
        >
            ← Back
        </button>

    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow border p-4">
        <div class="relative">

            <input
                v-model="search"
                type="text"
                placeholder="Search topics..."
                class="w-full border rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-blue-500"
            />

            <button
                v-if="search"
                @click="clearSearch"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500"
            >
                ✕
            </button>

        </div>
    </div>

    <!-- Desktop Table -->
    <div
        v-if="topics.data.length"
        class="hidden lg:block bg-white rounded-2xl shadow border overflow-hidden"
    >
        <table class="w-full">
            <thead class="bg-slate-100">
                <tr>
                    <th class="p-4 text-left">Topic</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="topic in topics.data"
                    :key="topic.id"
                    class="border-t hover:bg-slate-50 transition"
                >
                    <td class="p-4 font-medium">
                        {{ topic.title }}
                    </td>

                    <td class="p-4">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold"
                            :class="topic.content
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700'"
                        >
                            {{ topic.content ? 'Added' : 'Missing' }}
                        </span>
                    </td>

                    <td class="p-4 text-right">
                        <Link
                            :href="`/admin/topic-content/${topic.id}/edit`"
                            class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 font-medium hover:bg-blue-100 transition"
                        >
                            Edit Content
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div
        v-if="topics.data.length"
        class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:hidden"
    >
        <div
            v-for="topic in topics.data"
            :key="topic.id"
            class="bg-white rounded-2xl shadow border p-5"
        >
            <div class="space-y-4">

                <div>
                    <h3 class="font-semibold text-slate-800">
                        {{ topic.title }}
                    </h3>
                </div>

                <div>
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold"
                        :class="topic.content
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700'"
                    >
                        {{ topic.content ? 'Content Added' : 'Content Missing' }}
                    </span>
                </div>

                <Link
                    :href="`/admin/topic-content/${topic.id}/edit`"
                    class="block text-center bg-blue-50 text-blue-600 py-2 rounded-xl font-medium hover:bg-blue-100 transition"
                >
                    Edit Content
                </Link>

            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div
        v-if="topics.links?.length"
        class="flex flex-wrap gap-2 justify-center"
    >
        <template
            v-for="(link, index) in topics.links"
            :key="index"
        >
            <Link
                v-if="link.url"
                :href="link.url"
                v-html="link.label"
                class="px-4 py-2 rounded-lg border text-sm"
                :class="link.active
                    ? 'bg-blue-600 text-white border-blue-600'
                    : 'bg-white hover:bg-slate-50'"
            />

            <span
                v-else
                v-html="link.label"
                class="px-4 py-2 rounded-lg border text-sm text-slate-400 bg-slate-50"
            />
        </template>
    </div>

    <!-- Empty State -->
    <div
        v-if="topics.data.length === 0"
        class="bg-white rounded-2xl shadow border p-12 text-center"
    >
        <h3 class="text-lg font-semibold text-slate-700 mb-2">
            No Topics Found
        </h3>

        <p class="text-slate-500">
            Topics will appear here once created.
        </p>
    </div>

</div>
</AdminLayout>
</template>
