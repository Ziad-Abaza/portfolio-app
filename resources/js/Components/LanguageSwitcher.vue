<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    currentLocale: String,
});

const toggleLanguage = (locale) => {
    window.location.href = `/language/${locale}`;
};
</script>

<template>
    <div class="flex items-center space-x-2">
        <button
            @click="toggleLanguage('en')"
            :class="{ 'text-cyan-500 font-semibold': currentLocale === 'en', 'text-gray-600': currentLocale !== 'en' }"
            class="px-3 py-1 rounded-lg hover:bg-gray-100 transition-colors duration-200"
        >
            EN
        </button>
        <span class="text-gray-400">|</span>
        <button
            @click="toggleLanguage('ar')"
            :class="{ 'text-cyan-500 font-semibold': currentLocale === 'ar', 'text-gray-600': currentLocale !== 'ar' }"
            class="px-3 py-1 rounded-lg hover:bg-gray-100 transition-colors duration-200"
        >
            AR
        </button>
    </div>
</template>
