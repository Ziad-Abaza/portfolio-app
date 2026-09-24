<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { del, get, post, put } from '../api';
import BField from '../components/BField.vue';
import JsonField from '../components/JsonField.vue';

const props = defineProps<{ id: string }>();

const project = ref<any | null>(null);
const blocks = ref<any[]>([]);
const saving = ref(false);
const msg = ref('');
const newBlockType = ref('text');
const blockTypes = ['problem', 'approach', 'architecture', 'outcome', 'metrics', 'text', 'gallery'];

async function load(): Promise<void> {
    const res = await get<{ data: any }>(`/projects/${props.id}`);
    project.value = res.data;
    blocks.value = res.data.blocks ?? [];
}
onMounted(load);

async function save(): Promise<void> {
    saving.value = true; msg.value = '';
    try {
        await put(`/projects/${props.id}`, project.value);
        msg.value = 'Saved.';
        await load();
    } catch (e: any) {
        msg.value = e.payload?.errors ? JSON.stringify(e.payload.errors) : (e.message ?? 'Save failed');
    } finally {
        saving.value = false;
    }
}

async function addBlock(): Promise<void> {
    const res = await post<{ data: any }>(`/projects/${props.id}/blocks`, {
        type: newBlockType.value,
        content: { en: '', ar: '' },
        sort_order: blocks.value.length,
    });
    blocks.value.push({ ...res.data, content: res.data.content ? JSON.parse(res.data.content) : { en: '', ar: '' } });
}

async function saveBlock(b: any): Promise<void> {
    await put(`/blocks/${b.id}`, { type: b.type, content: b.content, sort_order: b.sort_order });
    msg.value = 'Block saved.';
}

async function removeBlock(b: any): Promise<void> {
    if (!confirm('Delete this block?')) return;
    await del(`/blocks/${b.id}`);
    blocks.value = blocks.value.filter((x) => x.id !== b.id);
}

const stackText = {
    get: () => (project.value?.stack ?? []).join(', '),
    set: (v: string) => { project.value.stack = v.split(',').map((s) => s.trim()).filter(Boolean); },
};
</script>

<template>
    <div v-if="project">
        <div class="flex items-center justify-between mb-6">
            <h1 class="mono text-lg tracking-widest uppercase">
                {{ project.title?.en ?? project.slug }}
                <span class="a-badge ms-2" :class="project.status === 'published' ? 'on' : 'warn'">{{ project.status }}</span>
            </h1>
            <div class="flex gap-2">
                <a class="a-btn" :href="`/en/work/${project.slug}`" target="_blank">Preview ↗</a>
                <button class="a-btn a-btn-primary" :disabled="saving" @click="save">{{ saving ? 'Saving…' : 'Save' }}</button>
            </div>
        </div>
        <p v-if="msg" class="mono text-xs mb-4" style="color:var(--accent)">{{ msg }}</p>

        <div class="a-card mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><span class="a-label">Slug</span><input v-model="project.slug" class="a-input" dir="ltr"></div>
                <div>
                    <span class="a-label">Status</span>
                    <select v-model="project.status" class="a-select">
                        <option value="draft">draft</option>
                        <option value="published">published</option>
                    </select>
                </div>
                <BField v-model="project.title" label="Title" />
                <BField v-model="project.summary" label="Summary" multiline />
                <BField v-model="project.role" label="Role" />
                <BField v-model="project.domain" label="Domain" />
                <div class="md:col-span-2">
                    <span class="a-label">Stack (comma separated)</span>
                    <input :value="stackText.get()" @input="stackText.set(($event.target as HTMLInputElement).value)" class="a-input" dir="ltr">
                </div>
                <BField v-model="project.body" label="Body (long-form)" multiline />
                <div><span class="a-label">Sort order</span><input v-model.number="project.sort_order" type="number" class="a-input"></div>
                <label class="flex items-center gap-3 pt-5 cursor-pointer">
                    <button type="button" class="a-switch" role="switch" :aria-checked="!!project.featured"
                        @click="project.featured = project.featured ? 0 : 1"></button>
                    <span class="a-label" style="margin:0">Featured on home</span>
                </label>
                <div class="md:col-span-2"><JsonField v-model="project.links" label="Links {live, github}" /></div>
                <div class="md:col-span-2"><JsonField v-model="project.metrics" label="Metrics [{label:{en,ar}, value}]" /></div>
            </div>
        </div>

        <h2 class="mono text-xs tracking-widest uppercase mb-3" style="color:var(--text-dim)">Case study blocks</h2>
        <div class="a-card mb-4" v-for="b in blocks" :key="b.id">
            <div class="flex items-center gap-3 mb-3">
                <span class="a-badge on">{{ b.type }}</span>
                <input v-model.number="b.sort_order" type="number" class="a-input" style="width:5rem" aria-label="Sort order">
                <div class="ms-auto flex gap-2">
                    <button class="a-btn" style="padding:.3rem .7rem" @click="saveBlock(b)">Save</button>
                    <button class="a-btn a-btn-danger" style="padding:.3rem .7rem" @click="removeBlock(b)">Del</button>
                </div>
            </div>
            <BField v-if="typeof b.content === 'object' && !Array.isArray(b.content)" v-model="b.content" label="Content" multiline />
            <JsonField v-else v-model="b.content" label="Content (JSON)" />
        </div>

        <div class="a-card flex items-center gap-3">
            <select v-model="newBlockType" class="a-select" style="width:12rem">
                <option v-for="t in blockTypes" :key="t" :value="t">{{ t }}</option>
            </select>
            <button class="a-btn a-btn-primary" @click="addBlock">+ Add block</button>
        </div>
    </div>
</template>
