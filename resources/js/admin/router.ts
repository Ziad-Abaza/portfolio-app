import { createRouter, createWebHistory } from 'vue-router';

export const router = createRouter({
    history: createWebHistory('/admin'),
    routes: [
        { path: '/', name: 'dashboard', component: () => import('./views/Dashboard.vue') },
        { path: '/sections', name: 'sections', component: () => import('./views/ResourceList.vue'), props: { resource: 'sections' } },
        { path: '/projects', name: 'projects', component: () => import('./views/Projects.vue') },
        { path: '/projects/:id', name: 'project-edit', component: () => import('./views/ProjectEdit.vue'), props: true },
        { path: '/skills', name: 'skills', component: () => import('./views/ResourceList.vue'), props: { resource: 'skills' } },
        { path: '/timeline', name: 'timeline', component: () => import('./views/ResourceList.vue'), props: { resource: 'timeline' } },
        { path: '/metrics', name: 'metrics', component: () => import('./views/ResourceList.vue'), props: { resource: 'metrics' } },
        { path: '/socials', name: 'socials', component: () => import('./views/ResourceList.vue'), props: { resource: 'socials' } },
        { path: '/messages', name: 'messages', component: () => import('./views/Messages.vue') },
        { path: '/themes', name: 'themes', component: () => import('./views/Themes.vue') },
        { path: '/effects', name: 'effects', component: () => import('./views/Effects.vue') },
        { path: '/seo', name: 'seo', component: () => import('./views/Seo.vue') },
        { path: '/locales', name: 'locales', component: () => import('./views/Locales.vue') },
        { path: '/media', name: 'media', component: () => import('./views/Media.vue') },
        { path: '/settings', name: 'settings', component: () => import('./views/Settings.vue') },
    ],
});
