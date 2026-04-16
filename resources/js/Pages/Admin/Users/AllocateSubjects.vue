<script setup>
import { ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    students: Array,
    subjects: Array,
    selectedStudentId: [String, Number],
    allocatedSubjectIds: Array
})

/*
|--------------------------------------------------------------------------
| Selected Student
|--------------------------------------------------------------------------
*/
const selectedStudent = ref(
    props.selectedStudentId
        ? Number(props.selectedStudentId)
        : ''
)

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
const form = useForm({
    student_id: props.selectedStudentId
        ? Number(props.selectedStudentId)
        : '',

    subject_ids: (props.allocatedSubjectIds || []).map(Number)
})

/*
|--------------------------------------------------------------------------
| Reload Allocations When Student Changes
|--------------------------------------------------------------------------
*/
watch(selectedStudent, (studentId) => {
    if (!studentId) return

    router.get(
        '/admin/student-subject-allocation',
        {
            student_id: studentId
        },
        {
            preserveScroll: true,
            preserveState: false
        }
    )
})

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/
const submit = () => {
    form.student_id = Number(selectedStudent.value)

    form.transform((data) => ({
        ...data,
        subject_ids: data.subject_ids.map(Number)
    })).post('/admin/student-subject-allocation')
}
</script>

<template>
    <AdminLayout>
        <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow p-8">

            <h1 class="text-3xl font-bold mb-6">
                Allocate Subjects
            </h1>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Student Select -->
                <div>
                    <label class="block font-medium mb-2">
                        Select Student
                    </label>

                    <select
                        v-model="selectedStudent"
                        class="w-full border rounded-xl px-4 py-3"
                    >
                        <option value="">
                            Choose Student
                        </option>

                        <option
                            v-for="student in students"
                            :key="student.id"
                            :value="Number(student.id)"
                        >
                            {{ student.name }}
                        </option>
                    </select>
                </div>

                <!-- Subjects -->
                <div v-if="selectedStudent">
                    <label class="block font-medium mb-3">
                        Assign Subjects
                    </label>

                    <div class="grid md:grid-cols-2 gap-4">
                        <label
                            v-for="subject in subjects"
                            :key="subject.id"
                            class="flex items-center gap-3 border rounded-xl p-4 hover:bg-slate-50 cursor-pointer"
                        >
                            <input
                                type="checkbox"
                                :value="Number(subject.id)"
                                v-model="form.subject_ids"
                                class="w-5 h-5"
                            />

                            {{ subject.name }}
                        </label>
                    </div>
                </div>

                <!-- Debug -->
                <!-- <pre>{{ form.subject_ids }}</pre> -->

                <!-- Errors -->
                <div
                    v-if="form.hasErrors"
                    class="text-red-600 text-sm space-y-1"
                >
                    <div
                        v-for="(error, key) in form.errors"
                        :key="key"
                    >
                        {{ error }}
                    </div>
                </div>

                <!-- Submit -->
                <button
                    v-if="selectedStudent"
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl"
                    :disabled="form.processing"
                >
                    {{
                        form.processing
                            ? 'Saving...'
                            : 'Save Allocation'
                    }}
                </button>

            </form>
        </div>
    </AdminLayout>
</template>
