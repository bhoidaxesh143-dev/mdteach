<script setup>
import { Link, usePage } from '@inertiajs/vue3'

defineProps({
    open: Boolean
})

defineEmits(['close'])

const page = usePage()

const isActive = (url) => page.url.startsWith(url)

const linkClass = (url) =>
    isActive(url)
        ? 'flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition'
</script>

<template>
    <div>

        <!-- Mobile Overlay -->
        <div v-if="open" class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="$emit('close')" />

        <!-- Sidebar -->
        <aside :class="[
            'fixed lg:relative inset-y-0 left-0 z-50 w-72 h-screen shrink-0 bg-slate-900 text-white transform transition duration-300 flex flex-col border-r border-slate-800',
            open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
        ]">

            <!-- Header -->
            <div class="p-6 border-b border-slate-800 shrink-0">
                <h1 class="text-2xl font-bold tracking-wide">
                    EMS - MD
                </h1>

                <p class="text-xs text-slate-400 mt-1">
                    <!-- Enterprise Panel -->
                </p>
            </div>

            <!-- Scrollable Menu -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-6">

                <!-- Dashboard -->
                <div>
                    <p class="text-xs uppercase text-slate-500 px-4 mb-2">
                        Dashboard
                    </p>

                    <Link href="/admin/dashboard" :class="linkClass('/admin/dashboard')">
                        📊 Dashboard
                    </Link>
                </div>
                <!-- User Management -->
                <div>
                    <p class="text-xs uppercase text-slate-500 px-4 mb-2">
                        User Management
                    </p>
                    <Link href="/admin/users" :class="linkClass('/admin/users')">
                        👥 Users
                    </Link>
                    <Link
    href="/admin/student-subject-allocation"
    :class="linkClass('/admin/student-subject-allocation')"
>
    🎯 Subject Allocation
</Link>
                </div>

                <!-- Exam Management -->
                <div>
                    <p class="text-xs uppercase text-slate-500 px-4 mb-2">
                        Exam Management
                    </p>

                    <div class="space-y-1">
                        <Link href="/admin/exams" :class="linkClass('/admin/exams')">
                            📝 Exams
                        </Link>

                        <Link href="/admin/imports/questions" :class="linkClass('/admin/imports/questions')">
                            📥 Import Questions
                        </Link>

                        <Link href="/admin/imports/students" :class="linkClass('/admin/imports/students')">
                            👨‍🎓 Import Students
                        </Link>

                        <Link href="/admin/allocations/create" :class="linkClass('/admin/allocations')">
                            🎯 Allocations
                        </Link>

                        <Link href="/admin/reports" :class="linkClass('/admin/reports')">
                            📈 Reports
                        </Link>
                    </div>
                </div>

                <!-- Learning Hub -->
                <div>
                    <p class="text-xs uppercase text-slate-500 px-4 mb-2">
                        Learning Hub
                    </p>

                    <div class="space-y-1">
                        <Link href="/admin/subjects" :class="linkClass('/admin/subjects')">
                            📚 Subjects
                        </Link>

                        <Link href="/admin/chapters" :class="linkClass('/admin/chapters')">
                            📖 Chapters
                        </Link>

                        <Link href="/admin/topics" :class="linkClass('/admin/topics')">
                            🧠 Topics
                        </Link>

                        <Link href="/admin/topic-content" :class="linkClass('/admin/topic-content')">
                            📄 Theory Content
                        </Link>
                    </div>
                </div>

                <!-- System -->
                <div>
                    <p class="text-xs uppercase text-slate-500 px-4 mb-2">
                        System
                    </p>

                    <div class="space-y-1">
                        <Link href="/admin/settings" :class="linkClass('/admin/settings')">
                            ⚙️ Settings
                        </Link>

                        <Link href="/admin/logs" :class="linkClass('/admin/logs')">
                            📜 Logs
                        </Link>
                    </div>
                </div>

            </nav>

            <!-- Footer -->
            <div class="p-4 border-t border-slate-800 text-xs text-slate-400 shrink-0">
                EMS Pro v1.0
            </div>

        </aside>

    </div>
</template>
