<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import PortfolioLayout from '@/Layouts/PortfolioLayout.vue';
import { useScrollAnimations } from '@/Composables/useScrollAnimations.js';

defineOptions({ layout: PortfolioLayout });

const props = defineProps({
    data: Object,
    translations: Object,
    currentLocale: String,
    success: String,
});

useScrollAnimations();

const form = reactive({
    name: '',
    email: '',
    message: '',
});

const errors = ref({});
const isSubmitting = ref(false);
const showSuccess = ref(props.success || '');

const submitForm = async () => {
    isSubmitting.value = true;
    errors.value = {};
    
    try {
        const response = await fetch('/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(form),
        });
        
        if (response.ok) {
            showSuccess.value = 'Thank you for your message! I\'ll get back to you soon.';
            form.name = '';
            form.email = '';
            form.message = '';
        } else {
            const data = await response.json();
            errors.value = data.errors || {};
        }
    } catch (error) {
        errors.value = { general: 'An error occurred. Please try again.' };
    } finally {
        isSubmitting.value = false;
    }
};

const validateField = (field) => {
    if (errors.value[field]) {
        delete errors.value[field];
    }
};
</script>

<template>
    <Head :title="translations.contact" />
    
    <!-- Hero Section -->
    <section class="section-padding bg-gradient-to-r from-cyan-600 to-cyan-800 dark:from-cyan-700 dark:to-cyan-900 text-white">
        <div class="container-max">
            <div class="text-center animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    {{ translations.contact_title }}
                </h1>
                <p class="text-xl max-w-3xl mx-auto text-cyan-100 dark:text-cyan-50">
                    {{ translations.contact_description }}
                </p>
            </div>
        </div>
    </section>
    
    <!-- Contact Content -->
    <section class="section-padding bg-slate-50 dark:bg-slate-900 transition-colors duration-500">
        <div class="container-max">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Contact Form -->
                <div class="animate-slide-in-left">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 transition-all duration-500 border border-white/20 dark:border-slate-700/50 hover:shadow-2xl">
                        <h2 class="text-2xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-300">
                            {{ translations.send_message }}
                        </h2>
                        
                        <!-- Success Message -->
                        <div v-if="showSuccess" class="mb-6 p-4 bg-green-100 dark:bg-green-900/50 border border-green-300 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg transition-colors duration-300">
                            {{ showSuccess }}
                        </div>
                        
                        <!-- Error Message -->
                        <div v-if="errors.general" class="mb-6 p-4 bg-red-100 dark:bg-red-900/50 border border-red-300 dark:border-red-800 text-red-700 dark:text-red-300 rounded-lg transition-colors duration-300">
                            {{ errors.general }}
                        </div>
                        
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                    {{ translations.your_name }}
                                </label>
                                <div class="relative">
                                    <input 
                                        v-model="form.name"
                                        @input="validateField('name')"
                                        type="text" 
                                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 focus:border-cyan-500 dark:focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-900/20 placeholder-slate-400 dark:placeholder-slate-500 text-slate-800 dark:text-slate-200 transition-all duration-300"
                                        :class="{ 'border-red-500 dark:border-red-500': errors.name }"
                                        :placeholder="translations.your_name"
                                        required
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg v-if="!form.name" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.name[0] }}</p>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                    {{ translations.your_email }}
                                </label>
                                <div class="relative">
                                    <input 
                                        v-model="form.email"
                                        @input="validateField('email')"
                                        type="email" 
                                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 focus:border-cyan-500 dark:focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-900/20 placeholder-slate-400 dark:placeholder-slate-500 text-slate-800 dark:text-slate-200 transition-all duration-300"
                                        :class="{ 'border-red-500 dark:border-red-500': errors.email }"
                                        placeholder="your.email@example.com"
                                        required
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg v-if="!form.email" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <p v-if="errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.email[0] }}</p>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                    {{ translations.your_message }}
                                </label>
                                <div class="relative">
                                    <textarea 
                                        v-model="form.message"
                                        @input="validateField('message')"
                                        rows="5"
                                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 focus:border-cyan-500 dark:focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-900/20 placeholder-slate-400 dark:placeholder-slate-500 text-slate-800 dark:text-slate-200 transition-all duration-300"
                                        :class="{ 'border-red-500 dark:border-red-500': errors.message }"
                                        :placeholder="translations.your_message || 'Your message here...'"
                                        required
                                    ></textarea>
                                </div>
                                <p v-if="errors.message" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.message[0] }}</p>
                            </div>
                            
                            <button 
                                type="submit" 
                                class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 dark:from-cyan-600 dark:to-cyan-700 dark:hover:from-cyan-700 dark:hover:to-cyan-800 text-white font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed"
                                :disabled="isSubmitting"
                            >
                                <span v-if="isSubmitting" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ translations.sending || 'Sending...' }}
                                </span>
                                <span v-else class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ translations.send_message }}
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="animate-slide-in-right space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 transition-all duration-500 border border-white/20 dark:border-slate-700/50 hover:shadow-2xl">
                        <h2 class="text-2xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-300">
                            {{ translations.contact_title }}
                        </h2>
                        
                        <div class="space-y-6">
                            <div class="flex items-start group">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 flex items-center justify-center text-white shadow-lg mr-4 transform transition-transform duration-300 group-hover:scale-110">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ translations.email }}</p>
                                    <p class="text-slate-800 dark:text-slate-200 font-medium">{{ data.email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start group">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 flex items-center justify-center text-white shadow-lg mr-4 transform transition-transform duration-300 group-hover:scale-110">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ translations.phone }}</p>
                                    <p class="text-slate-800 dark:text-slate-200 font-medium">{{ data.phone }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start group">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 flex items-center justify-center text-white shadow-lg mr-4 transform transition-transform duration-300 group-hover:scale-110">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ translations.location }}</p>
                                    <p class="text-slate-800 dark:text-slate-200 font-medium">{{ data.location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 transition-all duration-500 border border-white/20 dark:border-slate-700/50 hover:shadow-2xl">
                        <h3 class="text-xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-400 dark:to-cyan-300">
                            {{ translations.social_links || 'Connect With Me' }}
                        </h3>
                        <div class="flex space-x-4">
                            <a :href="data.github" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700/50 hover:bg-cyan-100 dark:hover:bg-cyan-900/30 text-slate-700 dark:text-slate-300 hover:text-cyan-600 dark:hover:text-cyan-400 flex items-center justify-center transition-all duration-300 border border-slate-200 dark:border-slate-700 hover:border-cyan-200 dark:hover:border-cyan-800/50 shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.49.5.09.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.337-3.369-1.337-.454-1.151-1.11-1.458-1.11-1.458-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.526 2.341 1.085 2.91.83.092-.645.35-1.085.636-1.334-2.22-.253-4.555-1.11-4.555-4.94 0-1.09.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.27 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.7 1.028 1.593 1.028 2.682 0 3.842-2.339 4.683-4.566 4.93.36.31.68.92.68 1.853 0 1.337-.263 2.415-.663 2.743 0 .267.18.578.688.48C19.14 20.16 22 16.416 22 12c0-5.523-4.477-10-10-10z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                            <a :href="data.linkedin" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700/50 hover:bg-cyan-100 dark:hover:bg-cyan-900/30 text-slate-700 dark:text-slate-300 hover:text-cyan-600 dark:hover:text-cyan-400 flex items-center justify-center transition-all duration-300 border border-slate-200 dark:border-slate-700 hover:border-cyan-200 dark:hover:border-cyan-800/50 shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path>
                                </svg>
                            </a>
                            <a :href="data.twitter" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700/50 hover:bg-cyan-100 dark:hover:bg-cyan-900/30 text-slate-700 dark:text-slate-300 hover:text-cyan-600 dark:hover:text-cyan-400 flex items-center justify-center transition-all duration-300 border border-slate-200 dark:border-slate-700 hover:border-cyan-200 dark:hover:border-cyan-800/50 shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"></path>
                                </svg>
                            </a>
                            <a :href="data.dribbble" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700/50 hover:bg-cyan-100 dark:hover:bg-cyan-900/30 text-slate-700 dark:text-slate-300 hover:text-cyan-600 dark:hover:text-cyan-400 flex items-center justify-center transition-all duration-300 border border-slate-200 dark:border-slate-700 hover:border-cyan-200 dark:hover:border-cyan-800/50 shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 24c6.627 0 12-5.373 12-12S18.627 0 12 0 0 5.373 0 12s5.373 12 12 12z" fill="#EA4C89"></path>
                                    <path d="M12 4.163c-4.302 0-7.837 3.535-7.837 7.837 0 3.44 2.232 6.363 5.302 7.43-.07-.53-.134-1.347.028-2.135.146-.622.943-3.96.943-3.96s-.24-.48-.24-1.188c0-1.112.645-1.942 1.448-1.942.683 0 1.013.512 1.013 1.127 0 .687-.437 1.712-.663 2.665-.19.79.396 1.433 1.175 1.433 1.41 0 2.494-1.486 2.494-3.63 0-1.897-1.364-3.225-3.31-3.225-2.254 0-3.576 1.69-3.576 3.437 0 .687.263 1.424.593 1.824.065.08.075.15.055.23-.06.25-.19.79-.22.9-.035.15-.116.18-.268.11-1-.465-1.624-1.925-1.624-3.1 0-2.523 1.834-4.837 5.286-4.837 2.775 0 4.933 1.977 4.933 4.62 0 2.757-1.74 4.976-4.155 4.976-.81 0-1.573-.42-1.833-.918l-.5 1.9c-.18.693-.67 1.56-.997 2.09.75.23 1.543.355 2.37.355 4.303 0 7.837-3.535 7.837-7.837S16.303 4.163 12 4.163z" fill="#FFF"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
