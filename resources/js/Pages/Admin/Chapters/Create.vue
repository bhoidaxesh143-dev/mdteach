<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

defineProps({
    subjects: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    subject_id: '',
    title: '',
    description: '',
    sort_order: 0
})

const submit = () => {
    form.post('/admin/chapters', {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Chapter Created',
                text: 'Chapter has been added successfully.',
                timer: 2000,
                showConfirmButton: false
            })

            form.reset()
            form.sort_order = 0
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
                                <Link href="/admin/chapters" class="hover:underline">
                                    Chapters
                                </Link>

                                <span>/</span>

                                <span>Create Chapter</span>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-bold text-white">
                                Create Chapter
                            </h2>

                            <p class="text-blue-100 text-sm mt-1">
                                Add a new chapter to learning hierarchy
                            </p>
                        </div>

                        <Link
                            href="/admin/chapters"
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

                    <!-- Subject Select -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Subject
                        </label>

                        <select
                            v-model="form.subject_id"
                            :class="[
                                'w-full rounded-xl border px-4 py-3 transition focus:ring-2 focus:outline-none',
                                form.errors.subject_id
                                    ? 'border-red-500 focus:ring-red-200'
                                    : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                            ]"
                        >
                            <option value="">
                                Select Subject
                            </option>

                            <option
                                v-for="subject in subjects"
                                :key="subject.id"
                                :value="subject.id"
                            >
                                {{ subject.name }}
                            </option>
                        </select>

                        <p v-if="form.errors.subject_id" class="text-red-500 text-sm mt-2">
                            {{ form.errors.subject_id }}
                        </p>
                    </div>

                    <!-- Chapter Title -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Chapter Title
                        </label>

                        <input
                            v-model="form.title"
                            type="text"
                            placeholder="Enter chapter title"
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
                            placeholder="Enter chapter description"
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

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Sort Order
                        </label>

                        <input
                            v-model="form.sort_order"
                            type="number"
                            min="0"
                            :class="[
                                'w-full rounded-xl border px-4 py-3 transition focus:ring-2 focus:outline-none',
                                form.errors.sort_order
                                    ? 'border-red-500 focus:ring-red-200'
                                    : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                            ]"
                        />

                        <p v-if="form.errors.sort_order" class="text-red-500 text-sm mt-2">
                            {{ form.errors.sort_order }}
                        </p>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving Chapter...' : 'Save Chapter' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AdminLayout>
</template>
