<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'

const props = defineProps({
    attempts: {
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
    router.get('/admin/reports', {
        search: value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
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
        router.visit('/admin/dashboard')
    }
}

const clearSearch = () => {
    search.value = ''
}
</script>

<template>
<AdminLayout>
<div class="max-w-7xl mx-auto px-4 space-y-6">

    <!-- Header -->
    <div class="flex flex-col xl:flex-row xl:justify-between xl:items-center gap-4">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Reports Dashboard
            </h1>

            <p class="text-slate-500 mt-1">
                View and export exam attempt reports.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            <button
                @click="goBack"
                class="px-5 py-3 rounded-xl border border-slate-300 hover:bg-slate-50 transition"
            >
                ← Back
            </button>

            <a
                href="/admin/exports/results"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl shadow transition"
            >
                Export Excel
            </a>

            <a
                href="/admin/exports/results-pdf"
                class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl shadow transition"
            >
                Export PDF
            </a>

        </div>

    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow border p-4">
        <div class="relative">

            <input
                v-model="search"
                type="text"
                placeholder="Search by student, exam, or status..."
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

    <!-- Desktop Table -->
    <div
        v-if="attempts.data.length"
        class="hidden lg:block bg-white rounded-2xl shadow border overflow-hidden"
    >
        <table class="w-full">
            <thead class="bg-slate-100">
                <tr>
                    <th class="p-4 text-left">Student</th>
                    <th class="p-4 text-left">Exam</th>
                    <th class="p-4 text-left">Score</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Submitted At</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="attempt in attempts.data"
                    :key="attempt.id"
                    class="border-t hover:bg-slate-50 transition"
                >
                    <td class="p-4 font-medium">
                        {{ attempt.student?.name || 'N/A' }}
                    </td>

                    <td class="p-4">
                        {{ attempt.exam?.title || 'N/A' }}
                    </td>

                    <td class="p-4 font-semibold text-blue-600">
                        {{ attempt.score }}
                    </td>

                    <td class="p-4">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold"
                            :class="attempt.status === 'passed'
                                ? 'bg-green-100 text-green-700'
                                : attempt.status === 'failed'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-yellow-100 text-yellow-700'"
                        >
                            {{ attempt.status }}
                        </span>
                    </td>

                    <td class="p-4 text-slate-600">
                        {{ attempt.submitted_at || '-' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div
        v-if="attempts.data.length"
        class="lg:hidden space-y-4"
    >
        <div
            v-for="attempt in attempts.data"
            :key="attempt.id"
            class="bg-white rounded-2xl shadow border p-5"
        >
            <div class="space-y-3">

                <div>
                    <p class="text-xs text-slate-500">Student</p>
                    <p class="font-semibold">{{ attempt.student?.name || 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs text-slate-500">Exam</p>
                    <p>{{ attempt.exam?.title || 'N/A' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <p class="text-xs text-slate-500">Score</p>
                        <p class="font-semibold text-blue-600">{{ attempt.score }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">Status</p>

                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold"
                            :class="attempt.status === 'passed'
                                ? 'bg-green-100 text-green-700'
                                : attempt.status === 'failed'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-yellow-100 text-yellow-700'"
                        >
                            {{ attempt.status }}
                        </span>
                    </div>

                </div>

                <div>
                    <p class="text-xs text-slate-500">Submitted At</p>
                    <p>{{ attempt.submitted_at || '-' }}</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div
        v-if="attempts.links?.length"
        class="flex flex-wrap gap-2 justify-center"
    >
        <template
            v-for="(link, index) in attempts.links"
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

    <!-- Empty State -->
    <div
        v-if="attempts.data.length === 0"
        class="bg-white rounded-2xl shadow border p-12 text-center"
    >
        <h3 class="text-lg font-semibold text-slate-700 mb-2">
            No Reports Available
        </h3>

        <p class="text-slate-500">
            Reports will appear here once students submit exams.
        </p>
    </div>

</div>
</AdminLayout>
</template>
