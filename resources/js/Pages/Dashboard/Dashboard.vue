<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  stats: Object,
  recentProjects: Array,
  recentMessages: Array,
});
</script>

<template>
  <Head title="Dashboard" />

  <div>
    <!-- Page Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
      <p class="text-gray-600">Manage your portfolio content and settings.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <!-- Services -->
      <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center">
          <div class="rounded-lg bg-blue-100 p-3">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M9 11a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Services</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.services }}</p>
          </div>
        </div>
      </div>

      <!-- Projects -->
      <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center">
          <div class="rounded-lg bg-green-100 p-3">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5.555 19.5l-.5 1.5h13l-.5-1.5" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Projects</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.projects }}</p>
          </div>
        </div>
      </div>

      <!-- Skills -->
      <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center">
          <div class="rounded-lg bg-purple-100 p-3">
            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Skills</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.skills }}</p>
          </div>
        </div>
      </div>

      <!-- Messages -->
      <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center">
          <div class="rounded-lg bg-amber-100 p-3">
            <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Messages</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.messages }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
      <!-- Recent Projects -->
      <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-800">Recent Projects</h2>
          <Link :href="route('admin.projects.index')" class="text-sm text-indigo-600 hover:underline">
            View all
          </Link>
        </div>
        <ul class="space-y-3">
          <li v-for="project in recentProjects" :key="project.id" class="flex items-center justify-between">
            <div>
              <span class="text-gray-700 font-medium">{{ project.title }}</span>
              <span class="block text-xs text-gray-500">{{ project.category }} · {{ project.updated_at }}</span>
            </div>
            <Link :href="project.edit_url" class="text-sm text-indigo-600 hover:underline">Edit</Link>
          </li>
          <li v-if="recentProjects.length === 0" class="text-gray-500 text-sm">No recent projects.</li>
        </ul>
      </div>

      <!-- Recent Messages -->
      <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-800">Recent Messages</h2>
          <Link :href="route('admin.messages.index')" class="text-sm text-indigo-600 hover:underline">
            View all
          </Link>
        </div>
        <ul class="space-y-3">
          <li v-for="message in recentMessages" :key="message.id" class="text-sm">
            <div class="font-medium text-gray-800">{{ message.name }}</div>
            <div class="text-gray-600">{{ message.email }}</div>
            <div class="text-gray-500 mt-1">{{ message.preview }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ message.created_at }}</div>
            <Link :href="message.view_url" class="text-indigo-600 hover:underline mt-1 inline-block">View</Link>
          </li>
          <li v-if="recentMessages.length === 0" class="text-gray-500 text-sm">No new messages.</li>
        </ul>
      </div>
    </div>
  </div>
</template>