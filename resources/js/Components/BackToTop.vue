<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isVisible = ref(false);

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

const handleScroll = () => {
    isVisible.value = window.scrollY > 300;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <button
        v-if="isVisible"
        @click="scrollToTop"
        class="fixed bottom-8 right-8 w-12 h-12 rounded-full gradient-bg text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 z-40"
        title="Back to top"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
</template>
