<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    appName: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head :title="`Welcome to ${appName}`" />
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex flex-col justify-center items-center text-center px-6">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white">Welcome to {{ appName }}</h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 max-w-xl">
            Your go-to solution for managing tasks efficiently. Start by logging in or signing up to explore the features!
        </p>
        
        <nav v-if="canLogin" class="mt-6 flex space-x-4">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Go to Dashboard
            </Link>
            <template v-else>
                <Link :href="route('login')" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Log in
                </Link>
                <Link v-if="canRegister" :href="route('register')" class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800">
                    Register
                </Link>
            </template>
        </nav>
    </div>
</template>
