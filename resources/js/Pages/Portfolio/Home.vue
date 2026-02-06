<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import PortfolioLayout from '@/Layouts/PortfolioLayout.vue';
import { useScrollAnimations } from '@/Composables/useScrollAnimations.js';
import { useGeometricShowcase } from '@/Composables/useGeometricShowcase.js';

defineOptions({ layout: PortfolioLayout });

const props = defineProps({
    data: Object,
    translations: Object,
    currentLocale: String,
});

const heroRef = ref(null);
const titleRef = ref(null);
const subtitleRef = ref(null);
const textRef = ref(null);
const buttonsRef = ref([]);

// Initialize composables
useScrollAnimations();
const {
    geometricElements,
    connectionLines,
    ripples,
    isDarkMode,
    handleMouseMove,
    getElementStyle,
    getLineStyle,
    getRippleStyle
} = useGeometricShowcase();

onMounted(() => {
    // Additional setup if needed
});
</script>

<template>
    <Head :title="translations.home" />
    
    <!-- Geometric Showcase Hero Section -->
    <section 
        ref="heroRef"
        @mousemove="handleMouseMove"
        class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-gray-900 dark:to-gray-800 text-gray-900 dark:text-white transition-colors duration-300 overflow-hidden"
    >
        <!-- Geometric Elements Layer -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Connection Lines -->
            <svg class="absolute inset-0 w-full h-full">
                <line
                    v-for="line in connectionLines"
                    :key="line.id"
                    :x1="line.x1"
                    :y1="line.y1"
                    :x2="line.x2"
                    :y2="line.y2"
                    :stroke="line.color"
                    :opacity="line.opacity"
                    stroke-width="1"
                />
            </svg>
            
            <!-- Floating Geometric Elements -->
            <div
                v-for="element in geometricElements"
                :key="element.id"
                :style="getElementStyle(element)"
            />
            
            <!-- Ripple Effects -->
            <div
                v-for="ripple in ripples"
                :key="ripple.id"
                :style="getRippleStyle(ripple)"
            />
        </div>
        
        <!-- Hero Content -->
        <div class="container-max px-4 relative z-10">
            <div class="text-center">
                <!-- Animated Title -->
                <h1 
                    ref="titleRef"
                    class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in"
                >
                    {{ translations.greeting }} 
                    <span class="text-cyan-600 dark:text-cyan-400 relative inline-block animate-pulse-glow" style="box-shadow: none !important; text-shadow:none !important">
                        {{ data.name }}
                    </span>
                </h1>
                
                <!-- Animated Subtitle -->
                <h2 
                    ref="subtitleRef"
                    class="text-2xl md:text-4xl mb-8 text-cyan-700 dark:text-cyan-300 animate-slide-up"
                    style="animation-delay: 0.2s"
                >
                    {{ data.title }}
                </h2>
                
                <!-- Animated Description -->
                <p 
                    ref="textRef"
                    class="text-lg md:text-xl mb-12 max-w-3xl mx-auto text-gray-700 dark:text-gray-300 leading-relaxed animate-slide-up"
                    style="animation-delay: 0.4s"
                >
                    {{ data.summary }}
                </p>
                
                <!-- Interactive Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-slide-up" style="animation-delay: 0.6s">
                    <a 
                        href="/contact" 
                        class="hero-button bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 relative overflow-hidden group"
                    >
                        <span class="relative z-10">{{ translations.contact_me }}</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 to-cyan-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                    </a>
                    <a 
                        href="/about" 
                        class="hero-button border-2 border-cyan-600 dark:border-cyan-400 text-cyan-600 dark:text-cyan-400 hover:bg-cyan-600 hover:text-white dark:hover:bg-cyan-400 dark:hover:text-gray-900 px-8 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 relative overflow-hidden group"
                    >
                        <span class="relative z-10">{{ translations.about }}</span>
                        <div class="absolute inset-0 bg-cyan-600 dark:bg-cyan-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>
    
    <!-- Quick Info Section -->
    <section class="section-padding bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container-max">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 hover:shadow-xl transition-shadow duration-300 text-center animate-on-scroll border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-cyan-500 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-white">{{ translations.location }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ data.location }}</p>
                </div>
                
                <div class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 hover:shadow-xl transition-shadow duration-300 text-center animate-on-scroll border border-gray-100 dark:border-gray-700" style="animation-delay: 0.1s">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-cyan-500 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-white">{{ translations.email }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ data.email }}</p>
                </div>
                
                <div class="card bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 hover:shadow-xl transition-shadow duration-300 text-center animate-on-scroll border border-gray-100 dark:border-gray-700" style="animation-delay: 0.2s">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-cyan-500 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-white">{{ translations.phone }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ data.phone }}</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Interactive Expertise Preview -->
    <section 
        @mousemove="handleMouseMove"
        class="section-padding bg-gray-50 dark:bg-gray-800 transition-colors duration-300 relative overflow-hidden"
    >
        <!-- Geometric Elements Layer -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Connection Lines -->
            <svg class="absolute inset-0 w-full h-full">
                <line
                    v-for="line in connectionLines"
                    :key="line.id"
                    :x1="line.x1"
                    :y1="line.y1"
                    :x2="line.x2"
                    :y2="line.y2"
                    :stroke="line.color"
                    :opacity="line.opacity * 0.5"
                    stroke-width="1"
                />
            </svg>
            
            <!-- Floating Geometric Elements (subset for performance) -->
            <div
                v-for="element in geometricElements.slice(0, 8)"
                :key="element.id"
                :style="getElementStyle(element)"
            />
        </div>
        
        <div class="container-max relative z-10">
            <h2 class="text-4xl font-bold text-center mb-12 text-gradient animate-on-scroll">
                {{ translations.expertise_areas }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="(area, index) in data.expertise_areas.slice(0, 8)" :key="index" 
                     class="bg-white dark:bg-gray-700 rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 text-center animate-on-scroll border border-gray-100 dark:border-gray-600 hover:scale-105 group relative overflow-hidden"
                     :style="`animation-delay: ${index * 0.1}s`"
                     data-stagger>
                    
                    <!-- Interactive Background Effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 to-transparent dark:from-cyan-800/40 dark:to-cyan-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                    
                    <!-- Icon Container with Interactive Effect -->
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-cyan-100 dark:bg-cyan-800/50 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 relative group-hover:bg-cyan-200 dark:group-hover:bg-cyan-700/60">
                        <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400 group-hover:text-cyan-700 dark:group-hover:text-cyan-300 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        
                        <!-- Pulse Ring Effect -->
                        <div class="absolute inset-0 rounded-full border-2 border-cyan-400 dark:border-cyan-300 opacity-0 group-hover:animate-ping" />
                    </div>
                    
                    <h3 class="font-semibold text-cyan-600 dark:text-cyan-400 relative z-10 group-hover:text-cyan-700 dark:group-hover:text-cyan-200 transition-colors duration-300">
                        {{ area }}
                    </h3>
                    
                    <!-- Hover Indicator -->
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300" />
                </div>
            </div>
        </div>
    </section>
    
    <!-- Enhanced Services Section -->
    <section class="section-padding bg-white dark:bg-gray-900 relative overflow-hidden transition-colors duration-300">
        <!-- Background decoration -->
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 via-white to-cyan-100 opacity-50 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 dark:opacity-20"></div>
        
        <div class="container-max relative">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold mb-6 text-gradient">
                    {{ translations.what_i_offer }}
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ translations.services_subtitle || 'Comprehensive solutions tailored to bring your ideas to life with cutting-edge technology and expertise.' }}
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <div v-for="(service, index) in data.services" :key="service.id" 
                     class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-500 group animate-on-scroll border border-gray-100 dark:border-gray-700 hover:scale-105 hover:-translate-y-2 relative overflow-hidden" 
                     :style="`animation-delay: ${index * 0.15}s`"
                     data-stagger>
                    
                    <!-- Animated Background Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50/0 via-transparent to-cyan-100/0 dark:from-cyan-800/0 dark:to-cyan-900/0 group-hover:from-cyan-50/50 group-hover:to-cyan-100/50 dark:group-hover:from-cyan-800/30 dark:group-hover:to-cyan-900/30 transition-all duration-500" />
                    
                    <!-- Card Header with Image/Icon -->
                    <div class="relative mb-6">
                        <!-- Service Image or Icon Fallback -->
                        <div v-if="service.image_url" class="w-full h-48 rounded-xl overflow-hidden bg-cyan-100 dark:from-gray-700 dark:to-gray-800 relative group">
                            <img :src="service.image_url" :alt="service.title" 
                                 class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:rotate-1">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <!-- Shimmer Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000" />
                        </div>
                        
                        <!-- Icon Fallback -->
                        <div v-else class="w-full h-48 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 flex items-center justify-center group transition-all duration-500 group-hover:from-cyan-600 group-hover:to-cyan-700 dark:group-hover:from-cyan-700 dark:group-hover:to-cyan-800 relative overflow-hidden">
                            <!-- Animated Background Pattern -->
                            <div class="absolute inset-0 opacity-10">
                                <div class="absolute inset-0 bg-white/20 transform rotate-45 translate-x-full group-hover:translate-x-0 transition-transform duration-1000" />
                            </div>
                            
                            <div class="w-20 h-20 rounded-full bg-white/20 dark:bg-gray-800/30 backdrop-blur-sm flex items-center justify-center transition-all duration-500 group-hover:scale-110 group-hover:rotate-12">
                                <svg class="w-12 h-12 text-white transition-transform duration-500 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-if="service.icon === 'code'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    <path v-else-if="service.icon === 'brain'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    <path v-else-if="service.icon === 'microchip'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    <path v-else-if="service.icon === 'database'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Floating Badge with Animation -->
                        <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm shadow-lg text-sm font-medium text-cyan-600 dark:text-cyan-400 transition-all duration-500 group-hover:scale-110 group-hover:shadow-xl">
                            <span class="relative z-10">{{ translations[service.icon] || 'Service' }}</span>
                            <!-- Badge Glow -->
                            <div class="absolute inset-0 rounded-full bg-cyan-400/20 group-hover:animate-pulse" />
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="space-y-4 relative z-10">
                        <h3 class="text-xl font-bold text-cyan-600 dark:text-cyan-400 transition-all duration-300 group-hover:text-cyan-700 dark:group-hover:text-cyan-300 group-hover:translate-x-1">
                            {{ service.title }}
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed transition-all duration-300 group-hover:text-gray-700 dark:group-hover:text-gray-200">
                            {{ service.description }}
                        </p>
                        
                        <!-- Features List with Animation -->
                        <div v-if="service.features && service.features.length > 0" class="space-y-2">
                            <div v-for="feature in service.features.slice(0, 3)" :key="feature" 
                                 class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 transition-all duration-300 group-hover:text-gray-800 dark:group-hover:text-gray-100">
                                <svg class="w-4 h-4 text-cyan-500 dark:text-cyan-400 flex-shrink-0 transition-all duration-300 group-hover:text-cyan-600 dark:group-hover:text-cyan-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="transition-transform duration-300 group-hover:translate-x-1">{{ feature }}</span>
                            </div>
                        </div>
                        
                        <!-- Technologies with Hover Effect -->
                        <div class="flex flex-wrap gap-2">
                            <span v-for="tech in service.technologies.slice(0, 4)" :key="tech" 
                                  class="px-3 py-1 rounded-full bg-cyan-50 dark:from-gray-700 dark:to-gray-800 text-cyan-700 dark:text-cyan-300 text-sm font-medium border border-cyan-200 dark:border-gray-600 hover:border-cyan-300 dark:hover:border-cyan-500 transition-all duration-300 hover:scale-105 hover:bg-cyan-100 dark:hover:bg-cyan-700/50">
                                {{ tech }}
                            </span>
                            <span v-if="service.technologies.length > 4" 
                                  class="px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium transition-all duration-300 hover:scale-105">
                                +{{ service.technologies.length - 4 }}
                            </span>
                        </div>
                        
                        <!-- Enhanced Action Button -->
                        <div class="pt-2">
                            <a :href="`/services/${service.slug}`" 
                               class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-cyan-500 to-cyan-600 text-white font-semibold hover:from-cyan-600 hover:to-cyan-700 dark:from-cyan-600 dark:to-cyan-700 dark:hover:from-cyan-700 dark:hover:to-cyan-800 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl inline-block text-center relative overflow-hidden group">
                                <span class="relative z-10">{{ translations.learn_more || 'Learn More' }}</span>
                                <!-- Button Shimmer -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700" />
                                <!-- Button Glow -->
                                <div class="absolute inset-0 rounded-xl bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section CTA -->
            <div class="text-center mt-16 animate-on-scroll" data-stagger>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                    {{ translations.services_cta || 'Need a custom solution? Let\'s discuss your project requirements.' }}
                </p>
                <a href="/contact" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-cyan-500 text-white font-semibold hover:bg-cyan-600 dark:bg-cyan-600 dark:hover:bg-cyan-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                    {{ translations.discuss_project || 'Discuss Your Project' }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Enhanced Statistics Section -->
    <section 
        @mousemove="handleMouseMove"
        class="section-padding bg-gradient-to-br from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 text-white relative overflow-hidden transition-all duration-500"
    >
        <!-- Interactive Geometric Background -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Floating Elements -->
            <div
                v-for="element in geometricElements.slice(0, 10)"
                :key="element.id"
                :style="getElementStyle(element)"
            />
            
            <!-- Connection Lines -->
            <svg class="absolute inset-0 w-full h-full">
                <line
                    v-for="line in connectionLines"
                    :key="line.id"
                    :x1="line.x1"
                    :y1="line.y1"
                    :x2="line.x2"
                    :y2="line.y2"
                    :stroke="line.color"
                    :opacity="line.opacity * 0.4"
                    stroke-width="1"
                />
            </svg>
            
            <!-- Animated Background Orbs -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay animate-float"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/5 rounded-full mix-blend-overlay animate-float" style="animation-delay: 2s"></div>
            <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-cyan-300/10 rounded-full mix-blend-overlay animate-pulse"></div>
        </div>
        
        <div class="container-max relative">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center animate-on-scroll group" data-stagger>
                    <div class="relative inline-block mb-4">
                        <!-- Animated Glow Ring -->
                        <div class="absolute inset-0 bg-white/10 rounded-full scale-0 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative z-10 w-24 h-24 flex items-center justify-center mx-auto mb-4 rounded-full bg-white/10 backdrop-blur-sm border-2 border-white/20 group-hover:border-cyan-300/50 group-hover:bg-white/20 transition-all duration-500">
                            <div class="text-4xl md:text-5xl font-bold text-white transition-all duration-500 group-hover:scale-110">
                                {{ data.statistics.projects_completed }}+
                            </div>
                            <!-- Rotating Ring -->
                            <div class="absolute inset-0 rounded-full border-2 border-cyan-400/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <div class="absolute inset-0 rounded-full border border-cyan-400/20 animate-spin" style="animation-duration: 8s"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-lg text-white font-medium group-hover:text-cyan-200 dark:group-hover:text-cyan-100 transition-all duration-300">
                        {{ translations.projects_completed }}
                    </div>
                </div>
                
                <div class="text-center animate-on-scroll group" data-stagger style="animation-delay: 0.1s">
                    <div class="relative inline-block mb-4">
                        <div class="absolute inset-0 bg-white/10 rounded-full scale-0 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative z-10 w-24 h-24 flex items-center justify-center mx-auto mb-4 rounded-full bg-white/10 backdrop-blur-sm border-2 border-white/20 group-hover:border-cyan-300/50 group-hover:bg-white/20 transition-all duration-500">
                            <div class="text-4xl md:text-5xl font-bold text-white transition-all duration-500 group-hover:scale-110">
                                {{ data.statistics.years_experience }}+
                            </div>
                            <!-- Rotating Ring -->
                            <div class="absolute inset-0 rounded-full border-2 border-cyan-400/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <div class="absolute inset-0 rounded-full border border-cyan-400/20 animate-spin" style="animation-duration: 10s"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-lg text-white font-medium group-hover:text-cyan-200 dark:group-hover:text-cyan-100 transition-all duration-300">
                        {{ translations.years_experience }}
                    </div>
                </div>
                
                <div class="text-center animate-on-scroll group" data-stagger style="animation-delay: 0.2s">
                    <div class="relative inline-block mb-4">
                        <div class="absolute inset-0 bg-white/10 rounded-full scale-0 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative z-10 w-24 h-24 flex items-center justify-center mx-auto mb-4 rounded-full bg-white/10 backdrop-blur-sm border-2 border-white/20 group-hover:border-cyan-300/50 group-hover:bg-white/20 transition-all duration-500">
                            <div class="text-4xl md:text-5xl font-bold text-white transition-all duration-500 group-hover:scale-110">
                                {{ data.statistics.happy_clients }}+
                            </div>
                            <!-- Rotating Ring -->
                            <div class="absolute inset-0 rounded-full border-2 border-cyan-400/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <div class="absolute inset-0 rounded-full border border-cyan-400/20 animate-spin" style="animation-duration: 12s"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-lg text-white font-medium group-hover:text-cyan-200 dark:group-hover:text-cyan-100 transition-all duration-300">
                        {{ translations.happy_clients }}
                    </div>
                </div>
                
                <div class="text-center animate-on-scroll group" data-stagger style="animation-delay: 0.3s">
                    <div class="relative inline-block mb-4">
                        <div class="absolute inset-0 bg-white/10 rounded-full scale-0 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative z-10 w-24 h-24 flex items-center justify-center mx-auto mb-4 rounded-full bg-white/10 backdrop-blur-sm border-2 border-white/20 group-hover:border-cyan-300/50 group-hover:bg-white/20 transition-all duration-500">
                            <div class="text-4xl md:text-5xl font-bold text-white transition-all duration-500 group-hover:scale-110">
                                {{ data.statistics.technologies_mastered }}+
                            </div>
                            <!-- Rotating Ring -->
                            <div class="absolute inset-0 rounded-full border-2 border-cyan-400/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <div class="absolute inset-0 rounded-full border border-cyan-400/20 animate-spin" style="animation-duration: 15s"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-lg text-white font-medium group-hover:text-cyan-200 dark:group-hover:text-cyan-100 transition-all duration-300">
                        {{ translations.technologies }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Interactive Recent Posts Section -->
    <section 
        @mousemove="handleMouseMove"
        class="section-padding bg-gray-50 dark:bg-gray-800 transition-colors duration-300 relative overflow-hidden"
    >
        <!-- Geometric Elements Layer -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Connection Lines -->
            <svg class="absolute inset-0 w-full h-full">
                <line
                    v-for="line in connectionLines"
                    :key="line.id"
                    :x1="line.x1"
                    :y1="line.y1"
                    :x2="line.x2"
                    :y2="line.y2"
                    :stroke="line.color"
                    :opacity="line.opacity * 0.3"
                    stroke-width="1"
                />
            </svg>
            
            <!-- Floating Geometric Elements (subset for performance) -->
            <div
                v-for="element in geometricElements.slice(0, 6)"
                :key="element.id"
                :style="getElementStyle(element)"
            />
        </div>
        
        <div class="container-max relative z-10">
            <h2 class="text-4xl font-bold text-center mb-12 text-gradient animate-on-scroll">
                {{ translations.recent_updates }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="(post, index) in data.recent_posts" :key="index" 
                     class="bg-white dark:bg-gray-700 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 animate-on-scroll hover:scale-105 group relative overflow-hidden border border-gray-100 dark:border-gray-600" 
                     :style="`animation-delay: ${index * 0.1}s`"
                     data-stagger>
                    
                    <!-- Interactive Background Effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50/50 to-transparent dark:from-cyan-800/30 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                    
                    <!-- Category and Date -->
                    <div class="flex items-center justify-between mb-3 relative z-10">
                        <span class="text-sm px-3 py-1 rounded-full bg-cyan-100 dark:bg-cyan-800/50 text-cyan-700 dark:text-cyan-300 font-medium group-hover:bg-cyan-200 dark:group-hover:bg-cyan-700/60 transition-colors duration-300">
                            {{ translations['category_' + post.category.toLowerCase()] || post.category }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors duration-300">
                            {{ new Date(post.date).toLocaleDateString() }}
                        </span>
                    </div>
                    
                    <!-- Post Title -->
                    <h3 class="text-xl font-bold mb-3 text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-600 dark:from-cyan-400 dark:to-blue-400 group-hover:from-cyan-500 group-hover:to-blue-500 dark:group-hover:from-cyan-300 dark:group-hover:to-blue-300 transition-all duration-300 relative z-10">
                        {{ post.title }}
                    </h3>
                    
                    <!-- Post Excerpt -->
                    <p class="text-gray-600 dark:text-gray-300 mb-4 group-hover:text-gray-700 dark:group-hover:text-gray-200 transition-colors duration-300 relative z-10">
                        {{ post.excerpt }}
                    </p>
                    
                    <!-- Read More Link -->
                    <a href="#" class="inline-flex items-center text-cyan-500 dark:text-cyan-400 font-semibold hover:text-cyan-600 dark:hover:text-cyan-300 transition-all duration-300 group relative z-10">
                        <span class="group-hover:translate-x-1 transition-transform duration-300 inline-block">
                            {{ translations.read_more || 'Read More' }}
                        </span>
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                        
                        <!-- Hover Underline -->
                        <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 dark:from-cyan-400 dark:to-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300" />
                    </a>
                    
                    <!-- Floating Icon on Hover -->
                    <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-cyan-100 dark:bg-cyan-800/50 flex items-center justify-center opacity-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-300">
                        <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Call to Action Section -->
    <section class="section-padding bg-cyan-500 dark:bg-cyan-600 text-white transition-colors duration-300">
        <div class="container-max text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-on-scroll">
                {{ translations.ready_to_start }}
            </h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto animate-on-scroll text-cyan-50 dark:text-cyan-100" data-stagger>
                {{ translations.project_description }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-on-scroll" data-stagger>
                <a href="/contact" class="px-8 py-4 rounded-xl bg-white text-cyan-600 hover:bg-cyan-50 font-semibold transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    {{ translations.get_in_touch }}
                </a>
                <a href="/projects" class="px-8 py-4 rounded-xl border-2 border-white text-white hover:bg-white/10 font-semibold transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    {{ translations.view_my_work }}
                </a>
            </div>
        </div>
    </section>
</template>
