<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    
        <Head title="Log in" />

        <div class="min-h-screen flex items-center justify-center bg-[#f9f9fb] px-4">
            <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
                <h2 class="text-3xl font-bold text-[#1e144f] mb-6 text-center">Welcome Back</h2>

                <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
                    {{ status }}
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-gray-500 underline hover:text-gray-700"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <div class="mt-6">
                        <PrimaryButton
                            class="w-full justify-center bg-yellow-400 hover:bg-yellow-500 text-white font-semibold"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Log in
                        </PrimaryButton>
                    </div>

                    <!-- Register Redirect -->
                    <div class="mt-6 text-center text-sm text-gray-600">
                        Don't have an account?
                        <Link :href="route('register')" class="text-yellow-500 font-semibold hover:underline">
                            Register here
                        </Link>
                    </div>
                </form>
            </div>
        </div>
</template>

<style scoped>
body {
    font-family: 'Inter', sans-serif;
}
</style>
