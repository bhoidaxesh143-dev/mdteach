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
    Link as CKLink,
    List,
    BlockQuote,
    Table,
    TableToolbar,
    FontColor,
    Highlight,
    Undo
} from 'ckeditor5'

const props = defineProps({
    topic: {
        type: Object,
        required: true
    }
})

/*
|--------------------------------------------------------------------------
| Original Data
|--------------------------------------------------------------------------
*/
const originalData = {
    content_html: props.topic.content?.content_html || '',
    shortcut_tips: props.topic.content?.shortcut_tips || '',
    video_url: props.topic.content?.video_url || '',
    diagram_url: props.topic.content?.diagram_url || '',
    estimated_read_minutes:
        props.topic.content?.estimated_read_minutes || 5
}

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
const form = useForm({
    ...originalData
})

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/
const goBack = () => {
    if (window.history.length > 1) {
        window.history.back()
    } else {
        router.visit('/admin/topic-content')
    }
}

/*
|--------------------------------------------------------------------------
| Reset Form
|--------------------------------------------------------------------------
*/
const resetForm = () => {
    Object.assign(form, originalData)
    form.clearErrors()
}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/
const submit = () => {
    form.put(`/admin/topic-content/${props.topic.id}`, {
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
|--------------------------------------------------------------------------
| Editor
|--------------------------------------------------------------------------
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
        CKLink,
        List,
        BlockQuote,
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
        'strikethrough',
        '|',
        'fontColor',
        'highlight',
        '|',
        'link',
        'bulletedList',
        'numberedList',
        '|',
        'blockQuote',
        'insertTable'
    ]
}
</script>

<template>
<AdminLayout>
<div class="max-w-6xl mx-auto px-4">

    <div class="bg-white rounded-2xl shadow-xl border overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

                <div>
                    <h1 class="text-2xl font-bold text-white">
                        Edit Theory Content
                    </h1>

                    <p class="text-blue-100 text-sm mt-1">
                        Manage theory, tips, media, and study information
                    </p>
                </div>

                <button
                    @click="goBack"
                    type="button"
                    class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-xl transition"
                >
                    ← Back
                </button>

            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="p-8 space-y-8">

            <!-- Topic Name -->
            <div class="bg-slate-50 rounded-xl p-4 border">
                <p class="text-sm text-slate-500 mb-1">
                    Editing Content For Topic
                </p>

                <h3 class="font-semibold text-slate-800 text-lg">
                    {{ topic.title }}
                </h3>
            </div>

            <!-- Theory Content -->
            <div>
                <label class="block mb-3 font-semibold">
                    Theory Content
                </label>

                <div class="border rounded-xl overflow-hidden">
                    <ckeditor
                        :editor="editor"
                        :config="editorConfig"
                        v-model="form.content_html"
                    />
                </div>

                <p
                    v-if="form.errors.content_html"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.content_html }}
                </p>
            </div>

            <!-- Shortcut Tips -->
            <div>
                <label class="block mb-2 font-semibold">
                    Shortcut Tips
                </label>

                <textarea
                    v-model="form.shortcut_tips"
                    rows="4"
                    class="w-full border rounded-xl p-4"
                    placeholder="Optional quick tips / mnemonics / tricks..."
                />

                <p
                    v-if="form.errors.shortcut_tips"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.shortcut_tips }}
                </p>
            </div>

            <!-- URLs -->
            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-2 font-semibold">
                        Video URL
                    </label>

                    <input
                        v-model="form.video_url"
                        type="url"
                        class="w-full border rounded-xl p-4"
                        placeholder="https://youtube.com/..."
                    />

                    <p
                        v-if="form.errors.video_url"
                        class="text-red-500 text-sm mt-2"
                    >
                        {{ form.errors.video_url }}
                    </p>
                </div>

                <div>
                    <label class="block mb-2 font-semibold">
                        Diagram URL
                    </label>

                    <input
                        v-model="form.diagram_url"
                        type="url"
                        class="w-full border rounded-xl p-4"
                        placeholder="https://example.com/image.png"
                    />

                    <p
                        v-if="form.errors.diagram_url"
                        class="text-red-500 text-sm mt-2"
                    >
                        {{ form.errors.diagram_url }}
                    </p>
                </div>

            </div>

            <!-- Read Time -->
            <div>
                <label class="block mb-2 font-semibold">
                    Estimated Read Minutes
                </label>

                <input
                    v-model="form.estimated_read_minutes"
                    type="number"
                    min="1"
                    max="999"
                    class="w-full border rounded-xl p-4"
                />

                <p
                    v-if="form.errors.estimated_read_minutes"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.estimated_read_minutes }}
                </p>
            </div>

            <!-- Actions -->
            <div class="grid md:grid-cols-2 gap-4">

                <button
                    type="button"
                    @click="resetForm"
                    class="border border-slate-300 py-3 rounded-xl font-semibold hover:bg-slate-50 transition"
                >
                    Reset Changes
                </button>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl font-semibold shadow hover:shadow-lg transition disabled:opacity-50"
                >
                    {{ form.processing ? 'Saving Content...' : 'Save Content' }}
                </button>

            </div>

        </form>

    </div>

</div>
</AdminLayout>
</template>

<style>
.ck-editor__editable {
    min-height: 300px;
}
</style>
