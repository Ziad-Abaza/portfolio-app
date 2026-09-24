<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { del, get, post } from '../api';
import { adminBoot } from '../api';

const rows = ref<any[]>([]);
const open = ref<number | null>(null);

async function load(): Promise<void> {
    rows.value = (await get<{ data: any[] }>('/messages')).data;
}
onMounted(load);

async function toggle(m: any): Promise<void> {
    open.value = open.value === m.id ? null : m.id;
    if (!m.read_at) {
        await post(`/messages/${m.id}/read`);
        m.read_at = new Date().toISOString();
        adminBoot.unread = Math.max(0, adminBoot.unread - 1);
    }
}

async function remove(m: any): Promise<void> {
    if (!confirm('Delete message?')) return;
    await del(`/messages/${m.id}`);
    rows.value = rows.value.filter((x) => x.id !== m.id);
}
</script>

<template>
    <div>
        <h1 class="mono text-lg tracking-widest uppercase mb-6">Messages</h1>
        <div class="flex flex-col gap-3">
            <div v-for="m in rows" :key="m.id" class="a-card" style="cursor:pointer" @click="toggle(m)">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="status-dot" :style="m.read_at ? 'background:var(--border);box-shadow:none;animation:none' : ''" aria-hidden="true"></span>
                    <strong>{{ m.name }}</strong>
                    <span class="mono text-xs" style="color:var(--text-dim)">&lt;{{ m.email }}&gt;</span>
                    <span class="a-badge ms-auto">{{ m.locale }}</span>
                    <span class="mono text-xs" style="color:var(--text-dim)">{{ m.created_at }}</span>
                </div>
                <p v-if="open === m.id" class="mt-3 text-sm whitespace-pre-wrap" style="color:var(--text);line-height:1.8">{{ m.message }}</p>
                <div v-if="open === m.id" class="flex gap-2 mt-3" @click.stop>
                    <a class="a-btn" :href="`mailto:${m.email}`">Reply ↗</a>
                    <button class="a-btn a-btn-danger" @click="remove(m)">Delete</button>
                </div>
            </div>
            <p v-if="!rows.length" class="a-card text-center" style="color:var(--text-dim)">Inbox zero.</p>
        </div>
    </div>
</template>
