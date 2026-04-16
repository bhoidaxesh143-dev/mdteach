<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import Swal from 'sweetalert2'

const props = defineProps({
    subjects: {
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
    router.get('/admin/subjects', {
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
const destroySubject = (id) => {
    Swal.fire({
        title: 'Delete Subject?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/subjects/${id}`, {
                preserveScroll: true,
                preserveState: true,

                onSuccess: () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        text: 'Subject deleted successfully.',
                        timer: 1800,
                        showConfirmButton: false
                    })
                },

                onError: () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed',
                        text: 'Unable to delete subject.'
                    })
                }
            })
        }
    })
}
</script>

<template>
<AdminLayout>
<div class="space-y-6 max-w-7xl mx-auto px-4">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Subject Management
            </h1>

            <p class="text-slate-500 mt-1">
                Manage exam subjects professionally
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
                href="/admin/subjects/create"
                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transition"
            >
                + Add Subject
            </Link>

        </div>

    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow border p-4">
        <div class="relative">

            <input
                v-model="search"
                type="text"
                placeholder="Search subjects..."
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
        v-if="subjects.data.length"
        class="hidden lg:block bg-white rounded-2xl shadow border overflow-hidden"
    >
        <table class="w-full">
            <thead class="bg-slate-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left">ID</th>
                    <th class="px-6 py-4 text-left">Name</th>
                    <th class="px-6 py-4 text-left">Description</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="subject in subjects.data"
                    :key="subject.id"
                    class="border-b hover:bg-slate-50 transition"
                >
                    <td class="px-6 py-4">#{{ subject.id }}</td>

                    <td class="px-6 py-4 font-semibold">
                        {{ subject.name }}
                    </td>

                    <td class="px-6 py-4">
                        {{ subject.description || '-' }}
                    </td>

                    <td class="px-6 py-4">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold"
                            :class="subject.is_active
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700'"
                        >
                            {{ subject.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-3">

                            <Link
                                :href="`/admin/subjects/${subject.id}/edit`"
                                class="px-4 py-2 rounded-lg bg-blue-50 text-blue-600 font-medium hover:bg-blue-100 transition"
                            >
                                Edit
                            </Link>

                            <button
                                @click="destroySubject(subject.id)"
                                class="px-4 py-2 rounded-lg bg-red-50 text-red-600 font-medium hover:bg-red-100 transition"
                            >
                                Delete
                            </button>

                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile / Tablet Cards -->
    <div
        v-if="subjects.data.length"
        class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:hidden"
    >
        <div
            v-for="subject in subjects.data"
            :key="subject.id"
            class="bg-white rounded-2xl shadow border p-5"
        >
            <div class="flex justify-between items-start mb-4">

                <div>
                    <h3 class="font-bold text-lg text-slate-800">
                        {{ subject.name }}
                    </h3>

                    <p class="text-sm text-slate-500">
                        ID: #{{ subject.id }}
                    </p>
                </div>

                <span
                    class="px-3 py-1 rounded-full text-xs font-semibold"
                    :class="subject.is_active
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700'"
                >
                    {{ subject.is_active ? 'Active' : 'Inactive' }}
                </span>

            </div>

            <p class="text-slate-600 text-sm mb-4">
                {{ subject.description || 'No description available.' }}
            </p>

            <div class="flex gap-3">

                <Link
                    :href="`/admin/subjects/${subject.id}/edit`"
                    class="flex-1 text-center bg-blue-50 text-blue-600 py-2 rounded-lg font-medium hover:bg-blue-100 transition"
                >
                    Edit
                </Link>

                <button
                    @click="destroySubject(subject.id)"
                    class="flex-1 bg-red-50 text-red-600 py-2 rounded-lg font-medium hover:bg-red-100 transition"
                >
                    Delete
                </button>

            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div
        v-if="subjects.links?.length"
        class="flex flex-wrap gap-2 justify-center"
    >
        <template
            v-for="(link, index) in subjects.links"
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
        v-if="subjects.data.length === 0"
        class="bg-white rounded-2xl shadow border p-12 text-center"
    >
        <h3 class="text-lg font-semibold text-slate-600">
            No Subjects Found
        </h3>

        <p class="text-slate-500 mt-2 mb-6">
            Start by adding your first subject.
        </p>

        <Link
            href="/admin/subjects/create"
            class="bg-blue-600 text-white px-6 py-3 rounded-xl"
        >
            + Add First Subject
        </Link>
    </div>

</div>
</AdminLayout>
</template>
