<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
    exam: {
        type: Object,
        required: true
    }
})

const form = useForm({
    title: props.exam?.title ?? '',
    description: props.exam?.description ?? '',
    duration_minutes: props.exam?.duration_minutes ?? '',
    negative_marking: props.exam?.negative_marking ?? 0.25,
    max_attempts: props.exam?.max_attempts ?? 1,
})

const submit = () => {
    form.put(`/admin/exams/${props.exam.id}`, {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Exam Updated',
                text: 'Exam updated successfully.',
                timer: 2000,
                showConfirmButton: false
            })
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
                <div class="px-5 sm:px-8 py-5 sm:py-6 border-b bg-gradient-to-r from-indigo-600 to-blue-600">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div>
                            <div class="flex items-center gap-2 text-sm text-blue-100 mb-2">
                                <Link href="/admin/exams" class="hover:underline">
                                    Exams
                                </Link>

                                <span>/</span>

                                <span>Edit Exam</span>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-bold text-white">
                                Edit Exam
                            </h2>

                            <p class="text-blue-100 text-sm mt-1">
                                Update examination configuration
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

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Exam Title
                        </label>

                        <input
                            v-model="form.title"
                            type="text"
                            placeholder="Enter exam title"
                            :class="[
                                'w-full rounded-xl border px-4 py-3 transition focus:ring-2 focus:outline-none',
                                form.errors.title
                                    ? 'border-red-500 focus:ring-red-200'
                                    : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                            ]"
                        />

                        <p v-if="form.errors.title" class="text-red-500 text-sm mt-2">
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Description
                        </label>

                        <textarea
                            v-model="form.description"
                            rows="4"
                            placeholder="Enter exam description"
                            :class="[
                                'w-full rounded-xl border px-4 py-3 transition focus:ring-2 focus:outline-none',
                                form.errors.description
                                    ? 'border-red-500 focus:ring-red-200'
                                    : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                            ]"
                        />

                        <p v-if="form.errors.description" class="text-red-500 text-sm mt-2">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Grid Fields -->
                    <div class="grid md:grid-cols-3 gap-6">

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Duration (Minutes)
                            </label>

                            <input
                                type="number"
                                min="1"
                                v-model="form.duration_minutes"
                                :class="[
                                    'w-full rounded-xl border px-4 py-3 transition focus:ring-2 focus:outline-none',
                                    form.errors.duration_minutes
                                        ? 'border-red-500 focus:ring-red-200'
                                        : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                                ]"
                            />

                            <p v-if="form.errors.duration_minutes" class="text-red-500 text-sm mt-2">
                                {{ form.errors.duration_minutes }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Negative Marking
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                v-model="form.negative_marking"
                                :class="[
                                    'w-full rounded-xl border px-4 py-3 transition focus:ring-2 focus:outline-none',
                                    form.errors.negative_marking
                                        ? 'border-red-500 focus:ring-red-200'
                                        : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                                ]"
                            />

                            <p v-if="form.errors.negative_marking" class="text-red-500 text-sm mt-2">
                                {{ form.errors.negative_marking }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Max Attempts
                            </label>

                            <input
                                type="number"
                                min="1"
                                v-model="form.max_attempts"
                                :class="[
                                    'w-full rounded-xl border px-4 py-3 transition focus:ring-2 focus:outline-none',
                                    form.errors.max_attempts
                                        ? 'border-red-500 focus:ring-red-200'
                                        : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                                ]"
                            />

                            <p v-if="form.errors.max_attempts" class="text-red-500 text-sm mt-2">
                                {{ form.errors.max_attempts }}
                            </p>
                        </div>

                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Updating Exam...' : 'Update Exam' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AdminLayout>
</template>
