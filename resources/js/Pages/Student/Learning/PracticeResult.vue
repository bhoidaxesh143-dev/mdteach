<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    attempt: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <StudentLayout>
        <div class="max-w-5xl mx-auto">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl p-8 mb-8 shadow">
                <h1 class="text-3xl font-bold mb-2">
                    Practice Completed 🎉
                </h1>

                <p class="opacity-90">
                    Review your performance for this topic.
                </p>
            </div>

            <!-- Score Cards -->
            <div class="grid md:grid-cols-3 gap-6 mb-8">

                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <p class="text-slate-500 text-sm">
                        Topic
                    </p>

                    <h2 class="text-xl font-bold mt-2">
                        {{ attempt.topic?.title }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <p class="text-slate-500 text-sm">
                        Score
                    </p>

                    <h2 class="text-4xl font-bold text-blue-600 mt-2">
                        {{ attempt.score }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <p class="text-slate-500 text-sm">
                        Total Questions
                    </p>

                    <h2 class="text-4xl font-bold text-green-600 mt-2">
                        {{ attempt.total_questions }}
                    </h2>
                </div>

            </div>

            <!-- Percentage -->
            <div class="bg-white rounded-2xl shadow p-6 mb-8">
                <div class="flex justify-between mb-3">
                    <span class="font-medium text-slate-700">
                        Accuracy
                    </span>

                    <span class="font-bold text-green-600">
                        {{
                            Math.round(
                                (attempt.score / attempt.total_questions) * 100
                            )
                        }}%
                    </span>
                </div>

                <div class="w-full bg-slate-200 rounded-full h-4">
                    <div
                        class="bg-green-500 h-4 rounded-full transition-all"
                        :style="{
                            width:
                                Math.round(
                                    (attempt.score / attempt.total_questions) * 100
                                ) + '%'
                        }"
                    />
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-4">

                <Link
                    :href="`/student/learning/topic/${attempt.topic_id}`"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl"
                >
                    Back to Topic
                </Link>

                <Link
                    :href="`/student/learning/topic/${attempt.topic_id}/practice`"
                    class="bg-slate-200 hover:bg-slate-300 px-6 py-3 rounded-xl"
                >
                    Retry Practice
                </Link>

                <Link
                    href="/student/learning"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-3 rounded-xl"
                >
                    Learning Hub
                </Link>

            </div>

        </div>
    </StudentLayout>
</template>
