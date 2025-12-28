<script setup>
import { Head } from '@inertiajs/vue3';
import PortfolioLayout from '@/Layouts/PortfolioLayout.vue';
import { useScrollAnimations } from '@/Composables/useScrollAnimations.js';

defineOptions({ layout: PortfolioLayout });

const props = defineProps({
    service: Object,
    relatedServices: Array,
    translations: Object,
});

useScrollAnimations();
</script>

<template>
    <Head :title="service.title" />
    
    <!-- Hero Section -->
    <section class="section-padding bg-white dark:bg-slate-900 relative overflow-hidden transition-colors duration-500">
        <!-- Background decoration -->
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 via-white to-blue-50 dark:from-slate-800/30 dark:via-slate-900/80 dark:to-slate-800/30 opacity-50"></div>
        
        <div class="container-max relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Service Image/Icon -->
                <div class="animate-on-scroll slide-left">
                    <div v-if="service.image_url" class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img :src="service.image_url" :alt="service.title" 
                             class="w-full h-96 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                        
                        <!-- Floating Badge -->
                        <div class="absolute top-6 right-6 px-4 py-2 rounded-full bg-white/90 backdrop-blur-sm shadow-lg">
                            <span class="text-sm font-medium text-cyan-600">{{ translations[service.icon] || 'Service' }}</span>
                        </div>
                    </div>
                    
                    <!-- Icon Fallback -->
                    <div v-else class="w-full h-96 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-2xl">
                        <div class="w-32 h-32 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path v-if="service.icon === 'code'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                <path v-else-if="service.icon === 'brain'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                <path v-else-if="service.icon === 'microchip'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                <path v-else-if="service.icon === 'database'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Service Title and Description -->
                <div class="animate-on-scroll slide-right">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">
                        {{ service.title }}
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300 leading-relaxed mb-8">
                        {{ service.description }}
                    </p>
                    
                    <!-- Key Features -->
                    <div v-if="service.features && service.features.length > 0" class="mb-8">
                        <h3 class="text-2xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">
                            {{ translations.key_features || 'Key Features' }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div v-for="feature in service.features" :key="feature" 
                                 class="flex items-center gap-3 group">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500 flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 dark:text-gray-300 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors duration-300">
                                    {{ feature }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Technologies -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">
                            {{ translations.technologies || 'Technologies' }}
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            <span v-for="tech in service.technologies" :key="tech" 
                                  class="px-4 py-2 rounded-full bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-slate-700/50 dark:to-slate-700/70 text-cyan-700 dark:text-cyan-300 font-medium border border-cyan-200 dark:border-slate-600 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                                {{ tech }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/contact" 
                           class="px-6 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-600 dark:to-blue-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 text-center">
                            {{ translations.get_in_touch || 'Get In Touch' }}
                            <span class="inline-block ml-1.5 group-hover:translate-x-1 transition-transform duration-300">→</span>
                        </a>
                        <a href="/projects" 
                           class="px-6 py-3.5 bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 font-medium rounded-xl border-2 border-cyan-500 dark:border-cyan-600 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 text-center">
                            {{ translations.view_projects || 'View Projects' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Related Projects -->
    <section v-if="service.projects && service.projects.length > 0" class="section-padding bg-gray-50 dark:bg-slate-800/50 transition-colors duration-500">
        <div class="container-max">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-4xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">
                    {{ translations.related_projects || 'Related Projects' }}
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ translations.projects_subtitle || 'Explore some of the projects where I\'ve applied this service.' }}
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="(project, index) in service.projects" :key="project.id" 
                     class="group animate-on-scroll scale" 
                     :style="`animation-delay: ${index * 0.1}s`"
                     data-stagger>
                    <div class="h-full bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-gray-100 dark:border-slate-700/50">
                        <div class="relative h-48 bg-gradient-to-br from-cyan-100 to-blue-100 dark:from-slate-700 dark:to-slate-800 overflow-hidden">
                            <img v-if="project.thumbnail_url" :src="project.thumbnail_url" :alt="project.title" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            <div class="absolute bottom-4 left-4">
                                <span class="px-3 py-1 rounded-full bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm text-sm font-medium text-cyan-600 dark:text-cyan-400">
                                    {{ translations['category_' + project.category.toLowerCase()] || project.category }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-800 dark:text-white">{{ project.title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">{{ project.description }}</p>
                            
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span v-for="tech in project.technologies.slice(0, 3)" :key="tech" 
                                      class="px-2.5 py-1 rounded-full bg-cyan-100 dark:bg-slate-700 text-cyan-700 dark:text-cyan-300 text-xs font-medium">
                                    {{ tech }}
                                </span>
                            </div>
                            
                            <div class="flex gap-4 mt-5">
                                <a v-if="project.github_url" :href="project.github_url" target="_blank" 
                                   class="flex items-center text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 font-medium text-sm transition-colors duration-300">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                    </svg>
                                    GitHub
                                </a>
                                <a v-if="project.live_url" :href="project.live_url" target="_blank" 
                                   class="flex items-center text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 font-medium text-sm transition-colors duration-300">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Live Demo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="/projects" 
                   class="inline-block px-8 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-600 dark:to-blue-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                    {{ translations.view_all_projects || 'View All Projects' }}
                    <span class="inline-block ml-1.5 group-hover:translate-x-1 transition-transform duration-300">→</span>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Related Services -->
    <section v-if="relatedServices && relatedServices.length > 0" class="section-padding bg-white dark:bg-slate-900 transition-colors duration-500">
        <div class="container-max">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-4xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">
                    {{ translations.related_services || 'Related Services' }}
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ translations.services_subtitle || 'Discover other services that might complement your needs.' }}
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="(relatedService, index) in relatedServices" :key="relatedService.id" 
                     class="group animate-on-scroll scale" 
                     :style="`animation-delay: ${index * 0.1}s`"
                     data-stagger>
                    <div class="h-full bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-gray-100 dark:border-slate-700/50">
                        <div class="relative">
                            <div v-if="relatedService.image_url" class="w-full h-40 bg-gradient-to-br from-cyan-100 to-blue-100 dark:from-slate-700 dark:to-slate-800 overflow-hidden">
                                <img :src="relatedService.image_url" :alt="relatedService.title" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            </div>
                            
                            <div v-else class="w-full h-40 bg-gradient-to-br from-cyan-600 to-blue-700 dark:from-cyan-700 dark:to-blue-800 flex items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path v-if="relatedService.icon === 'code'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                        <path v-else-if="relatedService.icon === 'brain'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        <path v-else-if="relatedService.icon === 'microchip'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        <path v-else-if="relatedService.icon === 'database'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="absolute bottom-4 right-4">
                                <span class="px-3 py-1 rounded-full bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm text-sm font-medium text-cyan-600 dark:text-cyan-400">
                                    {{ translations[`service_${relatedService.icon}`] || relatedService.icon }}
                                </span>
                            </div>
                        </div>
                    
                        <div class="p-6 space-y-4">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ relatedService.title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3">{{ relatedService.description }}</p>
                            
                            <div class="flex flex-wrap gap-2">
                                <span v-for="tech in relatedService.technologies.slice(0, 3)" :key="tech" 
                                      class="px-2.5 py-1 rounded-full bg-cyan-100 dark:bg-slate-700 text-cyan-700 dark:text-cyan-300 text-xs font-medium">
                                    {{ tech }}
                                </span>
                            </div>
                            
                            <a :href="`/services/${relatedService.slug}`" 
                               class="inline-flex items-center text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 font-medium text-sm mt-2 transition-colors duration-300">
                                {{ translations.learn_more || 'Learn more' }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Skills Section -->
    <section v-if="service.skills && service.skills.length > 0" class="section-padding gradient-bg text-white">
        <div class="container-max">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-4xl font-bold mb-6">
                    {{ translations.core_skills || 'Core Skills' }}
                </h2>
                <p class="text-xl max-w-3xl mx-auto opacity-90">
                    {{ translations.skills_subtitle || 'Key technologies and expertise areas for this service.' }}
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div v-for="skill in service.skills" :key="skill.name" 
                     class="text-center animate-on-scroll scale" 
                     :style="`animation-delay: $index * 0.1)s`"
                     data-stagger>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <h4 class="font-semibold text-lg mb-2">{{ skill.name }}</h4>
                        <div class="text-sm opacity-80">{{ skill.category }}</div>
                        <div class="mt-3">
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="bg-white rounded-full h-2" :style="`width: ${skill.proficiency_level}%`"></div>
                            </div>
                            <div class="text-xs mt-1 opacity-80">{{ skill.proficiency_level }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section-padding bg-white dark:bg-slate-900 transition-colors duration-500">
        <div class="container-max text-center">
            <div class="animate-on-scroll" data-stagger>
                <h2 class="text-4xl md:text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">
                    {{ translations.ready_to_start || 'Ready to Get Started?' }}
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
                    {{ translations.service_cta || 'Let\'s discuss how this service can help bring your project to life.' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/contact" 
                       class="px-8 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-600 dark:to-blue-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                        {{ translations.start_project || 'Start Your Project' }}
                        <span class="inline-block ml-1.5 group-hover:translate-x-1 transition-transform duration-300">→</span>
                    </a>
                    <a href="/services" 
                       class="px-8 py-3.5 bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 font-medium rounded-xl border-2 border-cyan-500 dark:border-cyan-600 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                        {{ translations.view_all_services || 'View All Services' }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
