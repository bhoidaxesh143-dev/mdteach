<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const emit = defineEmits(['toggle-sidebar'])

const user = usePage().props.auth.user
</script>

<template>
    <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b px-4 sm:px-6 py-4 flex items-center justify-between">

        <div class="flex items-center gap-4">
            <button
                @click="emit('toggle-sidebar')"
                class="lg:hidden text-slate-700 text-2xl"
            >
                ☰
            </button>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-800">
                    Admin Dashboard
                </h2>
                <p class="text-xs sm:text-sm text-slate-500">
                    Welcome back, {{ user.name }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button class="relative p-2 rounded-xl hover:bg-slate-100">
                🔔
                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1">
                    3
                </span>
            </button>

            <div class="hidden sm:block text-right">
                <p class="font-semibold text-sm">{{ user.name }}</p>
                <p class="text-xs text-slate-500">Administrator</p>
            </div>

            <Link
                href="/logout"
                method="post"
                as="button"
                class="bg-red-600 text-white px-4 py-2 rounded-xl"
            >
                Logout
            </Link>
        </div>

    </header>
</template>
