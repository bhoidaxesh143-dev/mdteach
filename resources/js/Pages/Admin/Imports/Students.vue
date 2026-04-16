<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const form = useForm({
    file: null
})

const submit = () => {
    form.post('/admin/imports/students', {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Students Imported',
                text: 'Student accounts uploaded successfully.',
                timer: 2500,
                showConfirmButton: false
            })

            form.reset()
        },

        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Import Failed',
                text: 'Please check validation errors and file format.'
            })
        }
    })
}
</script>

<template>
    <AdminLayout>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-0">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

                <!-- Header -->
                <div class="px-5 sm:px-8 py-5 sm:py-6 border-b bg-gradient-to-r from-blue-600 to-indigo-600">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div>
                            <div class="flex items-center gap-2 text-sm text-blue-100 mb-2">
                                <Link href="/admin/dashboard" class="hover:underline">
                                    Dashboard
                                </Link>

                                <span>/</span>

                                <span>Import Students</span>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-bold text-white">
                                Import Students
                            </h2>

                            <p class="text-blue-100 text-sm mt-1">
                                Bulk upload student accounts via Excel / CSV
                            </p>
                        </div>

                        <Link
                            href="/admin/dashboard"
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-xl text-sm font-medium transition"
                        >
                            ← Back
                        </Link>

                    </div>
                </div>

                <!-- Form -->
                <form
                    @submit.prevent="submit"
                    class="p-5 sm:p-8 space-y-6"
                >

                    <!-- File Upload -->
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-700">
                            Upload Student File
                        </label>

                        <div
                            class="border-2 border-dashed border-slate-300 rounded-2xl p-6 bg-slate-50 hover:bg-slate-100 transition"
                        >
                            <input
                                type="file"
                                accept=".csv,.xlsx,.xls"
                                @input="form.file = $event.target.files[0]"
                                class="w-full text-sm"
                            />

                            <p class="text-xs text-slate-500 mt-3">
                                Supported formats: CSV, XLSX, XLS
                            </p>
                        </div>

                        <p
                            v-if="form.errors.file"
                            class="text-red-500 text-sm mt-2"
                        >
                            {{ form.errors.file }}
                        </p>
                    </div>

                    <!-- Required Columns -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
                        <h3 class="font-semibold text-slate-800 mb-3">
                            Required Excel Columns
                        </h3>

                        <ul class="list-disc ml-5 space-y-2 text-slate-600 text-sm">
                            <li><strong>name</strong></li>
                            <li><strong>email</strong></li>
                            <li><strong>password</strong> (optional)</li>
                        </ul>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                        <h3 class="font-semibold text-blue-800 mb-3">
                            Import Instructions
                        </h3>

                        <ul class="list-disc ml-5 space-y-2 text-blue-700 text-sm">
                            <li>Email addresses must be unique.</li>
                            <li>If password is empty, system may generate default password.</li>
                            <li>Column names must match exactly.</li>
                            <li>Large imports may take additional processing time.</li>
                        </ul>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Uploading Students...' : 'Upload Students' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AdminLayout>
</template>
