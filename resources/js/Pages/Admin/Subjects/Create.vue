<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const form = useForm({
    name: '',
    description: '',
    is_active: true
})

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/
const goBack = () => {
    if (window.history.length > 1) {
        window.history.back()
    } else {
        router.visit('/admin/subjects')
    }
}

/*
|--------------------------------------------------------------------------
| Reset Form
|--------------------------------------------------------------------------
*/
const resetForm = () => {
    form.reset()
    form.is_active = true
    form.clearErrors()
}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/
const submit = () => {
    form.post('/admin/subjects', {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Subject Created',
                text: 'Subject has been added successfully.',
                timer: 2000,
                showConfirmButton: false
            })

            resetForm()
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
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-0">

    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

        <!-- Header -->
        <div class="px-5 sm:px-8 py-5 sm:py-6 border-b bg-gradient-to-r from-blue-600 to-indigo-600">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white">
                        Create Subject
                    </h2>

                    <p class="text-blue-100 text-sm mt-1">
                        Add a new subject for exam/question management
                    </p>
                </div>

                <button
                    @click="goBack"
                    type="button"
                    class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-xl transition"
                >
                    ← Back
                </button>

            </div>

        </div>

        <!-- Form -->
        <form
            @submit.prevent="submit"
            class="p-5 sm:p-8 space-y-6"
        >

            <!-- Subject Name -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Subject Name
                </label>

                <input
                    v-model="form.name"
                    type="text"
                    placeholder="Enter subject name"
                    :class="[
                        'w-full rounded-xl border px-4 py-3 text-sm sm:text-base transition focus:ring-2 focus:outline-none',
                        form.errors.name
                            ? 'border-red-500 focus:ring-red-200'
                            : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                    ]"
                />

                <p
                    v-if="form.errors.name"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.name }}
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
                    placeholder="Enter subject description"
                    :class="[
                        'w-full rounded-xl border px-4 py-3 text-sm sm:text-base transition focus:ring-2 focus:outline-none',
                        form.errors.description
                            ? 'border-red-500 focus:ring-red-200'
                            : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500'
                    ]"
                />

                <p
                    v-if="form.errors.description"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.description }}
                </p>
            </div>

            <!-- Active Toggle -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-xl bg-slate-50 p-4 border">
                <div>
                    <h4 class="font-semibold text-slate-700">
                        Active Status
                    </h4>

                    <p class="text-sm text-slate-500">
                        Enable this subject for exams/questions
                    </p>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        v-model="form.is_active"
                        class="sr-only peer"
                    />

                    <div class="w-11 h-6 bg-slate-300 rounded-full peer peer-checked:bg-blue-600 transition"></div>

                    <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></div>
                </label>
            </div>

            <!-- Actions -->
            <div class="grid sm:grid-cols-2 gap-4 pt-2">

                <button
                    type="button"
                    @click="resetForm"
                    class="w-full border border-slate-300 py-3 rounded-xl font-semibold hover:bg-slate-50 transition"
                >
                    Reset Form
                </button>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition disabled:opacity-50"
                >
                    {{ form.processing ? 'Saving Subject...' : 'Save Subject' }}
                </button>

            </div>

        </form>

    </div>

</div>
</AdminLayout>
</template>
