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
    MediaEmbed,
    FontFamily,
    FontSize,
    FontColor,
    FontBackgroundColor,
    Highlight,
    SimpleUploadAdapter,
    Undo
} from 'ckeditor5'

const props = defineProps({
    topic: Object
})

/*
|------------------------------------------------------------------
| SAFE DATA (NO CRASH EVER)
|------------------------------------------------------------------
*/
const topicData = props.topic ?? {}
const content = topicData?.content ?? {}

/*
|------------------------------------------------------------------
| FORM
|------------------------------------------------------------------
*/
const form = useForm({
    content_html: content?.content_html ?? '',
    shortcut_tips: content?.shortcut_tips ?? '',
    video_url: content?.video_url ?? '',
    diagram_url: content?.diagram_url ?? '',
    estimated_read_minutes: content?.estimated_read_minutes ?? 5
})

/*
|------------------------------------------------------------------
| ACTIONS
|------------------------------------------------------------------
*/
const goBack = () => {
    window.history.length > 1
        ? window.history.back()
        : router.visit('/admin/topic-content')
}

const resetForm = () => {
    form.content_html = content?.content_html ?? ''
    form.shortcut_tips = content?.shortcut_tips ?? ''
    form.video_url = content?.video_url ?? ''
    form.diagram_url = content?.diagram_url ?? ''
    form.estimated_read_minutes = content?.estimated_read_minutes ?? 5
    form.clearErrors()
}

const submit = () => {
    if (!topicData?.id) return // ✅ prevent crash

    form.put(`/admin/topic-content/${topicData.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Saved Successfully',
                text: 'Topic content updated successfully.',
                timer: 2000,
                showConfirmButton: false
            })
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

/*
|------------------------------------------------------------------
| EDITOR
|------------------------------------------------------------------
*/
const editor = ClassicEditor

const editorConfig = {
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
        Undo,
        SimpleUploadAdapter
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
        'insertImage',
        'mediaEmbed',
        'insertTable'
    ],
simpleUpload: {
    uploadUrl: '/admin/upload-image',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        'Accept': 'application/json'
    },
    withCredentials: true // ✅ IMPORTANT
},

    mediaEmbed: {
        previewsInData: true
    }
}
</script>

<template>
    <AdminLayout>
<div v-if="topicData?.id" class="max-w-6xl px-4 mx-auto">
        <div class="max-w-6xl px-4 mx-auto">

            <div class="overflow-hidden bg-white border shadow-xl rounded-2xl">

                <!-- Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-blue-600 to-indigo-600">
                    <div class="flex flex-col gap-4 md:flex-row md:justify-between md:items-center">

                        <div>
                            <h1 class="text-2xl font-bold text-white">
                                Edit Theory Content
                            </h1>

                            <p class="mt-1 text-sm text-blue-100">
                                Manage theory, tips, media, and study information
                            </p>
                        </div>

                        <button @click="goBack" type="button"
                            class="px-4 py-2 text-white transition bg-white/20 hover:bg-white/30 rounded-xl">
                            ← Back
                        </button>

                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="p-8 space-y-8">

                    <!-- Topic Name -->
                    <div class="p-4 border bg-slate-50 rounded-xl">
                        <p class="mb-1 text-sm text-slate-500">
                            Editing Content For Topic
                        </p>

                        <h3 class="text-lg font-semibold text-slate-800">
                            {{ topic.title }}
                        </h3>
                    </div>

                    <!-- Theory Content -->
                    <div>
                        <label class="block mb-3 font-semibold">
                            Theory Content
                        </label>

                        <div class="overflow-hidden border rounded-xl">
                           <ckeditor
        :editor="editor"
        v-model="form.content_html"
        :config="editorConfig"
    />
                        </div>

                        <p v-if="form.errors.content_html" class="mt-2 text-sm text-red-500">
                            {{ form.errors.content_html }}
                        </p>
                    </div>

                    <!-- Shortcut Tips -->
                    <div>
<label class="block mb-3 font-semibold">
    Shortcut Tips (Rich Content)
</label>
<div class="overflow-hidden border rounded-xl">
    <ckeditor
        :editor="editor"
        v-model="form.shortcut_tips"
        :config="editorConfig"
    />
</div>

                        <p v-if="form.errors.shortcut_tips" class="mt-2 text-sm text-red-500">
                            {{ form.errors.shortcut_tips }}
                        </p>
                    </div>

                    <!-- URLs -->
                    <div class="grid gap-6 md:grid-cols-2">

                        <div>
                            <label class="block mb-2 font-semibold">
                                Video URL
                            </label>

                            <input v-model="form.video_url" type="url" class="w-full p-4 border rounded-xl"
                                placeholder="https://youtube.com/..." />

                            <p v-if="form.errors.video_url" class="mt-2 text-sm text-red-500">
                                {{ form.errors.video_url }}
                            </p>
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">
                                Diagram URL
                            </label>

                            <input v-model="form.diagram_url" type="url" class="w-full p-4 border rounded-xl"
                                placeholder="https://example.com/image.png" />

                            <p v-if="form.errors.diagram_url" class="mt-2 text-sm text-red-500">
                                {{ form.errors.diagram_url }}
                            </p>
                        </div>

                    </div>

                    <!-- Read Time -->
                    <div>
                        <label class="block mb-2 font-semibold">
                            Estimated Read Minutes
                        </label>

                        <input v-model="form.estimated_read_minutes" type="number" min="1" max="999"
                            class="w-full p-4 border rounded-xl" />

                        <p v-if="form.errors.estimated_read_minutes" class="mt-2 text-sm text-red-500">
                            {{ form.errors.estimated_read_minutes }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="grid gap-4 md:grid-cols-2">

                        <button type="button" @click="resetForm"
                            class="py-3 font-semibold transition border border-slate-300 rounded-xl hover:bg-slate-50">
                            Reset Changes
                        </button>

                        <button type="submit" :disabled="form.processing"
                            class="py-3 font-semibold text-white transition shadow bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:shadow-lg disabled:opacity-50">
                            {{ form.processing ? 'Saving Content...' : 'Save Content' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>
        </div>
        <div v-else class="p-10 text-center text-gray-500">
    Loading...
</div>
    </AdminLayout>
</template>

<style>
.ck-editor__editable {
    min-height: 300px;
}
</style>
