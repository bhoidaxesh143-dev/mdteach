<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const sidebarOpen = ref(false)

const isActive = (url) => page.url.startsWith(url)

const activeClass =
    'flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow'

const normalClass =
    'flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition'
</script>

<template>
<div class="h-screen flex overflow-hidden bg-slate-100">

    <!-- Mobile Overlay -->
    <div
        v-if="sidebarOpen"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden"
        @click="sidebarOpen = false"
    />

    <!-- Sidebar -->
    <aside
        :class="[
            'fixed lg:relative inset-y-0 left-0 z-50 w-72 h-screen bg-slate-900 text-white flex flex-col transform transition duration-300',
            sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
        ]"
    >
        <!-- Logo -->
        <div class="p-6 border-b border-slate-800 shrink-0">
            <h1 class="text-2xl font-bold tracking-wide">
                Student Portal
            </h1>

            <p class="text-xs text-slate-400 mt-1">
                Examination Dashboard
            </p>
        </div>

        <!-- Menu -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-2">

            <Link
                href="/student/dashboard"
                :class="isActive('/student/dashboard') ? activeClass : normalClass"
            >
                📊 Dashboard
            </Link>

            <Link
                href="/student/my-exams"
                :class="isActive('/student/my-exams') ? activeClass : normalClass"
            >
                📝 My Exams
            </Link>

     <Link
    href="/student/results"
    :class="isActive('/student/results') ? activeClass : normalClass"
>
    📈 Results
</Link>

            <Link
                href="/student/learning"
                :class="isActive('/student/learning') ? activeClass : normalClass"
            >
                📚 Learning Hub
            </Link>

            <Link
                href="/student/revision-dashboard"
                :class="isActive('/student/revision-dashboard') ? activeClass : normalClass"
            >
                🔁 Revision Dashboard
            </Link>

        </nav>

        <!-- Footer -->
        <div class="p-4 border-t border-slate-800 text-xs text-slate-400 shrink-0">
            Student Portal v1.0
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        <!-- Navbar -->
        <header
            class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b px-4 sm:px-6 py-4 flex justify-between items-center shadow-sm"
        >
            <div class="flex items-center gap-4">

                <button
                    @click="sidebarOpen = true"
                    class="lg:hidden text-2xl"
                >
                    ☰
                </button>

                <div>
                    <h2 class="text-xl font-bold text-slate-800">
                        Student Dashboard
                    </h2>

                    <p class="text-sm text-slate-500">
                        Welcome back
                    </p>
                </div>

            </div>

            <Link
                href="/logout"
                method="post"
                as="button"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl shadow"
            >
                Logout
            </Link>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <slot />
        </main>

    </div>

</div>
</template>
