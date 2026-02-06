<!-- resources/js/Components/AdminSidebar.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const props = defineProps({
  user: Object,
  navigation: Array,
  isMobile: Boolean,
});

const emit = defineEmits(['close']);

const page = usePage();
const isCollapsed = ref(false);

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
  if (props.isMobile) {
    emit('close');
  }
};

const isActive = (href) => {
  return page.url.startsWith(href);
};
</script>

<template>
  <aside
    class="flex flex-col transition-all duration-300 ease-in-out border-r border-gray-200 bg-white shadow-sm h-full"
    :class="isCollapsed ? 'w-16' : 'w-full'"
  >
    <!-- Header with Toggle -->
    <div class="flex items-center justify-between h-16 px-4 bg-gradient-to-r from-indigo-500 to-purple-600">
      <Link :href="route('admin.dashboard')" class="flex items-center">
        <ApplicationLogo class="h-6 w-auto text-white" />
        <span v-if="!isCollapsed" class="ml-3 text-white font-semibold text-sm">Admin Panel</span>
      </Link>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4" style="background-color: #f9fafb !important;">
      <ul class="space-y-1 px-2">
        <li v-for="item in navigation" :key="item.name" class="mb-2 last:mb-0 border-b border-gray-200">
          <Link
            :href="item.href"
            class="flex items-center w-full p-3 rounded-lg transition-all duration-200 group"
            :class="{
              'bg-indigo-100 text-indigo-700 border-r-2 border-indigo-500': isActive(item.href),
              'text-gray-600 hover:bg-gray-100 hover:text-gray-900': !isActive(item.href),
            }"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
            </svg>
            <span
              v-if="!isCollapsed"
              class="ml-3 text-sm font-medium whitespace-nowrap transition-opacity duration-300"
              :class="isActive(item.href) ? 'text-indigo-700' : 'text-gray-600 group-hover:text-gray-900'"
            >
              {{ item.name }}
            </span>
          </Link>
        </li>
      </ul>
    </nav>

    <!-- User Profile (Collapsible) -->
    <div class="p-3 border-t border-gray-200">
      <div class="flex items-center space-x-3 group">
        <img
          :src="user.avatar_url || '/images/avatare.png'"
          class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm"
        />
        <div v-if="!isCollapsed" class="flex-1 min-w-0">
          <p class="text-xs font-medium text-gray-800 truncate">{{ user.name }}</p>
          <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
        </div>
      </div>

      <div class="mt-3 flex justify-center border-t border-gray-200 pt-2">
         <button @click="toggleSidebar" class="text-gray-600 hover:text-indigo-600 w-full flex items-center justify-center focus:outline-none transition-colors duration-200 cursor-pointer">
        <svg v-if="isCollapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      </div>

      <!-- Logout Button -->
      <Link
        :href="route('logout')"
        method="post"
        as="button"
        class="mt-3 w-full flex items-center justify-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <span v-if="!isCollapsed" class="ml-2">Sign out</span>
      </Link>
    </div>
  </aside>
</template>