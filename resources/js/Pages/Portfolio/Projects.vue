<script setup>
import { Head } from '@inertiajs/vue3';
import PortfolioLayout from '@/Layouts/PortfolioLayout.vue';
import { useScrollAnimations } from '@/Composables/useScrollAnimations.js';
import { ref, computed } from 'vue';

defineOptions({ layout: PortfolioLayout });

const props = defineProps({
    data: Object,
    translations: Object,
    currentLocale: String,
});

useScrollAnimations();

const selectedCategory = ref('all');
const searchQuery = ref('');

const categories = computed(() => {
    const cats = ['all', ...new Set(props.data.projects?.map(p => p.category) || [])];
    return cats.map(cat => ({
        id: cat,
        name: cat === 'all' ? props.translations.all_projects : cat,
        count: cat === 'all' ? props.data.projects?.length || 0 : props.data.projects?.filter(p => p.category === cat).length || 0
    }));
});

const filteredProjects = computed(() => {
    let projects = props.data.projects || [];
    
    if (selectedCategory.value !== 'all') {
        projects = projects.filter(project => project.category === selectedCategory.value);
    }
    
    if (searchQuery.value) {
        projects = projects.filter(project => 
            project.title?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            project.description?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            project.technologies?.some(tech => tech.toLowerCase().includes(searchQuery.value.toLowerCase()))
        );
    }
    
    return projects;
});

const selectCategory = (category) => {
    selectedCategory.value = category;
};

const clearSearch = () => {
    searchQuery.value = '';
};

// Interactive animations
const isHovering = ref(false);
const mouseX = ref(0);
const mouseY = ref(0);
const particles = ref([]);
const connections = ref([]);

// Initialize neural network particles
const initParticles = () => {
    particles.value = Array.from({ length: 25 }, (_, i) => ({
        id: i,
        x: Math.random() * 100,
        y: Math.random() * 100,
        vx: (Math.random() - 0.5) * 0.02,
        vy: (Math.random() - 0.5) * 0.02,
        baseX: Math.random() * 100,
        baseY: Math.random() * 100,
        radius: 2 + Math.random() * 2,
        pulsePhase: Math.random() * Math.PI * 2,
        energy: Math.random()
    }));
};

initParticles();

// Calculate connections between nearby particles
const updateConnections = () => {
    connections.value = [];
    for (let i = 0; i < particles.value.length; i++) {
        for (let j = i + 1; j < particles.value.length; j++) {
            const dx = particles.value[i].x - particles.value[j].x;
            const dy = particles.value[i].y - particles.value[j].y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < 25) { // Connection threshold
                connections.value.push({
                    from: i,
                    to: j,
                    strength: 1 - (distance / 25),
                    energy: (particles.value[i].energy + particles.value[j].energy) / 2
                });
            }
        }
    }
};

// Animation loop for natural movement
const animateParticles = () => {
    particles.value = particles.value.map(particle => {
        // Natural flow movement
        let newX = particle.x + particle.vx;
        let newY = particle.y + particle.vy;
        
        // Bounce off edges
        if (newX <= 0 || newX >= 100) particle.vx *= -1;
        if (newY <= 0 || newY >= 100) particle.vy *= -1;
        
        // Keep within bounds
        newX = Math.max(0, Math.min(100, newX));
        newY = Math.max(0, Math.min(100, newY));
        
        // Add slight random movement
        particle.vx += (Math.random() - 0.5) * 0.001;
        particle.vy += (Math.random() - 0.5) * 0.001;
        
        // Limit speed
        const speed = Math.sqrt(particle.vx * particle.vx + particle.vy * particle.vy);
        if (speed > 0.05) {
            particle.vx = (particle.vx / speed) * 0.05;
            particle.vy = (particle.vy / speed) * 0.05;
        }
        
        // Update energy and pulse
        particle.pulsePhase += 0.02;
        particle.energy = 0.5 + Math.sin(particle.pulsePhase) * 0.5;
        
        return {
            ...particle,
            x: newX,
            y: newY
        };
    });
    
    updateConnections();
};

// Start animation loop
const animationFrame = ref();
const startAnimation = () => {
    const animate = () => {
        animateParticles();
        animationFrame.value = requestAnimationFrame(animate);
    };
    animate();
};

startAnimation();

const handleMouseMove = (e) => {
    mouseX.value = e.clientX;
    mouseY.value = e.clientY;
    
    const mousePercentX = (e.clientX / window.innerWidth) * 100;
    const mousePercentY = (e.clientY / window.innerHeight) * 100;
    
    // Push particles away from mouse
    particles.value = particles.value.map(particle => {
        const dx = particle.x - mousePercentX;
        const dy = particle.y - mousePercentY;
        const distance = Math.sqrt(dx * dx + dy * dy);
        
        if (distance < 20 && distance > 0) { // Repel radius
            const force = (20 - distance) / 20; // Stronger when closer
            const pushX = (dx / distance) * force * 2;
            const pushY = (dy / distance) * force * 2;
            
            particle.vx += pushX * 0.1;
            particle.vy += pushY * 0.1;
            particle.energy = Math.min(1, particle.energy + force * 0.5); // Boost energy when repelled
        }
        
        return particle;
    });
};

const handleMouseEnter = () => {
    isHovering.value = true;
};

const handleMouseLeave = () => {
    isHovering.value = false;
};
</script>

<template>
    <Head :title="translations.projects" />
    
    <!-- Hero Section -->
    <section 
        class="min-h-screen py-16 flex items-center justify-center bg-gradient-to-br from-slate-950 via-gray-900 to-slate-950 text-white transition-colors duration-300 relative overflow-hidden"
        @mousemove="handleMouseMove"
        @mouseenter="handleMouseEnter"
        @mouseleave="handleMouseLeave"
    >
        <!-- Animated Background Grid -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0 bg-grid-pattern"></div>
        </div>
        
        <!-- Interactive Mouse Follower -->
        <div 
            v-if="isHovering"
            class="pointer-events-none fixed z-50 w-8 h-8 rounded-full bg-cyan-400/20 blur-xl transition-all duration-300 ease-out"
            :style="{ 
                left: mouseX.value - 16 + 'px', 
                top: mouseY.value - 16 + 'px',
                transform: 'scale(' + (1 + Math.sin(Date.now() * 0.002) * 0.2) + ')'
            }"
        ></div>
        
        <!-- Floating Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-cyan-500/20 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 3s"></div>
            <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 5s"></div>
            
            <!-- Neural Network Particles -->
            <div class="absolute inset-0">
                <!-- Connection Lines -->
                <svg class="absolute inset-0 w-full h-full" style="pointer-events: none;">
                    <line
                        v-for="connection in connections"
                        :key="`${connection.from}-${connection.to}`"
                        :x1="particles[connection.from].x + '%'"
                        :y1="particles[connection.from].y + '%'"
                        :x2="particles[connection.to].x + '%'"
                        :y2="particles[connection.to].y + '%'"
                        :stroke="`rgba(6, 182, 212, ${connection.strength * 0.3 * (isHovering ? 1.5 : 1)})`"
                        stroke-width="1"
                        class="transition-all duration-300"
                    />
                </svg>
                
                <!-- Particles -->
                <div
                    v-for="particle in particles"
                    :key="particle.id"
                    class="absolute rounded-full transition-all duration-200 ease-out"
                    :style="{
                        left: particle.x + '%',
                        top: particle.y + '%',
                        width: particle.radius + 'px',
                        height: particle.radius + 'px',
                        transform: 'translate(-50%, -50%)',
                        backgroundColor: `rgba(6, 182, 212, ${0.4 + particle.energy * 0.4})`,
                        boxShadow: `0 0 ${particle.radius * 2}px rgba(6, 182, 212, ${particle.energy * 0.5})`,
                        animation: `pulse ${2 + particle.energy}s infinite`
                    }"
                >
                    <!-- Inner core -->
                    <div class="absolute inset-0 rounded-full bg-white/80" :style="{ transform: `scale(${0.3 + particle.energy * 0.3})` }"></div>
                </div>
            </div>
        </div>
        
        <div class="container-max px-4 relative z-10">
            <div class="text-center max-w-5xl mx-auto">
                <!-- Minimal Badge -->
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-cyan-500/10 border border-cyan-500/30 mb-8 animate-fade-in">
                    <div class="w-2 h-2 rounded-full bg-cyan-400 mr-3 animate-pulse"></div>
                    <span class="text-cyan-300 text-sm font-medium">{{ translations.available_for_work || 'Available for Work' }}</span>
                </div>
                
                <!-- Strong Statement -->
                <h1 class="text-6xl md:text-8xl font-bold mb-6 leading-tight animate-fade-in">
                    <span class="block text-white animate-slide-in-left">{{ translations.building || 'Building' }}</span>
                    <span class="block text-transparent py-16 bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400 animate-slide-in-right">{{ translations.digital_experiences || 'Digital Experiences' }}</span>
                </h1>
                
                <!-- Subtle Description -->
                <p class="text-xl text-gray-400 mb-16 max-w-3xl mx-auto animate-fade-in" style="animation-delay: 0.2s">
                    {{ translations.projects_subtitle || 'Crafting elegant solutions with modern technologies' }}
                </p>
                
                <!-- Interactive Stats Showcase -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto mb-16">
                    <div class="group relative animate-on-scroll">
                        <div class="absolute inset-0 bg-cyan-500/20 rounded-2xl blur-xl group-hover:scale-110 transition-transform duration-500"></div>
                        <div class="relative bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700/50 hover:border-cyan-500/50 transition-all duration-300 transform hover:-translate-y-2">
                            <div class="text-4xl font-bold text-white mb-2 animate-count-up">{{ data.projects?.length || 0 }}</div>
                            <div class="text-gray-400 text-sm uppercase tracking-wider">{{ translations.projects || 'Projects' }}</div>
                            <div class="absolute -top-2 -right-2 w-4 h-4 bg-cyan-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-ping"></div>
                        </div>
                    </div>
                    
                    <div class="group relative animate-on-scroll" style="animation-delay: 0.1s">
                        <div class="absolute inset-0 bg-purple-500/20 rounded-2xl blur-xl group-hover:scale-110 transition-transform duration-500"></div>
                        <div class="relative bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700/50 hover:border-purple-500/50 transition-all duration-300 transform hover:-translate-y-2">
                            <div class="text-4xl font-bold text-white mb-2 animate-count-up">{{ categories.length - 1 }}</div>
                            <div class="text-gray-400 text-sm uppercase tracking-wider">{{ translations.categories || 'Categories' }}</div>
                            <div class="absolute -top-2 -right-2 w-4 h-4 bg-purple-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-ping"></div>
                        </div>
                    </div>
                    
                    <div class="group relative animate-on-scroll" style="animation-delay: 0.2s">
                        <div class="absolute inset-0 bg-green-500/20 rounded-2xl blur-xl group-hover:scale-110 transition-transform duration-500"></div>
                        <div class="relative bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700/50 hover:border-green-500/50 transition-all duration-300 transform hover:-translate-y-2">
                            <div class="text-4xl font-bold text-white mb-2 animate-count-up">{{ data.projects?.length || 0 }}</div>
                            <div class="text-gray-400 text-sm uppercase tracking-wider">{{ translations.completed || 'Completed' }}</div>
                            <div class="absolute -top-2 -right-2 w-4 h-4 bg-green-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-ping"></div>
                        </div>
                    </div>
                    
                    <div class="group relative animate-on-scroll" style="animation-delay: 0.3s">
                        <div class="absolute inset-0 bg-orange-500/20 rounded-2xl blur-xl group-hover:scale-110 transition-transform duration-500"></div>
                        <div class="relative bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700/50 hover:border-orange-500/50 transition-all duration-300 transform hover:-translate-y-2">
                            <div class="text-4xl font-bold text-white mb-2 animate-count-up">0</div>
                            <div class="text-gray-400 text-sm uppercase tracking-wider">{{ translations.ongoing || 'Ongoing' }}</div>
                            <div class="absolute -top-2 -right-2 w-4 h-4 bg-orange-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-ping"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Interactive CTA -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in" style="animation-delay: 0.4s">
                    <a
                        :href="route('portfolio.contact')"
                        class="group relative px-8 py-4 bg-gradient-to-r from-cyan-600 to-blue-600 rounded-xl font-medium text-white overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-cyan-500/25 transform hover:-translate-y-1"
                    >
                        <span class="relative z-10 flex items-center gap-2">
                            {{ translations.lets_collaborate || 'Let\'s Collaborate' }}
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 to-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute inset-0 bg-white/20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                    </a>
                    <a
                        href="#projects"
                        class="group px-8 py-4 rounded-xl font-medium text-gray-300 border border-gray-700 hover:border-cyan-500 hover:text-cyan-400 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/10"
                    >
                        <span class="flex items-center gap-2">
                            {{ translations.view_work || 'View Work' }}
                            <svg class="w-4 h-4 group-hover:translate-y-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <!-- <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-gray-600 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-gray-400 rounded-full mt-2 animate-pulse"></div>
            </div>
        </div> -->
    </section>
    
    <!-- Projects Section -->
    <section id="projects" class="section-padding bg-gray-900 text-white transition-colors duration-300">
        <div class="container-max">
            <!-- Search and Filter -->
            <div class="mb-12 animate-on-scroll">
                <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
                    <!-- Search Bar -->
                    <div class="relative w-full lg:w-96">
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="translations.search_projects"
                            class="w-full px-4 py-3 pl-12 rounded-xl border border-gray-700 bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-300"
                        >
                        <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <button
                            v-if="searchQuery"
                            @click="clearSearch"
                            class="absolute right-4 top-3.5 text-gray-400 hover:text-white transition-colors duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="category in categories"
                            :key="category.id"
                            @click="selectCategory(category.id)"
                            :class="[
                                'px-4 py-2 rounded-lg font-medium transition-all duration-300',
                                selectedCategory === category.id
                                    ? 'bg-cyan-600 text-white shadow-lg transform scale-105'
                                    : 'bg-gray-800 text-gray-300 hover:bg-gray-700 border border-gray-600'
                            ]"
                        >
                            {{ category.name }}
                            <span class="ml-2 text-sm opacity-75">({{ category.count }})</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Projects Grid -->
            <div v-if="filteredProjects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="(project, index) in filteredProjects"
                    :key="project.id"
                    :style="{ animationDelay: index * 0.1 + 's' }"
                    class="group bg-gray-800 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden animate-on-scroll border border-gray-700 hover:border-cyan-600 transform hover:-translate-y-2"
                >
                    <!-- Project Image -->
                    <div class="relative h-48 overflow-hidden bg-gradient-to-br from-gray-700 to-gray-800">
                        <img
                            v-if="project.image"
                            :src="project.image"
                            :alt="project.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        >
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <div class="text-cyan-400">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Status Indicator -->
                        <div class="absolute top-4 right-4">
                            <div class="w-3 h-3 rounded-full bg-green-400 animate-pulse"></div>
                        </div>
                    </div>
                    
                    <!-- Project Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-white group-hover:text-cyan-400 transition-colors duration-300">
                            {{ project.title }}
                        </h3>
                        
                        <p class="text-gray-300 mb-4 line-clamp-3">
                            {{ project.description }}
                        </p>
                        
                        <!-- Technologies -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span
                                v-for="tech in project.technologies?.slice(0, 3)"
                                :key="tech"
                                class="px-3 py-1 bg-cyan-900/50 text-cyan-300 text-xs rounded-full border border-cyan-700/50"
                            >
                                {{ tech }}
                            </span>
                            <span
                                v-if="project.technologies?.length > 3"
                                class="px-3 py-1 bg-gray-700 text-gray-400 text-xs rounded-full border border-gray-600"
                            >
                                +{{ project.technologies.length - 3 }}
                            </span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <a
                                v-if="project.live_url"
                                :href="project.live_url"
                                target="_blank"
                                class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg font-medium text-center transition-colors duration-300"
                            >
                                {{ translations.view_live || 'View Live' }}
                            </a>
                            <a
                                v-if="project.github_url"
                                :href="project.github_url"
                                target="_blank"
                                class="flex-1 border border-cyan-600 text-cyan-400 hover:bg-cyan-600 hover:text-white px-4 py-2 rounded-lg font-medium text-center transition-colors duration-300"
                            >
                                {{ translations.view_code || 'View Code' }}
                            </a>
                            <!-- Debug: Show if no URLs available -->
                            <div v-if="!project.live_url && !project.github_url" class="flex gap-3">
                                <button class="flex-1 bg-gray-700 text-gray-400 px-4 py-2 rounded-lg font-medium text-center cursor-not-allowed" disabled>
                                    {{ translations.view_live || 'View Live' }}
                                </button>
                                <button class="flex-1 bg-gray-700 text-gray-400 px-4 py-2 rounded-lg font-medium text-center cursor-not-allowed" disabled>
                                    {{ translations.view_code || 'View Code' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- No Results -->
            <div v-else class="text-center py-20">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-800 flex items-center justify-center border border-gray-700">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">
                        {{ translations.no_projects_found }}
                    </h3>
                    <p class="text-gray-400 mb-6">
                        {{ translations.try_different_filters }}
                    </p>
                    <button
                        @click="clearSearch"
                        class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-300"
                    >
                        {{ translations.clear_filters }}
                    </button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Call to Action -->
    <section class="section-padding bg-gradient-to-br from-gray-800 to-gray-900 text-white border-t border-gray-700">
        <div class="container-max px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                {{ translations.have_project_in_mind }}
            </h2>
            <p class="text-lg mb-8 max-w-2xl mx-auto text-gray-300">
                {{ translations.lets_work_together }}
            </p>
            <a
                :href="route('portfolio.contact')"
                class="inline-block bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-lg font-medium transition-colors duration-300"
            >
                {{ translations.get_in_touch }}
            </a>
        </div>
    </section>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.bg-grid-pattern {
    background-image: linear-gradient(rgba(6, 182, 212, 0.1) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(6, 182, 212, 0.1) 1px, transparent 1px);
    background-size: 50px 50px;
}

@keyframes pulse {
    0%, 100% { 
        opacity: 0.6;
        transform: translate(-50%, -50%) scale(1);
    }
    50% { 
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.2);
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-20px) rotate(1deg); }
    66% { transform: translateY(10px) rotate(-1deg); }
}

@keyframes slideInLeft {
    from { 
        opacity: 0;
        transform: translateX(-50px);
    }
    to { 
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from { 
        opacity: 0;
        transform: translateX(50px);
    }
    to { 
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes countUp {
    from { 
        opacity: 0;
        transform: translateY(20px) scale(0.5);
    }
    to { 
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-slide-in-left {
    animation: slideInLeft 1s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 1s ease-out 0.3s both;
}

.animate-count-up {
    animation: countUp 0.8s ease-out;
}
</style>