<script setup lang="ts">
/** Bilingual field — edits a {"en":..,"ar":..} JSON object as two inputs. */
const props = defineProps<{
    label: string;
    modelValue: Record<string, string> | string | null | undefined;
    multiline?: boolean;
}>();
const emit = defineEmits<{ 'update:modelValue': [Record<string, string>] }>();

function current(): Record<string, string> {
    if (typeof props.modelValue === 'object' && props.modelValue !== null) return props.modelValue;
    if (typeof props.modelValue === 'string') return { en: props.modelValue, ar: '' };
    return { en: '', ar: '' };
}
function set(locale: string, value: string): void {
    emit('update:modelValue', { ...current(), [locale]: value });
}
</script>

<template>
    <div>
        <span class="a-label">{{ label }}</span>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div>
                <component :is="multiline ? 'textarea' : 'input'" class="a-input" :rows="multiline ? 3 : undefined"
                    :value="current().en" @input="set('en', ($event.target as HTMLInputElement).value)"
                    placeholder="English" dir="ltr" />
            </div>
            <div>
                <component :is="multiline ? 'textarea' : 'input'" class="a-input" :rows="multiline ? 3 : undefined"
                    :value="current().ar" @input="set('ar', ($event.target as HTMLInputElement).value)"
                    placeholder="العربية" dir="rtl" />
            </div>
        </div>
    </div>
</template>
