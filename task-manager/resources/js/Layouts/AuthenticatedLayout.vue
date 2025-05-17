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
    Bell, Search, Clock, CheckCircle, LayoutDashboard, ClipboardList
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
         <Link :href="route('projects.index')" class="p-2 hover:bg-[#f8c400] hover:text-white rounded-lg">
    <LayoutDashboard />
</Link>
          <Link :href="route('tasks.index')" class="p-2 hover:bg-[#f8c400] hover:text-white rounded-lg">
    <ClipboardList />
</Link>
 <Link :href="route('timesheets.index')" class="p-2 hover:bg-[#f8c400] hover:text-white rounded-lg">
      <Clock class="w-5 h-5" />
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
                                <img :src=" 'data:image/jpeg;base64,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'" alt="avatar" class="h-11 w-11 rounded-lg border" />
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
            <footer style=" position: fixed;
          left: 0;
  bottom: 0;
  width: 100%;

  text-align: center;" class="bg-white border-t px-6 py-4 flex items-center justify-between shadow-sm">
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
