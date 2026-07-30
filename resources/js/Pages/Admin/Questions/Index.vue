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
<div class="px-4 mx-auto space-y-6 max-w-7xl">

    <!-- Header -->
    <div class="flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-center">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Question Management
            </h1>

            <p class="mt-1 text-slate-500">
                {{ exam.title }}
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            <button
                @click="goBack"
                class="px-5 py-3 transition border rounded-xl border-slate-300 hover:bg-slate-50"
            >
                ← Back
            </button>

            <Link
                :href="`/admin/exams/${exam.id}/questions/create`"
                class="px-5 py-3 text-white transition shadow bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:shadow-lg"
            >
                + Add Question
            </Link>

        </div>

    </div>

    <!-- Search -->
    <div class="p-4 bg-white border shadow rounded-2xl">
        <div class="relative">

            <input
                v-model="search"
                type="text"
                placeholder="Search questions..."
                class="w-full px-4 py-3 pr-12 border rounded-xl focus:ring-2 focus:ring-blue-500"
            />

            <button
                v-if="search"
                @click="clearSearch"
                class="absolute -translate-y-1/2 right-3 top-1/2 text-slate-400 hover:text-red-500"
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
            class="p-6 bg-white border shadow rounded-2xl"
        >
            <div class="flex flex-col gap-6 xl:flex-row xl:justify-between">

                <!-- Left -->
                <div class="flex-1 min-w-0">

                    <!-- Meta -->
                    <div class="flex flex-wrap gap-2 mb-4">

                        <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                            {{ question.subject?.name || 'No Subject' }}
                        </span>

                        <span class="px-3 py-1 text-xs font-semibold text-purple-700 capitalize bg-purple-100 rounded-full">
                            {{ question.type.replace('_', ' ') }}
                        </span>

                        <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                            {{ question.marks }} Marks
                        </span>

                    </div>

                    <!-- Question -->
                    <div
                        class="font-medium prose break-words max-w-none"
                        v-html="question.question"
                    />

                </div>

                <!-- Actions -->
                <div class="flex flex-row gap-3 xl:flex-col shrink-0">

                    <Link
                        :href="`/admin/questions/${question.id}/edit`"
                        class="px-4 py-2 font-medium text-center text-blue-600 transition rounded-xl bg-blue-50 hover:bg-blue-100"
                    >
                        Edit
                    </Link>

                    <button
                        @click="destroyQuestion(question.id)"
                        class="px-4 py-2 font-medium text-red-600 transition rounded-xl bg-red-50 hover:bg-red-100"
                    >
                        Delete
                    </button>

                </div>

            </div>

            <!-- Options -->
            <div class="grid gap-3 mt-6 md:grid-cols-2">
                <div
                    v-for="option in question.options"
                    :key="option.id"
                    class="px-4 py-3 border rounded-xl"
                    :class="option.is_correct
                        ? 'bg-green-50 border-green-300 text-green-700'
                        : 'bg-slate-50 border-slate-200'"
                >
                    <div
                        class="text-sm prose break-words max-w-none"
                        v-html="option.option_text"
                    />
                </div>
            </div>

            <!-- Explanation -->
            <div
                v-if="question.explanation"
                class="p-4 mt-6 border border-blue-200 bg-blue-50 rounded-xl"
            >
                <h4 class="mb-2 font-semibold text-blue-700">
                    Explanation
                </h4>

                <div
                    class="text-sm prose break-words max-w-none"
                    v-html="question.explanation"
                />
            </div>

        </div>

        <!-- Pagination -->
        <div
            v-if="questions.links?.length"
            class="flex flex-wrap justify-center gap-2 pt-4"
        >
            <template
                v-for="(link, index) in questions.links"
                :key="index"
            >
                <Link
                    v-if="link.url"
                    :href="link.url"
                    v-html="link.label"
                    class="px-4 py-2 text-sm border rounded-lg"
                    :class="link.active
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-white hover:bg-slate-50'"
                />

                <span
                    v-else
                    v-html="link.label"
                    class="px-4 py-2 text-sm border rounded-lg text-slate-400 bg-slate-50"
                />
            </template>
        </div>

    </template>

    <!-- Empty State -->
    <div
        v-else
        class="p-12 text-center bg-white border shadow rounded-2xl"
    >
        <h3 class="mb-2 text-lg font-semibold text-slate-700">
            No Questions Found
        </h3>

        <p class="mb-6 text-slate-500">
            Start by adding your first question for this exam.
        </p>

        <Link
            :href="`/admin/exams/${exam.id}/questions/create`"
            class="px-6 py-3 text-white bg-blue-600 rounded-xl"
        >
            + Add First Question
        </Link>
    </div>

</div>
</AdminLayout>
</template>
