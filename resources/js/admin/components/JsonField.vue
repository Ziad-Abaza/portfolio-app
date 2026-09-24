<script setup lang="ts">
/** JSON editor field — textarea with live validation. */
import { computed, ref, watch } from 'vue';

const props = defineProps<{ label: string; modelValue: unknown }>();
const emit = defineEmits<{ 'update:modelValue': [unknown] }>();

const text = ref(JSON.stringify(props.modelValue ?? {}, null, 2));
const invalid = ref(false);

watch(() => props.modelValue, (v) => { text.value = JSON.stringify(v ?? {}, null, 2); });

const cls = computed(() => invalid.value ? 'a-textarea border-[var(--err)]' : 'a-textarea');

function onInput(e: Event): void {
    const raw = (e.target as HTMLTextAreaElement).value;
    text.value = raw;
    try {
        emit('update:modelValue', JSON.parse(raw));
        invalid.value = false;
    } catch {
        invalid.value = true;
    }
}
</script>

<template>
    <div>
        <span class="a-label">{{ label }} <span v-if="invalid" style="color:var(--err)">— invalid JSON</span></span>
        <textarea :class="cls" rows="5" :value="text" @input="onInput" spellcheck="false" dir="ltr" style="font-family:var(--font-mono);font-size:.78rem"></textarea>
    </div>
</template>
