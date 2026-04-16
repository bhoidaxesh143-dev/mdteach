<script setup>
import { ref } from 'vue'
import AdminSidebar from '@/Components/AdminSidebar.vue'
import AdminNavbar from '@/Components/AdminNavbar.vue'

const sidebarOpen = ref(false)
</script>

<template>
<div class="h-screen flex overflow-hidden bg-slate-100">

    <!-- Sidebar -->
    <AdminSidebar
        :open="sidebarOpen"
        @close="sidebarOpen = false"
    />

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Navbar -->
        <AdminNavbar
            @toggle-sidebar="sidebarOpen = true"
        />

        <!-- Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <slot />
        </main>

    </div>

</div>
</template>
