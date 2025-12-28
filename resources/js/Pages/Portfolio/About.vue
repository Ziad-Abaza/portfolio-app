<script setup>
import { Head } from '@inertiajs/vue3';
import PortfolioLayout from '@/Layouts/PortfolioLayout.vue';

defineOptions({ layout: PortfolioLayout });

const props = defineProps({
    data: Object,
    translations: Object,
    currentLocale: String,
});
</script>

<template>
    <Head :title="translations.about" />
    
    <!-- Hero Section -->
    <section class="section-padding bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 text-white">
        <div class="container-max">
            <div class="text-center animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    {{ translations.about_title }}
                </h1>
                <p class="text-xl max-w-3xl mx-auto text-cyan-50 dark:text-cyan-100">
                    {{ translations.about_description }}
                </p>
            </div>
        </div>
    </section>
    
    <!-- About Content -->
    <section class="section-padding bg-white dark:bg-slate-800 transition-colors duration-300">
        <div class="container-max">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Profile Image/Info -->
                <div class="animate-slide-in-left">
                    <div class="relative">
                        <div class="w-64 h-64 mx-auto rounded-full bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 p-1 shadow-xl">
                            <div class="w-full h-full rounded-full bg-white dark:bg-slate-800 flex items-center justify-center transition-colors duration-300">
                                <div class="text-6xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-300">ZH</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- About Details -->
                <div class="animate-slide-in-right">
                    <h2 class="text-3xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-300">{{ data.name }}</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 transition-colors duration-300">
                        {{ data.summary }}
                    </p>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center text-gray-700 dark:text-gray-200 transition-colors duration-300">
                            <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span><strong class="font-semibold text-slate-900 dark:text-white">{{ translations.location }}:</strong> {{ data.location }}</span>
                        </div>
                        
                        <div class="flex items-center text-gray-700 dark:text-gray-200 transition-colors duration-300">
                            <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span><strong class="font-semibold text-slate-900 dark:text-white">{{ translations.email }}:</strong> {{ data.email }}</span>
                        </div>
                        
                        <div class="flex items-center text-gray-700 dark:text-gray-200 transition-colors duration-300">
                            <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span><strong class="font-semibold text-slate-900 dark:text-white">{{ translations.phone }}:</strong> {{ data.phone }}</span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-4">
                        <a :href="data.github" target="_blank" class="px-6 py-2.5 rounded-lg bg-cyan-500 hover:bg-cyan-600 dark:bg-cyan-600 dark:hover:bg-cyan-700 text-white font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                            GitHub
                        </a>
                        <a :href="data.linkedin" target="_blank" class="px-6 py-2.5 rounded-lg bg-white dark:bg-slate-700 border border-cyan-500 dark:border-cyan-600 text-cyan-600 dark:text-cyan-400 hover:bg-cyan-50 dark:hover:bg-slate-600 font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                            LinkedIn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Academic Background -->
    <section class="section-padding relative overflow-hidden bg-gradient-to-b from-white to-slate-50 dark:from-slate-900 dark:to-slate-800 transition-colors duration-500">
        <!-- Animated gradient background -->
        <div class="absolute inset-0 -z-10 opacity-30 dark:opacity-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-cyan-400/20 to-transparent dark:from-cyan-500/10"></div>
            <div class="absolute -right-20 -top-20 w-72 h-72 bg-cyan-400/30 dark:bg-cyan-500/20 rounded-full mix-blend-multiply filter blur-3xl animate-float"></div>
            <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-cyan-300/30 dark:bg-cyan-400/20 rounded-full mix-blend-multiply filter blur-3xl animate-float" style="animation-delay: 2s"></div>
        </div>
        
        <div class="container-max relative">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 text-sm font-medium text-cyan-700 dark:text-cyan-100 bg-cyan-100/80 dark:bg-cyan-900/40 backdrop-blur-sm border border-cyan-200/50 dark:border-cyan-800/50 rounded-full mb-4 transition-all duration-300 hover:scale-105">
                    {{ translations.academic_title || 'Education' }}
                </span>
                <h2 class="text-4xl font-bold text-slate-800 dark:text-white mb-4">
                    {{ translations.academic_background }}
                </h2>
                <div class="w-20 h-1 bg-gradient-to-r from-cyan-400 to-cyan-600 dark:from-cyan-400 dark:to-cyan-500 mx-auto rounded-full"></div>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm rounded-2xl shadow-2xl p-8 md:p-10 animate-slide-up transition-all duration-500 border border-white/30 dark:border-slate-700/70 hover:shadow-2xl hover:-translate-y-1 dark:hover:border-cyan-500/30">
                    <div class="flex flex-col md:flex-row items-start mb-8 group">
                        <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-8 transform transition-transform duration-300 group-hover:scale-105">
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 flex items-center justify-center text-white shadow-lg ring-2 ring-white/20 ring-offset-2 ring-offset-cyan-100/50 dark:ring-offset-slate-800/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors duration-300">
                                {{ data.education?.degree || 'Computer Science Degree' }}
                            </h3>
                            <p class="text-lg text-cyan-600 dark:text-cyan-400 font-medium mb-1">
                                {{ data.education?.institution || 'University Name' }}
                            </p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                {{ data.education?.period || '2018 - 2022' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300">
                        <p class="text-lg leading-relaxed mb-6">
                            {{ translations.education_text }}
                        </p>
                        
                        <div v-if="data.education?.highlights" class="mt-8 space-y-4">
                            <h4 class="text-xl font-semibold text-slate-800 dark:text-white flex items-center">
                                <svg class="w-5 h-5 text-cyan-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Key Achievements
                            </h4>
                            <ul class="space-y-3">
                                <li v-for="(highlight, index) in data.education.highlights" :key="index" class="flex items-start group">
                                    <span class="flex-shrink-0 w-2 h-2 mt-2.5 mr-3 rounded-full bg-cyan-500 dark:bg-cyan-400 group-hover:scale-150 transition-transform duration-300"></span>
                                    <span class="text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors duration-300">
                                        {{ highlight }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div v-if="data.education?.skills" class="mt-10 pt-8 border-t border-slate-200 dark:border-slate-700/70">
                        <h4 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 text-cyan-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            Skills Gained
                        </h4>
                        <div class="flex flex-wrap gap-2.5">
                            <span v-for="(skill, index) in data.education.skills" :key="index" 
                                  class="px-4 py-2 text-sm font-medium rounded-full bg-cyan-100/70 dark:bg-cyan-900/40 text-cyan-700 dark:text-cyan-300 border border-cyan-200/50 dark:border-cyan-800/50 hover:bg-cyan-200/50 dark:hover:bg-cyan-800/50 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                                {{ skill }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Decorative elements at bottom -->
            <div class="absolute -bottom-20 left-1/2 -translate-x-1/2 w-64 h-64 rounded-full bg-cyan-400/10 dark:bg-cyan-500/10 filter blur-3xl"></div>
        </div>
    </section>
</template>
