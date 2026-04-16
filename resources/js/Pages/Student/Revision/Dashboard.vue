<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    continueLearning: Object,
    weakTopics: {
        type: Array,
        default: () => []
    },
    bookmarks: {
        type: Array,
        default: () => []
    },
    notes: {
        type: Array,
        default: () => []
    }
})

const truncate = (text, length = 120) => {
    if (!text) return ''
    return text.length > length
        ? text.substring(0, length) + '...'
        : text
}
</script>

<template>
<StudentLayout>
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Smart Revision Dashboard
        </h1>

        <p class="text-slate-500 mt-1">
            Continue learning, review weak topics, and revise smarter.
        </p>
    </div>

    <!-- Stats Overview -->
    <div class="grid sm:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500 text-sm">Weak Topics</p>
            <h3 class="text-3xl font-bold text-red-600 mt-2">
                {{ weakTopics.length }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500 text-sm">Bookmarks</p>
            <h3 class="text-3xl font-bold text-yellow-600 mt-2">
                {{ bookmarks.length }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <p class="text-slate-500 text-sm">Notes Saved</p>
            <h3 class="text-3xl font-bold text-blue-600 mt-2">
                {{ notes.length }}
            </h3>
        </div>

    </div>

    <!-- Continue Learning -->
    <div
        v-if="continueLearning"
        class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl shadow p-6"
    >
        <p class="text-sm opacity-80 mb-2">
            Continue Learning
        </p>

        <h2 class="text-2xl font-bold mb-3">
            {{ continueLearning.topic.title }}
        </h2>

        <Link
            :href="`/student/learning/topic/${continueLearning.topic.id}`"
            class="inline-flex bg-white text-blue-700 px-5 py-3 rounded-xl font-semibold hover:bg-slate-100"
        >
            Resume Topic →
        </Link>
    </div>

    <!-- Main Grid -->
    <div class="grid xl:grid-cols-3 gap-6">

        <!-- Weak Topics -->
        <div class="bg-white rounded-2xl shadow border p-6">
            <h2 class="text-xl font-bold mb-4 text-red-600">
                Weak Topics
            </h2>

            <div
                v-if="weakTopics.length"
                class="space-y-3"
            >
                <Link
                    v-for="topic in weakTopics"
                    :key="topic.id"
                    :href="`/student/learning/topic/${topic.id}`"
                    class="block border rounded-xl px-4 py-3 hover:bg-red-50 hover:border-red-300 transition"
                >
                    {{ topic.title }}
                </Link>
            </div>

            <p
                v-else
                class="text-slate-500 text-sm"
            >
                No weak topics detected yet.
            </p>
        </div>

        <!-- Bookmarks -->
        <div class="bg-white rounded-2xl shadow border p-6">
            <h2 class="text-xl font-bold mb-4 text-yellow-600">
                Bookmarked Topics
            </h2>

            <div
                v-if="bookmarks.length"
                class="space-y-3"
            >
                <Link
                    v-for="bookmark in bookmarks"
                    :key="bookmark.id"
                    :href="`/student/learning/topic/${bookmark.topic.id}`"
                    class="block border rounded-xl px-4 py-3 hover:bg-yellow-50 hover:border-yellow-300 transition"
                >
                    {{ bookmark.topic.title }}
                </Link>
            </div>

            <p
                v-else
                class="text-slate-500 text-sm"
            >
                No bookmarks added.
            </p>
        </div>

        <!-- Notes -->
        <div class="bg-white rounded-2xl shadow border p-6">
            <h2 class="text-xl font-bold mb-4 text-blue-600">
                Personal Notes
            </h2>

            <div
                v-if="notes.length"
                class="space-y-4"
            >
                <div
                    v-for="note in notes"
                    :key="note.id"
                    class="border rounded-xl p-4 hover:bg-slate-50 transition"
                >
                    <h3 class="font-semibold text-slate-800">
                        {{ note.topic.title }}
                    </h3>

                    <p class="text-slate-600 text-sm mt-2">
                        {{ truncate(note.note) }}
                    </p>
                </div>
            </div>

            <p
                v-else
                class="text-slate-500 text-sm"
            >
                No personal notes created.
            </p>
        </div>

    </div>

</div>
</StudentLayout>
</template>
