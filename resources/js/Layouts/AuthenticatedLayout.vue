<!-- resources/js/Layouts/AuthenticatedLayout.vue -->
<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AdminSidebar from '@/Components/AdminSidebar.vue';

const page = usePage();
const { user } = page.props.auth;
const mobileSidebarOpen = ref(false);

const closeMobileSidebar = () => {
  mobileSidebarOpen.value = false;
};

const navigation = [
  { name: 'Dashboard', href: route('admin.dashboard'), icon: 'M3 13v-8h18v8M3 21h18M3 9l9-7 9 7' },
  { name: 'Portfolio', href: route('admin.portfolio.index'), icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
  { name: 'Projects', href: route('admin.projects.index'), icon: 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z' },
  { name: 'Messages', href: route('admin.messages.index'), icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
];
</script>

<template>
  <div class="flex h-screen bg-gray-50 overflow-hidden">
    <!-- Desktop Sidebar -->
    <div class="hidden md:block">
      <AdminSidebar :user="user" :navigation="navigation" />
    </div>

    <!-- Mobile Sidebar -->
    <div v-if="mobileSidebarOpen" class="md:hidden fixed inset-0 z-50">
      <div 
        class="fixed inset-0 bg-black bg-opacity-50" 
        @click="closeMobileSidebar"
      ></div>
      <div class="relative h-full bg-white shadow-2xl w-80 max-w-[80vw]">
        <button
          @click="closeMobileSidebar"
          class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 z-10"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <AdminSidebar :user="user" :navigation="navigation" :is-mobile="true" @close="closeMobileSidebar" />
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-1 flex-col overflow-hidden">
      <!-- Top Bar -->
      <header class="flex h-16 items-center justify-between px-4 bg-white shadow-sm md:px-6">
        <button
          @click="mobileSidebarOpen = true"
          class="md:hidden text-gray-500 hover:text-gray-700"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        
        <div class="flex items-center space-x-3">
          <img
            :src="user.avatar_url || '/images/default-avatar.png'"
            class="h-8 w-8 rounded-full object-cover"
          />
          <span class="hidden sm:block text-sm font-medium text-gray-700">{{ user.name }}</span>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6">
        <slot />
      </main>
    </div>
  </div>
</template>