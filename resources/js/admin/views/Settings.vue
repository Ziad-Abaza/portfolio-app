<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { get, put } from '../api';
import BField from '../components/BField.vue';

const settings = ref<Record<string, any>>({});
const msg = ref('');
const pw = ref({ current_password: '', new_password: '' });
const pwMsg = ref('');

onMounted(async () => {
    settings.value = (await get<{ data: any }>('/settings')).data;
});

async function save(): Promise<void> {
    await put('/settings', { settings: settings.value });
    msg.value = 'Saved.';
    setTimeout(() => (msg.value = ''), 3000);
}

async function changePassword(): Promise<void> {
    pwMsg.value = '';
    try {
        await put('/password', pw.value);
        pwMsg.value = 'Password updated.';
        pw.value = { current_password: '', new_password: '' };
    } catch (e: any) {
        pwMsg.value = e.payload?.errors ? 'Check fields — new password needs 10+ chars, current must be correct.' : e.message;
    }
}
</script>

<template>
    <div>
        <h1 class="mono text-lg tracking-widest uppercase mb-6">Settings</h1>
        <p v-if="msg" class="mono text-xs mb-4" style="color:var(--accent)">{{ msg }}</p>

        <div class="a-card mb-4">
            <p class="a-label mb-3">Identity</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><span class="a-label">Name</span><input v-model="settings['site.name']" class="a-input"></div>
                <div><span class="a-label">Email</span><input v-model="settings['site.email']" class="a-input" dir="ltr" type="email"></div>
                <BField v-model="settings['site.role']" label="Role" />
                <BField v-model="settings['site.tagline']" label="Tagline" multiline />
                <BField v-model="settings['site.location']" label="Location" />
                <BField v-model="settings['footer.note']" label="Footer note" />
            </div>
            <label class="flex items-center gap-3 mt-4 cursor-pointer">
                <button type="button" class="a-switch" role="switch" :aria-checked="!!settings['site.availability']"
                    @click="settings['site.availability'] = !settings['site.availability']"></button>
                <span class="a-label" style="margin:0">Show "available" status</span>
            </label>
        </div>

        <div class="a-card mb-6">
            <p class="a-label mb-3">Change password</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><span class="a-label">Current</span><input v-model="pw.current_password" type="password" class="a-input" autocomplete="current-password"></div>
                <div><span class="a-label">New (10+ chars)</span><input v-model="pw.new_password" type="password" class="a-input" autocomplete="new-password"></div>
            </div>
            <p v-if="pwMsg" class="text-xs mt-3" :style="pwMsg.includes('updated') ? 'color:var(--ok)' : 'color:var(--err)'">{{ pwMsg }}</p>
            <button class="a-btn mt-4" @click="changePassword">Update password</button>
        </div>

        <button class="a-btn a-btn-primary" @click="save">Save settings</button>
    </div>
</template>
