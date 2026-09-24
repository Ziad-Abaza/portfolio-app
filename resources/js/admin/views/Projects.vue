<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { del, get, post } from '../api';
import { useRouter } from 'vue-router';

const router = useRouter();
const rows = ref<any[]>([]);

async function load(): Promise<void> {
    rows.value = (await get<{ data: any[] }>('/projects')).data;
}
onMounted(load);

async function create(): Promise<void> {
    const slug = prompt('Slug (latin, kebab-case):', 'new-project');
    if (!slug) return;
    const res = await post<{ data: any }>('/projects', {
        slug, title: { en: 'New project', ar: '' }, status: 'draft', sort_order: rows.value.length,
    });
    router.push(`/projects/${res.data.id}`);
}

async function remove(row: any): Promise<void> {
    if (!confirm(`Delete ${row.title?.en ?? row.slug}? Blocks go with it.`)) return;
    await del(`/projects/${row.id}`);
    await load();
}
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="mono text-lg tracking-widest uppercase">Projects</h1>
            <button class="a-btn a-btn-primary" @click="create">+ New project</button>
        </div>
        <div class="a-card" style="padding:0;overflow-x:auto">
            <table class="a-table">
                <thead><tr><th>Project</th><th>Domain</th><th>Status</th><th>Featured</th><th style="text-align:end">Actions</th></tr></thead>
                <tbody>
                    <tr v-for="p in rows" :key="p.id" style="cursor:pointer" @click="router.push(`/projects/${p.id}`)">
                        <td>
                            <strong>{{ p.title?.en ?? p.slug }}</strong>
                            <span class="mono block text-xs" style="color:var(--text-dim)">/{{ p.slug }}</span>
                        </td>
                        <td class="mono text-xs" style="color:var(--text-dim)">{{ p.domain?.en }}</td>
                        <td><span class="a-badge" :class="p.status === 'published' ? 'on' : 'warn'">{{ p.status }}</span></td>
                        <td><span v-if="p.featured" class="a-badge on">★ featured</span></td>
                        <td style="text-align:end;white-space:nowrap" @click.stop>
                            <button class="a-btn a-btn-danger" style="padding:.3rem .7rem" @click="remove(p)">Del</button>
                        </td>
                    </tr>
                    <tr v-if="!rows.length"><td colspan="5" style="text-align:center;color:var(--text-dim);padding:2rem">No projects yet.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
