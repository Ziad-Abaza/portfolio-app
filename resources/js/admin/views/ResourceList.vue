<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import { del, get, post, put } from '../api';
import BField from '../components/BField.vue';
import JsonField from '../components/JsonField.vue';

const props = defineProps<{ resource: string }>();

interface FieldDef {
    key: string; label?: string;
    type: 'text' | 'number' | 'bool' | 'bilingual' | 'json' | 'select';
    options?: string[]; readonly?: boolean;
}

const SCHEMAS: Record<string, { title: string; fields: FieldDef[]; summary: (r: any) => string }> = {
    sections: {
        title: 'Sections',
        summary: (r) => `${r.key}`,
        fields: [
            { key: 'key', type: 'text', readonly: true },
            { key: 'name', type: 'bilingual', label: 'Name' },
            { key: 'props', type: 'json', label: 'Props — items / targets' },
            { key: 'sort_order', type: 'number' },
            { key: 'visible', type: 'bool' },
        ],
    },
    skills: {
        title: 'Skills',
        summary: (r) => `${r.name} — ${typeof r.grp === 'object' ? r.grp?.en ?? '' : r.grp}`,
        fields: [
            { key: 'grp', type: 'bilingual', label: 'Group' },
            { key: 'name', type: 'text' },
            { key: 'level', type: 'number' },
            { key: 'note', type: 'bilingual' },
            { key: 'sort_order', type: 'number' },
        ],
    },
    timeline: {
        title: 'Timeline',
        summary: (r) => `${r.year} — ${typeof r.title === 'object' ? r.title?.en ?? '' : r.title}`,
        fields: [
            { key: 'year', type: 'text' },
            { key: 'title', type: 'bilingual' },
            { key: 'description', type: 'bilingual' },
            { key: 'kind', type: 'select', options: ['work', 'oss', 'product', 'milestone'] },
            { key: 'sort_order', type: 'number' },
        ],
    },
    metrics: {
        title: 'Metrics',
        summary: (r) => `${r.value}${r.suffix ?? ''} — ${typeof r.label === 'object' ? r.label?.en ?? '' : r.label}`,
        fields: [
            { key: 'label', type: 'bilingual' },
            { key: 'value', type: 'text' },
            { key: 'suffix', type: 'text' },
            { key: 'context', type: 'bilingual' },
            { key: 'sort_order', type: 'number' },
        ],
    },
    socials: {
        title: 'Social Links',
        summary: (r) => `${r.label} — ${r.url}`,
        fields: [
            { key: 'label', type: 'text' },
            { key: 'url', type: 'text' },
            { key: 'icon', type: 'select', options: ['github', 'linkedin', 'x', 'mail', 'link'] },
            { key: 'sort_order', type: 'number' },
            { key: 'visible', type: 'bool' },
        ],
    },
};

const schema = computed(() => SCHEMAS[props.resource]);
const rows = ref<any[]>([]);
const editing = ref<any | null>(null);
const saving = ref(false);
const error = ref('');

async function load(): Promise<void> {
    const res = await get<{ data: any[] }>(`/content/${props.resource}`);
    rows.value = res.data;
}
onMounted(load);
watch(() => props.resource, load);

function blank(): any {
    const row: any = {};
    for (const f of schema.value.fields) {
        row[f.key] = f.type === 'bilingual' ? { en: '', ar: '' }
            : f.type === 'json' ? {}
            : f.type === 'bool' ? 0
            : f.type === 'number' ? 0 : '';
    }
    return row;
}

async function save(): Promise<void> {
    saving.value = true; error.value = '';
    try {
        if (editing.value.id) {
            await put(`/content/${props.resource}/${editing.value.id}`, editing.value);
        } else {
            await post(`/content/${props.resource}`, editing.value);
        }
        editing.value = null;
        await load();
    } catch (e: any) {
        error.value = e.message ?? 'Save failed';
    } finally {
        saving.value = false;
    }
}

async function remove(row: any): Promise<void> {
    if (!confirm(`Delete ${schema.value.summary(row)}?`)) return;
    await del(`/content/${props.resource}/${row.id}`);
    await load();
}

async function move(row: any, dir: -1 | 1): Promise<void> {
    const list = [...rows.value];
    const i = list.findIndex((r) => r.id === row.id);
    const j = i + dir;
    if (i < 0 || j < 0 || j >= list.length) return;
    [list[i], list[j]] = [list[j]!, list[i]!];
    rows.value = list;
    await post(`/content/${props.resource}/reorder`, { order: list.map((r) => r.id) });
}
</script>

<template>
    <div v-if="schema">
        <div class="flex items-center justify-between mb-6">
            <h1 class="mono text-lg tracking-widest uppercase" style="color:var(--text)">{{ schema.title }}</h1>
            <button class="a-btn a-btn-primary" @click="editing = blank()">+ New</button>
        </div>

        <div v-if="editing" class="a-card mb-6">
            <p class="mono text-xs uppercase tracking-widest mb-4" style="color:var(--accent)">
                {{ editing.id ? 'Edit' : 'Create' }} {{ props.resource }}
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template v-for="f in schema.fields" :key="f.key">
                    <div :class="(f.type === 'bilingual' || f.type === 'json') ? 'md:col-span-2' : ''">
                        <BField v-if="f.type === 'bilingual'" v-model="editing[f.key]" :label="f.label ?? f.key" />
                        <JsonField v-else-if="f.type === 'json'" v-model="editing[f.key]" :label="f.label ?? f.key" />
                        <label v-else-if="f.type === 'bool'" class="flex items-center gap-3 pt-5 cursor-pointer">
                            <button type="button" class="a-switch" role="switch" :aria-checked="!!editing[f.key]"
                                @click="editing[f.key] = editing[f.key] ? 0 : 1"></button>
                            <span class="a-label" style="margin:0">{{ f.label ?? f.key }}</span>
                        </label>
                        <div v-else>
                            <span class="a-label">{{ f.label ?? f.key }}</span>
                            <select v-if="f.type === 'select'" v-model="editing[f.key]" class="a-select">
                                <option v-for="o in f.options" :key="o" :value="o">{{ o }}</option>
                            </select>
                            <input v-else v-model="editing[f.key]" class="a-input" :type="f.type === 'number' ? 'number' : 'text'"
                                :readonly="f.readonly" :style="f.readonly ? 'opacity:.5' : ''">
                        </div>
                    </div>
                </template>
            </div>
            <p v-if="error" class="mt-3 text-sm" style="color:var(--err)">{{ error }}</p>
            <div class="flex gap-2 mt-5">
                <button class="a-btn a-btn-primary" :disabled="saving" @click="save">{{ saving ? 'Saving…' : 'Save' }}</button>
                <button class="a-btn" @click="editing = null">Cancel</button>
            </div>
        </div>

        <div class="a-card" style="padding:0;overflow-x:auto">
            <table class="a-table">
                <thead>
                    <tr>
                        <th style="width:5rem">Order</th>
                        <th>Item</th>
                        <th style="width:9rem;text-align:end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in rows" :key="row.id">
                        <td class="mono" style="color:var(--text-dim)">
                            <button class="a-btn" style="padding:.15rem .45rem" @click="move(row, -1)" aria-label="Move up">↑</button>
                            <button class="a-btn" style="padding:.15rem .45rem" @click="move(row, 1)" aria-label="Move down">↓</button>
                        </td>
                        <td>{{ schema.summary(row) }}</td>
                        <td style="text-align:end;white-space:nowrap">
                            <button class="a-btn" style="padding:.3rem .7rem" @click="editing = { ...row }">Edit</button>
                            <button class="a-btn a-btn-danger" style="padding:.3rem .7rem" @click="remove(row)">Del</button>
                        </td>
                    </tr>
                    <tr v-if="!rows.length"><td colspan="3" style="text-align:center;color:var(--text-dim);padding:2rem">Empty — create the first one.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
