<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { get } from '../api';

const stats = ref<Record<string, number>>({});
const messages = ref<any[]>([]);

onMounted(async () => {
    const res = await get<{ stats: Record<string, number>; recent_messages: any[] }>('/overview');
    stats.value = res.stats;
    messages.value = res.recent_messages;
});

const cards = [
    ['projects', 'Projects'], ['published', 'Published'], ['sections_visible', 'Live sections'],
    ['skills', 'Skills'], ['metrics', 'Metrics'], ['unread_messages', 'Unread messages'],
];
</script>

<template>
    <div>
        <h1 class="mono text-lg tracking-widest uppercase mb-6">Dashboard</h1>
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 mb-8">
            <div v-for="[key, label] in cards" :key="key" class="a-card">
                <p class="mono" style="font-size:1.9rem;color:var(--accent)">{{ stats[key] ?? '—' }}</p>
                <p class="a-label" style="margin:0">{{ label }}</p>
            </div>
        </div>

        <h2 class="mono text-xs tracking-widest uppercase mb-3" style="color:var(--text-dim)">Recent messages</h2>
        <div class="a-card" style="padding:0">
            <table class="a-table">
                <thead><tr><th>From</th><th>Locale</th><th>Received</th><th></th></tr></thead>
                <tbody>
                    <tr v-for="m in messages" :key="m.id">
                        <td>{{ m.name }} <span style="color:var(--text-dim)">&lt;{{ m.email }}&gt;</span></td>
                        <td class="mono">{{ m.locale }}</td>
                        <td class="mono" style="color:var(--text-dim)">{{ m.created_at }}</td>
                        <td><span class="a-badge" :class="m.read_at ? '' : 'warn'">{{ m.read_at ? 'read' : 'new' }}</span></td>
                    </tr>
                    <tr v-if="!messages.length"><td colspan="4" style="text-align:center;color:var(--text-dim);padding:2rem">No messages yet.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
