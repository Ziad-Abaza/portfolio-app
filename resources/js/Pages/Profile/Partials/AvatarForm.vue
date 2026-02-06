<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const user = computed(() => usePage().props.auth.user);
const avatarPreview = ref(user.value.avatar_url);

const form = useForm({
    avatar: null,
});

const avatarInput = ref(null);

const selectAvatar = () => {
    avatarInput.value.click();
};

const updateAvatarPreview = () => {
    const photo = avatarInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        avatarPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const uploadAvatar = () => {
    form.post(route('profile.avatar.upload'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            avatarPreview.value = user.value.avatar_url;
        },
        onError: () => {
            avatarPreview.value = user.value.avatar_url;
        },
    });
};

const deleteAvatar = () => {
    form.delete(route('profile.avatar.delete'), {
        preserveScroll: true,
        onSuccess: () => {
            avatarPreview.value = user.value.avatar_url;
        },
    });
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Avatar</h2>
            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile avatar.
            </p>
        </header>

        <div class="space-y-4">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img
                        :src="avatarPreview"
                        :alt="user.name"
                        class="h-20 w-20 rounded-full object-cover"
                    />
                    <div
                        v-if="user.avatar"
                        class="absolute -bottom-2 -right-2 rounded-full bg-red-500 p-1 text-white hover:bg-red-600"
                    >
                        <button
                            @click="deleteAvatar"
                            class="flex h-6 w-6 items-center justify-center"
                            :disabled="form.processing"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <input
                        ref="avatarInput"
                        type="file"
                        class="hidden"
                        @change="updateAvatarPreview"
                        @input="form.avatar = $event.target.files[0]"
                        accept="image/*"
                    />
                    
                    <button
                        @click="selectAvatar"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                        :disabled="form.processing"
                    >
                        Choose Avatar
                    </button>

                    <button
                        v-if="form.avatar"
                        @click="uploadAvatar"
                        class="ml-2 rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                        :disabled="form.processing"
                    >
                        Upload
                    </button>
                </div>
            </div>

            <div v-if="form.errors.avatar" class="text-sm text-red-600">
                {{ form.errors.avatar }}
            </div>

            <div class="text-xs text-gray-500">
                Accepted file types: JPEG, PNG, JPG, GIF. Maximum file size: 2MB.
            </div>
        </div>

        <div v-if="$page.props.status === 'avatar-uploaded'" class="text-sm text-green-600">
            Avatar uploaded successfully!
        </div>

        <div v-if="$page.props.status === 'avatar-deleted'" class="text-sm text-green-600">
            Avatar deleted successfully!
        </div>
    </section>
</template>
