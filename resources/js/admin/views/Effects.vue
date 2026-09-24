<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { get, put } from '../api';

const fx = ref<any>(null);
const msg = ref('');

onMounted(async () => {
    fx.value = (await get<{ data: any }>('/effects')).data;
});

async function save(): Promise<void> {
    const res = await put<{ data: any }>('/effects', { effects: fx.value });
    fx.value = res.data;
    msg.value = 'Saved — effects apply on next page load.';
    setTimeout(() => (msg.value = ''), 3000);
}

const toggles = [
    ['magnetic', 'Magnetic buttons', 'CTAs attract the cursor within a radius'],
    ['parallax', 'Parallax', 'Layered depth on scroll'],
    ['transitions', 'Signal-sweep transitions', 'Cinematic page-change sweep'],
    ['cursor', 'Cursor reticle', 'Engineer crosshair cursor on desktop'],
    ['boot', 'Boot sequence', 'Orchestrated intro on first load'],
];
</script>

<template>
    <div v-if="fx">
        <h1 class="mono text-lg tracking-widest uppercase mb-2">Effects & Field</h1>
        <p class="text-sm mb-6" style="color:var(--text-dim)">prefers-reduced-motion always overrides these settings.</p>
        <p v-if="msg" class="mono text-xs mb-4" style="color:var(--accent)">{{ msg }}</p>

        <div class="a-card mb-4">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <strong>System Field</strong>
                    <p class="text-xs mt-1" style="color:var(--text-dim)">The living node-graph background</p>
                </div>
                <button type="button" class="a-switch" role="switch" :aria-checked="fx.field.enabled"
                    @click="fx.field.enabled = !fx.field.enabled" aria-label="Toggle system field"></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="a-label">Density — {{ fx.field.density.toFixed(1) }}×</span>
                    <input type="range" class="a-range" min="0.2" max="2" step="0.1" v-model.number="fx.field.density">
                </div>
                <div>
                    <span class="a-label">Intensity — {{ fx.field.intensity.toFixed(1) }}×</span>
                    <input type="range" class="a-range" min="0" max="2" step="0.1" v-model.number="fx.field.intensity">
                </div>
            </div>
        </div>

        <div class="a-card mb-6">
            <div v-for="[key, label, desc] in toggles" :key="key" class="flex items-center justify-between py-3 border-b border-[var(--border)] last:border-0">
                <div>
                    <strong class="text-sm">{{ label }}</strong>
                    <p class="text-xs" style="color:var(--text-dim)">{{ desc }}</p>
                </div>
                <button type="button" class="a-switch" role="switch" :aria-checked="!!fx[key]"
                    @click="fx[key] = !fx[key]" :aria-label="`Toggle ${label}`"></button>
            </div>
        </div>

        <button class="a-btn a-btn-primary" @click="save">Save effects</button>
    </div>
</template>
