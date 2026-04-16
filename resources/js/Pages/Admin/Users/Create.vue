<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

defineProps({
    roles: Array
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_id: ''
})

const submit = () => form.post('/admin/users')
</script>

<template>
<AdminLayout>
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow p-8">

    <h1 class="text-3xl font-bold mb-6">
        Create User
    </h1>

    <form @submit.prevent="submit" class="space-y-5">

        <input
            v-model="form.name"
            placeholder="Full Name"
            class="w-full border rounded-xl px-4 py-3"
        />

        <input
            v-model="form.email"
            placeholder="Email Address"
            class="w-full border rounded-xl px-4 py-3"
        />

        <select
            v-model="form.role_id"
            class="w-full border rounded-xl px-4 py-3"
        >
            <option value="">Select Role</option>

            <option
                v-for="role in roles"
                :key="role.id"
                :value="role.id"
            >
                {{ role.name }}
            </option>
        </select>

        <input
            v-model="form.password"
            type="password"
            placeholder="Password"
            class="w-full border rounded-xl px-4 py-3"
        />

        <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirm Password"
            class="w-full border rounded-xl px-4 py-3"
        />

        <button
            class="bg-blue-600 text-white px-6 py-3 rounded-xl"
        >
            Save User
        </button>

    </form>

</div>
</AdminLayout>
</template>
