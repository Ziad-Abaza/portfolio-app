<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { onMounted, ref, computed } from 'vue';
import { useScrollAnimations } from '@/Composables/useScrollAnimations.js';
import { useProjectsStore } from '@/Stores/useProjectsStore';
import PortfolioLayout from '@/Layouts/PortfolioLayout.vue';

defineOptions({ layout: PortfolioLayout });

const props = defineProps({
    data: Object,
    translations: Object,
    currentLocale: String,
});

// Initialize store
const projectsStore = useProjectsStore();

// Initialize scroll animations
useScrollAnimations();

// Debug props
console.log('Projects component mounted with props:', props);

// Set initial projects data when component mounts
onMounted(() => {
    console.log('Component mounted, setting projects data');
    
    // The projects data is in props.data.projects
    if (props.data?.projects && Array.isArray(props.data.projects)) {
        console.log('Projects data found in props.data.projects:', props.data.projects);
        projectsStore.setProjects(props.data.projects);
    } else if (props.projects) {
        console.log('Projects data found in props.projects:', props.projects);
        projectsStore.setProjects(props.projects);
    } else {
        console.warn('No projects data found in expected locations');
        console.log('Full props.data:', props.data);
        console.log('Full props:', props);
    }

    // Debug: Log store state after setting projects
    console.log('Store state after setting projects:', {
        projects: projectsStore.projects,
        categories: projectsStore.categories,
        filteredProjects: projectsStore.filteredProjects
    });
});

// Methods
const clearSearch = () => {
    projectsStore.clearFilters();
};

// Expose store state and methods to template
const { 
    searchTerm, 
    selectedCategory, 
    sortBy, 
    categories, 
    filteredProjects, 
    getCategoryCount,
    setSearchTerm,
    setSelectedCategory,
    setSortBy
} = projectsStore;

// Debug: Watch for changes in the store
watch([
    () => projectsStore.searchTerm,
    () => projectsStore.selectedCategory,
    () => projectsStore.sortBy,
    () => projectsStore.filteredProjects
], () => {
    console.log('Store state changed:', {
        searchTerm: projectsStore.searchTerm,
        selectedCategory: projectsStore.selectedCategory,
        sortBy: projectsStore.sortBy,
        filteredProjectsCount: projectsStore.filteredProjects.length
    });
}, { deep: true });
</script>

<template>
    <Head :title="translations.projects" />
    
    <!-- Hero Section -->
    <section class="section-padding bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 text-white">
        <div class="container-max">
            <div class="text-center animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    {{ translations.projects_title || 'My Projects' }}
                </h1>
                <p class="text-xl max-w-3xl mx-auto text-cyan-50 dark:text-cyan-100">
                    {{ translations.projects_description || 'Explore my portfolio of web development projects and case studies.' }}
                </p>
            </div>
        </div>
    </section>
    
    <!-- Projects Grid -->
    <section class="section-padding bg-white dark:bg-slate-800 transition-colors duration-300">
        <div class="container-max">
            <!-- Enhanced Search and Filter Controls -->
            <div class="mb-12 animate-on-scroll">
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-slate-700 dark:to-slate-800 rounded-2xl p-6 shadow-lg border border-cyan-100 dark:border-slate-600/50 transition-colors duration-300">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Enhanced Search Bar -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2 transition-colors duration-300">
                                {{ translations.search_projects || 'Search Projects' }}
                            </label>
                            <div class="relative">
                                <input
                                    v-model="searchTerm"
                                    type="text"
                                    :placeholder="translations.search_placeholder || 'Search by project name, technology, or description...'"
                                    class="w-full px-4 py-3 pl-12 rounded-xl border border-gray-200 dark:border-slate-600 focus:border-cyan-500 dark:focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-800 transition-all duration-300 bg-white dark:bg-slate-700 dark:text-white shadow-sm"
                                >
                                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <div v-if="searchTerm" class="absolute right-4 top-3.5">
                                    <button @click="clearSearch" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sort Options -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2 transition-colors duration-300">
                                {{ translations.sort_by || 'Sort By' }}
                            </label>
                            <select :value="sortBy" 
                                    @change="(e) => {
                                        console.log('Sort by changed:', e.target.value);
                                        setSortBy(e.target.value);
                                    }"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-all duration-300 bg-white shadow-sm dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                <option value="latest">{{ translations.latest || 'Latest' }}</option>
                                <option value="name">{{ translations.name || 'Name' }}</option>
                                <option value="category">{{ translations.category || 'Category' }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Enhanced Category Filter -->
                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3 transition-colors duration-300">
                            {{ translations.filter_by_category || 'Filter by Category' }}
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="category in categories"
                                :key="category"
                                @click="() => {
                                    console.log('Category selected:', category);
                                    setSelectedCategory(category);
                                }"
                                :class="selectedCategory === category 
                                    ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg transform scale-105' 
                                    : 'bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-slate-600 hover:border-cyan-300 dark:hover:border-cyan-400 hover:bg-cyan-50 dark:hover:bg-slate-600'"
                                class="px-4 py-2 rounded-xl font-medium transition-all duration-300 text-sm"
                            >
                                <span v-if="category === 'all'">
                                    {{ translations.all_categories || 'All Categories' }}
                                </span>
                                <span v-else>
                                    {{ translations['category_' + category.toLowerCase()] || category }}
                                </span>
                                <span v-if="selectedCategory === category" class="ml-1">
                                    ({{ getCategoryCount(category) }})
                                </span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Active Filters Display -->
                    <div v-if="searchTerm || selectedCategory !== 'all'" class="mt-4 flex items-center gap-2 text-sm">
                        <span class="text-gray-600 dark:text-gray-300">{{ translations.active_filters || 'Active filters:' }}</span>
                        <span v-if="searchTerm" class="px-2 py-1 rounded-full bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 flex items-center gap-1">
                            {{ translations.search || 'Search' }}: {{ searchTerm }}
                            <button @click="setSearchTerm('')" class="text-cyan-600 hover:text-cyan-800">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                        <span v-if="selectedCategory !== 'all'" class="px-2 py-1 rounded-full bg-cyan-100 text-cyan-700 flex items-center gap-1">
                            {{ translations.category || 'Category' }}: {{ translations['category_' + selectedCategory.toLowerCase()] || selectedCategory }}
                            <button @click="setSelectedCategory('all')" class="text-cyan-600 hover:text-cyan-800">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Results Count -->
            <div v-if="searchTerm || selectedCategory !== 'all'" class="mb-6 text-gray-600 dark:text-gray-300">
                {{ (translations.found_projects || 'Found :count projects').replace(':count', filteredProjects.length) }}
            </div>
            
            <!-- Projects Grid -->
            <div v-if="filteredProjects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="(project, index) in filteredProjects" :key="index" 
                     class="enhanced-project-card group animate-on-scroll bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-slate-700" 
                     :style="`animation-delay: ${index * 0.1}s`"
                     data-stagger>
                    <!-- Project Header with Thumbnail -->
                    <div class="relative h-48 bg-gradient-to-br from-cyan-100 to-blue-100 dark:from-slate-700 dark:to-slate-800 rounded-t-2xl overflow-hidden">
                        <img v-if="project.thumbnail_url" 
                             :src="project.thumbnail_url" 
                             :alt="project.title" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 rounded-full bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm text-sm font-medium text-cyan-600 dark:text-cyan-400 border border-cyan-100 dark:border-slate-700">
                                {{ translations['category_' + project.category.toLowerCase()] || project.category }}
                            </span>
                        </div>
                        
                        <!-- Featured Badge -->
                        <div v-if="project.is_featured" class="absolute top-4 right-4">
                            <span class="px-3 py-1 rounded-full bg-yellow-400 dark:bg-yellow-500 text-yellow-900 dark:text-yellow-100 text-sm font-medium">
                                {{ translations.featured || 'Featured' }}
                            </span>
                        </div>
                        
                        <!-- Overlay on Hover -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    
                    <!-- Project Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-800 dark:text-white">{{ project.title }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">{{ project.description }}</p>
                        
                        <!-- Technologies -->
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">{{ translations.technologies_used || 'Technologies Used' }}</p>
                            <div class="flex flex-wrap gap-2">
                                <span v-for="tech in project.technologies.slice(0, 4)" :key="tech" 
                                      class="px-3 py-1 rounded-full bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-slate-700/50 dark:to-slate-800/50 text-cyan-700 dark:text-cyan-300 text-xs font-medium border border-cyan-200 dark:border-slate-600">
                                    {{ tech }}
                                </span>
                                <span v-if="project.technologies.length > 4" 
                                      class="px-3 py-1 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-medium">
                                    +{{ project.technologies.length - 4 }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Project Actions -->
                        <div class="flex gap-3">
                            <a v-if="project.github_url" 
                               :href="project.github_url" 
                               target="_blank"
                               class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold hover:from-cyan-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl text-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                                {{ translations.view_code || 'View Code' }}
                            </a>
                            <a v-if="project.live_url" 
                               :href="project.live_url" 
                               target="_blank"
                               class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-700 text-cyan-600 dark:text-cyan-400 border-2 border-cyan-500 dark:border-cyan-400 font-semibold hover:bg-cyan-50 dark:hover:bg-slate-600 transform hover:scale-105 transition-all duration-300 text-sm shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                {{ translations.live_demo || 'Live Demo' }}
                            </a>
                        </div>
                        
                        <!-- View Details Link -->
                        <div class="mt-4 text-center">
                            <a href="#" class="text-cyan-500 hover:text-cyan-600 dark:text-cyan-400 dark:hover:text-cyan-300 font-medium text-sm transition-colors">
                                {{ translations.view_details || 'View Details' }} →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- No Results -->
            <div v-else class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">{{ translations.no_projects_found || 'No projects found' }}</h3>
                <p class="text-gray-500 dark:text-gray-400">{{ translations.try_adjusting_search || 'Try adjusting your search or filter criteria' }}</p>
            </div>
        </div>
    </section>
    
    <!-- Project Categories -->
    <section class="section-padding bg-gradient-to-b from-white to-gray-50 dark:from-slate-800 dark:to-slate-900 transition-colors duration-300">
        <div class="container-max">
            <h2 class="text-3xl font-bold text-center mb-12 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">
                {{ translations.my_projects || 'My Projects' }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-800/50 dark:border dark:border-slate-700/50 rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-slide-up">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800 dark:text-white">{{ translations.web_development || 'Web Development' }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ translations.web_development_desc || 'Full-stack applications with modern frameworks' }}</p>
                </div>
                
                <div class="bg-white dark:bg-slate-800/50 dark:border dark:border-slate-700/50 rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-slide-up" style="animation-delay: 0.1s">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800 dark:text-white">{{ translations.ai_ml || 'AI & ML' }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ translations.ai_ml_desc || 'Intelligent systems and data-driven solutions' }}</p>
                </div>
                
                <div class="bg-white dark:bg-slate-800/50 dark:border dark:border-slate-700/50 rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-slide-up" style="animation-delay: 0.2s">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800 dark:text-white">{{ translations.iot_solutions || 'IoT Solutions' }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ translations.iot_solutions_desc || 'Smart devices and connected systems' }}</p>
                </div>
            </div>
        </div>
    </section>
</template>
