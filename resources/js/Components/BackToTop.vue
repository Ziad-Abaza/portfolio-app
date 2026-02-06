<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isVisible = ref(false);
const isScrolling = ref(false);
let scrollTimeout = null;

const scrollToTop = () => {
    isScrolling.value = true;
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
    
    // Reset scrolling state after animation completes
    setTimeout(() => {
        isScrolling.value = false;
    }, 800);
};

const handleScroll = () => {
    isVisible.value = window.scrollY > 300;
    
    // Clear existing timeout
    if (scrollTimeout) {
        clearTimeout(scrollTimeout);
    }
    
    // Set scrolling state for visual feedback
    isScrolling.value = true;
    scrollTimeout = setTimeout(() => {
        isScrolling.value = false;
    }, 150);
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    if (scrollTimeout) {
        clearTimeout(scrollTimeout);
    }
});
</script>

<template>
    <button
        v-if="isVisible"
        @click="scrollToTop"
        :class="[
            'fixed bottom-8 right-8 w-14 h-14 rounded-full shadow-2xl transform transition-all duration-500 z-50 group',
            'bg-gradient-to-br from-cyan-500 via-cyan-600 to-cyan-700 hover:from-cyan-400 hover:via-cyan-500 hover:to-cyan-600',
            'backdrop-blur-sm border border-cyan-400/20 hover:border-cyan-300/40',
            'hover:scale-110 active:scale-95',
            isScrolling ? 'animate-pulse' : 'hover:rotate-12',
            'shadow-cyan-500/25 hover:shadow-cyan-400/40 hover:shadow-2xl'
        ]"
        :title="isScrolling ? 'Scrolling to top...' : 'Back to top'"
    >
        <!-- Button Content Container -->
        <div class="relative w-full h-full flex items-center justify-center overflow-hidden rounded-full">
            <!-- Animated Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
            
            <!-- Radial Glow Effect -->
            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-cyan-400/30 to-cyan-600/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur-xl"></div>
            
            <!-- Pulsing Ring Effect -->
            <div class="absolute inset-0 rounded-full border-2 border-cyan-300/50 opacity-0 group-hover:animate-ping transition-opacity duration-300"></div>
            <div class="absolute inset-0 rounded-full border-2 border-cyan-400/40 opacity-0 group-hover:animate-ping transition-opacity duration-300" style="animation-delay: 0.5s"></div>
            
            <!-- Enhanced Arrow Icon -->
            <svg 
                :class="[
                    'w-7 h-7 text-white relative z-10 transition-all duration-300',
                    isScrolling ? 'animate-bounce' : 'group-hover:animate-pulse'
                ]" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2.5" 
                    d="M5 10l7-7m0 0l7 7m-7-7v18"
                ></path>
            </svg>
            
            <!-- Floating Particles -->
            <div class="absolute top-1 left-1 w-1 h-1 bg-cyan-300/60 rounded-full animate-ping opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="absolute bottom-2 right-2 w-1.5 h-1.5 bg-cyan-200/50 rounded-full animate-ping opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="animation-delay: 0.3s"></div>
            <div class="absolute top-3 right-1 w-1 h-1 bg-white/40 rounded-full animate-ping opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="animation-delay: 0.6s"></div>
        </div>
        
        <!-- Tooltip -->
        <div class="absolute bottom-full right-0 mb-2 px-3 py-1.5 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 whitespace-nowrap pointer-events-none">
            <span class="relative">
                {{ isScrolling ? 'Scrolling...' : 'Back to top' }}
                <!-- Tooltip Arrow -->
                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 w-2 h-2 bg-slate-900 dark:bg-slate-700 transform rotate-45"></div>
            </span>
        </div>
        
        <!-- Progress Indicator (optional visual feedback) -->
        <div 
            v-if="isScrolling"
            class="absolute inset-0 rounded-full border-2 border-cyan-300/60 animate-spin"
            style="animation-duration: 1s; border-top-color: transparent; border-right-color: transparent;"
        ></div>
    </button>
</template>
