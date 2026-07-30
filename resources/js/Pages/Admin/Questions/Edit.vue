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
    Image,
    ImageToolbar,
    ImageCaption,
    ImageStyle,
    ImageResize,
    ImageUpload,
    SimpleUploadAdapter,
    MediaEmbed,
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
| Advanced Editor Config
|--------------------------------------------------------------------------
*/
const advancedEditorConfig = {
    licenseKey: 'GPL',

    plugins: [
        Essentials, Paragraph, Heading, Bold, Italic, Underline,
        Strikethrough, Subscript, Superscript, Code, RemoveFormat,
        CKLink, List, TodoList, Alignment, BlockQuote, HorizontalLine,
        CodeBlock, Table, TableToolbar, TableProperties, TableCellProperties,
        Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize, ImageUpload,
        SimpleUploadAdapter, MediaEmbed, FontFamily, FontSize, FontColor,
        FontBackgroundColor, Highlight, Undo
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
        'insertTable','mediaEmbed','insertImage'
    ],

    simpleUpload: {
        uploadUrl: '/admin/upload-image',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json'
        },
        withCredentials: true
    },

    image: {
        toolbar: [
            'imageStyle:inline',
            'imageStyle:block',
            'imageStyle:side',
            '|',
            'toggleImageCaption',
            'imageTextAlternative'
        ]
    },

    table: {
        contentToolbar: [
            'tableColumn', 'tableRow', 'mergeTableCells',
            'tableProperties', 'tableCellProperties'
        ]
    }
}

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
const form = useForm({
    question: props.question.question,
    marks: props.question.marks,
    type: props.question.type ?? 'single_choice',
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
    if (form.type === 'single_choice') {
        form.options.forEach(option => {
            option.is_correct = option.id === selectedOption.id
        })
    }
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
<div class="max-w-6xl px-4 mx-auto">

    <div class="overflow-hidden bg-white border shadow-xl rounded-2xl">

        <!-- Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-indigo-600 to-blue-600">
            <div class="flex flex-col gap-4 md:flex-row md:justify-between md:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit Question</h1>
                    <p class="text-sm text-blue-100">Modify question details and correct answers</p>
                </div>

                <button
                    type="button"
                    @click="goBack"
                    class="px-4 py-2 text-white transition bg-white/20 hover:bg-white/30 rounded-xl"
                >
                    ← Back
                </button>
            </div>
        </div>

        <form @submit.prevent="submit" class="p-8 space-y-8">

            <!-- Question -->
            <div>
                <label class="block mb-3 font-semibold">Question</label>
                <div class="overflow-hidden border rounded-xl">
                    <ckeditor
                        :editor="editor"
                        v-model="form.question"
                        :config="advancedEditorConfig"
                    />
                </div>
                <p v-if="form.errors.question" class="mt-2 text-sm text-red-500">
                    {{ form.errors.question }}
                </p>
            </div>

            <!-- Marks -->
            <div>
                <label class="block mb-2 font-semibold">Marks</label>
                <input
                    type="number"
                    step="0.25"
                    min="0"
                    v-model="form.marks"
                    class="w-full px-4 py-3 border rounded-xl"
                />
                <p v-if="form.errors.marks" class="mt-2 text-sm text-red-500">
                    {{ form.errors.marks }}
                </p>
            </div>

            <!-- Options -->
            <div>
                <label class="block mb-4 font-semibold">Options</label>
                <p v-if="form.errors.options" class="mb-3 text-sm text-red-500">
                    {{ form.errors.options }}
                </p>

                <div class="space-y-4">
                    <div
                        v-for="option in form.options"
                        :key="option.id"
                        class="p-4 border rounded-2xl bg-slate-50"
                    >
                        <div class="flex items-start gap-4">
                            <div class="pt-2">
                                <input
                                    v-if="form.type === 'single_choice'"
                                    type="radio"
                                    :checked="option.is_correct"
                                    @change="setCorrectOption(option)"
                                    class="w-5 h-5 cursor-pointer"
                                />
                                <input
                                    v-else
                                    type="checkbox"
                                    v-model="option.is_correct"
                                    class="w-5 h-5 cursor-pointer"
                                />
                            </div>

                            <div class="flex-1 overflow-hidden bg-white border rounded-xl">
                                <ckeditor
                                    :editor="editor"
                                    v-model="option.option_text"
                                    :config="advancedEditorConfig"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Explanation -->
            <div>
                <label class="block mb-3 font-semibold">Explanation / Solution</label>
                <div class="overflow-hidden border rounded-xl">
                    <ckeditor
                        :editor="editor"
                        v-model="form.explanation"
                        :config="advancedEditorConfig"
                    />
                </div>
                <p v-if="form.errors.explanation" class="mt-2 text-sm text-red-500">
                    {{ form.errors.explanation }}
                </p>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-4 font-semibold text-white transition bg-gradient-to-r from-indigo-600 to-blue-600 rounded-xl disabled:opacity-50 hover:from-indigo-700 hover:to-blue-700"
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
