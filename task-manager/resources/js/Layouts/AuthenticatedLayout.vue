<script setup>
import { ref, onMounted } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link } from '@inertiajs/vue3'

// Icons
import {
    LogOut, Home, Users, Calendar, BarChart3, Settings,
    Bell, Search, Clock, CheckCircle
} from 'lucide-vue-next'

// Sidebar dropdown toggle
const showingNavigationDropdown = ref(false)

// Notifications
const showNotifications = ref(false)
const notifications = [
    { id: 1, message: 'New team member added to Project Alpha.' },
    { id: 2, message: 'You have a meeting at 3 PM.' },
    { id: 3, message: 'Task “Design Landing Page” is due tomorrow.' },
]

// Time & Footer Stats
const currentDate = ref('')
const currentTime = ref('')
const tasksCompletedToday = ref(5) // Replace with actual dynamic data
const totalTeams = ref(3) // Replace with actual dynamic data

const updateDateTime = () => {
    const now = new Date()
    currentDate.value = now.toLocaleDateString()
    currentTime.value = now.toLocaleTimeString()
}

onMounted(() => {
    updateDateTime()
    setInterval(updateDateTime, 1000)
})
</script>

<template>
    <div class="min-h-screen bg-[#f9f9fb] flex">
        <!-- Sidebar -->
        <aside class="w-[80px] bg-white shadow-md flex flex-col items-center py-4 space-y-8 rounded-tr-3xl rounded-br-3xl">
            <Link :href="route('dashboard')" class="p-2 bg-[#f8c400] rounded-lg">
                <Home class="text-white" />
            </Link>
            <Link :href="route('teams.index')" class="p-2 hover:bg-[#f8c400] hover:text-white rounded-lg">
                <Users />
            </Link>
            <Link href="#" class="p-2 hover:bg-[#f8c400] hover:text-white rounded-lg">
                <Calendar />
            </Link>
            <Link href="#" class="p-2 hover:bg-[#f8c400] hover:text-white rounded-lg">
                <BarChart3 />
            </Link>
            <Link :href="route('profile.edit')" class="p-2 hover:bg-[#f8c400] hover:text-white rounded-lg">
                <Settings />
            </Link>

    <button>
            <DropdownLink class="p-2 hover:bg-red-500 hover:text-white rounded-lg" :href="route('logout')" method="post">
                <LogOut />
            </DropdownLink>
            </button>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Nav -->
            <nav class="flex items-center justify-between bg-white px-6 py-4 shadow-sm">
                <!-- Search -->
                <div class="relative w-1/3">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <Search class="w-5 h-5" />
                    </span>
                    <input
                        type="text"
                        placeholder="Search your project"
                        class="pl-10 pr-4 py-2 border rounded-full w-full shadow-sm focus:outline-none focus:ring"
                    />
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-4">
                    <!-- Notification Bell -->
                    <div class="relative">
                        <button
                            @click="showNotifications = !showNotifications"
                            type="button"
                            class="relative inline-flex items-center gap-2 rounded-full text-sm font-medium text-gray-700 focus:outline-none hover:bg-gray-100 p-2"
                        >
                            <Bell class="w-5 h-5 text-[#1e144f]" />
                            <span
                                v-if="notifications.length"
                                class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"
                            ></span>
                        </button>

                       
                        <div
                            v-if="showNotifications"
                            class="absolute right-0 mt-2 w-64 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                        >
                            <div class="p-4 text-sm text-gray-800 font-semibold border-b">
                                Notifications
                            </div>
                            <ul class="max-h-60 overflow-y-auto text-sm text-gray-700 divide-y">
                                <li v-for="n in notifications" :key="n.id" class="px-4 py-2 hover:bg-gray-100">
                                    {{ n.message }}
                                </li>
                            </ul>
                            <div class="px-4 py-2 text-xs text-right text-blue-600 hover:underline cursor-pointer">
                                View all
                            </div>
                        </div>
                    </div>

              
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-full text-sm font-medium text-gray-700 focus:outline-none"
                            >
                                <img :src="$page.props.auth.user.profile_photo_url || ''" alt="avatar" class="h-11 w-11 rounded-lg border" />
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">
                                Profile
                            </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto px-6 py-6">
                <slot />
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t px-6 py-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <Calendar class="w-4 h-4 text-[#1e144f]" />
                    <span>{{ currentDate }}</span>

                    <Clock class="w-4 h-4 text-[#1e144f]" />
                    <span>{{ currentTime }}</span>
                </div>

                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <CheckCircle class="w-4 h-4 text-green-500" />
                    <span>Tasks done: {{ tasksCompletedToday }}</span>

                    <Users class="w-4 h-4 text-blue-600" />
                    <span>Total teams: {{ totalTeams }}</span>
                </div>
            </footer>
        </div>
    </div>
</template>

<style scoped>
input::placeholder {
    color: #a0aec0;
}
</style>
