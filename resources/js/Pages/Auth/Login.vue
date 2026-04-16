<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: '',
    remember: false
})

const submit = () => {
    form.post('/login')
}
</script>

<template>
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 flex items-center justify-center px-4">

    <div class="grid lg:grid-cols-2 bg-white rounded-3xl shadow-2xl overflow-hidden max-w-6xl w-full">

        <!-- Left Branding -->
        <div class="hidden lg:flex flex-col justify-center bg-gradient-to-br from-blue-700 to-indigo-800 text-white p-12">
            <h1 class="text-4xl font-bold mb-4">
                EMS Pro
            </h1>

            <p class="text-blue-100 text-lg leading-relaxed">
                Secure Government-Grade Examination Management System
                for students, administrators, and evaluators.
            </p>

            <div class="mt-10 space-y-4 text-sm text-blue-100">
                <div>✓ Secure Authentication</div>
                <div>✓ Real-Time Exam Monitoring</div>
                <div>✓ Learning & Revision Dashboard</div>
                <div>✓ Analytics & Reports</div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="p-8 sm:p-12">

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-slate-800">
                    Welcome Back
                </h2>

                <p class="text-slate-500 mt-2">
                    Login to continue to your dashboard
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">
                        Email Address
                    </label>

                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="Enter your email"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    />

                    <p
                        v-if="form.errors.email"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">
                        Password
                    </label>

                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="Enter your password"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    />

                    <p
                        v-if="form.errors.password"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <!-- Remember -->
                <label class="flex items-center gap-3 text-sm text-slate-600">
                    <input
                        type="checkbox"
                        v-model="form.remember"
                        class="rounded border-slate-300"
                    />
                    Remember Me
                </label>

                <!-- Button -->
                <button
                    :disabled="form.processing"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold shadow transition disabled:opacity-50"
                >
                    {{ form.processing ? 'Logging In...' : 'Login' }}
                </button>

            </form>

        </div>

    </div>

</div>
</template>
