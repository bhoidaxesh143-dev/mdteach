<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

import {
    ClassicEditor,
    Essentials,
    Paragraph,
    Heading,
    Bold,
    Italic,
    Underline,
    Strikethrough,
    Subscript,
    Superscript,
    Code,
    RemoveFormat,
    Link as CKLink,
    List,
    TodoList,
    Alignment,
    BlockQuote,
    HorizontalLine,
    CodeBlock,
    Table,
    TableToolbar,
    TableProperties,
    TableCellProperties,
    FontFamily,
    FontSize,
    FontColor,
    FontBackgroundColor,
    Highlight,
    Undo
} from 'ckeditor5'

const props = defineProps({
    question: {
        type: Object,
        required: true
    }
})

const editor = ClassicEditor

/*
|--------------------------------------------------------------------------
| Question Editor Config
|--------------------------------------------------------------------------
*/
const questionEditorConfig = {
    licenseKey: 'GPL',

    plugins: [
        Essentials,
        Paragraph,
        Heading,
        Bold,
        Italic,
        Underline,
        CKLink,
        List,
        Table,
        TableToolbar,
        FontColor,
        Highlight,
        Undo
    ],

    toolbar: [
        'undo',
        'redo',
        '|',
        'heading',
        '|',
        'bold',
        'italic',
        'underline',
        '|',
        'fontColor',
        'highlight',
        '|',
        'bulletedList',
        'numberedList',
        '|',
        'insertTable'
    ]
}

/*
|--------------------------------------------------------------------------
| Explanation Editor Config
|--------------------------------------------------------------------------
*/
const explanationEditorConfig = {
    licenseKey: 'GPL',

    plugins: [
        Essentials,
        Paragraph,
        Heading,
        Bold,
        Italic,
        Underline,
        Strikethrough,
        Subscript,
        Superscript,
        Code,
        RemoveFormat,
        CKLink,
        List,
        TodoList,
        Alignment,
        BlockQuote,
        HorizontalLine,
        CodeBlock,
        Table,
        TableToolbar,
        TableProperties,
        TableCellProperties,
        FontFamily,
        FontSize,
        FontColor,
        FontBackgroundColor,
        Highlight,
        Undo
    ],

    toolbar: [
        'undo','redo','|',
        'heading','|',
        'fontFamily','fontSize','|',
        'fontColor','fontBackgroundColor','highlight','|',
        'bold','italic','underline','strikethrough','|',
        'subscript','superscript','code','removeFormat','|',
        'link','bulletedList','numberedList','todoList','|',
        'alignment','|',
        'blockQuote','horizontalLine','codeBlock','|',
        'insertTable'
    ]
}

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
const form = useForm({
    question: props.question.question,
    marks: props.question.marks,
    explanation: props.question.explanation ?? '',

    options: props.question.options.map(option => ({
        id: option.id,
        option_text: option.option_text,
        is_correct: option.is_correct
    }))
})

/*
|--------------------------------------------------------------------------
| Methods
|--------------------------------------------------------------------------
*/
const setCorrectOption = (selectedOption) => {
    form.options.forEach(option => {
        option.is_correct = option.id === selectedOption.id
    })
}

const goBack = () => {
    if (window.history.length > 1) {
        window.history.back()
    } else {
        router.visit(`/admin/exams/${props.question.exam_id}/questions`)
    }
}

const submit = () => {
    form.put(`/admin/questions/${props.question.id}`, {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Question Updated',
                text: 'Question updated successfully.',
                timer: 2000,
                showConfirmButton: false
            })
        },

        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Validation Failed',
                text: 'Please fix the highlighted errors.'
            })
        }
    })
}
</script>

<template>
<AdminLayout>
<div class="max-w-6xl mx-auto px-4">

    <div class="bg-white rounded-2xl shadow-xl border overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

                <div>
                    <h1 class="text-2xl font-bold text-white">
                        Edit Question
                    </h1>

                    <p class="text-blue-100 text-sm">
                        Modify question details and correct answers
                    </p>
                </div>

                <button
                    type="button"
                    @click="goBack"
                    class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-xl transition"
                >
                    ← Back
                </button>

            </div>
        </div>

        <form @submit.prevent="submit" class="p-8 space-y-8">

            <!-- Question -->
            <div>
                <label class="font-semibold block mb-3">
                    Question
                </label>

                <div class="border rounded-xl overflow-hidden">
                    <ckeditor
                        :editor="editor"
                        v-model="form.question"
                        :config="questionEditorConfig"
                    />
                </div>

                <p v-if="form.errors.question" class="text-red-500 text-sm mt-2">
                    {{ form.errors.question }}
                </p>
            </div>

            <!-- Marks -->
            <div>
                <label class="font-semibold block mb-2">
                    Marks
                </label>

                <input
                    type="number"
                    step="0.25"
                    min="0"
                    v-model="form.marks"
                    class="w-full border rounded-xl px-4 py-3"
                />

                <p v-if="form.errors.marks" class="text-red-500 text-sm mt-2">
                    {{ form.errors.marks }}
                </p>
            </div>

            <!-- Options -->
            <div>
                <label class="font-semibold block mb-4">
                    Options
                </label>

                <p v-if="form.errors.options" class="text-red-500 text-sm mb-3">
                    {{ form.errors.options }}
                </p>

                <div class="space-y-4">
                    <div
                        v-for="option in form.options"
                        :key="option.id"
                        class="border rounded-2xl p-4 bg-slate-50"
                    >
                        <div class="flex gap-4 items-center">

                            <input
                                type="radio"
                                :checked="option.is_correct"
                                @change="setCorrectOption(option)"
                            />

                            <input
                                v-model="option.option_text"
                                class="flex-1 border rounded-xl px-4 py-3"
                                placeholder="Option Text"
                            />

                        </div>
                    </div>
                </div>
            </div>

            <!-- Explanation -->
            <div>
                <label class="font-semibold block mb-3">
                    Explanation / Solution
                </label>

                <div class="border rounded-xl overflow-hidden">
                    <ckeditor
                        :editor="editor"
                        v-model="form.explanation"
                        :config="explanationEditorConfig"
                    />
                </div>

                <p v-if="form.errors.explanation" class="text-red-500 text-sm mt-2">
                    {{ form.errors.explanation }}
                </p>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-4 rounded-xl font-semibold disabled:opacity-50"
            >
                {{ form.processing ? 'Updating Question...' : 'Update Question' }}
            </button>

        </form>

    </div>

</div>
</AdminLayout>
</template>

<style>
.ck-editor__editable {
    min-height: 180px;
}
</style>
