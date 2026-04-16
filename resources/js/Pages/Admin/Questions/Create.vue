<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'

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
    Image,
    ImageToolbar,
    ImageCaption,
    ImageStyle,
    ImageResize,
    ImageUpload,
    MediaEmbed,
    FontFamily,
    FontSize,
    FontColor,
    FontBackgroundColor,
    Highlight,
    Undo
} from 'ckeditor5'

const props = defineProps({
    examId: Number,
    subjects: Array
})

const editor = ClassicEditor

/*
|--------------------------------------------------------------------------
| Question Editor Config (Minimal)
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
| Explanation Editor Config (Advanced)
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
        Image,
        ImageToolbar,
        ImageCaption,
        ImageStyle,
        ImageResize,
        ImageUpload,
        MediaEmbed,
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
        'insertTable','mediaEmbed'
    ],

    table: {
        contentToolbar: [
            'tableColumn',
            'tableRow',
            'mergeTableCells',
            'tableProperties',
            'tableCellProperties'
        ]
    }
}

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
const form = useForm({
    subject_id: '',
    question: '',
    type: 'single_choice',
    marks: 1,
    explanation: '',
    options: [
        { text: '', is_correct: false },
        { text: '', is_correct: false }
    ]
})

/*
|--------------------------------------------------------------------------
| Methods
|--------------------------------------------------------------------------
*/
const addOption = () => {
    form.options.push({
        text: '',
        is_correct: false
    })
}

const removeOption = (index) => {
    if (form.options.length <= 2) {
        Swal.fire({
            icon: 'warning',
            title: 'Minimum 2 Options Required'
        })
        return
    }

    form.options.splice(index, 1)
}

const handleCorrectSelection = (selectedIndex) => {
    if (form.type === 'single_choice') {
        form.options.forEach((option, index) => {
            option.is_correct = index === selectedIndex
        })
    }
}

const submit = () => {
    form.post(`/admin/exams/${props.examId}/questions`, {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Question Saved',
                text: 'Question created successfully.',
                timer: 2000,
                showConfirmButton: false
            })

            form.reset()

            form.options = [
                { text: '', is_correct: false },
                { text: '', is_correct: false }
            ]

            form.type = 'single_choice'
            form.marks = 1
        },

        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Validation Failed',
                text: 'Please fix highlighted fields.'
            })
        }
    })
}
</script>

<template>
<AdminLayout>
<div class="max-w-6xl px-4 mx-auto">

    <div class="overflow-hidden bg-white border shadow-xl rounded-2xl">

        <!-- Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="flex flex-col gap-4 md:flex-row md:justify-between md:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">
                        Add Question
                    </h1>
                    <p class="text-sm text-blue-100">
                        Create exam question with options and explanation
                    </p>
                </div>

                <Link
                    :href="`/admin/exams/${examId}/questions`"
                    class="px-4 py-2 text-white bg-white/20 rounded-xl"
                >
                    ← Back
                </Link>
            </div>
        </div>

        <form @submit.prevent="submit" class="p-8 space-y-8">

            <!-- Meta -->
            <div class="grid gap-6 md:grid-cols-3">

                <div>
                    <label class="block mb-2 font-semibold">Subject</label>
                    <select v-model="form.subject_id" class="w-full p-3 border rounded-xl">
                        <option value="">Select Subject</option>
                        <option
                            v-for="subject in subjects"
                            :key="subject.id"
                            :value="subject.id"
                        >
                            {{ subject.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.subject_id" class="mt-2 text-sm text-red-500">
                        {{ form.errors.subject_id }}
                    </p>
                </div>

                <div>
                    <label class="block mb-2 font-semibold">Question Type</label>
                    <select v-model="form.type" class="w-full p-3 border rounded-xl">
                        <option value="single_choice">Single Choice</option>
                        <option value="multiple_choice">Multiple Choice</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-semibold">Marks</label>
                    <input
                        v-model="form.marks"
                        type="number"
                        step="0.25"
                        class="w-full p-3 border rounded-xl"
                    />
                    <p v-if="form.errors.marks" class="mt-2 text-sm text-red-500">
                        {{ form.errors.marks }}
                    </p>
                </div>

            </div>

            <!-- Question -->
            <div>
                <label class="block mb-3 font-semibold">Question</label>

                <div class="overflow-hidden border rounded-xl">
                    <ckeditor
                        :editor="editor"
                        v-model="form.question"
                        :config="questionEditorConfig"
                    />
                </div>

                <p v-if="form.errors.question" class="mt-2 text-sm text-red-500">
                    {{ form.errors.question }}
                </p>
            </div>

            <!-- Options -->
            <div>
                <div class="flex justify-between mb-4">
                    <label class="font-semibold">Options</label>

                    <button
                        type="button"
                        @click="addOption"
                        class="px-4 py-2 text-white bg-blue-600 rounded-xl"
                    >
                        + Add Option
                    </button>
                </div>

                <p v-if="form.errors.options" class="mb-3 text-sm text-red-500">
                    {{ form.errors.options }}
                </p>

                <div class="space-y-4">
                    <div
                        v-for="(option, index) in form.options"
                        :key="index"
                        class="p-4 border rounded-2xl bg-slate-50"
                    >
                        <div class="flex items-center gap-4">

                            <input
                                v-model="option.text"
                                class="flex-1 p-3 border rounded-xl"
                                :placeholder="`Option ${index + 1}`"
                            />

                            <input
                                v-if="form.type === 'single_choice'"
                                type="radio"
                                :checked="option.is_correct"
                                @change="handleCorrectSelection(index)"
                            />

                            <input
                                v-else
                                type="checkbox"
                                v-model="option.is_correct"
                            />

                            <button
                                type="button"
                                @click="removeOption(index)"
                                class="text-lg font-bold text-red-600"
                            >
                                ✕
                            </button>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Explanation -->
            <div>
                <label class="block mb-3 font-semibold">
                    Explanation / Solution
                </label>

                <div class="overflow-hidden border rounded-xl">
                    <ckeditor
                        :editor="editor"
                        v-model="form.explanation"
                        :config="explanationEditorConfig"
                    />
                </div>

                <p v-if="form.errors.explanation" class="mt-2 text-sm text-red-500">
                    {{ form.errors.explanation }}
                </p>
            </div>

            <!-- Submit -->
            <button
                :disabled="form.processing"
                class="w-full py-4 font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl disabled:opacity-50"
            >
                {{ form.processing ? 'Saving Question...' : 'Save Question' }}
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
