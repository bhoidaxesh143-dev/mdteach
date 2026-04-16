<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    exams: {
        type: Array,
        default: () => []
    }
})
</script>

<template>
    <StudentLayout>
        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-slate-800">
                    My Exams
                </h1>

                <p class="text-slate-500 mt-1">
                    Manage and attend your allocated examinations.
                </p>
            </div>

            <!-- Empty State -->
            <div
                v-if="exams.length === 0"
                class="bg-white rounded-2xl shadow p-10 text-center border border-slate-200"
            >
                <div class="text-5xl mb-4">📄</div>
                <h3 class="text-xl font-semibold text-slate-700">
                    No Exams Allocated
                </h3>
                <p class="text-slate-500 mt-2">
                    Contact administrator for exam assignments.
                </p>
            </div>

            <!-- Cards -->
            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                <div
                    v-for="exam in exams"
                    :key="exam.id"
                    class="bg-white rounded-2xl shadow border border-slate-200 p-6
                           hover:shadow-lg transition duration-300"
                >
                    <!-- Top -->
                    <div class="flex justify-between items-start mb-5">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">
                                {{ exam.title }}
                            </h2>

                            <p class="text-sm text-slate-400 mt-1">
                                Examination Portal
                            </p>
                        </div>

                        <span
    v-if="exam.remaining_attempts === 0"
    class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold"
>
    Attempts Exhausted
</span>

<span
    v-else
    class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold"
>
    Attempt {{ exam.attempts_used }} / {{ exam.max_attempts }}
</span>


                    </div>

                    <!-- Info -->
                    <div class="space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Duration</span>
                            <strong>{{ exam.duration_minutes }} Min</strong>
                        </div>

                        <div class="flex justify-between">
                            <span>Negative Marking</span>
                            <strong>{{ exam.negative_marking }}</strong>
                        </div>

                        <div class="flex justify-between">
                            <span>Starts</span>
                            <strong>{{ exam.starts_at || 'Anytime' }}</strong>
                        </div>

                        <div class="flex justify-between">
                            <span>Ends</span>
                            <strong>{{ exam.ends_at || 'No Limit' }}</strong>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="mt-6">
    <Link
        v-if="exam.remaining_attempts > 0"
        :href="`/student/exam/${exam.id}/start`"
        method="post"
        as="button"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl"
    >
        {{
            exam.attempts_used > 0
                ? 'Re-Attempt Exam'
                : 'Start Exam'
        }}
    </Link>

    <button
        v-else
        disabled
        class="w-full bg-slate-300 text-slate-600 px-5 py-3 rounded-xl"
    >
        Attempt Limit Reached
    </button>
</div>
                </div>
            </div>

        </div>
    </StudentLayout>
</template>
