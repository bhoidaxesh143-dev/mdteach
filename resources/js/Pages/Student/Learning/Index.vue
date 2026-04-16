<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({
    subjects: Array,
    progress: Object,
    analytics: Object
})

const openChapters = ref([])

const toggleChapter = (id) => {
    if (openChapters.value.includes(id)) {
        openChapters.value = openChapters.value.filter(x => x !== id)
    } else {
        openChapters.value.push(id)
    }
}

const isOpen = (id) => openChapters.value.includes(id)
</script>

<template>
<StudentLayout>
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Learning Hub
        </h1>
        <p class="text-slate-500 mt-1">
            Explore structured learning content and track your mastery.
        </p>
    </div>

    <!-- Analytics -->
    <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow p-6 border">
            <p class="text-slate-500 text-sm">Completed Topics</p>
            <h2 class="text-3xl font-bold mt-2">
                {{ analytics.completed_topics }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border">
            <p class="text-slate-500 text-sm">Total Topics</p>
            <h2 class="text-3xl font-bold mt-2">
                {{ analytics.total_topics }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border">
            <p class="text-slate-500 text-sm">Progress</p>
            <h2 class="text-3xl font-bold mt-2 text-green-600">
                {{ analytics.progress_percent }}%
            </h2>
        </div>
    </div>

    <!-- Global Progress Bar -->
    <div class="bg-white rounded-2xl shadow p-6 border">
        <div class="flex justify-between mb-3">
            <span class="font-semibold text-slate-700">
                Overall Completion
            </span>

            <span class="text-green-600 font-bold">
                {{ analytics.progress_percent }}%
            </span>
        </div>

        <div class="w-full bg-slate-200 rounded-full h-3">
            <div
                class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all duration-500"
                :style="{ width: analytics.progress_percent + '%' }"
            />
        </div>
    </div>

    <!-- Subjects -->
    <div
        v-for="subject in subjects"
        :key="subject.id"
        class="bg-white rounded-2xl shadow border overflow-hidden"
    >
        <!-- Subject Header -->
        <div class="px-6 py-5 bg-slate-50 border-b">
            <h2 class="text-2xl font-bold text-slate-800">
                {{ subject.name }}
            </h2>
        </div>

        <!-- Chapters -->
        <div class="p-6 space-y-4">

            <div
                v-for="chapter in subject.chapters"
                :key="chapter.id"
                class="border rounded-2xl overflow-hidden"
            >
                <!-- Chapter Header -->
                <button
                    @click="toggleChapter(chapter.id)"
                    class="w-full px-5 py-4 bg-slate-50 hover:bg-slate-100 flex justify-between items-center text-left"
                >
                    <div>
                        <h3 class="font-semibold text-lg text-slate-800">
                            {{ chapter.title }}
                        </h3>

                        <p class="text-sm text-slate-500">
                            {{ chapter.topics.length }} Topics
                        </p>
                    </div>

                    <span class="text-xl">
                        {{ isOpen(chapter.id) ? '−' : '+' }}
                    </span>
                </button>

                <!-- Topics -->
                <div
                    v-show="isOpen(chapter.id)"
                    class="p-4 bg-white space-y-3"
                >
                    <Link
                        v-for="topic in chapter.topics"
                        :key="topic.id"
                        :href="`/student/learning/topic/${topic.id}`"
                        class="flex items-center justify-between border rounded-xl px-4 py-3 hover:bg-slate-50 transition"
                    >
                        <div>
                            <p class="font-medium text-slate-800">
                                {{ topic.title }}
                            </p>
                        </div>

                        <span
                            v-if="progress[topic.id]"
                            class="text-green-600 text-sm font-semibold"
                        >
                            ✓ Completed
                        </span>

                        <span
                            v-else
                            class="text-slate-400 text-sm"
                        >
                            Pending
                        </span>
                    </Link>
                </div>

            </div>

        </div>
    </div>

</div>
</StudentLayout>
</template>
