import { defineStore } from 'pinia';
import { ref, computed, watch } from 'vue';

const STORAGE_KEY = 'portfolio_projects_store';

// Load state from localStorage
const loadState = () => {
    try {
        const serializedState = localStorage.getItem(STORAGE_KEY);
        if (serializedState === null) {
            return undefined;
        }
        return JSON.parse(serializedState);
    } catch (err) {
        console.warn('Failed to load state from localStorage:', err);
        return undefined;
    }
};

// Helper to safely get values from Proxies
const toRawIfProxy = (value) => {
    if (value && typeof value === 'object' && '__v_raw' in value) {
        return toRawIfProxy(JSON.parse(JSON.stringify(value)));
    }
    if (Array.isArray(value)) {
        return value.map(toRawIfProxy);
    }
    if (value && typeof value === 'object') {
        const result = {};
        for (const key in value) {
            result[key] = toRawIfProxy(value[key]);
        }
        return result;
    }
    return value;
};

// Save state to localStorage
const saveState = (state) => {
    try {
        // Convert any Proxy objects to plain objects
        const stateToSave = {
            searchTerm: state.searchTerm,
            selectedCategory: state.selectedCategory,
            sortBy: state.sortBy,
            projects: toRawIfProxy(state.projects)
        };
        const serializedState = JSON.stringify(stateToSave);
        localStorage.setItem(STORAGE_KEY, serializedState);
    } catch (err) {
        console.warn('Failed to save state to localStorage:', err);
    }
};

export const useProjectsStore = defineStore('projects', () => {
    // Load initial state from localStorage
    const initialState = loadState() || {};
    
    // State with initial values from localStorage
    const searchTerm = ref(initialState.searchTerm || '');
    const selectedCategory = ref(initialState.selectedCategory || 'all');
    const sortBy = ref(initialState.sortBy || 'latest');
    const projects = ref(initialState.projects || []);
    const isLoading = ref(false);
    const error = ref(null);

    // Getters
    const categories = computed(() => {
        const cats = ['all'];
        
        if (!projects.value || !Array.isArray(projects.value)) {
            return cats;
        }
        
        projects.value.forEach(project => {
            if (project?.category && !cats.includes(project.category)) {
                cats.push(project.category);
            }
            if (project?.technologies?.length) {
                project.technologies.forEach(tech => {
                    if (tech && !cats.includes(tech)) {
                        cats.push(tech);
                    }
                });
            }
        });
        return cats;
    });

    const getCategoryCount = (category) => {
        if (!Array.isArray(projects.value) || !projects.value.length) {
            console.log('No projects available for category count');
            return 0;
        }
        
        if (category === 'all') {
            return projects.value.length;
        }
        
        const count = projects.value.filter(project => {
            const categoryMatch = project?.category && 
                String(project.category).toLowerCase() === category.toLowerCase();
                
            const techMatch = Array.isArray(project?.technologies) && 
                project.technologies.some(tech => 
                    String(tech || '').toLowerCase() === category.toLowerCase()
                );
                
            return categoryMatch || techMatch;
        }).length;
        
        console.log(`Category "${category}" count:`, count);
        return count;
    };

    const filteredProjects = computed(() => {
        console.log('\n--- Filtering Projects ---');
        console.log('Search term:', searchTerm.value);
        console.log('Selected category:', selectedCategory.value);
        console.log('Sort by:', sortBy.value);
        
        if (!Array.isArray(projects.value) || projects.value.length === 0) {
            console.log('No projects to filter');
            return [];
        }

        // Convert search term to lowercase once for case-insensitive search
        const searchTermLower = (searchTerm.value || '').trim().toLowerCase();
        const selectedCategoryValue = (selectedCategory.value || 'all').toLowerCase();
        
        // Debug: Log first project to check structure
        if (projects.value.length > 0) {
            console.log('Sample project structure:', JSON.parse(JSON.stringify(projects.value[0])));
        }
        
        // Filter projects
        const filtered = projects.value.filter(project => {
            if (!project || typeof project !== 'object') {
                console.log('Invalid project:', project);
                return false;
            }
            
            // 1. Apply search filter if search term exists
            let matchesSearch = true;
            if (searchTermLower) {
                matchesSearch = [
                    project.title || '',
                    project.description || '',
                    ...(Array.isArray(project.technologies) ? project.technologies : [])
                ].some(text => 
                    String(text || '').toLowerCase().includes(searchTermLower)
                );
                console.log(`Project "${project.title}" search match:`, matchesSearch);
            }
            
            // 2. Apply category filter
            let matchesCategory = true;
            if (selectedCategoryValue !== 'all') {
                const projectCategory = String(project.category || '').toLowerCase();
                const projectTechnologies = Array.isArray(project.technologies) 
                    ? project.technologies.map(t => String(t || '').toLowerCase())
                    : [];
                
                matchesCategory = projectCategory === selectedCategoryValue || 
                                 projectTechnologies.includes(selectedCategoryValue);
                
                console.log(`Project "${project.title}" category match:`, matchesCategory, {
                    projectCategory,
                    selectedCategoryValue,
                    projectTechnologies
                });
            }
            
            const include = matchesSearch && matchesCategory;
            if (include) {
                console.log(`✅ Project "${project.title}" matches all filters`);
            } else {
                console.log(`❌ Project "${project.title}" filtered out (search: ${matchesSearch}, category: ${matchesCategory})`);
            }
            
            return include;
        });
        
        console.log(`Found ${filtered.length} projects after filtering`);

        // Sort projects
        const sorted = [...filtered].sort((a, b) => {
            const aTitle = String(a.title || '').toLowerCase();
            const bTitle = String(b.title || '').toLowerCase();
            const aCategory = String(a.category || '').toLowerCase();
            const bCategory = String(b.category || '').toLowerCase();
            
            switch (sortBy.value) {
                case 'name':
                    return aTitle.localeCompare(bTitle);
                    
                case 'category':
                    return aCategory.localeCompare(bCategory) || aTitle.localeCompare(bTitle);
                    
                case 'latest':
                default:
                    const dateA = a.created_at ? new Date(a.created_at) : new Date(0);
                    const dateB = b.created_at ? new Date(b.created_at) : new Date(0);
                    return dateB - dateA;
            }
        });
        
        console.log('Final filtered and sorted projects:', sorted.map(p => p.title));
        return sorted;
    });

    // Actions
    const setProjects = (newProjects) => {
        console.log('Setting projects...');
        
        if (!Array.isArray(newProjects)) {
            console.warn('Invalid projects data:', newProjects);
            projects.value = [];
            return;
        }
        
        // Process and validate each project
        projects.value = newProjects.map(project => ({
            id: project.id || Math.random().toString(36).substr(2, 9),
            title: project.title || 'Untitled Project',
            description: project.description || '',
            category: project.category || 'uncategorized',
            technologies: Array.isArray(project.technologies) 
                ? project.technologies.map(t => String(t || '').trim()).filter(Boolean)
                : [],
            created_at: project.created_at || new Date().toISOString(),
            thumbnail_url: project.thumbnail_url || '',
            github_url: project.github_url || '',
            live_url: project.live_url || '',
            is_featured: Boolean(project.is_featured),
            ...project // Keep any additional properties
        }));
        
        console.log(`Set ${projects.value.length} projects`);
        if (projects.value.length > 0) {
            console.log('First project sample:', JSON.parse(JSON.stringify(projects.value[0])));
        }
    };

    const setSearchTerm = (term) => {
        console.log('Setting search term:', term);
        searchTerm.value = term || '';
        console.log('Search term after set:', searchTerm.value);
    };

    const setSelectedCategory = (category) => {
        selectedCategory.value = category || 'all';
    };

    const setSortBy = (sortOption) => {
        sortBy.value = sortOption || 'latest';
    };

    const clearFilters = () => {
        searchTerm.value = '';
        selectedCategory.value = 'all';
    };

    // Watch for state changes and persist to localStorage
    watch(
        [searchTerm, selectedCategory, sortBy, projects],
        () => {
            saveState({
                searchTerm: searchTerm.value,
                selectedCategory: selectedCategory.value,
                sortBy: sortBy.value,
                projects: projects.value
            });
        },
        { deep: true }
    );

    return {
        // State
        searchTerm,
        selectedCategory,
        sortBy,
        projects,
        isLoading,
        error,
        
        // Getters
        categories,
        filteredProjects,
        getCategoryCount,
        
        // Actions
        setProjects,
        setSearchTerm,
        setSelectedCategory,
        setSortBy,
        clearFilters
    };
});
