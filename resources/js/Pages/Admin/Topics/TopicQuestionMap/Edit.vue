<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import Swal from 'sweetalert2'

const props = defineProps({
    topic: {
        type: Object,
        required: true
    },
    questions: {
        type: Array,
        required: true
    }
})

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
const form = useForm({
    question_ids: props.topic.questions.map(q => q.id)
})

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/
const search = ref('')

const filteredQuestions = computed(() => {
    if (!search.value) return props.questions

    return props.questions.filter(question =>
        question.question
            ?.toLowerCase()
            .includes(search.value.toLowerCase())
    )
})

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/
const selectedCount = computed(() => form.question_ids.length)

const selectAllVisible = () => {
    const visibleIds = filteredQuestions.value.map(q => q.id)

    form.question_ids = Array.from(
        new Set([
            ...form.question_ids,
            ...visibleIds
        ])
    )
}

const clearAllVisible = () => {
    const visibleIds = filteredQuestions.value.map(q => q.id)

    form.question_ids = form.question_ids.filter(
        id => !visibleIds.includes(id)
    )
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
        router.visit('/admin/topics')
    }
}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/
const submit = () => {
    form.put(`/admin/topics/${props.topic.id}/questions`, {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Mapping Saved',
                text: 'Questions mapped successfully.',
                timer: 2000,
                showConfirmButton: false
            })
        },

        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Save Failed',
                text: 'Unable to save mapping.'
            })
        }
    })
}
</script>

<template>
<AdminLayout>
<div class="max-w-6xl mx-auto px-4">

    <div class="bg-white rounded-2xl shadow-xl border overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

                <div>
                    <h1 class="text-2xl font-bold text-white">
                        Map Questions To Topic
                    </h1>

                    <p class="text-blue-100 text-sm mt-1">
                        Assign questions for topic:
                        <strong>{{ topic.title }}</strong>
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

        <div class="p-8 space-y-6">

            <!-- Search / Bulk Actions -->
            <div class="grid lg:grid-cols-3 gap-4">

                <input
                    v-model="search"
                    type="text"
                    placeholder="Search questions..."
                    class="lg:col-span-2 border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500"
                />

                <div class="flex gap-3">

                    <button
                        @click="selectAllVisible"
                        type="button"
                        class="flex-1 border border-blue-300 text-blue-600 py-3 rounded-xl hover:bg-blue-50 transition"
                    >
                        Select All
                    </button>

                    <button
                        @click="clearAllVisible"
                        type="button"
                        class="flex-1 border border-red-300 text-red-600 py-3 rounded-xl hover:bg-red-50 transition"
                    >
                        Clear All
                    </button>

                </div>

            </div>

            <!-- Selected Count -->
            <div class="bg-slate-50 rounded-xl border px-4 py-3 text-sm text-slate-600">
                Selected Questions:
                <strong>{{ selectedCount }}</strong>
            </div>

            <!-- Validation -->
            <p
                v-if="form.errors.question_ids"
                class="text-red-500 text-sm"
            >
                {{ form.errors.question_ids }}
            </p>

            <!-- Question List -->
            <div
                class="space-y-3 max-h-[600px] overflow-y-auto pr-2 border rounded-2xl p-4 bg-slate-50"
            >
                <label
                    v-for="question in filteredQuestions"
                    :key="question.id"
                    class="flex gap-4 items-start border rounded-xl p-4 bg-white hover:border-blue-300 hover:bg-blue-50/30 transition cursor-pointer"
                >
                    <input
                        type="checkbox"
                        :value="question.id"
                        v-model="form.question_ids"
                        class="mt-1 w-5 h-5 rounded text-blue-600"
                    />

                    <div class="flex-1 min-w-0">
                        <p
                            class="text-slate-800 break-words"
                            v-html="question.question"
                        />
                    </div>
                </label>

                <div
                    v-if="filteredQuestions.length === 0"
                    class="text-center py-8 text-slate-500"
                >
                    No matching questions found.
                </div>
            </div>

            <!-- Submit -->
            <button
                @click="submit"
                :disabled="form.processing"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 rounded-xl font-semibold shadow hover:shadow-lg transition disabled:opacity-50"
            >
                {{ form.processing ? 'Saving Mapping...' : 'Save Mapping' }}
            </button>

        </div>

    </div>

</div>
</AdminLayout>
</template>
