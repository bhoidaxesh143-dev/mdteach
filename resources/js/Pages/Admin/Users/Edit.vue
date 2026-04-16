<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    user: Object,
    roles: Array
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role_id: props.user.role_id
})

const submit = () => {
    form.put(`/admin/users/${props.user.id}`)
}
</script>

<template>
<AdminLayout>
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow p-8">

    <h1 class="text-3xl font-bold mb-6">
        Edit User
    </h1>

    <form @submit.prevent="submit" class="space-y-5">

        <input
            v-model="form.name"
            class="w-full border rounded-xl px-4 py-3"
        />

        <input
            v-model="form.email"
            class="w-full border rounded-xl px-4 py-3"
        />

        <select
            v-model="form.role_id"
            class="w-full border rounded-xl px-4 py-3"
        >
            <option
                v-for="role in roles"
                :key="role.id"
                :value="role.id"
            >
                {{ role.name }}
            </option>
        </select>

        <button
            class="bg-blue-600 text-white px-6 py-3 rounded-xl"
        >
            Update User
        </button>

    </form>

</div>
</AdminLayout>
</template>
