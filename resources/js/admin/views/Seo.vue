<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { get, put } from '../api';
import BField from '../components/BField.vue';

const items = ref<any[]>([]);
const msg = ref('');

onMounted(async () => {
    items.value = (await get<{ data: any[] }>('/seo')).data;
});

async function save(): Promise<void> {
    await put('/seo', {
        items: items.value.map((i) => ({ page: i.page, title: i.title, description: i.description, og_image: i.og_image })),
    });
    msg.value = 'Saved.';
    setTimeout(() => (msg.value = ''), 3000);
}

function addPage(): void {
    const page = prompt('Page key (home, work, contact, project:slug):');
    if (page) items.value.push({ page, title: { en: '', ar: '' }, description: { en: '', ar: '' }, og_image: '' });
}
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="mono text-lg tracking-widest uppercase">SEO</h1>
            <div class="flex gap-2">
                <button class="a-btn" @click="addPage">+ Page</button>
                <button class="a-btn a-btn-primary" @click="save">Save all</button>
            </div>
        </div>
        <p v-if="msg" class="mono text-xs mb-4" style="color:var(--accent)">{{ msg }}</p>

        <div class="a-card mb-4" v-for="item in items" :key="item.page">
            <p class="a-badge mb-3">{{ item.page }}</p>
            <div class="flex flex-col gap-3">
                <BField v-model="item.title" label="Meta title" />
                <BField v-model="item.description" label="Meta description" multiline />
                <div>
                    <span class="a-label">OG image path</span>
                    <input v-model="item.og_image" class="a-input" dir="ltr" placeholder="/media/…">
                </div>
            </div>
        </div>
    </div>
</template>
