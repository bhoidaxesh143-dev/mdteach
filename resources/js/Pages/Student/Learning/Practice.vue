<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    topic: Object
})

const form = useForm({
    answers: {}
})

const submit = () => {
    form.post(
        `/student/learning/topic/${props.topic.id}/practice`
    )
}
</script>

<template>
    <StudentLayout>
        <div class="max-w-5xl mx-auto">

            <!-- Breadcrumb / Back -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm text-slate-500 mb-1">
                        Learning Hub / {{ topic.title }} / Practice
                    </p>

                    <h1 class="text-3xl font-bold">
                        Practice: {{ topic.title }}
                    </h1>
                </div>

                <Link
                    :href="`/student/learning/topic/${topic.id}`"
                    class="bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-xl text-sm font-medium"
                >
                    ← Back to Topic
                </Link>
            </div>

            <!-- Questions -->
            <div
                v-for="question in topic.questions"
                :key="question.id"
                class="bg-white rounded-2xl shadow p-6 mb-6"
            >
                <h2
                    class="font-bold text-lg mb-4 prose max-w-none"
                    v-html="question.question"
                />

                <label
                    v-for="option in question.options"
                    :key="option.id"
                    class="flex gap-3 mb-3 p-3 rounded-xl hover:bg-slate-50 cursor-pointer"
                >
                    <input
                        type="radio"
                        :name="'q' + question.id"
                        :value="option.id"
                        v-model="form.answers[question.id]"
                    />

                    <span>{{ option.option_text }}</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <Link
                    :href="`/student/learning/topic/${topic.id}`"
                    class="px-6 py-3 rounded-xl bg-slate-200 hover:bg-slate-300"
                >
                    Cancel
                </Link>

                <button
                    @click="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl"
                >
                    Submit Practice
                </button>
            </div>

        </div>
    </StudentLayout>
</template>
