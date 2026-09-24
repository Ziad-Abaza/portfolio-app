<script setup lang="ts">
import { computed } from 'vue';
import { adminBoot } from './api';

const nav = [
    { group: 'Overview', items: [{ to: '/', label: 'Dashboard', icon: '◈' }] },
    {
        group: 'Content',
        items: [
            { to: '/sections', label: 'Sections', icon: '▤' },
            { to: '/projects', label: 'Projects', icon: '◆' },
            { to: '/skills', label: 'Skills', icon: '◍' },
            { to: '/timeline', label: 'Timeline', icon: '◔' },
            { to: '/metrics', label: 'Metrics', icon: '◫' },
            { to: '/socials', label: 'Social Links', icon: '⌁' },
            { to: '/messages', label: 'Messages', icon: '✉' },
        ],
    },
    {
        group: 'Appearance',
        items: [
            { to: '/themes', label: 'Themes', icon: '◐' },
            { to: '/effects', label: 'Effects & Field', icon: '✦' },
        ],
    },
    {
        group: 'System',
        items: [
            { to: '/seo', label: 'SEO', icon: '◎' },
            { to: '/locales', label: 'Languages', icon: '文' },
            { to: '/media', label: 'Media', icon: '▣' },
            { to: '/settings', label: 'Settings', icon: '⚙' },
        ],
    },
];

const user = computed(() => adminBoot.user);
const csrf = adminBoot.csrf;
</script>

<template>
    <div class="flex min-h-screen">
        <aside class="w-56 shrink-0 border-e border-[var(--border)] bg-[var(--surface)] flex flex-col" style="position:sticky;top:0;height:100vh;overflow-y:auto">
            <div class="p-4 border-b border-[var(--border)]">
                <p class="mono text-xs tracking-widest" style="color:var(--accent)">ZH.SYS</p>
                <p class="mono text-[0.66rem] mt-1" style="color:var(--text-dim)">CONSOLE</p>
            </div>
            <nav class="flex-1 p-2" aria-label="Admin">
                <div v-for="group in nav" :key="group.group" class="mb-4">
                    <p class="mono text-[0.6rem] tracking-widest uppercase px-3 py-2" style="color:var(--text-dim)">{{ group.group }}</p>
                    <RouterLink v-for="item in group.items" :key="item.to" :to="item.to" class="a-nav-link" exact-active-class="router-link-active">
                        <span aria-hidden="true" style="width:1rem;text-align:center">{{ item.icon }}</span>
                        {{ item.label }}
                        <span v-if="item.to === '/messages' && adminBoot.unread > 0" class="a-badge warn ms-auto">{{ adminBoot.unread }}</span>
                    </RouterLink>
                </div>
            </nav>
            <div class="p-3 border-t border-[var(--border)]">
                <p class="text-xs mb-2" style="color:var(--text-dim)">{{ user?.email }}</p>
                <div class="flex gap-2">
                    <a href="/" class="a-btn flex-1 justify-center" target="_blank">Site ↗</a>
                    <form method="post" action="/admin/logout" class="flex-1">
                        <input type="hidden" name="_token" :value="csrf">
                        <button type="submit" class="a-btn a-btn-danger w-full justify-center">Logout</button>
                    </form>
                </div>
            </div>
        </aside>
        <main class="flex-1 min-w-0 p-6 lg:p-8" style="max-width:1400px">
            <RouterView />
        </main>
    </div>
</template>
