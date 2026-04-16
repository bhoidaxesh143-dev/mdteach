<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link } from '@inertiajs/vue3'
import { onMounted, nextTick } from 'vue'
import renderMathInElement from 'katex/contrib/auto-render'

const props = defineProps({
    attempt: Object
})

const getAnswer = (questionId) => {
    return props.attempt.answers?.find(
        answer => answer.question_id === questionId
    ) || null
}

const formatDate = (date) => {
    if (!date) return '-'

    return new Date(date).toLocaleString('en-IN', {
        dateStyle: 'medium',
        timeStyle: 'short'
    })
}

const totalQuestions = props.attempt.exam.questions.length
const correctAnswers = props.attempt.answers.filter(a => a.is_correct).length
const incorrectAnswers = totalQuestions - correctAnswers

onMounted(async () => {
    await nextTick()

    const resultContainer = document.getElementById('exam-result-content')

    if (resultContainer) {
        renderMathInElement(resultContainer, {
            delimiters: [
                { left: '$$', right: '$$', display: true },
                { left: '$', right: '$', display: false }
            ]
        })
    }
})
</script>

<template>
<StudentLayout>
<div
    id="exam-result-content"
    class="max-w-6xl mx-auto space-y-8"
>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Exam Result
            </h1>

            <p class="text-slate-500 mt-1">
                Review your performance and detailed solutions.
            </p>
        </div>

        <Link
            href="/student/results"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow"
        >
            Back to Results
        </Link>
    </div>

    <!-- Summary Stats -->
    <div class="grid md:grid-cols-4 gap-6">

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500 text-sm">Exam</p>
            <h3 class="font-bold text-lg mt-2">
                {{ attempt.exam.title }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500 text-sm">Score</p>
            <h3 class="font-bold text-2xl text-blue-600 mt-2">
                {{ attempt.score }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500 text-sm">Correct</p>
            <h3 class="font-bold text-2xl text-green-600 mt-2">
                {{ correctAnswers }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500 text-sm">Incorrect</p>
            <h3 class="font-bold text-2xl text-red-600 mt-2">
                {{ incorrectAnswers }}
            </h3>
        </div>

    </div>

    <!-- Meta -->
    <div class="bg-white rounded-2xl shadow border p-6 grid md:grid-cols-2 gap-6">
        <div>
            <p class="text-slate-500 text-sm">Status</p>
            <h3 class="font-bold text-lg capitalize">
                {{ attempt.status }}
            </h3>
        </div>

        <div>
            <p class="text-slate-500 text-sm">Submitted At</p>
            <h3 class="font-bold text-lg">
                {{ formatDate(attempt.submitted_at) }}
            </h3>
        </div>
    </div>

    <!-- Question Review -->
    <div class="space-y-6">

        <div
            v-for="(question, index) in attempt.exam.questions"
            :key="question.id"
            class="bg-white rounded-2xl shadow border p-6"
        >

            <!-- Question Header -->
            <div class="flex flex-col md:flex-row md:justify-between gap-4 mb-5">

                <h2
                    class="font-bold text-lg prose max-w-none"
                    v-html="`Q${index + 1}. ${question.question}`"
                />

                <span
                    v-if="getAnswer(question.id)?.is_correct"
                    class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold h-fit"
                >
                    ✓ Correct
                </span>

                <span
                    v-else
                    class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-semibold h-fit"
                >
                    ✕ Incorrect
                </span>

            </div>

            <!-- Options -->
            <div class="space-y-3">

                <div
                    v-for="option in question.options"
                    :key="option.id"
                    class="p-4 rounded-xl border transition"
                    :class="{
                        'bg-green-50 border-green-500 text-green-900':
                            option.is_correct,

                        'bg-red-50 border-red-500 text-red-900':
                            getAnswer(question.id)?.selected_option_id === option.id &&
                            !option.is_correct
                    }"
                >
                    <div
                        class="prose max-w-none"
                        v-html="option.option_text"
                    />
                </div>

            </div>

            <!-- Marks -->
            <div class="mt-4 text-sm text-slate-600">
                Marks Awarded:
                <strong>
                    {{ getAnswer(question.id)?.awarded_marks ?? 0 }}
                </strong>
            </div>

            <!-- Explanation -->
            <div
                v-if="question.explanation"
                class="mt-5 border-t pt-5"
            >
                <h4 class="font-semibold mb-3 text-slate-700">
                    Explanation / Solution
                </h4>

                <div
                    class="prose max-w-none text-slate-700"
                    v-html="question.explanation"
                />
            </div>

        </div>

    </div>

</div>
</StudentLayout>
</template>
