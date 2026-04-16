<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import Swal from 'sweetalert2'

const props = defineProps({
    chapters: Object,
    filters: Object
})

const search = ref(props.filters?.search || '')

const performSearch = useDebounceFn((value) => {
    router.get('/admin/chapters', {
        search: value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}, 400)

watch(search, performSearch)

const deleteChapter = (id) => {
    Swal.fire({
        title: 'Delete Chapter?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/chapters/${id}`)
        }
    })
}
</script>

<template>
    <AdminLayout>
        <div class="space-y-6 max-w-7xl mx-auto">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800">
                        Chapter Management
                    </h1>

                    <p class="text-slate-500">
                        Manage subject chapters and learning hierarchy
                    </p>
                </div>

                <Link
                    href="/admin/chapters/create"
                    class="w-full md:w-auto text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transition"
                >
                    + Add Chapter
                </Link>
            </div>

            <!-- Search -->
            <div class="bg-white rounded-2xl shadow p-4 border border-slate-100">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search chapters..."
                    class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                />
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left">Title</th>
                            <th class="px-6 py-4 text-left">Subject</th>
                            <th class="px-6 py-4 text-left">Sort Order</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="chapter in chapters.data"
                            :key="chapter.id"
                            class="border-b hover:bg-slate-50 transition"
                        >
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ chapter.title }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ chapter.subject?.name || '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ chapter.sort_order }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3">
                                    <Link
                                        :href="`/admin/chapters/${chapter.id}/edit`"
                                        class="text-blue-600 font-medium"
                                    >
                                        Edit
                                    </Link>

                                    <button
                                        @click="deleteChapter(chapter.id)"
                                        class="text-red-600 font-medium"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="chapters.data.length === 0">
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                No chapters found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:hidden">
                <div
                    v-for="chapter in chapters.data"
                    :key="chapter.id"
                    class="bg-white rounded-2xl shadow p-5 border border-slate-100"
                >
                    <div class="mb-4">
                        <h3 class="font-bold text-lg text-slate-800">
                            {{ chapter.title }}
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Subject: {{ chapter.subject?.name || '-' }}
                        </p>
                    </div>

                    <div class="text-sm text-slate-600 mb-4">
                        Sort Order:
                        <strong>{{ chapter.sort_order }}</strong>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <Link
                            :href="`/admin/chapters/${chapter.id}/edit`"
                            class="bg-blue-50 text-blue-600 py-2 rounded-lg text-center font-medium"
                        >
                            Edit
                        </Link>

                        <button
                            @click="deleteChapter(chapter.id)"
                            class="bg-red-50 text-red-600 py-2 rounded-lg font-medium"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>
