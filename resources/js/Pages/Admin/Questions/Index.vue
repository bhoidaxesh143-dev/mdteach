<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import Swal from 'sweetalert2'

const props = defineProps({
    exam: {
        type: Object,
        required: true
    },
    questions: {
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
    router.get(
        `/admin/exams/${props.exam.id}/questions`,
        { search: value },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    )
}, 400)

watch(search, performSearch)

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/
const goBack = () => {
    if (window.history.length > 1) {
        window.history.back()
    } else {
        router.visit('/admin/exams')
    }
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/
const clearSearch = () => {
    search.value = ''
}

/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/
const destroyQuestion = (id) => {
    Swal.fire({
        title: 'Delete Question?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/questions/${id}`, {
                preserveScroll: true,
                preserveState: true,

                onSuccess: () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        text: 'Question deleted successfully.',
                        timer: 1800,
                        showConfirmButton: false
                    })
                },

                onError: () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed',
                        text: 'Unable to delete question.'
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
                Question Management
            </h1>

            <p class="text-slate-500 mt-1">
                {{ exam.title }}
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
                :href="`/admin/exams/${exam.id}/questions/create`"
                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-3 rounded-xl shadow hover:shadow-lg transition"
            >
                + Add Question
            </Link>

        </div>

    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow border p-4">
        <div class="relative">

            <input
                v-model="search"
                type="text"
                placeholder="Search questions..."
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

    <!-- Questions -->
    <template v-if="questions.data.length">

        <div
            v-for="question in questions.data"
            :key="question.id"
            class="bg-white rounded-2xl shadow border p-6"
        >
            <div class="flex flex-col xl:flex-row xl:justify-between gap-6">

                <!-- Left -->
                <div class="flex-1 min-w-0">

                    <!-- Meta -->
                    <div class="flex flex-wrap gap-2 mb-4">

                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ question.subject?.name || 'No Subject' }}
                        </span>

                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold capitalize">
                            {{ question.type.replace('_', ' ') }}
                        </span>

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ question.marks }} Marks
                        </span>

                    </div>

                    <!-- Question -->
                    <div
                        class="prose max-w-none font-medium break-words"
                        v-html="question.question"
                    />

                </div>

                <!-- Actions -->
                <div class="flex flex-row xl:flex-col gap-3 shrink-0">

                    <Link
                        :href="`/admin/questions/${question.id}/edit`"
                        class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 font-medium hover:bg-blue-100 transition text-center"
                    >
                        Edit
                    </Link>

                    <button
                        @click="destroyQuestion(question.id)"
                        class="px-4 py-2 rounded-xl bg-red-50 text-red-600 font-medium hover:bg-red-100 transition"
                    >
                        Delete
                    </button>

                </div>

            </div>

            <!-- Options -->
            <div class="mt-6 grid md:grid-cols-2 gap-3">
                <div
                    v-for="option in question.options"
                    :key="option.id"
                    class="px-4 py-3 rounded-xl border"
                    :class="option.is_correct
                        ? 'bg-green-50 border-green-300 text-green-700'
                        : 'bg-slate-50 border-slate-200'"
                >
                    {{ option.option_text }}
                </div>
            </div>

            <!-- Explanation -->
            <div
                v-if="question.explanation"
                class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4"
            >
                <h4 class="font-semibold text-blue-700 mb-2">
                    Explanation
                </h4>

                <div
                    class="prose max-w-none text-sm break-words"
                    v-html="question.explanation"
                />
            </div>

        </div>

        <!-- Pagination -->
        <div
            v-if="questions.links?.length"
            class="flex flex-wrap gap-2 justify-center pt-4"
        >
            <template
                v-for="(link, index) in questions.links"
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

    </template>

    <!-- Empty State -->
    <div
        v-else
        class="bg-white rounded-2xl shadow border p-12 text-center"
    >
        <h3 class="text-lg font-semibold text-slate-700 mb-2">
            No Questions Found
        </h3>

        <p class="text-slate-500 mb-6">
            Start by adding your first question for this exam.
        </p>

        <Link
            :href="`/admin/exams/${exam.id}/questions/create`"
            class="bg-blue-600 text-white px-6 py-3 rounded-xl"
        >
            + Add First Question
        </Link>
    </div>

</div>
</AdminLayout>
</template>
