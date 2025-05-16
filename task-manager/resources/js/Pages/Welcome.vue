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
    <Head :title="`Welcome to Task Manager`" />

    <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <div class="text-2xl font-bold text-[#1e144f]"><img src="./components/images/logo-task-manager.jpg" alt="" style="width: 10%;border-radius: 100%;"></div>
        <nav class="hidden md:flex gap-6 text-[#1e144f] font-medium">
            <a href="#" class="hover:text-yellow-500">Home</a>
            <a href="#" class="hover:text-yellow-500">About Us</a>
            <a href="#" class="hover:text-yellow-500">Blog</a>
            <Link
                v-if="canLogin"
                :href="route('login')"
                class="ml-6 bg-yellow-400 text-white px-5 py-2 rounded-full font-semibold hover:bg-yellow-500 transition"
            >
                Login
            </Link>
        </nav>
    </header>

    <section
        class="relative bg-cover bg-center h-[80vh] flex items-center justify-center text-center"
        style="background-image: url('https://images.pexels.com/photos/3867836/pexels-photo-3867836.jpeg?auto=compress&cs=tinysrgb&w=600');"
    >
        <div class="bg-black bg-opacity-50 w-full h-full absolute top-0 left-0"></div>

        <div class="relative z-10 text-white px-4">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Organize Tasks. Empower Teams.</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Welcome to Task Manager — the modern way to manage projects and boost productivity.
            </p>
            <Link
                v-if="canLogin"
                :href="route('login')"
                class="bg-yellow-400 hover:bg-yellow-500 text-white px-8 py-3 rounded-full text-lg font-semibold transition"
            >
                Get Started
            </Link>
        </div>
    </section>
    <section class="py-16 bg-gray-100">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-semibold text-gray-800 mb-6">Why Choose Task Manager?</h2>
        <p class="text-xl text-gray-600 mb-12 max-w-3xl mx-auto">
            Discover the powerful features that help you manage tasks, collaborate with your team, and boost productivity.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <img src="https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg" alt="Collaboration" class="w-full h-48 object-cover rounded-lg mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Seamless Collaboration</h3>
                <p class="text-gray-600">
                    Work together in real-time, share updates, and keep your team aligned to reach goals faster.
                </p>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6">
                <img src="https://images.pexels.com/photos/3183197/pexels-photo-3183197.jpeg" alt="Task Management" class="w-full h-48 object-cover rounded-lg mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Efficient Task Management</h3>
                <p class="text-gray-600">
                    Stay on top of your tasks with customizable to-do lists, deadlines, and reminders.
                </p>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6">
                <img src="https://images.pexels.com/photos/3183184/pexels-photo-3183184.jpeg" alt="Insights and Analytics" class="w-full h-48 object-cover rounded-lg mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Insights & Analytics</h3>
                <p class="text-gray-600">
                    Get valuable insights into your team's performance and track project progress with powerful analytics.
                </p>
            </div>
        </div>
    </div>
</section>


    <footer class="bg-[#1e144f] text-white text-center py-6 mt-10">
        <p class="text-sm">
            © {{ new Date().getFullYear() }} {{ appName }}. All rights reserved. <br />
            Powered by <span class="font-semibold text-yellow-400">Managers Team</span>.
        </p>
    </footer>
</template>

<style scoped>
body {
    font-family: 'Inter', sans-serif;
}
</style>
