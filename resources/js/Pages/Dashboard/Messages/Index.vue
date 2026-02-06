<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  messages: Object,
});
</script>

<template>
  <Head title="Messages" />

  <div>
    <!-- Page Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Messages</h1>
      <p class="text-gray-600">Manage contact form submissions.</p>
    </div>

    <!-- Messages Table -->
    <div class="rounded-lg bg-white shadow">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Message
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="message in messages.data" :key="message.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ message.name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ message.email }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ message.preview }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ message.created_at }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <Link :href="route('admin.messages.show', message)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                  View
                </Link>
                <button 
                  @click="deleteMessage(message)" 
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
            <tr v-if="messages.data.length === 0">
              <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                No messages found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div v-if="messages.links" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
          <Link 
            v-if="messages.prev_page_url" 
            :href="messages.prev_page_url" 
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Previous
          </Link>
          <Link 
            v-if="messages.next_page_url" 
            :href="messages.next_page_url" 
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Next
          </Link>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ messages.from || 0 }}</span>
              to
              <span class="font-medium">{{ messages.to || 0 }}</span>
              of
              <span class="font-medium">{{ messages.total }}</span>
              results
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
              <template v-for="link in messages.links" :key="link.label">
                <Link 
                  v-if="link.url && !link.active" 
                  :href="link.url"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  v-html="link.label"
                />
                <span 
                  v-else-if="link.active"
                  class="relative inline-flex items-center px-4 py-2 border border-indigo-500 text-sm font-medium rounded-md bg-indigo-50 text-indigo-600"
                  v-html="link.label"
                />
                <span 
                  v-else
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-100 cursor-not-allowed"
                  v-html="link.label"
                />
              </template>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3';

export default {
  methods: {
    deleteMessage(message) {
      if (confirm('Are you sure you want to delete this message?')) {
        router.delete(route('admin.messages.destroy', message), {
          onSuccess: () => {
            // Success message will be shown via flash message
          }
        });
      }
    }
  }
}
</script>
