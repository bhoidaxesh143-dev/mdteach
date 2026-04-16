<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import Swal from 'sweetalert2'

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
    router.get('/admin/topics', {
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

/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/
const deleteTopic = (id) => {
    Swal.fire({
        title: 'Delete Topic?',
        text: 'This will remove the topic permanently.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/topics/${id}`, {
                preserveScroll: true,
                preserveState: true,

                onSuccess: () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        text: 'Topic deleted successfully.',
                        timer: 1800,
                        showConfirmButton: false
                    })
                },

                onError: () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed',
                        text: 'Unable to delete topic.'
                    })
                }
            })
        }
    })
}
</script>

<template>
<AdminLayout>
<div class="max-w-7xl mx-auto px-4 space-y-6">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Topics Management
            </h1>

            <p class="text-slate-500">
                Manage topic hierarchy and theory structure.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            <button
                @click="goBack"
                class="px-5 py-3 rounded-xl border border-slate-300 hover:bg-slate-50 transition"
            >
                ← Back
            </button>

            <Link
                href="/admin/topics/create"
                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-3 rounded-xl shadow"
            >
                + Add Topic
            </Link>

        </div>

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
        class="hidden xl:block bg-white rounded-2xl shadow border overflow-hidden"
    >
        <table class="w-full">
            <thead class="bg-slate-100">
                <tr>
                    <th class="p-4 text-left">Title</th>
                    <th class="p-4 text-left">Chapter</th>
                    <th class="p-4 text-left">Parent Topic</th>
                    <th class="p-4 text-left">Sort</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="topic in topics.data"
                    :key="topic.id"
                    class="border-t hover:bg-slate-50"
                >
                    <td class="p-4 font-medium">
                        {{ topic.title }}
                    </td>

                    <td class="p-4">
                        {{ topic.chapter?.title || '-' }}
                    </td>

                    <td class="p-4">
                        {{ topic.parent?.title || '-' }}
                    </td>

                    <td class="p-4">
                        {{ topic.sort_order }}
                    </td>

                    <td class="p-4">
                        <div class="flex justify-end gap-2 flex-wrap">

                            <Link
                                :href="`/admin/topics/${topic.id}/edit`"
                                class="px-3 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100"
                            >
                                Edit
                            </Link>

                            <Link
                                :href="`/admin/topics/${topic.id}/questions/edit`"
                                class="px-3 py-2 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100"
                            >
                                Map Questions
                            </Link>

                            <button
                                @click="deleteTopic(topic.id)"
                                class="px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100"
                            >
                                Delete
                            </button>

                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div
        v-if="topics.data.length"
        class="grid grid-cols-1 md:grid-cols-2 gap-4 xl:hidden"
    >
        <div
            v-for="topic in topics.data"
            :key="topic.id"
            class="bg-white rounded-2xl shadow border p-5"
        >
            <div class="space-y-3">

                <div>
                    <h3 class="font-semibold text-lg text-slate-800">
                        {{ topic.title }}
                    </h3>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">

                    <div>
                        <p class="text-slate-500">Chapter</p>
                        <p>{{ topic.chapter?.title || '-' }}</p>
                    </div>

                    <div>
                        <p class="text-slate-500">Parent</p>
                        <p>{{ topic.parent?.title || '-' }}</p>
                    </div>

                    <div>
                        <p class="text-slate-500">Sort</p>
                        <p>{{ topic.sort_order }}</p>
                    </div>

                </div>

                <div class="grid grid-cols-3 gap-2 pt-2">

                    <Link
                        :href="`/admin/topics/${topic.id}/edit`"
                        class="text-center py-2 rounded-lg bg-blue-50 text-blue-600"
                    >
                        Edit
                    </Link>

                    <Link
                        :href="`/admin/topics/${topic.id}/questions/edit`"
                        class="text-center py-2 rounded-lg bg-purple-50 text-purple-600"
                    >
                        Map
                    </Link>

                    <button
                        @click="deleteTopic(topic.id)"
                        class="py-2 rounded-lg bg-red-50 text-red-600"
                    >
                        Delete
                    </button>

                </div>

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

    <!-- Empty -->
    <div
        v-if="topics.data.length === 0"
        class="bg-white rounded-2xl shadow border p-12 text-center"
    >
        <h3 class="text-lg font-semibold text-slate-700 mb-2">
            No Topics Found
        </h3>

        <p class="text-slate-500 mb-6">
            Start building your learning hierarchy by adding a topic.
        </p>

        <Link
            href="/admin/topics/create"
            class="bg-blue-600 text-white px-6 py-3 rounded-xl"
        >
            + Add First Topic
        </Link>
    </div>

</div>
</AdminLayout>
</template>
