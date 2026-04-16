<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import Swal from 'sweetalert2'

const props = defineProps({
    exams: Object,
    filters: Object
})

const search = ref(props.filters?.search || '')

const performSearch = useDebounceFn((value) => {
    router.get('/admin/exams', {
        search: value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}, 400)

watch(search, performSearch)

const destroyExam = (id) => {
    Swal.fire({
        title: 'Delete Exam?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/exams/${id}`)
        }
    })
}
</script>

<template>
    <AdminLayout>
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800">
                        Exam Management
                    </h1>

                    <p class="text-slate-500">
                        Manage all exams in the system
                    </p>
                </div>

                <Link
                    href="/admin/exams/create"
                    class="w-full md:w-auto text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transition"
                >
                    + Create Exam
                </Link>
            </div>

            <!-- Search -->
            <div class="bg-white rounded-2xl shadow p-4 border border-slate-100">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search exams..."
                    class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                />
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left">Title</th>
                            <th class="px-6 py-4 text-left">Duration</th>
                            <th class="px-6 py-4 text-left">Negative Marking</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="exam in exams.data"
                            :key="exam.id"
                            class="border-b hover:bg-slate-50 transition"
                        >
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ exam.title }}
                            </td>

                            <td class="px-6 py-4">
                                {{ exam.duration_minutes }} Min
                            </td>

                            <td class="px-6 py-4">
                                {{ exam.negative_marking }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    Active
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3">
                                    <Link
                                        :href="`/admin/exams/${exam.id}/questions`"
                                        class="text-emerald-600 font-medium"
                                    >
                                        Questions
                                    </Link>

                                    <Link
                                        :href="`/admin/exams/${exam.id}/edit`"
                                        class="text-blue-600 font-medium"
                                    >
                                        Edit
                                    </Link>

                                    <button
                                        @click="destroyExam(exam.id)"
                                        class="text-red-600 font-medium"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="exams.data.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                No exams found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:hidden">
                <div
                    v-for="exam in exams.data"
                    :key="exam.id"
                    class="bg-white rounded-2xl shadow p-5 border border-slate-100"
                >
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-slate-800">
                                {{ exam.title }}
                            </h3>

                            <p class="text-sm text-slate-500">
                                {{ exam.duration_minutes }} Minutes
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Active
                        </span>
                    </div>

                    <div class="space-y-2 text-sm text-slate-600 mb-4">
                        <p>
                            Negative Marking:
                            <strong>{{ exam.negative_marking }}</strong>
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <Link
                            :href="`/admin/exams/${exam.id}/questions`"
                            class="bg-emerald-50 text-emerald-600 py-2 rounded-lg text-center font-medium"
                        >
                            Questions
                        </Link>

                        <Link
                            :href="`/admin/exams/${exam.id}/edit`"
                            class="bg-blue-50 text-blue-600 py-2 rounded-lg text-center font-medium"
                        >
                            Edit
                        </Link>

                        <button
                            @click="destroyExam(exam.id)"
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
