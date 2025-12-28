<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';
import BackToTop from '@/Components/BackToTop.vue';

const page = usePage();

const props = defineProps({
    title: String,
    translations: Object,
    currentLocale: String,
});

const isRTL = computed(() => props.currentLocale === 'ar');
const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
};
</script>

<template>
    <div :class="{ 'rtl': isRTL }" class="min-h-screen">
        <Head :title="title" />
        <meta name="csrf-token" :content="page.props.csrfToken">
        
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md shadow-sm z-50 transition-all duration-500 border-b border-slate-100/50 dark:border-slate-800/50">
            <div class="container-max">
                <div class="flex items-center justify-between h-16 px-4">
                    <!-- Logo -->
                    <Link :href="route('portfolio.home')" class="text-2xl font-bold text-gradient">
                        ZH
                    </Link>
                    
                    <!-- Desktop Navigation Links -->
                    <div class="hidden md:flex items-center space-x-2">
                        <Link :href="route('portfolio.home')" class="px-4 py-2.5 rounded-lg text-slate-800 dark:text-slate-100 font-medium transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-cyan-600 dark:hover:text-cyan-300">
                            {{ translations.home }}
                        </Link>
                        <Link :href="route('portfolio.about')" class="px-4 py-2.5 rounded-lg text-slate-800 dark:text-slate-100 font-medium transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-cyan-600 dark:hover:text-cyan-300">
                            {{ translations.about }}
                        </Link>
                        <Link :href="route('portfolio.skills')" class="px-4 py-2.5 rounded-lg text-slate-800 dark:text-slate-100 font-medium transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-cyan-600 dark:hover:text-cyan-300">
                            {{ translations.skills }}
                        </Link>
                        <Link :href="route('portfolio.projects')" class="px-4 py-2.5 rounded-lg text-slate-800 dark:text-slate-100 font-medium transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-cyan-600 dark:hover:text-cyan-300">
                            {{ translations.projects }}
                        </Link>
                        <Link :href="route('portfolio.contact')" class="px-4 py-2.5 rounded-lg text-slate-800 dark:text-slate-100 font-medium transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-cyan-600 dark:hover:text-cyan-300">
                            {{ translations.contact }}
                        </Link>
                    </div>
                    
                    <!-- Desktop Controls -->
                    <div class="hidden md:flex items-center space-x-4">
                        <LanguageSwitcher :current-locale="currentLocale" />
                        <DarkModeToggle />
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center space-x-2">
                        <DarkModeToggle />
                        <button
                            @click="toggleMobileMenu"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                        >
                            <svg v-if="!isMobileMenuOpen" class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg v-else class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Menu -->
                <div v-if="isMobileMenuOpen" class="md:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-2 space-y-1">
                        <Link @click="closeMobileMenu" :href="route('portfolio.home')" class="block px-4 py-3 rounded-lg text-slate-800 dark:text-slate-100 font-medium transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-cyan-600 dark:hover:text-cyan-300">
                            {{ translations.home }}
                        </Link>
                        <Link @click="closeMobileMenu" :href="route('portfolio.about')" class="block px-4 py-3 rounded-lg text-slate-800 dark:text-slate-100 font-medium transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-cyan-600 dark:hover:text-cyan-300">
                            {{ translations.about }}
                        </Link>
                        <Link @click="closeMobileMenu" :href="route('portfolio.skills')" class="block px-4 py-3 rounded-lg text-slate-800 dark:text-slate-100 font-medium transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-cyan-600 dark:hover:text-cyan-300">
                            {{ translations.skills }}
                        </Link>
                        <Link @click="closeMobileMenu" :href="route('portfolio.projects')" class="block px-4 py-3 rounded-lg text-slate-800 dark:text-slate-100 font-medium transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-cyan-600 dark:hover:text-cyan-300">
                            {{ translations.projects }}
                        </Link>
                        <Link @click="closeMobileMenu" :href="route('portfolio.contact')" class="block px-3 py-2 rounded-md text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700/50 dark:text-slate-200 dark:hover:text-cyan-300 transition-colors duration-300">
                            {{ translations.contact }}
                        </Link>
                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                            <LanguageSwitcher :current-locale="currentLocale" />
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="pt-16">
            <slot />
        </main>
        
        <!-- Back to Top Button -->
        <BackToTop />
        
        <!-- Footer -->
        <footer class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white pt-20 pb-10 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-white rounded-full mix-blend-overlay animate-float"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-500 rounded-full mix-blend-overlay opacity-20 animate-float" style="animation-delay: 2s"></div>
                <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-cyan-300 rounded-full mix-blend-overlay opacity-10 animate-float" style="animation-delay: 3s"></div>
            </div>
            <div class="container-max px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                    <!-- About Section -->
                    <div class="animate-on-scroll">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 flex items-center justify-center mr-3 shadow-lg">
                                <span class="text-white font-bold text-lg">ZH</span>
                            </div>
                            <h3 class="text-xl font-bold text-gradient">Ziad Hassan</h3>
                        </div>
                        <p class="text-gray-300 mb-4 leading-relaxed">
                            {{ translations.about_footer }}
                        </p>
                        <div class="flex space-x-4">
                            <a :href="page.props.data.github" target="_blank" class="w-10 h-10 rounded-full bg-gray-700 hover:bg-cyan-500 flex items-center justify-center transition-colors duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                            <a :href="page.props.data.linkedin" target="_blank" class="w-10 h-10 rounded-full bg-gray-700 hover:bg-cyan-500 flex items-center justify-center transition-colors duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <a :href="page.props.data.instagram" target="_blank" class="w-10 h-10 rounded-full bg-gray-700 hover:bg-cyan-500 flex items-center justify-center transition-colors duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12c0-3.403 2.759-6.162 6.162-6.162s6.162 2.759 6.162 6.162-2.759 6.162-6.162 6.162-6.162-2.759-6.162-6.162zm1.621 0c0 2.507 2.034 4.541 4.541 4.541s4.541-2.034 4.541-4.541-2.034-4.541-4.541-4.541-4.541 2.034-4.541 4.541zm9.889-6.331c0 .796.646 1.44 1.44 1.44s1.44-.646 1.44-1.44-.646-1.44-1.44-1.44-1.44.646-1.44 1.44z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="animate-on-scroll">
                        <h4 class="text-lg font-semibold mb-4 text-gradient">{{ translations.quick_links }}</h4>
                        <ul class="space-y-2">
                            <li>
                                <Link :href="route('portfolio.home')" class="text-gray-300 hover:text-cyan-400 transition-colors duration-300">
                                    {{ translations.home }}
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('portfolio.about')" class="text-gray-300 hover:text-cyan-400 transition-colors duration-300">
                                    {{ translations.about }}
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('portfolio.skills')" class="text-gray-300 hover:text-cyan-400 transition-colors duration-300">
                                    {{ translations.skills }}
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('portfolio.projects')" class="text-gray-300 hover:text-cyan-400 transition-colors duration-300">
                                    {{ translations.projects }}
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('portfolio.contact')" class="text-gray-300 hover:text-cyan-400 transition-colors duration-300">
                                    {{ translations.contact }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Services -->
                    <div class="animate-on-scroll">
                        <h4 class="text-lg font-semibold mb-4 text-gradient">{{ translations.services }}</h4>
                        <ul class="space-y-2">
                            <li>
                                <span class="text-gray-300 hover:text-cyan-400 transition-colors duration-300 cursor-pointer">
                                    {{ translations.web_development }}
                                </span>
                            </li>
                            <li>
                                <span class="text-gray-300 hover:text-cyan-400 transition-colors duration-300 cursor-pointer">
                                    {{ translations.ai_ml }}
                                </span>
                            </li>
                            <li>
                                <span class="text-gray-300 hover:text-cyan-400 transition-colors duration-300 cursor-pointer">
                                    {{ translations.iot_solutions }}
                                </span>
                            </li>
                            <li>
                                <span class="text-gray-300 hover:text-cyan-400 transition-colors duration-300 cursor-pointer">
                                    {{ translations.database_design }}
                                </span>
                            </li>
                            <li>
                                <span class="text-gray-300 hover:text-cyan-400 transition-colors duration-300 cursor-pointer">
                                    {{ translations.technical_consulting }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="animate-on-scroll">
                        <h4 class="text-lg font-semibold mb-4 text-gradient">{{ translations.get_in_touch_footer }}</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-300">{{ page.props.data.email }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-gray-300">{{ page.props.data.phone }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-gray-300">{{ page.props.data.location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Footer -->
                <div class="border-t border-gray-700 pt-8">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="text-center md:text-left mb-4 md:mb-0">
                            <p class="text-gray-400 text-sm">
                                {{ translations.designed_by }} Ziad Hassan {{ new Date().getFullYear() }}. 
                                {{ translations.all_rights_reserved }}
                            </p>
                            <p class="text-gray-500 text-xs mt-1">
                                {{ translations.built_with }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-6">
                            <a href="#" class="text-gray-400 hover:text-cyan-400 text-sm transition-colors duration-300">
                                {{ translations.privacy_policy }}
                            </a>
                            <a href="#" class="text-gray-400 hover:text-cyan-400 text-sm transition-colors duration-300">
                                {{ translations.terms_of_service }}
                            </a>
                            <a href="#" class="text-gray-400 hover:text-cyan-400 text-sm transition-colors duration-300">
                                {{ translations.sitemap }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
