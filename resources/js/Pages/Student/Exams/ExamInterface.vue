<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { router } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    attempt: Object
})

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/
const currentIndex = ref(0)
const selectedAnswers = ref({})

/*
|--------------------------------------------------------------------------
| Timer
|--------------------------------------------------------------------------
*/
const remaining = ref(props.attempt.exam.duration_minutes * 60)

let timer = null

onMounted(() => {
    timer = setInterval(() => {
        if (remaining.value > 0) {
            remaining.value--
        } else {
            clearInterval(timer)
            submitExam()
        }
    }, 1000)
})

onUnmounted(() => {
    clearInterval(timer)
})

/*
|--------------------------------------------------------------------------
| Save Answer
|--------------------------------------------------------------------------
*/
const saveAnswer = (questionId, optionId) => {
    selectedAnswers.value[questionId] = optionId

    router.post(
        `/student/attempt/${props.attempt.id}/save-answer`,
        {
            question_id: questionId,
            selected_option_id: optionId,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    )
}

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/
const nextQuestion = () => {
    if (currentIndex.value < props.attempt.exam.questions.length - 1) {
        currentIndex.value++
    }
}

const prevQuestion = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--
    }
}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/
const submitExam = () => {
    if (confirm('Are you sure you want to submit the exam?')) {
        router.post(`/student/attempt/${props.attempt.id}/submit`)
    }
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/
const formatTime = (sec) => {
    const mins = Math.floor(sec / 60)
    const secs = sec % 60
    return `${mins}:${secs.toString().padStart(2, '0')}`
}
</script>

<template>
    <StudentLayout>
        <div class="grid lg:grid-cols-4 gap-6">

            <!-- Main Exam Area -->
            <div class="lg:col-span-3">

                <!-- Sticky Header -->
                <div class="bg-white rounded-2xl shadow p-5 mb-6 flex justify-between items-center">
                    <h2 class="text-xl font-bold">
                        Exam Interface
                    </h2>

                    <div class="text-red-600 font-bold text-lg">
                        ⏳ {{ formatTime(remaining) }}
                    </div>
                </div>

                <!-- Current Question -->
                <div class="bg-white rounded-2xl shadow p-6">
                  <h3
    class="text-lg font-bold text-slate-800 prose max-w-none"
    v-html="`Q${currentIndex + 1}. ${attempt.exam.questions[currentIndex].question}`"
/>

                    <div
                        v-for="option in attempt.exam.questions[currentIndex].options"
                        :key="option.id"
                        class="mb-3"
                    >
                        <label
                            class="flex items-center gap-3 border rounded-xl px-4 py-3 hover:bg-slate-50 cursor-pointer"
                        >
                            <input
                                type="radio"
                                :name="`question-${attempt.exam.questions[currentIndex].id}`"
                                :checked="
                                    selectedAnswers[
                                        attempt.exam.questions[currentIndex].id
                                    ] === option.id
                                "
                                @change="
                                    saveAnswer(
                                        attempt.exam.questions[currentIndex].id,
                                        option.id
                                    )
                                "
                            />

                         <div
    class="prose max-w-none"
    v-html="option.option_text"
/>
                        </label>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-between mt-8">
                        <button
                            @click="prevQuestion"
                            :disabled="currentIndex === 0"
                            class="bg-slate-300 px-5 py-2 rounded-xl disabled:opacity-50"
                        >
                            Previous
                        </button>

                        <button
                            @click="nextQuestion"
                            :disabled="currentIndex === attempt.exam.questions.length - 1"
                            class="bg-blue-600 text-white px-5 py-2 rounded-xl disabled:opacity-50"
                        >
                            Next
                        </button>
                    </div>
                </div>

            </div>

            <!-- Question Palette Sidebar -->
            <div class="bg-white rounded-2xl shadow p-5 h-fit sticky top-6">
                <h3 class="font-bold mb-4">
                    Question Palette
                </h3>

                <div class="grid grid-cols-5 gap-2 mb-6">
                    <button
                        v-for="(question, index) in attempt.exam.questions"
                        :key="question.id"
                        @click="currentIndex = index"
                        class="w-10 h-10 rounded-lg font-medium"
                        :class="[
                            currentIndex === index
                                ? 'bg-blue-600 text-white'
                                : selectedAnswers[question.id]
                                    ? 'bg-green-500 text-white'
                                    : 'bg-slate-200'
                        ]"
                    >
                        {{ index + 1 }}
                    </button>
                </div>

                <button
                    @click="submitExam"
                    class="w-full bg-green-600 text-white py-3 rounded-xl font-medium"
                >
                    Submit Exam
                </button>
            </div>

        </div>
    </StudentLayout>
</template>
