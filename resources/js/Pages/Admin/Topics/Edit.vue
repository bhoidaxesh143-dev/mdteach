<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
    topic: {
        type: Object,
        required: true
    },
    chapters: {
        type: Array,
        required: true
    },
    parents: {
        type: Array,
        required: true
    }
})

/*
|--------------------------------------------------------------------------
| Original Data
|--------------------------------------------------------------------------
*/
const originalData = {
    chapter_id: props.topic.chapter_id ?? '',
    parent_id: props.topic.parent_id ?? '',
    title: props.topic.title ?? '',
    description: props.topic.description ?? '',
    sort_order: props.topic.sort_order ?? 0
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
        router.visit('/admin/topics')
    }
}

/*
|--------------------------------------------------------------------------
| Reset
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
    form.put(`/admin/topics/${props.topic.id}`, {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Topic Updated',
                text: 'Topic updated successfully.',
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
</script>

<template>
<AdminLayout>
<div class="max-w-4xl mx-auto px-4">

    <div class="bg-white rounded-2xl shadow-xl border overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

                <div>
                    <h1 class="text-2xl font-bold text-white">
                        Edit Topic
                    </h1>

                    <p class="text-blue-100 text-sm mt-1">
                        Update topic details and hierarchy
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
        <form @submit.prevent="submit" class="p-8 space-y-6">

            <!-- Chapter -->
            <div>
                <label class="block mb-2 font-semibold">
                    Chapter
                </label>

                <select
                    v-model="form.chapter_id"
                    class="w-full border rounded-xl p-3"
                >
                    <option value="">Select Chapter</option>

                    <option
                        v-for="chapter in chapters"
                        :key="chapter.id"
                        :value="chapter.id"
                    >
                        {{ chapter.title }}
                    </option>
                </select>

                <p
                    v-if="form.errors.chapter_id"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.chapter_id }}
                </p>
            </div>

            <!-- Parent Topic -->
            <div>
                <label class="block mb-2 font-semibold">
                    Parent Topic
                </label>

                <select
                    v-model="form.parent_id"
                    class="w-full border rounded-xl p-3"
                >
                    <option value="">No Parent</option>

                    <option
                        v-for="parent in parents"
                        :key="parent.id"
                        :value="parent.id"
                    >
                        {{ parent.title }}
                    </option>
                </select>

                <p class="text-xs text-slate-500 mt-2">
                    Optional: Choose parent for nested topic hierarchy.
                </p>

                <p
                    v-if="form.errors.parent_id"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.parent_id }}
                </p>
            </div>

            <!-- Title -->
            <div>
                <label class="block mb-2 font-semibold">
                    Topic Title
                </label>

                <input
                    v-model="form.title"
                    type="text"
                    class="w-full border rounded-xl p-3"
                    placeholder="Enter topic title"
                />

                <p
                    v-if="form.errors.title"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.title }}
                </p>
            </div>

            <!-- Description -->
            <div>
                <label class="block mb-2 font-semibold">
                    Description
                </label>

                <textarea
                    v-model="form.description"
                    rows="4"
                    class="w-full border rounded-xl p-3"
                    placeholder="Optional topic description"
                />

                <p
                    v-if="form.errors.description"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.description }}
                </p>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block mb-2 font-semibold">
                    Sort Order
                </label>

                <input
                    type="number"
                    v-model="form.sort_order"
                    min="0"
                    class="w-full border rounded-xl p-3"
                />

                <p class="text-xs text-slate-500 mt-2">
                    Lower number appears first.
                </p>

                <p
                    v-if="form.errors.sort_order"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.sort_order }}
                </p>
            </div>

            <!-- Actions -->
            <div class="grid md:grid-cols-2 gap-4 pt-2">

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
                    class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-3 rounded-xl font-semibold shadow hover:shadow-lg transition disabled:opacity-50"
                >
                    {{ form.processing ? 'Updating Topic...' : 'Update Topic' }}
                </button>

            </div>

        </form>

    </div>

</div>
</AdminLayout>
</template>
