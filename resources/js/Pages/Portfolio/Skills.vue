<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import PortfolioLayout from '@/Layouts/PortfolioLayout.vue';
import { useScrollAnimations } from '@/Composables/useScrollAnimations.js';
import { useGeometricShowcase } from '@/Composables/useGeometricShowcase.js';
import { useSpecializedGeometrics } from '@/Composables/useSpecializedGeometrics.js';

defineOptions({ layout: PortfolioLayout });

const props = defineProps({
    data: Object,
    translations: Object,
    currentLocale: String,
});

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

// Initialize specialized geometrics for different sections
const {
    hexagonGrid,
    circularPattern,
    handleMouseMove: handleSpecializedMouseMove,
    getHexagonStyle,
    getCircleStyle
} = useSpecializedGeometrics();

const skillLevels = ref({
    web_development: 90,
    ai_ml: 85,
    embedded_iot: 80,
    programming_languages: 88,
    tools_platforms: 92,
});

onMounted(() => {
    // Animate progress bars when they come into view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBar = entry.target;
                const level = progressBar.getAttribute('data-level');
                progressBar.style.setProperty('--progress-width', `${level}%`);
                progressBar.classList.add('animate');
            }
        });
    }, { threshold: 0.5 });

    setTimeout(() => {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => observer.observe(bar));
    }, 100);
});
</script>

<template>
    <Head :title="translations.skills" />
    
    <!-- Interactive Hero Section with Geometric Showcase -->
    <section 
        @mousemove="handleMouseMove"
        class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-cyan-600 via-cyan-700 to-cyan-800 dark:from-cyan-700 dark:via-cyan-800 dark:to-cyan-900 text-white transition-all duration-500 overflow-hidden"
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
                        {{ translations.skills_title }}
                        <!-- Animated Underline -->
                        <div class="absolute -bottom-2 left-0 right-0 h-1 bg-gradient-to-r from-cyan-300 via-white to-cyan-300 transform scale-x-0 animate-expand-line"></div>
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 blur-xl bg-cyan-300/30 animate-pulse"></div>
                    </span>
                </h1>
                
                <!-- Animated Subtitle with Typing Effect -->
                <p class="text-xl md:text-2xl lg:text-3xl max-w-4xl mx-auto text-cyan-50 dark:text-cyan-100 mb-12 animate-slide-up leading-relaxed" style="animation-delay: 0.3s">
                    {{ translations.technical_skills }}
                </p>
                
                <!-- Interactive Call-to-Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center animate-slide-up" style="animation-delay: 0.6s">
                    <a 
                        href="#expertise-areas" 
                        class="group relative px-8 py-4 rounded-2xl bg-gradient-to-r from-cyan-400 to-cyan-500 hover:from-cyan-500 hover:to-cyan-600 text-white font-semibold shadow-2xl hover:shadow-cyan-500/25 transform hover:scale-105 transition-all duration-500 overflow-hidden"
                    >
                        <!-- Button Shimmer Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        <!-- Button Glow -->
                        <div class="absolute inset-0 rounded-2xl bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 animate-bounce" style="animation-delay: 0.2s" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ translations.expertise_areas || 'Explore Skills' }}
                        </span>
                    </a>
                    <a 
                        href="#technical-skills" 
                        class="group relative px-8 py-4 rounded-2xl border-2 border-white/30 hover:border-white/60 text-white font-semibold backdrop-blur-sm hover:bg-white/10 transform hover:scale-105 transition-all duration-500 overflow-hidden"
                    >
                        <!-- Hover Background -->
                        <div class="absolute inset-0 bg-gradient-to-r from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative z-10 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            {{ translations.technical_skills || 'Technical Skills' }}
                        </span>
                    </a>
                </div>
                
                <!-- Floating Skill Icons -->
                <div class="absolute top-20 left-10 animate-float opacity-60">
                    <div class="w-16 h-16 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/20">
                        <svg class="w-8 h-8 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
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
            <div class="flex flex-col items-center group cursor-pointer" onclick="document.getElementById('expertise-areas').scrollIntoView({behavior: 'smooth'})">
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
    
    <!-- Enhanced Expertise Areas with Circular Pattern -->
    <section 
        id="expertise-areas"
        @mousemove="handleSpecializedMouseMove"
        class="section-padding bg-white dark:bg-slate-900 transition-colors duration-500 relative overflow-hidden"
    >
        <!-- Circular Pattern Geometric Elements -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Floating Circular Elements -->
            <div
                v-for="element in circularPattern"
                :key="element.id"
                :style="getCircleStyle(element)"
            />
            
            <!-- Orbital Connection Lines -->
            <svg class="absolute inset-0 w-full h-full">
                <circle
                    v-for="(element, index) in circularPattern.slice(0, 5)"
                    :key="`orbit-${element.id}`"
                    :cx="element.x"
                    :cy="element.y"
                    :r="30 + index * 10"
                    fill="none"
                    :stroke="element.color"
                    :opacity="0.1"
                    stroke-width="1"
                    stroke-dasharray="5,5"
                    class="animate-spin"
                    :style="`animation-duration: ${20 + index * 5}s`"
                />
            </svg>
            
            <!-- Radial Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-cyan-50/20 via-transparent to-cyan-100/20 dark:from-cyan-900/5 dark:to-cyan-900/5"></div>
        </div>
        
        <div class="container-max relative z-10">
            <div class="text-center mb-16 animate-on-scroll">
                <div class="relative group inline-block">
                    <!-- Background glow for badge -->
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-400/15 to-cyan-500/15 dark:from-cyan-500/10 dark:to-cyan-500/10 rounded-full blur-lg scale-0 group-hover:scale-150 transition-all duration-500"></div>
                    
                    <span class="relative inline-block px-4 py-1.5 text-sm font-medium bg-gradient-to-r from-cyan-700 to-cyan-600 dark:from-cyan-400 dark:to-cyan-400 text-white bg-clip-text backdrop-blur-sm border border-cyan-300/60 dark:border-cyan-800/50 rounded-full mb-4 transition-all duration-300 hover:scale-110 hover:shadow-lg">
                        {{ translations.expertise_areas || 'Core Competencies' }}
                    </span>
                </div>
                
                <h2 class="text-4xl md:text-5xl font-bold text-slate-800 dark:text-white mb-4 transition-all duration-500 group-hover:scale-105">
                    {{ translations.expertise_areas }}
                    <!-- Animated underline with gradient -->
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-1 bg-gradient-to-r from-cyan-400 via-cyan-300 to-cyan-500 group-hover:w-full transition-all duration-700"></div>
                </h2>
                
                <div class="relative group">
                    <!-- Animated line with dual gradient -->
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-400/15 to-cyan-500/15 rounded-full blur scale-0 group-hover:scale-150 transition-all duration-500"></div>
                    <div class="relative w-20 h-1 bg-gradient-to-r from-cyan-500 to-cyan-600 mx-auto rounded-full transition-all duration-500 group-hover:scale-x-125 group-hover:shadow-lg"></div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="(area, index) in data.expertise_areas" :key="index" 
                     class="group relative bg-cyan-100 dark:bg-cyan-900 rounded-2xl shadow-lg p-6 text-center animate-on-scroll transition-all duration-500 hover:shadow-2xl hover:-translate-y-3 border border-cyan-200/50 dark:border-cyan-800/50 overflow-hidden" 
                     :style="`animation-delay: ${index * 0.1}s`"
                     data-stagger>
                    
                    <!-- Interactive Background Effect with Radial Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50/30 via-transparent to-cyan-100/30 dark:from-cyan-800/40 dark:to-cyan-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                    
                    <!-- Circular Shimmer Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1200" />
                    
                    <!-- Rotating Orbital Background -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-30 transition-opacity duration-500">
                        <div class="absolute top-1/2 left-1/2 w-32 h-32 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-cyan-400/30 animate-spin" style="animation-duration: 10s"></div>
                        <div class="absolute top-1/2 left-1/2 w-24 h-24 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-cyan-500/30 animate-spin" style="animation-duration: 8s; animation-direction: reverse;"></div>
                    </div>
                    
                    <!-- Icon Container with Orbital Effects -->
                    <div class="relative w-16 h-16 mx-auto mb-4">
                        <!-- Rotating Ring Background -->
                        <div class="absolute inset-0 w-16 h-16 rounded-full bg-gradient-to-r from-cyan-400/30 to-cyan-500/30 scale-0 group-hover:scale-150 transition-transform duration-500"></div>
                        
                        <div class="relative w-16 h-16 rounded-full bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                            <svg class="w-8 h-8 text-white group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            
                            <!-- Double Ring Pulse Effect -->
                            <div class="absolute inset-0 rounded-full border-2 border-cyan-400/40 dark:border-cyan-300/60 opacity-0 group-hover:animate-ping"></div>
                            <div class="absolute inset-0 rounded-full border-2 border-cyan-500/40 dark:border-cyan-400/60 opacity-0 group-hover:animate-ping" style="animation-delay: 0.5s"></div>
                        </div>
                    </div>
                    
                    <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-100 relative z-10 group-hover:text-transparent group-hover:bg-gradient-to-r group-hover:from-cyan-600 group-hover:to-cyan-600 group-hover:bg-clip-text transition-all duration-300 group-hover:translate-y-[-2px]">
                        {{ area }}
                        <!-- Animated Underline -->
                        <div class="absolute -bottom-1 left-0 right-0 h-0.5 bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                    </h3>
                    
                    <!-- Dual Hover Indicator -->
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-600 to-cyan-600 dark:from-cyan-500 dark:to-cyan-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                    
                    <!-- Floating Orbital Particles -->
                    <div class="absolute top-2 right-2 w-2 h-2 bg-cyan-500/50 rounded-full animate-pulse opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-2 left-2 w-1.5 h-1.5 bg-cyan-400/50 rounded-full animate-pulse opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="animation-delay: 0.5s"></div>
                    <div class="absolute top-1/2 right-4 w-1 h-1 bg-cyan-300/50 rounded-full animate-pulse opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="animation-delay: 1s"></div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Enhanced Technical Skills -->
    <section 
        id="technical-skills"
        @mousemove="handleMouseMove"
        class="section-padding bg-slate-50 dark:bg-gradient-to-br dark:from-slate-900/95 dark:to-slate-900 transition-colors duration-500 relative overflow-hidden"
    >
        <!-- Geometric Elements Layer -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Connection Lines -->
            <svg class="absolute inset-0 w-full h-full">
                <line
                    v-for="line in connectionLines.slice(0, 5)"
                    :key="line.id"
                    :x1="line.x1"
                    :y1="line.y1"
                    :x2="line.x2"
                    :y2="line.y2"
                    :stroke="line.color"
                    :opacity="line.opacity * 0.2"
                    stroke-width="1"
                />
            </svg>
            
            <!-- Floating Elements -->
            <div
                v-for="element in geometricElements.slice(0, 6)"
                :key="element.id"
                :style="getElementStyle(element)"
            />
        </div>
        
        <div class="container-max relative z-10">
            <div class="text-center mb-16 animate-on-scroll">
                <div class="relative group inline-block">
                    <div class="absolute inset-0 bg-cyan-400/10 dark:bg-cyan-500/10 rounded-full blur-lg scale-0 group-hover:scale-150 transition-all duration-500"></div>
                    
                    <span class="relative inline-block px-4 py-1.5 text-sm font-medium text-cyan-700 dark:text-cyan-100 bg-cyan-100/80 dark:bg-cyan-900/40 backdrop-blur-sm border border-cyan-200/50 dark:border-cyan-800/50 rounded-full mb-4 transition-all duration-300 hover:scale-105">
                        {{ translations.technical_skills || 'Technologies' }}
                    </span>
                </div>
                
                <h2 class="text-4xl md:text-5xl font-bold text-slate-800 dark:text-white mb-4 transition-all duration-500">
                    {{ translations.technical_skills }}
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-cyan-400 to-cyan-600 dark:from-cyan-300 dark:to-cyan-500 group-hover:w-full transition-all duration-500"></div>
                </h2>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Enhanced Web Development -->
                <div class="group relative bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 animate-on-scroll transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 border border-white/20 dark:border-slate-700/50 overflow-hidden" 
                     :style="'animation-delay: 0.1s'"
                     data-stagger>
                    
                    <!-- Interactive Background Effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50/0 via-transparent to-cyan-100/0 dark:from-cyan-800/40 dark:to-cyan-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                    
                    <!-- Shimmer Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000" />
                    
                    <div class="relative z-10">
                        <div class="flex items-center mb-4">
                            <div class="relative w-10 h-10 mr-3">
                                <div class="absolute inset-0 w-10 h-10 rounded-lg bg-cyan-400/20 dark:bg-cyan-600/30 scale-0 group-hover:scale-150 transition-transform duration-500"></div>
                                <div class="relative w-10 h-10 rounded-lg bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-cyan-500 dark:text-cyan-400 group-hover:text-cyan-600 dark:group-hover:text-cyan-300 transition-colors duration-300">
                                {{ translations.web_dev }}
                            </h3>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <span v-for="skill in data.technical_skills.web_development" :key="skill" 
                                  class="skill-tag group/skill relative px-4 py-2 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 text-sm font-medium rounded-full border border-slate-200 dark:border-slate-600 transition-all duration-300 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 hover:border-cyan-200 dark:hover:border-cyan-700/50 hover:text-cyan-600 dark:hover:text-cyan-400 hover:scale-105 hover:shadow-lg">
                                {{ skill }}
                                <!-- Skill hover effect -->
                                <div class="absolute inset-0 rounded-full bg-cyan-400/10 scale-0 group-hover/skill:scale-100 transition-transform duration-300"></div>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- AI & Machine Learning -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 animate-slide-in-right transition-all duration-500 hover:shadow-xl hover:-translate-y-1 border border-white/20 dark:border-slate-700/50">
                    <h3 class="text-xl font-bold mb-4 text-cyan-500 dark:text-cyan-400">
                        {{ translations.ai_ml }}
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <span v-for="skill in data.technical_skills.ai_ml" :key="skill" 
                              class="px-4 py-2 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 text-sm font-medium rounded-full border border-slate-200 dark:border-slate-600 transition-all duration-300 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 hover:border-cyan-200 dark:hover:border-cyan-700/50 hover:text-cyan-600 dark:hover:text-cyan-400">
                            {{ skill }}
                        </span>
                    </div>
                </div>
                
                <!-- Embedded Systems & IoT -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 animate-slide-in-left transition-all duration-500 hover:shadow-xl hover:-translate-y-1 border border-white/20 dark:border-slate-700/50">
                    <h3 class="text-xl font-bold mb-4 text-cyan-500 dark:text-cyan-400">
                        {{ translations.embedded_iot }}
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <span v-for="skill in data.technical_skills.embedded_iot" :key="skill" 
                              class="px-4 py-2 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 text-sm font-medium rounded-full border border-slate-200 dark:border-slate-600 transition-all duration-300 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 hover:border-cyan-200 dark:hover:border-cyan-700/50 hover:text-cyan-600 dark:hover:text-cyan-400">
                            {{ skill }}
                        </span>
                    </div>
                </div>
                
                <!-- Programming Languages -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 animate-slide-in-right transition-all duration-500 hover:shadow-xl hover:-translate-y-1 border border-white/20 dark:border-slate-700/50">
                    <h3 class="text-xl font-bold mb-4 text-cyan-500 dark:text-cyan-400">
                        {{ translations.programming_languages }}
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <span v-for="skill in data.technical_skills.programming_languages" :key="skill" 
                              class="px-4 py-2 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 text-sm font-medium rounded-full border border-slate-200 dark:border-slate-600 transition-all duration-300 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 hover:border-cyan-200 dark:hover:border-cyan-700/50 hover:text-cyan-600 dark:hover:text-cyan-400">
                            {{ skill }}
                        </span>
                    </div>
                </div>
                
                <!-- Tools & Platforms -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 animate-slide-in-left transition-all duration-500 hover:shadow-xl hover:-translate-y-1 border border-white/20 dark:border-slate-700/50">
                    <h3 class="text-xl font-bold mb-4 text-cyan-500 dark:text-cyan-400">
                        {{ translations.tools_platforms }}
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <span v-for="skill in data.technical_skills.tools_platforms" :key="skill" 
                              class="px-4 py-2 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 text-sm font-medium rounded-full border border-slate-200 dark:border-slate-600 transition-all duration-300 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 hover:border-cyan-200 dark:hover:border-cyan-700/50 hover:text-cyan-600 dark:hover:text-cyan-400">
                            {{ skill }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Enhanced Skills Progress with Hexagon Grid -->
    <section 
        @mousemove="handleSpecializedMouseMove"
        class="section-padding bg-white dark:bg-slate-900 transition-colors duration-500 relative overflow-hidden"
    >
        <!-- Hexagon Grid Geometric Elements -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Floating Hexagon Elements -->
            <div
                v-for="element in hexagonGrid"
                :key="element.id"
                :style="getHexagonStyle(element)"
            />
            
            <!-- Hexagonal Connection Pattern -->
            <svg class="absolute inset-0 w-full h-full">
                <!-- Hexagon Grid Lines -->
                <g v-for="(element, index) in hexagonGrid.slice(0, 6)" :key="`hex-connections-${element.id}`">
                    <line
                        v-for="otherElement in hexagonGrid.slice(0, 6)"
                        :key="`hex-line-${element.id}-${otherElement.id}`"
                        v-show="otherElement.id !== element.id"
                        :x1="element.x"
                        :y1="element.y"
                        :x2="otherElement.x"
                        :y2="otherElement.y"
                        :stroke="element.color"
                        :opacity="0.05"
                        stroke-width="1"
                        stroke-dasharray="2,4"
                    />
                </g>
            </svg>
            
            <!-- Hexagonal Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-50/10 via-transparent to-cyan-50/10 dark:from-cyan-900/5 dark:to-cyan-900/5"></div>
            
            <!-- Animated Hexagon Field -->
            <div class="absolute inset-0">
                <div class="absolute top-1/4 left-1/4 w-3 h-3 bg-cyan-400/20 rounded-sm animate-pulse transform rotate-45"></div>
                <div class="absolute top-3/4 right-1/4 w-4 h-4 bg-cyan-400/20 rounded-sm animate-pulse transform rotate-45" style="animation-delay: 0.5s"></div>
                <div class="absolute top-1/2 left-3/4 w-2 h-2 bg-cyan-300/20 rounded-sm animate-pulse transform rotate-45" style="animation-delay: 1s"></div>
                <div class="absolute bottom-1/4 right-1/2 w-3 h-3 bg-cyan-300/20 rounded-sm animate-pulse transform rotate-45" style="animation-delay: 1.5s"></div>
            </div>
        </div>
        
        <div class="container-max relative z-10">
            <div class="text-center mb-16 animate-on-scroll">
                <div class="relative group inline-block">
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-400/10 to-cyan-400/10 dark:from-cyan-500/10 dark:to-cyan-500/10 rounded-full blur-lg scale-0 group-hover:scale-150 transition-all duration-500"></div>
                    
                    <span class="relative inline-block px-4 py-1.5 text-sm font-medium bg-gradient-to-r from-cyan-600 to-cyan-600 dark:from-cyan-400 dark:to-cyan-400 text-white bg-clip-text backdrop-blur-sm border border-cyan-200/50 dark:border-cyan-800/50 rounded-full mb-4 transition-all duration-300 hover:scale-110 hover:shadow-lg">
                        {{ translations.proficiency_level || 'Skill Levels' }}
                    </span>
                </div>
                
                <h2 class="text-4xl md:text-5xl font-bold text-slate-800 dark:text-white mb-4 transition-all duration-500">
                    {{ translations.proficiency_level }}
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-1 bg-gradient-to-r from-cyan-400 via-cyan-300 to-cyan-400 group-hover:w-full transition-all duration-700"></div>
                </h2>
            </div>
            
            <div class="max-w-4xl mx-auto space-y-8">
                <div v-for="(level, categoryName, index) in skillLevels" :key="categoryName" 
                     class="group relative animate-on-scroll" 
                     :style="`animation-delay: ${index * 0.15}s`"
                     data-stagger>
                    
                    <!-- Hexagonal Background Glow Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 to-cyan-600/5 dark:from-cyan-400/5 dark:to-cyan-500/5 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                    
                    <!-- Hexagonal Pattern Overlay -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                        <div class="absolute top-2 left-2 w-4 h-4 bg-cyan-400/30 transform rotate-45"></div>
                        <div class="absolute top-2 right-2 w-4 h-4 bg-cyan-400/30 transform rotate-45"></div>
                        <div class="absolute bottom-2 left-2 w-4 h-4 bg-cyan-400/30 transform rotate-45"></div>
                        <div class="absolute bottom-2 right-2 w-4 h-4 bg-cyan-400/30 transform rotate-45"></div>
                    </div>
                    
                    <div class="relative">
                        <div class="flex justify-between items-center mb-3">
                            <div class="flex items-center">
                                <div class="relative w-8 h-8 mr-3">
                                    <div class="absolute inset-0 w-8 h-8 transform rotate-45 bg-cyan-400/20 dark:bg-cyan-600/30 scale-0 group-hover:scale-150 transition-transform duration-500"></div>
                                    <div class="relative w-8 h-8 transform rotate-45 bg-gradient-to-r from-cyan-500 to-cyan-500 dark:from-cyan-600 dark:to-cyan-600 flex items-center justify-center group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                                        <svg class="w-4 h-4 text-white transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    
                                    <!-- Hexagonal Ring -->
                                    <div class="absolute inset-0 w-8 h-8 transform rotate-45 border-2 border-cyan-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        <div class="absolute inset-0 w-8 h-8 transform rotate-45 border border-cyan-400/30 animate-spin" style="animation-duration: 6s"></div>
                                    </div>
                                </div>
                                <h3 class="font-semibold capitalize text-slate-800 dark:text-slate-200 text-lg transition-all duration-300 group-hover:text-transparent group-hover:bg-gradient-to-r group-hover:from-cyan-500 group-hover:to-cyan-500 group-hover:bg-clip-text group-hover:translate-x-2">
                                    {{ categoryName.replace(/_/g, ' ') }}
                                </h3>
                            </div>
                            <div class="relative">
                                <span class="text-gradient bg-gradient-to-r from-cyan-500 to-cyan-500 dark:from-cyan-400 dark:to-cyan-400 font-semibold transition-all duration-300 group-hover:scale-110">
                                    {{ level }}%
                                </span>
                                <!-- Hexagonal Animated Ring -->
                                <div class="absolute inset-0 w-12 h-12 -translate-x-2 -translate-y-1/2 border-2 border-cyan-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                    <div class="absolute inset-0 w-12 h-12 border border-cyan-400/30 animate-spin" style="animation-duration: 10s"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative w-full bg-gray-200 dark:bg-slate-700/50 rounded-full h-3 overflow-hidden group-hover:shadow-lg transition-all duration-300">
                            <!-- Enhanced Progress Bar with Hexagonal Effects -->
                            <div 
                                class="progress-bar relative h-3 rounded-full bg-gradient-to-r from-cyan-500 to-cyan-500 dark:from-cyan-400 dark:to-cyan-400 transition-all duration-1500 ease-out group-hover:shadow-[0_0_25px_rgba(6,182,212,0.4)]"
                                :data-level="level"
                                :style="{ '--progress-width': '0%' }">
                                
                                <!-- Hexagonal Shimmer Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1200"
                                     style="background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%);"></div>
                                
                                <!-- Hexagonal Pulse Pattern -->
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100">
                                    <div class="absolute top-0 left-1/4 w-2 h-2 bg-cyan-400/40 transform rotate-45"></div>
                                    <div class="absolute top-0 right-1/4 w-2 h-2 bg-cyan-400/40 transform rotate-45"></div>
                                    <div class="absolute bottom-0 left-1/3 w-1.5 h-1.5 bg-cyan-400/40 transform rotate-45"></div>
                                    <div class="absolute bottom-0 right-1/3 w-1.5 h-1.5 bg-cyan-400/40 transform rotate-45"></div>
                                </div>
                                
                                <!-- Floating Hexagonal Particles -->
                                <div class="absolute top-1/2 -translate-y-1/2 left-2 w-1.5 h-1.5 bg-cyan-400/60 rounded-sm transform rotate-45 opacity-0 group-hover:opacity-100 animate-ping"></div>
                                <div class="absolute top-1/2 -translate-y-1/2 right-2 w-1.5 h-1.5 bg-cyan-400/60 rounded-sm transform rotate-45 opacity-0 group-hover:opacity-100 animate-ping" style="animation-delay: 0.5s"></div>
                            </div>
                            
                            <!-- Hexagonal Progress Indicator -->
                            <div class="absolute top-0 right-0 bottom-0 w-1 bg-gradient-to-b from-cyan-400 to-cyan-400 rounded-r-full transform scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-top"></div>
                        </div>
                        
                        <!-- Enhanced Skill Level Badges with Hexagonal Design -->
                        <div class="flex gap-2 mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span v-if="level >= 90" class="px-3 py-1 text-xs font-medium bg-gradient-to-r from-cyan-100 to-cyan-100 dark:from-cyan-900/30 dark:to-cyan-900/30 text-cyan-700 dark:text-cyan-300 rounded-full border border-cyan-200/50 dark:border-cyan-700/50">Expert</span>
                            <span v-else-if="level >= 75" class="px-3 py-1 text-xs font-medium bg-gradient-to-r from-cyan-100 to-cyan-100 dark:from-cyan-900/30 dark:to-cyan-900/30 text-cyan-700 dark:text-cyan-300 rounded-full border border-cyan-200/50 dark:border-cyan-700/50">Advanced</span>
                            <span v-else-if="level >= 60" class="px-3 py-1 text-xs font-medium bg-gradient-to-r from-cyan-100 to-cyan-100 dark:from-cyan-900/30 dark:to-cyan-900/30 text-cyan-700 dark:text-cyan-300 rounded-full border border-cyan-200/50 dark:border-cyan-700/50">Intermediate</span>
                            <span v-else class="px-3 py-1 text-xs font-medium bg-gradient-to-r from-cyan-100 to-cyan-100 dark:from-cyan-900/30 dark:to-cyan-900/30 text-cyan-700 dark:text-cyan-300 rounded-full border border-cyan-200/50 dark:border-cyan-700/50">Beginner</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
