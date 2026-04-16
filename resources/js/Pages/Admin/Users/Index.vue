<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'

defineProps({
    users: Object
})

const destroyUser = (id) => {
    if (confirm('Delete this user?')) {
        router.delete(`/admin/users/${id}`)
    }
}
</script>

<template>
<AdminLayout>
<div class="max-w-7xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                User Management
            </h1>

            <p class="text-slate-500">
                Manage platform users and roles.
            </p>
        </div>

        <Link
            href="/admin/users/create"
            class="bg-blue-600 text-white px-5 py-3 rounded-xl shadow"
        >
            + Create User
        </Link>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100">
                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Role</th>
                    <th class="p-4 text-left">Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="user in users.data"
                    :key="user.id"
                    class="border-t"
                >
                    <td class="p-4">{{ user.name }}</td>
                    <td class="p-4">{{ user.email }}</td>
                    <td class="p-4 capitalize">
                        {{ user.role?.name || '-' }}
                    </td>

                    <td class="p-4">
                        <div class="flex gap-4">

                            <Link
                                :href="`/admin/users/${user.id}/edit`"
                                class="text-blue-600"
                            >
                                Edit
                            </Link>

                            <button
                                @click="destroyUser(user.id)"
                                class="text-red-600"
                            >
                                Delete
                            </button>

                        </div>
                    </td>
                </tr>

                <tr v-if="users.data.length === 0">
                    <td colspan="4" class="p-6 text-center text-slate-500">
                        No users found.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
</AdminLayout>
</template>
