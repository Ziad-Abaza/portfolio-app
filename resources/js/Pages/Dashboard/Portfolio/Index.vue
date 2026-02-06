<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    portfolioItems: Object,
    filters: Object,
    types: Array,
});

const search = ref(props.filters.search || '');
const type = ref(props.filters.type || 'all');

// Watch for changes and navigate
watch(search, () => router.get(route('admin.portfolio.index'), { search: search.value || null, type: type.value === 'all' ? null : type.value }, { preserveState: true, replace: true }));
watch(type, () => router.get(route('admin.portfolio.index'), { search: search.value || null, type: type.value === 'all' ? null : type.value }, { preserveState: true, replace: true }));

const clearFilters = () => {
    search.value = '';
    type.value = 'all';
    router.get(route('admin.portfolio.index'));
};

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString();
};

const getTypeLabel = (type) => {
    const labels = {
        'service': 'Service',
        'skill': 'Skill', 
        'testimonial': 'Testimonial',
        'about': 'About',
        'contact_message': 'Contact Message'
    };
    return labels[type] || type;
};
</script>

<template>
    <Head title="Portfolio" />

    <!-- Header -->
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Portfolio Content</h1>
            <p class="text-gray-600">Manage your portfolio items, services, and skills.</p>
        </div>
        <Link
            :href="route('admin.portfolio.create')"
            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Item
        </Link>
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <!-- Search -->
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
            <input
                id="search"
                v-model="search"
                type="text"
                placeholder="Title or content..."
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            />
        </div>

        <!-- Type -->
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
            <select
                id="type"
                v-model="type"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            >
                <option value="all">All Types</option>
                <option v-for="t in types" :key="t" :value="t">{{ getTypeLabel(t) }}</option>
            </select>
        </div>

        <!-- Clear Filters -->
        <div class="flex items-end">
            <button
                v-if="search || type !== 'all'"
                type="button"
                @click="clearFilters"
                class="text-sm text-gray-500 hover:text-gray-700"
            >
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Portfolio Items Table -->
    <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Title</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Created</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="item in (portfolioItems.data || [])" :key="item.id" class="hover:bg-gray-50">
                    <td class="whitespace-nowrap px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ item.title }}</div>
                            <div class="text-sm text-gray-500 line-clamp-1">{{ item.content }}</div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ getTypeLabel(item.type) }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4">
                        <span
                            :class="[
                                'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
                            ]"
                        >
                            {{ item.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                        {{ formatDate(item.created_at) }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                        <Link
                            :href="route('admin.portfolio.edit', item)"
                            class="text-indigo-600 hover:text-indigo-900"
                        >
                            Edit
                        </Link>
                        <span class="mx-2 text-gray-300">|</span>
                        <button
                            type="button"
                            @click="router.delete(route('admin.portfolio.destroy', item))"
                            class="text-red-600 hover:text-red-900"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
                <tr v-if="!portfolioItems.data || portfolioItems.data.length === 0">
                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No portfolio items found.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div v-if="portfolioItems.meta && portfolioItems.meta.last_page > 1" class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ portfolioItems.meta.from || 0 }}</span> to
            <span class="font-medium">{{ portfolioItems.meta.to || 0 }}</span> of
            <span class="font-medium">{{ portfolioItems.meta.total || 0 }}</span> results
        </div>
        <div>
            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                <Link
                    v-for="(link, key) in portfolioItems.links"
                    :key="key"
                    :href="link.url"
                    v-html="link.label"
                    :class="[
                        'relative inline-flex items-center px-4 py-2 text-sm font-semibold',
                        link.active
                            ? 'z-10 bg-indigo-600 text-white focus:z-20 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600'
                            : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0',
                        key === 0 ? 'rounded-l-md' : '',
                        key === (portfolioItems.links ? portfolioItems.links.length - 1 : 0) ? 'rounded-r-md' : '',
                        !link.url ? 'pointer-events-none opacity-50' : '',
                    ]"
                    preserve-scroll
                    preserve-state
                />
            </nav>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
