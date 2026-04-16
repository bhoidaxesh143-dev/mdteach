<script setup>
import { useForm } from '@inertiajs/vue3'

defineProps({
    roles: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_id: ''
})

const submit = () => {
    form.post('/register')
}
</script>

<template>
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <form
        @submit.prevent="submit"
        class="bg-white p-8 rounded-xl shadow w-full max-w-md"
    >
        <h2 class="text-2xl font-bold mb-6">
            Register User
        </h2>

        <input
            v-model="form.name"
            placeholder="Name"
            class="w-full border p-3 rounded mb-3"
        />

        <input
            v-model="form.email"
            placeholder="Email"
            class="w-full border p-3 rounded mb-3"
        />

        <!-- Role Select -->
        <select
            v-model="form.role_id"
            class="w-full border p-3 rounded mb-3"
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
            class="w-full border p-3 rounded mb-3"
        />

        <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirm Password"
            class="w-full border p-3 rounded mb-4"
        />

        <button
            class="w-full bg-green-600 text-white p-3 rounded"
        >
            Register
        </button>
    </form>
</div>
</template>
