<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { del, get, post, put } from '../api';

const themes = ref<any[]>([]);
const editing = ref<any | null>(null);
const msg = ref('');

const TOKEN_KEYS = ['bg', 'surface', 'elevated', 'border', 'text', 'text-dim', 'accent', 'accent-soft', 'accent-ember', 'accent-glow', 'ok', 'err', 'info'];

async function load(): Promise<void> {
    themes.value = (await get<{ data: any[] }>('/themes')).data;
}
onMounted(load);

const editingTokens = computed(() => editing.value?.tokens ?? {});

function setToken(mode: string, key: string, value: string): void {
    if (!editing.value.tokens[mode]) editing.value.tokens[mode] = {};
    editing.value.tokens[mode][key] = value;
}

async function saveTheme(): Promise<void> {
    await put(`/themes/${editing.value.id}`, { name: editing.value.name, tokens: editing.value.tokens });
    msg.value = 'Saved.';
    await load();
}

async function activate(id: number): Promise<void> {
    await post(`/themes/${id}/activate`);
    msg.value = 'Theme activated — reload the site to see it.';
    await load();
}

async function duplicate(t: any): Promise<void> {
    await post('/themes', { name: `${t.name} copy`, tokens: t.tokens });
    await load();
}

async function remove(t: any): Promise<void> {
    if (!confirm(`Delete theme "${t.name}"?`)) return;
    try {
        await del(`/themes/${t.id}`);
        await load();
    } catch (e: any) {
        msg.value = e.message;
    }
}

const isColor = (v: string) => /^#|^rgb|^hsl/.test(v);
</script>

<template>
    <div>
        <h1 class="mono text-lg tracking-widest uppercase mb-6">Themes</h1>
        <p v-if="msg" class="mono text-xs mb-4" style="color:var(--accent)">{{ msg }}</p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="a-card" v-for="t in themes" :key="t.id">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <strong>{{ t.name }}</strong>
                        <span v-if="t.is_active" class="a-badge on ms-2">active</span>
                        <span v-if="t.is_builtin" class="a-badge ms-2">builtin</span>
                    </div>
                    <div class="flex gap-2">
                        <button class="a-btn" style="padding:.3rem .7rem" @click="editing = JSON.parse(JSON.stringify(t))">Edit</button>
                        <button class="a-btn" style="padding:.3rem .7rem" @click="duplicate(t)">Clone</button>
                        <button v-if="!t.is_active" class="a-btn a-btn-primary" style="padding:.3rem .7rem" @click="activate(t.id)">Activate</button>
                        <button v-if="!t.is_builtin && !t.is_active" class="a-btn a-btn-danger" style="padding:.3rem .7rem" @click="remove(t)">Del</button>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div v-for="mode in ['dark', 'light']" :key="mode" class="flex-1">
                        <p class="a-label">{{ mode }}</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span v-for="key in TOKEN_KEYS.slice(0, 8)" :key="key"
                                :title="`${key}: ${t.tokens?.[mode]?.[key] ?? ''}`"
                                :style="{ background: t.tokens?.[mode]?.[key] ?? '#888', border: '1px solid var(--border)' }"
                                style="width:22px;height:22px;border-radius:3px;display:inline-block"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Token editor -->
        <div v-if="editing" class="a-card mt-6">
            <div class="flex items-center justify-between mb-4">
                <input v-model="editing.name" class="a-input" style="max-width:16rem">
                <div class="flex gap-2">
                    <button class="a-btn a-btn-primary" @click="saveTheme">Save theme</button>
                    <button class="a-btn" @click="editing = null">Close</button>
                </div>
            </div>

            <!-- Live token preview -->
            <div class="mb-5 p-4 rounded" :style="{ background: editingTokens.dark?.bg, border: '1px solid ' + (editingTokens.dark?.border ?? '#333') }">
                <p class="mono text-xs mb-2" :style="{ color: editingTokens.dark?.['text-dim'] }">LIVE PREVIEW — DARK</p>
                <p class="text-lg font-semibold" :style="{ color: editingTokens.dark?.text }">The quick brown fox — نظام حي</p>
                <span class="a-btn mt-2" :style="{ borderColor: editingTokens.dark?.accent, color: editingTokens.dark?.accent }">Sample action</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="mode in ['dark', 'light']" :key="mode">
                    <p class="a-label mb-3">{{ mode.toUpperCase() }} TOKENS</p>
                    <div class="flex flex-col gap-2">
                        <div v-for="key in TOKEN_KEYS" :key="key" class="flex items-center gap-3">
                            <code class="mono text-xs" style="width:8rem;color:var(--text-dim)">--{{ key }}</code>
                            <input v-if="isColor(editingTokens[mode]?.[key] ?? '')" type="color"
                                :value="(editingTokens[mode]?.[key] ?? '#000000').slice(0, 7)"
                                @input="setToken(mode, key, ($event.target as HTMLInputElement).value)"
                                style="width:2rem;height:1.6rem;border:none;background:none;cursor:pointer;padding:0">
                            <input class="a-input" style="font-family:var(--font-mono);font-size:.78rem" dir="ltr"
                                :value="editingTokens[mode]?.[key] ?? ''"
                                @input="setToken(mode, key, ($event.target as HTMLInputElement).value)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
