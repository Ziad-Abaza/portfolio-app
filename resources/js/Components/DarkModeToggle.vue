<script setup>
import { ref, onMounted } from 'vue';

const isDark = ref(false);

// Check for saved user preference, if any, on load
const checkDarkMode = () => {
    // Check local storage first
    const savedMode = localStorage.getItem('darkMode');
    if (savedMode !== null) {
        isDark.value = savedMode === 'true';
    } else {
        // Fallback to system preference
        isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    // Apply the class
    document.documentElement.classList.toggle('dark', isDark.value);
};

const toggleDarkMode = () => {
    isDark.value = !isDark.value;
    document.documentElement.classList.toggle('dark', isDark.value);
    localStorage.setItem('darkMode', isDark.value);};

// Check on component mount
onMounted(() => {
    checkDarkMode();
    
    // Listen for system color scheme changes
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    darkModeMediaQuery.addEventListener('change', (e) => {
        // Only apply system preference if user hasn't explicitly set a preference
        if (localStorage.getItem('darkMode') === null) {
            isDark.value = e.matches;
            document.documentElement.classList.toggle('dark', e.matches);
        }
    });
});
</script>

<template>
    <button
        @click="toggleDarkMode"
        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
    >
        <svg v-if="!isDark" class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
        <svg v-else class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
    </button>
</template>
