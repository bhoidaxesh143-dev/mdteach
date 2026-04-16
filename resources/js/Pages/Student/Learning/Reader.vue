<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
    topic: Object
})

/*
|--------------------------------------------------------------------------
| Notes Form
|--------------------------------------------------------------------------
*/
const noteForm = useForm({
    note: props.topic.my_note?.note || ''
})

const saveNote = () => {
    noteForm.post(
        `/student/learning/topic/${props.topic.id}/notes`,
        {
            preserveScroll: true,

            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved',
                    text: 'Notes saved successfully.',
                    timer: 1500,
                    showConfirmButton: false
                })
            }
        }
    )
}

/*
|--------------------------------------------------------------------------
| Back Navigation
|--------------------------------------------------------------------------
*/
const goBack = () => {
    if (window.history.length > 1) {
        window.history.back()
    } else {
        router.visit('/student/learning')
    }
}
</script>

<template>
<StudentLayout>
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <button
                @click="goBack"
                class="text-blue-600 font-medium hover:underline mb-2"
            >
                ← Back to Learning Hub
            </button>

            <h1 class="text-3xl font-bold text-slate-800">
                {{ topic.title }}
            </h1>
        </div>

        <div class="flex gap-3 flex-wrap">
            <Link
                method="post"
                :href="`/student/learning/topic/${topic.id}/bookmark`"
                as="button"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-3 rounded-xl shadow"
            >
                Bookmark
            </Link>

            <Link
                :href="`/student/learning/topic/${topic.id}/practice`"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow"
            >
                Practice Topic
            </Link>

            <Link
                v-if="!topic.is_completed"
                method="post"
                :href="`/student/learning/topic/${topic.id}/complete`"
                as="button"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl shadow"
            >
                Mark Complete
            </Link>

            <button
                v-else
                disabled
                class="bg-slate-300 text-slate-600 px-5 py-3 rounded-xl cursor-not-allowed"
            >
                Completed ✓
            </button>
        </div>
    </div>

    <div class="grid lg:grid-cols-4 gap-6">

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow border p-5 sticky top-6">

                <h3 class="font-bold text-slate-800 mb-4">
                    Sub Topics
                </h3>

                <div
                    v-if="topic.children?.length"
                    class="space-y-2"
                >
                    <Link
                        v-for="child in topic.children"
                        :key="child.id"
                        :href="`/student/learning/topic/${child.id}`"
                        class="block px-3 py-2 rounded-lg text-blue-600 hover:bg-blue-50 transition"
                    >
                        {{ child.title }}
                    </Link>
                </div>

                <p
                    v-else
                    class="text-sm text-slate-400"
                >
                    No subtopics available.
                </p>

            </div>
        </div>

        <!-- Reader -->
        <div class="lg:col-span-3 space-y-6">

            <!-- Theory -->
            <div class="bg-white rounded-2xl shadow border p-8">

                <h2 class="text-xl font-bold mb-6">
                    Theory Content
                </h2>

                <div
                    v-if="topic.content?.content_html"
                    class="prose max-w-none"
                    v-html="topic.content.content_html"
                />

                <div
                    v-else
                    class="text-slate-400 italic"
                >
                    No theory content added yet.
                </div>

            </div>

            <!-- Shortcut Tips -->
            <div
                v-if="topic.content?.shortcut_tips"
                class="bg-yellow-50 border border-yellow-300 rounded-2xl p-6"
            >
                <h3 class="font-bold text-yellow-800 mb-3">
                    Shortcut Tips
                </h3>

                <div
                    class="prose max-w-none"
                    v-html="topic.content.shortcut_tips"
                />
            </div>

            <!-- Personal Notes -->
            <div class="bg-white rounded-2xl shadow border p-8">

                <h2 class="text-2xl font-bold mb-4">
                    Personal Revision Notes
                </h2>

                <p class="text-slate-500 mb-4">
                    Save formulas, shortcuts, tricks, and revision notes.
                </p>

                <form @submit.prevent="saveNote">

                    <textarea
                        v-model="noteForm.note"
                        rows="8"
                        placeholder="Write your personal notes..."
                        class="w-full border rounded-xl p-4 focus:ring-2 focus:ring-purple-500"
                    />

                    <button
                        class="mt-4 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl shadow"
                        :disabled="noteForm.processing"
                    >
                        {{ noteForm.processing ? 'Saving...' : 'Save Notes' }}
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
</StudentLayout>
</template>
