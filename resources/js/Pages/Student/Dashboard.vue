<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            allocated: 0,
            completed: 0,
            pending: 0,
            average_score: 0
        })
    },

    recentAttempts: {
        type: Array,
        default: () => []
    },

    gamification: {
        type: Object,
        default: () => ({
            xp: 0,
            level: 1,
            streak_days: 0
        })
    }
})

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
            Student Dashboard
        </h1>

        <p class="text-slate-500 mt-1">
            Overview of your exams, performance, and learning progress.
        </p>
    </div>

    <!-- Gamification Banner -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-2xl p-6 shadow">
        <h2 class="text-2xl font-bold mb-4">
            Your Learning Stats
        </h2>

        <div class="grid md:grid-cols-3 gap-6">

            <div>
                <p class="text-sm opacity-80">XP</p>
                <h3 class="text-3xl font-bold">
                    {{ gamification?.xp ?? 0 }}
                </h3>
            </div>

            <div>
                <p class="text-sm opacity-80">Level</p>
                <h3 class="text-3xl font-bold">
                    {{ gamification?.level ?? 1 }}
                </h3>
            </div>

            <div>
                <p class="text-sm opacity-80">Streak</p>
                <h3 class="text-3xl font-bold">
                    {{ gamification?.streak_days ?? 0 }} 🔥
                </h3>
            </div>

        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-4 gap-6">

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500">Allocated Exams</p>
            <h3 class="text-3xl font-bold mt-2">
                {{ stats.allocated }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500">Completed</p>
            <h3 class="text-3xl font-bold mt-2 text-green-600">
                {{ stats.completed }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500">Pending</p>
            <h3 class="text-3xl font-bold mt-2 text-yellow-600">
                {{ stats.pending }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500">Average Score</p>
            <h3 class="text-3xl font-bold mt-2 text-blue-600">
                {{ stats.average_score }}
            </h3>
        </div>

    </div>

    <!-- Recent Attempts -->
    <div class="bg-white rounded-2xl shadow border overflow-hidden">

        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">
                Recent Attempts
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px]">

                <thead class="bg-slate-100">
                    <tr>
                        <th class="p-4 text-left">Exam</th>
                        <th class="p-4 text-left">Score</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Submitted At</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="attempt in recentAttempts"
                        :key="attempt.id"
                        class="border-t hover:bg-slate-50 transition"
                    >
                        <td class="p-4 font-medium">
                            {{ attempt.exam?.title || 'N/A' }}
                        </td>

                        <td class="p-4 font-semibold text-blue-600">
                            {{ attempt.score ?? '-' }}
                        </td>

                        <td class="p-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold"
                                :class="attempt.status === 'submitted'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-yellow-100 text-yellow-700'"
                            >
                                {{ attempt.status }}
                            </span>
                        </td>

                        <td class="p-4 text-slate-600">
                            {{ formatDate(attempt.submitted_at) }}
                        </td>
                    </tr>

                    <tr v-if="recentAttempts.length === 0">
                        <td
                            colspan="4"
                            class="p-6 text-center text-slate-500"
                        >
                            No attempts yet.
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

    </div>

</div>
</StudentLayout>
</template>
