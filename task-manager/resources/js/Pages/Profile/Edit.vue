<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="p-6 space-y-8">
            <!-- Page Heading -->
            <h1 class="text-3xl font-bold text-white">Profile</h1>

            <!-- Profile Info Form -->
            <div class="bg-white p-6 rounded-xl shadow text-black">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <!-- Password Update -->
            <div class="bg-white p-6 rounded-xl shadow text-black">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <!-- Account Deletion -->
            <div class="bg-white p-6 rounded-xl shadow text-black">
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
