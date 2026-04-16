<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

defineProps({
    students: {
        type: Array,
        default: () => []
    },
    exams: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    student_id: '',
    exam_id: ''
})

const submit = () => {
    form.post('/admin/allocations', {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Exam Allocated',
                text: 'Exam has been allocated successfully.',
                timer: 2000,
                showConfirmButton: false
            })

            form.reset()
        },

        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Validation Failed',
                text: 'Please fix the highlighted fields.'
            })
        }
    })
}
</script>

<template>
    <AdminLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-0">
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

                                <span>Allocate Exam</span>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-bold text-white">
                                Allocate Exam
                            </h2>

                            <p class="text-blue-100 text-sm mt-1">
                                Assign exams to students for participation
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

                    <!-- Student Select -->
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-700">
                            Select Student
                        </label>

                        <select
                            v-model="form.student_id"
                            :class="[
                                'w-full rounded-xl border px-4 py-3 transition focus:ring-2 focus:outline-none',
                                form.errors.student_id
                                    ? 'border-red-500 focus:ring-red-200'
                                    : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                            ]"
                        >
                            <option value="">
                                Choose Student
                            </option>

                            <option
                                v-for="student in students"
                                :key="student.id"
                                :value="student.id"
                            >
                                {{ student.name }}
                            </option>
                        </select>

                        <p
                            v-if="form.errors.student_id"
                            class="text-red-500 text-sm mt-2"
                        >
                            {{ form.errors.student_id }}
                        </p>
                    </div>

                    <!-- Exam Select -->
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-700">
                            Select Exam
                        </label>

                        <select
                            v-model="form.exam_id"
                            :class="[
                                'w-full rounded-xl border px-4 py-3 transition focus:ring-2 focus:outline-none',
                                form.errors.exam_id
                                    ? 'border-red-500 focus:ring-red-200'
                                    : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                            ]"
                        >
                            <option value="">
                                Choose Exam
                            </option>

                            <option
                                v-for="exam in exams"
                                :key="exam.id"
                                :value="exam.id"
                            >
                                {{ exam.title }}
                            </option>
                        </select>

                        <p
                            v-if="form.errors.exam_id"
                            class="text-red-500 text-sm mt-2"
                        >
                            {{ form.errors.exam_id }}
                        </p>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Allocating Exam...' : 'Allocate Exam' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AdminLayout>
</template>
