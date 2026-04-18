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
<div class="mx-auto space-y-6 max-w-7xl">

    <!-- Top Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <button
                @click="goBack"
                class="mb-2 font-medium text-blue-600 hover:underline"
            >
                ← Back to Learning Hub
            </button>

            <h1 class="text-3xl font-bold text-slate-800">
                {{ topic.title }}
            </h1>
        </div>

        <div class="flex flex-wrap gap-3">
            <Link
                method="post"
                :href="`/student/learning/topic/${topic.id}/bookmark`"
                as="button"
                class="px-5 py-3 text-white bg-yellow-500 shadow hover:bg-yellow-600 rounded-xl"
            >
                Bookmark
            </Link>

            <Link
                :href="`/student/learning/topic/${topic.id}/practice`"
                class="px-5 py-3 text-white bg-blue-600 shadow hover:bg-blue-700 rounded-xl"
            >
                Practice Topic
            </Link>

            <Link
                v-if="!topic.is_completed"
                method="post"
                :href="`/student/learning/topic/${topic.id}/complete`"
                as="button"
                class="px-5 py-3 text-white bg-green-600 shadow hover:bg-green-700 rounded-xl"
            >
                Mark Complete
            </Link>

            <button
                v-else
                disabled
                class="px-5 py-3 cursor-not-allowed bg-slate-300 text-slate-600 rounded-xl"
            >
                Completed ✓
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-4">

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky p-5 bg-white border shadow rounded-2xl top-6">

                <h3 class="mb-4 font-bold text-slate-800">
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
                        class="block px-3 py-2 text-blue-600 transition rounded-lg hover:bg-blue-50"
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
        <div class="space-y-6 lg:col-span-3">

            <!-- Theory -->
            <div class="p-8 bg-white border shadow rounded-2xl">

                <h2 class="mb-6 text-xl font-bold">
                    Theory Content
                </h2>

                <div
                    v-if="topic.content?.content_html"
                    class="prose max-w-none"
                    v-html="topic.content.content_html"
                />

                <div
                    v-else
                    class="italic text-slate-400"
                >
                    No theory content added yet.
                </div>

            </div>

            <!-- Shortcut Tips -->
<div
    v-if="topic.content?.shortcut_tips && topic.content.shortcut_tips.replace(/<[^>]*>/g, '').trim()"
    class="p-6 border border-yellow-300 bg-yellow-50 rounded-2xl"
>
    <h3 class="mb-3 font-bold text-yellow-800">
        Shortcut Tips
    </h3>

    <div
        class="prose max-w-none"
        v-html="topic.content.shortcut_tips"
    />
</div>

            <!-- Personal Notes -->
            <div class="p-8 bg-white border shadow rounded-2xl">

                <h2 class="mb-4 text-2xl font-bold">
                    Personal Revision Notes
                </h2>

                <p class="mb-4 text-slate-500">
                    Save formulas, shortcuts, tricks, and revision notes.
                </p>

                <form @submit.prevent="saveNote">

                    <textarea
                        v-model="noteForm.note"
                        rows="8"
                        placeholder="Write your personal notes..."
                        class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-purple-500"
                    />

                    <button
                        class="px-6 py-3 mt-4 text-white bg-purple-600 shadow hover:bg-purple-700 rounded-xl"
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
