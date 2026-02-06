<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  message: Object,
});
</script>

<template>
  <Head title="Message Details" />

  <div>
    <!-- Page Header -->
    <div class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Message Details</h1>
          <p class="text-gray-600">View contact form submission.</p>
        </div>
        <div class="flex space-x-3">
          <Link 
            :href="route('admin.messages.index')" 
            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
          >
            Back to Messages
          </Link>
          <button 
            @click="deleteMessage" 
            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700"
          >
            Delete Message
          </button>
        </div>
      </div>
    </div>

    <!-- Message Content -->
    <div class="rounded-lg bg-white shadow">
      <div class="p-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <!-- From -->
          <div>
            <h3 class="text-sm font-medium text-gray-500">From</h3>
            <p class="mt-1 text-sm text-gray-900">{{ message.name }}</p>
          </div>
          
          <!-- Email -->
          <div>
            <h3 class="text-sm font-medium text-gray-500">Email</h3>
            <p class="mt-1 text-sm text-gray-900">{{ message.email }}</p>
          </div>
          
          <!-- Date -->
          <div>
            <h3 class="text-sm font-medium text-gray-500">Date Received</h3>
            <p class="mt-1 text-sm text-gray-900">{{ message.created_at }}</p>
          </div>
        </div>
        
        <!-- Message -->
        <div class="mt-6">
          <h3 class="text-sm font-medium text-gray-500">Message</h3>
          <div class="mt-1 p-4 bg-gray-50 rounded-md">
            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ message.content }}</p>
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
    deleteMessage() {
      if (confirm('Are you sure you want to delete this message?')) {
        router.delete(route('admin.messages.destroy', this.message), {
          onSuccess: () => {
            router.visit(route('admin.messages.index'));
          }
        });
      }
    }
  }
}
</script>
