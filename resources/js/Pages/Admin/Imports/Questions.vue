<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

defineProps({
    exams: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    exam_id: '',
    file: null
})

const submit = () => {
    form.post('/admin/imports/questions', {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Import Successful',
                text: 'Questions imported successfully.',
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

                                <span>Import Questions</span>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-bold text-white">
                                Import Questions
                            </h2>

                            <p class="text-blue-100 text-sm mt-1">
                                Upload bulk questions using CSV / Excel
                            </p>
                        </div>

                        <Link
                            href="/admin/exams"
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

                    <!-- Exam Select -->
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-700">
                            Select Exam
                        </label>

                        <select
                            v-model="form.exam_id"
                            :class="[
                                'w-full rounded-xl border px-4 py-3 focus:ring-2 focus:outline-none',
                                form.errors.exam_id
                                    ? 'border-red-500 focus:ring-red-200'
                                    : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                            ]"
                        >
                            <option value="">Choose Exam</option>

                            <option
                                v-for="exam in exams"
                                :key="exam.id"
                                :value="exam.id"
                            >
                                {{ exam.title }}
                            </option>
                        </select>

                        <p v-if="form.errors.exam_id" class="text-red-500 text-sm mt-2">
                            {{ form.errors.exam_id }}
                        </p>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-700">
                            Upload CSV / Excel File
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

                        <p v-if="form.errors.file" class="text-red-500 text-sm mt-2">
                            {{ form.errors.file }}
                        </p>
                    </div>

                    <!-- Format Guide -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
                        <h3 class="font-semibold text-slate-800 mb-3">
                            Required CSV Format
                        </h3>

                        <code class="text-sm text-slate-600 block break-words leading-7">
                            subject,question,type,marks,option_a,option_b,option_c,option_d,correct_answers,sort_order
                        </code>
                    </div>

                    <!-- Import Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                        <h3 class="font-semibold text-blue-800 mb-3">
                            Import Instructions
                        </h3>

                        <ul class="text-sm text-blue-700 space-y-2 list-disc pl-5">
                            <li>Ensure column names match exactly.</li>
                            <li>Correct answers should be comma separated if multiple.</li>
                            <li>Question type must match allowed values.</li>
                            <li>Maximum upload size depends on server settings.</li>
                        </ul>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Uploading Questions...' : 'Upload Questions' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AdminLayout>
</template>
