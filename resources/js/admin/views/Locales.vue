<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { get, put } from '../api';

const settings = ref<Record<string, any>>({});
const msg = ref('');

onMounted(async () => {
    settings.value = (await get<{ data: any }>('/settings')).data;
});

async function save(): Promise<void> {
    await put('/settings', { settings: settings.value });
    msg.value = 'Saved.';
    setTimeout(() => (msg.value = ''), 3000);
}

function toggleLocale(code: string): void {
    const list: string[] = settings.value['locales.enabled'] ?? [];
    settings.value['locales.enabled'] = list.includes(code) ? list.filter((l) => l !== code) : [...list, code];
}
</script>

<template>
    <div>
        <h1 class="mono text-lg tracking-widest uppercase mb-6">Languages</h1>
        <p v-if="msg" class="mono text-xs mb-4" style="color:var(--accent)">{{ msg }}</p>

        <div class="a-card mb-4">
            <p class="a-label">Enabled locales</p>
            <div class="flex gap-4 py-2">
                <label v-for="l in ['en', 'ar']" :key="l" class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" :checked="(settings['locales.enabled'] ?? []).includes(l)" @change="toggleLocale(l)" style="accent-color:var(--accent)">
                    <span class="mono text-sm">{{ l === 'en' ? 'English' : 'العربية' }} ({{ l }})</span>
                </label>
            </div>
            <p class="text-xs mt-2" style="color:var(--text-dim)">Adding a locale creates translated fields everywhere — content is stored per-locale.</p>
        </div>

        <div class="a-card mb-4">
            <span class="a-label">Default locale (root redirect + x-default)</span>
            <select v-model="settings['locales.default']" class="a-select" style="max-width:12rem">
                <option v-for="l in (settings['locales.enabled'] ?? ['en'])" :key="l" :value="l">{{ l }}</option>
            </select>
        </div>

        <div class="a-card mb-6">
            <span class="a-label">Default color mode</span>
            <select v-model="settings['theme.default_mode']" class="a-select" style="max-width:12rem">
                <option value="dark">dark</option>
                <option value="light">light</option>
            </select>
        </div>

        <button class="a-btn a-btn-primary" @click="save">Save</button>
    </div>
</template>
