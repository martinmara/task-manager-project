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

    <!-- Header -->
    <header class="fixed top-0 left-0 w-full z-50 bg-white/30 backdrop-blur-md border-b border-white/20 shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="./components/images/logo-task-manager.jpg" class="w-10 h-10 rounded-full" />
                <span class="text-xl font-extrabold text-[#1e144f] tracking-tight">{{ appName }}</span>
            </div>
            <nav class="hidden md:flex gap-7 font-bold text-[] items-center">
                <a href="#" class="hover:text-yellow-400 transition">Home</a>
                <a href="#" class="hover:text-yellow-400 transition">About</a>
                <a href="#" class="hover:text-yellow-400 transition">Blog</a>
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="ml-4 bg-yellow-400 hover:bg-yellow-500 text-white px-5 py-2 rounded-full transition shadow-md"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </Link>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section
        class="relative h-[90vh] w-full flex items-center justify-center text-white pt-24"
        style="background-image: url('https://images.pexels.com/photos/3867836/pexels-photo-3867836.jpeg?auto=compress&cs=tinysrgb&w=1200'); background-size: cover; background-position: center;"
    >
        <div class="absolute inset-0 bg-gradient-to-br from-[#1e144f]/90 to-[#000000]/70 z-0"></div>
        <div class="relative z-10 text-center px-6 max-w-3xl animate-fade-in-up">
            <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6 drop-shadow-xl">
                Organize Tasks. Empower Teams.
            </h1>
            <p class="text-lg md:text-xl mb-8 text-gray-300">
                Unlock your productivity with <span class="text-yellow-400 font-bold">{{ appName }}</span> — modern task management built for speed and clarity.
            </p>
            <Link
                v-if="canLogin"
                :href="route('login')"
                class="bg-yellow-400 hover:bg-yellow-500 text-[#1e144f] px-8 py-3 rounded-full font-semibold text-lg shadow-xl transition"
            >
                <i class="fas fa-rocket mr-2"></i>Get Started
            </Link>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-[#f7f8fc] py-20 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-4xl font-bold text-[#1e144f] mb-4">Why Choose {{ appName }}?</h2>
            <p class="text-lg text-gray-600 mb-12 max-w-3xl mx-auto">
                Intuitive tools to help you manage work and lead high-performance teams.
            </p>
            <div class="grid gap-10 grid-cols-1 md:grid-cols-3">
                <div class="rounded-xl bg-white p-8 shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
                    <div class="text-yellow-400 text-4xl mb-4"><i class="fas fa-users"></i></div>
                    <h3 class="text-xl font-bold text-[#1e144f] mb-2">Team Collaboration</h3>
                    <p class="text-gray-600">Stay in sync with real-time updates and shared workspaces.</p>
                </div>

                <div class="rounded-xl bg-white p-8 shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
                    <div class="text-yellow-400 text-4xl mb-4"><i class="fas fa-tasks"></i></div>
                    <h3 class="text-xl font-bold text-[#1e144f] mb-2">Task Management</h3>
                    <p class="text-gray-600">Organize and prioritize your daily tasks with ease.</p>
                </div>

                <div class="rounded-xl bg-white p-8 shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
                    <div class="text-yellow-400 text-4xl mb-4"><i class="fas fa-chart-line"></i></div>
                    <h3 class="text-xl font-bold text-[#1e144f] mb-2">Analytics</h3>
                    <p class="text-gray-600">Gain insights into team performance and productivity trends.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#1e144f] text-white text-center py-8 mt-12">
        <p class="text-sm leading-6">
            © {{ new Date().getFullYear() }} {{ appName }}. All rights reserved. <br />
            Powered by <span class="font-semibold text-yellow-400">Managers Team</span>.
        </p>
    </footer>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');

body {
    font-family: 'Inter', sans-serif;
}

@keyframes fade-in-up {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out forwards;
}
</style>
