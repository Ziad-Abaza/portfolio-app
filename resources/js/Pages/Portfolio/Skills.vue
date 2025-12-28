<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import PortfolioLayout from '@/Layouts/PortfolioLayout.vue';
import { useScrollAnimations } from '@/Composables/useScrollAnimations.js';

defineOptions({ layout: PortfolioLayout });

const props = defineProps({
    data: Object,
    translations: Object,
    currentLocale: String,
});

useScrollAnimations();

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
    
    <!-- Hero Section -->
    <section class="section-padding bg-gradient-to-r from-cyan-600 to-cyan-800 dark:from-cyan-700 dark:to-cyan-900 text-white transition-colors duration-500">
        <div class="container-max">
            <div class="text-center animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    {{ translations.skills_title }}
                </h1>
                <p class="text-xl max-w-3xl mx-auto text-cyan-100 dark:text-cyan-50">
                    {{ translations.technical_skills }}
                </p>
            </div>
        </div>
    </section>
    
    <!-- Expertise Areas -->
    <section class="section-padding bg-white dark:bg-slate-900 transition-colors duration-500">
        <div class="container-max">
            <h2 class="text-3xl font-bold text-center mb-12 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-300">
                {{ translations.expertise_areas }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="(area, index) in data.expertise_areas" :key="index" 
                     class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 text-center animate-slide-up transition-all duration-500 hover:shadow-xl hover:-translate-y-1 border border-white/20 dark:border-slate-700/50" 
                     :style="`animation-delay: ${index * 0.1}s`">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-100">{{ area }}</h3>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Technical Skills -->
    <section class="section-padding bg-slate-50 dark:bg-gradient-to-br dark:from-slate-900/95 dark:to-slate-900 transition-colors duration-500 relative overflow-hidden">
        <!-- Subtle background pattern -->
        <div class="absolute inset-0 opacity-5 dark:opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cGF0aCBkPSJNMjQgMzRjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtMTAtMTBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtMTAgMGMwLTIuMjA5LTEuNzkxLTQtNC00cy00IDEuNzkxLTQgNCAxLjc5MSA0IDQgNCA0LTEuNzkxIDQtNHptLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIwOS0xLjc5MS00LTQtNHMtNCAxLjc5MS00IDQgMS43OTEgNCA0IDQgNC0xLjc5MSA0LTRtLTEwIDBjMC0yLjIw9LTEuNzkxLTQtNC00cy00IDEuNzkxLTQgNCAxLjc5MSA0IDQgNCA0LTEuNzkxIDQtNHYwIiBzdHJva2U9IiMwMGFmZGYiIHN0cm9rZS13aWR0aD0iMSIvPjwvc3ZnPg==')]"></div>
        <div class="container-max">
            <h2 class="text-3xl font-bold text-center mb-12 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-300">
                {{ translations.technical_skills }}
            </h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Web Development -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 animate-slide-in-left transition-all duration-500 hover:shadow-xl hover:-translate-y-1 border border-white/20 dark:border-slate-700/50">
                    <h3 class="text-xl font-bold mb-4 text-cyan-500 dark:text-cyan-400">
                        {{ translations.web_dev }}
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <span v-for="skill in data.technical_skills.web_development" :key="skill" 
                              class="px-4 py-2 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 text-sm font-medium rounded-full border border-slate-200 dark:border-slate-600 transition-all duration-300 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 hover:border-cyan-200 dark:hover:border-cyan-700/50 hover:text-cyan-600 dark:hover:text-cyan-400">
                            {{ skill }}
                        </span>
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
    
    <!-- Skills Progress -->
    <section class="section-padding bg-white dark:bg-slate-900 transition-colors duration-500">
        <div class="container-max">
            <h2 class="text-3xl font-bold text-center mb-12 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-300">
                {{ translations.proficiency_level }}
            </h2>
            <div class="max-w-4xl mx-auto space-y-8">
                <div v-for="(level, categoryName) in skillLevels" :key="categoryName" 
                     class="animate-on-scroll group">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-semibold capitalize text-slate-800 dark:text-slate-200 text-lg transition-colors duration-300 group-hover:text-cyan-500 dark:group-hover:text-cyan-400">
                            {{ categoryName.replace(/_/g, ' ') }}
                        </h3>
                        <span class="text-cyan-500 dark:text-cyan-400 font-semibold transition-colors duration-300">{{ level }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-slate-700/50 rounded-full h-2.5 overflow-hidden">
                        <div 
                            class="progress-bar h-2.5 rounded-full bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-500 transition-all duration-1000 ease-out"
                            :data-level="level"
                            :style="{ '--progress-width': '0%' }"
                            :class="{ 'shadow-[0_0_10px_rgba(6,182,212,0.5)]': $page.props.darkMode }">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
