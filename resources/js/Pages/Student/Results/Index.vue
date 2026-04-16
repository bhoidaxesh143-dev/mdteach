<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    results: {
        type: Array,
        default: () => []
    }
})

const averageScore = props.results.length
    ? (
        props.results.reduce((sum, r) => sum + Number(r.score || 0), 0)
        / props.results.length
      ).toFixed(2)
    : 0

const formatDate = (date) => {
    if (!date) return '-'

    return new Date(date).toLocaleString('en-IN', {
        dateStyle: 'medium',
        timeStyle: 'short'
    })
}
</script>

<template>
<StudentLayout>
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            My Results
        </h1>

        <p class="text-slate-500 mt-1">
            Review all completed exam attempts and performance history.
        </p>
    </div>

    <!-- Stats -->
    <div class="grid sm:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500 text-sm">Total Attempts</p>
            <h3 class="text-3xl font-bold mt-2">
                {{ results.length }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500 text-sm">Average Score</p>
            <h3 class="text-3xl font-bold mt-2 text-blue-600">
                {{ averageScore }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500 text-sm">Completed</p>
            <h3 class="text-3xl font-bold mt-2 text-green-600">
                {{ results.filter(r => r.status === 'submitted').length }}
            </h3>
        </div>

    </div>

    <!-- Empty State -->
    <div
        v-if="results.length === 0"
        class="bg-white rounded-2xl shadow border p-12 text-center"
    >
        <div class="text-5xl mb-4">📊</div>

        <h3 class="text-xl font-semibold text-slate-700">
            No Results Yet
        </h3>

        <p class="text-slate-500 mt-2">
            Complete an exam to see your results here.
        </p>
    </div>

    <!-- Results Table -->
    <div
        v-else
        class="bg-white rounded-2xl shadow border overflow-hidden"
    >
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px]">

                <thead class="bg-slate-100">
                    <tr>
                        <th class="p-4 text-left">Exam</th>
                        <th class="p-4 text-left">Score</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Submitted</th>
                        <th class="p-4 text-left">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="result in results"
                        :key="result.id"
                        class="border-t hover:bg-slate-50 transition"
                    >

                        <td class="p-4 font-medium text-slate-800">
                            {{ result.exam.title }}
                        </td>

                        <td class="p-4 font-bold text-blue-600">
                            {{ result.score }}
                        </td>

                        <td class="p-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold"
                                :class="result.status === 'submitted'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-yellow-100 text-yellow-700'"
                            >
                                {{ result.status }}
                            </span>
                        </td>

                        <td class="p-4 text-slate-600">
                            {{ formatDate(result.submitted_at) }}
                        </td>

                        <td class="p-4">
                            <Link
    :href="`/student/results/${result.id}`"
    class="text-blue-600 font-medium"
>
    View Result
</Link>
                        </td>

                    </tr>
                </tbody>

            </table>
        </div>
    </div>

</div>
</StudentLayout>
</template>
