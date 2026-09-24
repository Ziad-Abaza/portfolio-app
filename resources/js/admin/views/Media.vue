<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { del, get, upload } from '../api';

const rows = ref<any[]>([]);
const uploading = ref(false);
const error = ref('');

async function load(): Promise<void> {
    rows.value = (await get<{ data: any[] }>('/media')).data;
}
onMounted(load);

async function onFile(e: Event): Promise<void> {
    const input = e.target as HTMLInputElement;
    const file = input.files?.[0];
    if (!file) return;
    uploading.value = true; error.value = '';
    try {
        const form = new FormData();
        form.append('file', file);
        await upload('/media', form);
        await load();
    } catch (err: any) {
        error.value = err.message ?? 'Upload failed';
    } finally {
        uploading.value = false;
        input.value = '';
    }
}

async function remove(m: any): Promise<void> {
    if (!confirm(`Delete ${m.orig_name}?`)) return;
    await del(`/media/${m.id}`);
    await load();
}

const url = (m: any) => `/media/${m.id}/${m.orig_name}`;
const isImage = (m: any) => (m.mime as string).startsWith('image/');
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="mono text-lg tracking-widest uppercase">Media Library</h1>
            <label class="a-btn a-btn-primary" style="cursor:pointer">
                {{ uploading ? 'Uploading…' : '+ Upload' }}
                <input type="file" class="hidden" accept="image/*,application/pdf" @change="onFile" :disabled="uploading">
            </label>
        </div>
        <p v-if="error" class="text-sm mb-4" style="color:var(--err)">{{ error }}</p>
        <p class="text-xs mb-4" style="color:var(--text-dim)">Max 4 MB — jpg, png, webp, avif, gif, svg (sanitized), pdf.</p>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <div v-for="m in rows" :key="m.id" class="a-card" style="padding:.75rem">
                <div style="aspect-ratio:1;background:var(--elevated);border-radius:4px;overflow:hidden;display:grid;place-items:center;margin-bottom:.625rem">
                    <img v-if="isImage(m)" :src="url(m)" :alt="m.alt?.en ?? m.orig_name" style="width:100%;height:100%;object-fit:cover" loading="lazy">
                    <span v-else class="mono text-xs" style="color:var(--text-dim)">{{ m.mime }}</span>
                </div>
                <p class="mono text-xs truncate" :title="m.orig_name">{{ m.orig_name }}</p>
                <div class="flex gap-1.5 mt-2">
                    <button class="a-btn" style="padding:.2rem .5rem;font-size:.62rem" @click="navigator.clipboard?.writeText(url(m))">Copy URL</button>
                    <button class="a-btn a-btn-danger" style="padding:.2rem .5rem;font-size:.62rem" @click="remove(m)">Del</button>
                </div>
            </div>
            <p v-if="!rows.length" class="a-card col-span-full text-center" style="color:var(--text-dim)">Empty library.</p>
        </div>
    </div>
</template>
