<script setup>
import { Head } from '@inertiajs/vue3';
import PortfolioLayout from '@/Layouts/PortfolioLayout.vue';
import { useScrollAnimations } from '@/Composables/useScrollAnimations.js';
import { useGeometricShowcase } from '@/Composables/useGeometricShowcase.js';

defineOptions({ layout: PortfolioLayout });

// Initialize geometric showcase
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

const props = defineProps({
    data: Object,
    translations: Object,
    currentLocale: String,
});
</script>

<template>
    <Head :title="translations.about" />
    
    <!-- Interactive Hero Section with Geometric Showcase -->
    <section 
        @mousemove="handleMouseMove"
        class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-cyan-500 via-cyan-600 to-cyan-700 dark:from-cyan-700 dark:via-cyan-800 dark:to-cyan-900 text-white transition-all duration-500 overflow-hidden"
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
                    :opacity="line.opacity * 0.7"
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
            
            <!-- Animated Background Orbs -->
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/5 rounded-full mix-blend-overlay animate-float"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-cyan-400/5 rounded-full mix-blend-overlay animate-float" style="animation-delay: 3s"></div>
            <div class="absolute top-1/3 right-1/4 w-48 h-48 bg-white/3 rounded-full mix-blend-overlay animate-pulse"></div>
            <div class="absolute bottom-1/3 left-1/3 w-64 h-64 bg-cyan-300/4 rounded-full mix-blend-overlay animate-float" style="animation-delay: 1.5s"></div>
            
            <!-- Particle Field Effect -->
            <div class="absolute inset-0">
                <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-cyan-300/30 rounded-full animate-pulse"></div>
                <div class="absolute top-3/4 right-1/4 w-3 h-3 bg-cyan-200/20 rounded-full animate-pulse" style="animation-delay: 0.5s"></div>
                <div class="absolute top-1/2 left-3/4 w-2 h-2 bg-white/20 rounded-full animate-pulse" style="animation-delay: 1s"></div>
                <div class="absolute bottom-1/4 right-1/2 w-4 h-4 bg-cyan-400/20 rounded-full animate-pulse" style="animation-delay: 1.5s"></div>
            </div>
        </div>
        
        <!-- Hero Content -->
        <div class="container-max px-4 relative z-10">
            <div class="text-center">
                <!-- Animated Title with Glow Effect -->
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-8 animate-fade-in relative">
                    <span class="relative inline-block">
                        {{ translations.about_title }}
                        <!-- Animated Underline -->
                        <div class="absolute -bottom-2 left-0 right-0 h-1 bg-gradient-to-r from-cyan-300 via-white to-cyan-300 transform scale-x-0 animate-expand-line"></div>
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 blur-xl bg-cyan-300/30 animate-pulse"></div>
                    </span>
                </h1>
                
                <!-- Animated Subtitle with Typing Effect -->
                <p class="text-xl md:text-2xl lg:text-3xl max-w-4xl mx-auto text-cyan-50 dark:text-cyan-100 mb-12 animate-slide-up leading-relaxed" style="animation-delay: 0.3s">
                    {{ translations.about_description }}
                </p>
                
                <!-- Interactive Call-to-Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center animate-slide-up" style="animation-delay: 0.6s">
                    <a 
                        href="#about-content" 
                        class="group relative px-8 py-4 rounded-2xl bg-gradient-to-r from-cyan-400 to-cyan-500 hover:from-cyan-500 hover:to-cyan-600 text-white font-semibold shadow-2xl hover:shadow-cyan-500/25 transform hover:scale-105 transition-all duration-500 overflow-hidden"
                    >
                        <!-- Button Shimmer Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        <!-- Button Glow -->
                        <div class="absolute inset-0 rounded-2xl bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 animate-bounce" style="animation-delay: 0.2s" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ translations.learn_more || 'Learn More' }}
                        </span>
                    </a>
                    <a 
                        href="#academic-background" 
                        class="group relative px-8 py-4 rounded-2xl border-2 border-white/30 hover:border-white/60 text-white font-semibold backdrop-blur-sm hover:bg-white/10 transform hover:scale-105 transition-all duration-500 overflow-hidden"
                    >
                        <!-- Hover Background -->
                        <div class="absolute inset-0 bg-gradient-to-r from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            {{ translations.academic_background || 'Education' }}
                        </span>
                    </a>
                </div>
                
                <!-- Floating About Icons -->
                <div class="absolute top-20 left-10 animate-float opacity-60">
                    <div class="w-16 h-16 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/20">
                        <svg class="w-8 h-8 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="absolute top-32 right-16 animate-float opacity-60" style="animation-delay: 1s">
                    <div class="w-14 h-14 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/20">
                        <svg class="w-7 h-7 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-20 left-20 animate-float opacity-60" style="animation-delay: 2s">
                    <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/20">
                        <svg class="w-6 h-6 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Enhanced Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="flex flex-col items-center group cursor-pointer" onclick="document.getElementById('about-content').scrollIntoView({behavior: 'smooth'})">
                <span class="text-cyan-200 text-sm mb-2 opacity-70 group-hover:opacity-100 transition-opacity duration-300">
                    {{ translations.scroll_down || 'Scroll Down' }}
                </span>
                <div class="relative">
                    <svg class="w-6 h-6 text-cyan-200 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    <!-- Glow Effect -->
                    <div class="absolute inset-0 blur-md bg-cyan-400/50 animate-pulse"></div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Content -->
    <section id="about-content" class="section-padding bg-white dark:bg-slate-800 transition-colors duration-300">
        <div class="container-max">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Profile Image/Info -->
                <div class="animate-slide-in-left">
                    <div class="relative group">
                        <!-- Animated Background Ring -->
                        <div class="absolute inset-0 w-64 h-64 mx-auto rounded-full bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 animate-pulse opacity-30 scale-110 group-hover:scale-125 transition-transform duration-700"></div>
                        
                        <!-- Profile Image Container -->
                        <div class="relative w-64 h-64 mx-auto rounded-full bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 p-1 shadow-2xl transform transition-all duration-500 group-hover:scale-105 group-hover:rotate-3">
                            <div class="w-full h-full rounded-full bg-white dark:bg-slate-800 flex items-center justify-center transition-all duration-500 group-hover:bg-cyan-50 dark:group-hover:bg-slate-700">
                                <div class="text-6xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-300 transition-all duration-500 group-hover:scale-110">ZH</div>
                            </div>
                            
                            <!-- Floating Particles Around Profile -->
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-cyan-400 rounded-full animate-pulse opacity-60"></div>
                            <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-cyan-300 rounded-full animate-pulse opacity-40" style="animation-delay: 0.5s"></div>
                            <div class="absolute top-1/2 -left-4 w-3 h-3 bg-cyan-500 rounded-full animate-pulse opacity-50" style="animation-delay: 1s"></div>
                        </div>
                        
                        <!-- Interactive Glow Effect -->
                        <div class="absolute inset-0 w-64 h-64 mx-auto rounded-full bg-cyan-400/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-pulse"></div>
                    </div>
                </div>
                
                <!-- About Details -->
                <div class="animate-slide-in-right">
                    <div class="relative group">
                        <!-- Animated Background Glow -->
                        <div class="absolute -inset-4 bg-gradient-to-r from-cyan-500/10 to-cyan-600/10 dark:from-cyan-400/10 dark:to-cyan-500/10 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                        
                        <h2 class="relative text-3xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-300 transition-all duration-500 group-hover:scale-105">
                            {{ data.name }}
                            <!-- Animated Underline -->
                            <div class="absolute -bottom-2 left-0 right-0 h-0.5 bg-gradient-to-r from-cyan-400 to-cyan-600 dark:from-cyan-300 dark:to-cyan-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                        </h2>
                    </div>
                    
                    <div class="relative group mb-6">
                        <!-- Text Background Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-50/50 to-transparent dark:from-cyan-900/20 dark:to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <p class="relative text-lg text-gray-600 dark:text-gray-300 leading-relaxed transition-all duration-300 group-hover:text-gray-700 dark:group-hover:text-gray-200">
                            {{ data.summary }}
                        </p>
                    </div>
                    
                    <div class="space-y-4 mb-8">
                        <div class="group relative">
                            <!-- Hover Background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-50/30 to-transparent dark:from-cyan-900/20 dark:to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-500 transform group-hover:translate-x-1"></div>
                            
                            <div class="relative flex items-center text-gray-700 dark:text-gray-200 transition-all duration-300 group-hover:translate-x-3">
                                <div class="relative flex-shrink-0 mr-3">
                                    <!-- Icon Background Ring -->
                                    <div class="absolute inset-0 w-10 h-10 rounded-lg bg-cyan-400/20 dark:bg-cyan-600/30 scale-0 group-hover:scale-150 transition-transform duration-500"></div>
                                    
                                    <!-- Icon Container -->
                                    <div class="relative w-10 h-10 rounded-lg bg-cyan-100 dark:bg-cyan-900/50 flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:bg-cyan-200 dark:group-hover:bg-cyan-800/70 group-hover:rotate-6">
                                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 transition-colors duration-300 group-hover:text-cyan-600 dark:group-hover:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        
                                        <!-- Rotating Ring -->
                                        <div class="absolute inset-0 rounded-lg border-2 border-cyan-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                            <div class="absolute inset-0 rounded-lg border border-cyan-400/30 animate-spin" style="animation-duration: 8s"></div>
                                        </div>
                                    </div>
                                </div>
                                <span class="flex-1 font-medium text-slate-900 dark:text-white transition-colors duration-300">
                                    <strong class="font-semibold">{{ translations.location }}:</strong> {{ data.location }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="group relative">
                            <!-- Hover Background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-50/30 to-transparent dark:from-cyan-900/20 dark:to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-500 transform group-hover:translate-x-1"></div>
                            
                            <div class="relative flex items-center text-gray-700 dark:text-gray-200 transition-all duration-300 group-hover:translate-x-3">
                                <div class="relative flex-shrink-0 mr-3">
                                    <!-- Icon Background Ring -->
                                    <div class="absolute inset-0 w-10 h-10 rounded-lg bg-cyan-400/20 dark:bg-cyan-600/30 scale-0 group-hover:scale-150 transition-transform duration-500"></div>
                                    
                                    <!-- Icon Container -->
                                    <div class="relative w-10 h-10 rounded-lg bg-cyan-100 dark:bg-cyan-900/50 flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:bg-cyan-200 dark:group-hover:bg-cyan-800/70 group-hover:rotate-6">
                                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 transition-colors duration-300 group-hover:text-cyan-600 dark:group-hover:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        
                                        <!-- Rotating Ring -->
                                        <div class="absolute inset-0 rounded-lg border-2 border-cyan-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                            <div class="absolute inset-0 rounded-lg border border-cyan-400/30 animate-spin" style="animation-duration: 10s"></div>
                                        </div>
                                    </div>
                                </div>
                                <span class="flex-1 font-medium text-slate-900 dark:text-white transition-colors duration-300">
                                    <strong class="font-semibold">{{ translations.email }}:</strong> {{ data.email }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="group relative">
                            <!-- Hover Background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-50/30 to-transparent dark:from-cyan-900/20 dark:to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-500 transform group-hover:translate-x-1"></div>
                            
                            <div class="relative flex items-center text-gray-700 dark:text-gray-200 transition-all duration-300 group-hover:translate-x-3">
                                <div class="relative flex-shrink-0 mr-3">
                                    <!-- Icon Background Ring -->
                                    <div class="absolute inset-0 w-10 h-10 rounded-lg bg-cyan-400/20 dark:bg-cyan-600/30 scale-0 group-hover:scale-150 transition-transform duration-500"></div>
                                    
                                    <!-- Icon Container -->
                                    <div class="relative w-10 h-10 rounded-lg bg-cyan-100 dark:bg-cyan-900/50 flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:bg-cyan-200 dark:group-hover:bg-cyan-800/70 group-hover:rotate-6">
                                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 transition-colors duration-300 group-hover:text-cyan-600 dark:group-hover:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        
                                        <!-- Rotating Ring -->
                                        <div class="absolute inset-0 rounded-lg border-2 border-cyan-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                            <div class="absolute inset-0 rounded-lg border border-cyan-400/30 animate-spin" style="animation-duration: 12s"></div>
                                        </div>
                                    </div>
                                </div>
                                <span class="flex-1 font-medium text-slate-900 dark:text-white transition-colors duration-300">
                                    <strong class="font-semibold">{{ translations.phone }}:</strong> {{ data.phone }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <!-- Background Glow Effect -->
                        <div class="absolute -inset-4 bg-gradient-to-r from-cyan-500/5 to-cyan-600/5 dark:from-cyan-400/5 dark:to-cyan-500/5 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                        
                        <div class="relative flex space-x-4">
                            <a :href="data.github" target="_blank" class="group/btn relative px-6 py-2.5 rounded-lg bg-cyan-500 hover:bg-cyan-600 dark:bg-cyan-600 dark:hover:bg-cyan-700 text-white font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 overflow-hidden">
                                <!-- Button Shimmer -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700"></div>
                                
                                <!-- Rotating Ring Background -->
                                <div class="absolute inset-0 rounded-lg border-2 border-cyan-400/20 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-500">
                                    <div class="absolute inset-0 rounded-lg border border-cyan-400/30 animate-spin" style="animation-duration: 6s"></div>
                                </div>
                                
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-5 h-5 mr-2 transform group-hover/btn:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.49.5.09.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.337-3.369-1.337-.454-1.151-1.11-1.458-1.11-1.458-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.526 2.341 1.085 2.91.83.092-.645.35-1.085.636-1.334-2.22-.253-4.555-1.11-4.555-4.94 0-1.09.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.27 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.7 1.028 1.593 1.028 2.682 0 3.842-2.339 4.683-4.566 4.93.36.31.68.92.68 1.853 0 1.337-.263 2.415-.663 2.743 0 .267.18.578.688.48C19.14 20.16 22 16.416 22 12c0-5.523-4.477-10-10-10z" clip-rule="evenodd"></path>
                                    </svg>
                                    GitHub
                                </span>
                            </a>
                            <a :href="data.linkedin" target="_blank" class="group/btn relative px-6 py-2.5 rounded-lg bg-white dark:bg-slate-700 border border-cyan-500 dark:border-cyan-600 text-cyan-600 dark:text-cyan-400 hover:bg-cyan-50 dark:hover:bg-slate-600 font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 overflow-hidden">
                                <!-- Button Shimmer -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-cyan-100/50 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700"></div>
                                
                                <!-- Rotating Ring Background -->
                                <div class="absolute inset-0 rounded-lg border-2 border-cyan-400/20 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-500">
                                    <div class="absolute inset-0 rounded-lg border border-cyan-400/30 animate-spin" style="animation-duration: 8s"></div>
                                </div>
                                
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-5 h-5 mr-2 transform group-hover/btn:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path>
                                    </svg>
                                    LinkedIn
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Academic Background -->
    <section id="academic-background" class="section-padding relative overflow-hidden bg-gradient-to-b from-white to-slate-50 dark:from-slate-900 dark:to-slate-800 transition-colors duration-500">
        <!-- Enhanced Animated gradient background -->
        <div class="absolute inset-0 -z-10 opacity-30 dark:opacity-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-cyan-400/20 to-transparent dark:from-cyan-500/10"></div>
            <div class="absolute -right-20 -top-20 w-72 h-72 bg-cyan-400/30 dark:bg-cyan-500/20 rounded-full mix-blend-multiply filter blur-3xl animate-float"></div>
            <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-cyan-300/30 dark:bg-cyan-400/20 rounded-full mix-blend-multiply filter blur-3xl animate-float" style="animation-delay: 2s"></div>
            
            <!-- Additional floating particles -->
            <div class="absolute top-1/4 right-1/4 w-3 h-3 bg-cyan-400/20 rounded-full animate-pulse"></div>
            <div class="absolute bottom-1/3 left-1/3 w-4 h-4 bg-cyan-300/15 rounded-full animate-pulse" style="animation-delay: 1s"></div>
            <div class="absolute top-2/3 right-1/3 w-2 h-2 bg-cyan-500/25 rounded-full animate-pulse" style="animation-delay: 2s"></div>
        </div>
        
        <div class="container-max relative">
            <div class="text-center mb-16">
                <div class="relative group inline-block">
                    <!-- Background glow for badge -->
                    <div class="absolute inset-0 bg-cyan-400/10 dark:bg-cyan-500/10 rounded-full blur-lg scale-0 group-hover:scale-150 transition-all duration-500"></div>
                    
                    <span class="relative inline-block px-4 py-1.5 text-sm font-medium text-cyan-700 dark:text-cyan-100 bg-cyan-100/80 dark:bg-cyan-900/40 backdrop-blur-sm border border-cyan-200/50 dark:border-cyan-800/50 rounded-full mb-4 transition-all duration-300 hover:scale-105 hover:bg-cyan-200/50 dark:hover:bg-cyan-900/60 hover:border-cyan-300/60 dark:hover:border-cyan-700/60">
                        {{ translations.academic_title || 'Education' }}
                    </span>
                </div>
                </div>
                
                <div class="relative group">
                    <!-- Animated background glow for title -->
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 to-cyan-600/5 dark:from-cyan-400/5 dark:to-cyan-500/5 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                    
                    <h2 class="relative text-4xl font-bold text-slate-800 dark:text-white mb-4 transition-all duration-500 group-hover:scale-105">
                        {{ translations.academic_background }}
                        <!-- Animated underline -->
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-cyan-400 to-cyan-600 dark:from-cyan-300 dark:to-cyan-500 group-hover:w-full transition-all duration-500"></div>
                    </h2>
                </div>
                
                <div class="relative group">
                    <!-- Animated line with glow -->
                    <div class="absolute inset-0 bg-cyan-400/10 dark:bg-cyan-500/10 rounded-full blur scale-0 group-hover:scale-150 transition-all duration-500"></div>
                    <div class="relative w-20 h-1 bg-gradient-to-r from-cyan-400 to-cyan-600 dark:from-cyan-400 dark:to-cyan-500 mx-auto rounded-full transition-all duration-500 group-hover:scale-x-125"></div>
                </div>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="relative group">
                    <!-- Background glow for main card -->
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 to-cyan-600/10 dark:from-cyan-400/10 dark:to-cyan-500/10 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                    
                    <div class="relative bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm rounded-2xl shadow-2xl p-8 md:p-10 animate-slide-up transition-all duration-500 border border-white/30 dark:border-slate-700/70 hover:shadow-2xl hover:-translate-y-1 dark:hover:border-cyan-500/30">
                        <div class="flex flex-col md:flex-row items-start mb-8 group">
                            <div class="relative flex-shrink-0 mb-6 md:mb-0 md:mr-8 transform transition-transform duration-300 group-hover:scale-105">
                                <!-- Rotating ring background -->
                                <div class="absolute inset-0 w-20 h-20 rounded-2xl bg-cyan-400/20 dark:bg-cyan-600/30 scale-0 group-hover:scale-150 transition-transform duration-500"></div>
                                
                                <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 flex items-center justify-center text-white shadow-lg ring-2 ring-white/20 ring-offset-2 ring-offset-cyan-100/50 dark:ring-offset-slate-800/50 transform transition-all duration-500 group-hover:scale-110 group-hover:rotate-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 transform transition-transform duration-300 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    
                                    <!-- Rotating ring -->
                                    <div class="absolute inset-0 rounded-2xl border-2 border-cyan-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        <div class="absolute inset-0 rounded-2xl border border-cyan-400/30 animate-spin" style="animation-duration: 10s"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="relative text-2xl font-bold text-slate-800 dark:text-white mb-2 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors duration-300">
                                    {{ data.education?.degree || 'Computer Science Degree' }}
                                    <!-- Animated underline -->
                                    <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-cyan-400 to-cyan-600 dark:from-cyan-300 dark:to-cyan-500 group-hover:w-full transition-all duration-500"></div>
                                </h3>
                                <p class="text-lg text-cyan-600 dark:text-cyan-400 font-medium mb-1 transition-colors duration-300 group-hover:text-cyan-700 dark:group-hover:text-cyan-300">
                                    {{ data.education?.institution || 'University Name' }}
                                </p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 transition-colors duration-300 group-hover:text-slate-600 dark:group-hover:text-slate-300">
                                    {{ data.education?.period || '2018 - 2022' }}
                                </p>
                            </div>
                        </div>
                    
                    <div class="prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300">
                        <p class="text-lg leading-relaxed mb-6">
                            {{ translations.education_text }}
                        </p>
                        
                        <div v-if="data.education?.highlights" class="mt-8 space-y-4">
                            <div class="relative group">
                                <!-- Background glow for achievements section -->
                                <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 to-cyan-600/5 dark:from-cyan-400/5 dark:to-cyan-500/5 rounded-xl blur-lg opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                                 
                                <h4 class="relative text-xl font-semibold text-slate-800 dark:text-white flex items-center transition-all duration-500 group-hover:scale-105">
                                    <div class="relative mr-2">
                                        <!-- Icon background ring -->
                                        <div class="absolute inset-0 w-5 h-5 rounded-full bg-cyan-400/20 dark:bg-cyan-600/30 scale-0 group-hover:scale-150 transition-transform duration-500"></div>
                                        
                                        <svg class="relative w-5 h-5 text-cyan-500 dark:text-cyan-400 transition-colors duration-300 group-hover:text-cyan-600 dark:group-hover:text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        
                                        <!-- Rotating ring -->
                                        <div class="absolute inset-0 rounded-full border-2 border-cyan-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                            <div class="absolute inset-0 rounded-full border border-cyan-400/30 animate-spin" style="animation-duration: 8s"></div>
                                        </div>
                                    </div>
                                    Key Achievements
                                    <!-- Animated underline -->
                                    <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-cyan-400 to-cyan-600 dark:from-cyan-300 dark:to-cyan-500 group-hover:w-full transition-all duration-500"></div>
                                </h4>
                            </div>
                             
                            <ul class="space-y-3">
                                <li v-for="(highlight, index) in data.education.highlights" :key="index" class="group relative flex items-start">
                                    <!-- Hover background -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-50/30 to-transparent dark:from-cyan-900/20 dark:to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                                    
                                    <span class="relative flex-shrink-0 w-2 h-2 mt-2.5 mr-3 rounded-full bg-cyan-500 dark:bg-cyan-400 group-hover:scale-150 transition-transform duration-300"></span>
                                    <span class="flex-1 text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors duration-300">
                                        {{ highlight }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        
                        <div v-if="data.education?.skills" class="mt-10 pt-8 border-t border-slate-200 dark:border-slate-700/70">
                            <div class="relative group">
                                <!-- Background glow for skills section -->
                                <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 to-cyan-600/5 dark:from-cyan-400/5 dark:to-cyan-500/5 rounded-xl blur-lg opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                                 
                                <h4 class="relative text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center transition-all duration-500 group-hover:scale-105">
                                    <div class="relative mr-2">
                                        <!-- Icon background ring -->
                                        <div class="absolute inset-0 w-5 h-5 rounded-full bg-cyan-400/20 dark:bg-cyan-600/30 scale-0 group-hover:scale-150 transition-transform duration-500"></div>
                                        
                                        <svg class="relative w-5 h-5 text-cyan-500 dark:text-cyan-400 transition-colors duration-300 group-hover:text-cyan-600 dark:group-hover:text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                        
                                        <!-- Rotating ring -->
                                        <div class="absolute inset-0 rounded-full border-2 border-cyan-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                            <div class="absolute inset-0 rounded-full border border-cyan-400/30 animate-spin" style="animation-duration: 12s"></div>
                                        </div>
                                    </div>
                                    Skills Gained
                                    <!-- Animated underline -->
                                    <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-cyan-400 to-cyan-600 dark:from-cyan-300 dark:to-cyan-500 group-hover:w-full transition-all duration-500"></div>
                                </h4>
                            </div>
                             
                            <div class="flex flex-wrap gap-2.5">
                                <span v-for="(skill, index) in data.education.skills" :key="index" 
                                      class="relative group px-4 py-2 text-sm font-medium rounded-full bg-cyan-100/70 dark:bg-cyan-900/40 text-cyan-700 dark:text-cyan-300 border border-cyan-200/50 dark:border-cyan-800/50 hover:bg-cyan-200/50 dark:hover:bg-cyan-800/50 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                                    <!-- Hover glow effect -->
                                    <div class="absolute inset-0 bg-cyan-400/20 dark:bg-cyan-600/30 rounded-full scale-0 group-hover:scale-150 transition-transform duration-500"></div>
                                    <span class="relative z-10">{{ skill }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="data.education?.skills" class="mt-10 pt-8 border-t border-slate-200 dark:border-slate-700/70">
                        <h4 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
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
