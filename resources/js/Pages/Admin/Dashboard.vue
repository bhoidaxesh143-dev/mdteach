<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({
    stats: Object
})
</script>

<template>
    <AdminLayout>
        <div class="space-y-8">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800">
                        Dashboard Overview
                    </h1>
                    <p class="text-slate-500">
                        Monitor platform performance & analytics
                    </p>
                </div>

                <div class="flex gap-3 flex-wrap">
                    <a
                        href="/admin/exports/results"
                        class="bg-green-600 text-white px-4 py-2 rounded-xl shadow"
                    >
                        Export Results
                    </a>

                    <a
                        href="/admin/exports/results-pdf"
                        class="bg-red-600 text-white px-4 py-2 rounded-xl shadow"
                    >
                        Export PDF
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow border">
                    <p class="text-slate-500">Total Exams</p>
                    <h3 class="text-3xl font-bold mt-2">{{ stats.total_exams }}</h3>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow border">
                    <p class="text-slate-500">Students</p>
                    <h3 class="text-3xl font-bold mt-2">{{ stats.total_students }}</h3>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow border">
                    <p class="text-slate-500">Attempts</p>
                    <h3 class="text-3xl font-bold mt-2">{{ stats.total_attempts }}</h3>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow border">
                    <p class="text-slate-500">Average Score</p>
                    <h3 class="text-3xl font-bold mt-2">{{ stats.average_score }}</h3>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>
