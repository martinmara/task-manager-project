<script setup>
import { ref, onMounted, computed } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link } from '@inertiajs/vue3'

import {
    LogOut, Home, Users, Calendar, BarChart3, Settings,
    Bell, Search, Clock, CheckCircle, LayoutDashboard, ClipboardList
} from 'lucide-vue-next'

const showingNavigationDropdown = ref(false)
const showNotifications = ref(false)
const notifications = [
    { id: 1, message: 'New team member added to Project Alpha.' },
    { id: 2, message: 'You have a meeting at 3 PM.' },
    { id: 3, message: 'Task “Design Landing Page” is due tomorrow.' },
]

const props = defineProps({
    user: Object,
    tasks: Array,
    teams: Array
})

const isAdmin = computed(() =>
    props.user && (
        (props.user.role && props.user.role.name?.toLowerCase() === 'admin') ||
        (props.user.roles?.some(r => r.name?.toLowerCase() === 'admin'))
    )
)

const userTasks = computed(() =>
    isAdmin.value ? (props.tasks ?? []) : (props.tasks ?? []).filter(t => t.user_id === props.user.id)
)

const tasksCompletedToday = computed(() =>
    userTasks.value.filter(
        t => t.status === 'done' && t.updated_at && new Date(t.updated_at).toDateString() === new Date().toDateString()
    ).length
)

const userTeams = computed(() =>
    isAdmin.value ? (props.teams ?? []) : (props.teams ?? []).filter(team =>
        team.users?.some(u => u.id === props.user.id)
    )
)

const totalTeams = computed(() => userTeams.value.length)

const currentDate = ref('')
const currentTime = ref('')

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
    <div class="min-h-screen bg-gray-900 flex text-white font-sans">
        <!-- Sidebar -->
        <aside class="w-[80px] bg-gray-800 flex flex-col items-center py-4 space-y-8 rounded-tr-3xl rounded-br-3xl border-r border-white/10">
            <Link :href="route('dashboard')" class="p-2 bg-yellow-400 text-black rounded-lg">
                <Home />
            </Link>
            <Link :href="route('teams.index')" class="p-2 hover:bg-yellow-400 hover:text-black rounded-lg">
                <Users />
            </Link>
            <Link :href="route('projects.index')" class="p-2 hover:bg-yellow-400 hover:text-black rounded-lg">
                <LayoutDashboard />
            </Link>
            <Link :href="route('tasks.index')" class="p-2 hover:bg-yellow-400 hover:text-black rounded-lg">
                <ClipboardList />
            </Link>
            <Link :href="route('timesheets.index')" class="p-2 hover:bg-yellow-400 hover:text-black rounded-lg">
                <Clock class="w-5 h-5" />
            </Link>
            <Link :href="route('profile.edit')" class="p-2 hover:bg-yellow-400 hover:text-black rounded-lg">
                <Settings />
            </Link>
            <button>
            <DropdownLink class="p-2 hover:bg-red-500 hover:text- rounded-lg" :href="route('logout')" method="post">
                <LogOut />
            </DropdownLink>
            </button>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Nav -->
            <nav class="flex items-center justify-between bg-gray-800 px-6 py-4 shadow-md border-b border-white/10 relative z-40">
                <!-- Search -->
                <div class="relative w-1/3">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <Search class="w-5 h-5" />
                    </span>
                    <input
                        type="text"
                        placeholder="Search your project"
                        class="pl-10 pr-4 py-2 rounded-full w-full bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring"
                    />
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-4">
                    <!-- Notification Bell -->
                    <div class="relative">
                        <button
                            @click="showNotifications = !showNotifications"
                            class="relative inline-flex items-center gap-2 rounded-full focus:outline-none hover:bg-gray-700 p-2"
                        >
                            <Bell class="w-5 h-5 text-white" />
                            <span v-if="notifications.length" class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        </button>

                        <div
                            v-if="showNotifications"
                            class="absolute right-0 mt-2 w-64 rounded-lg shadow-lg bg-white text-black ring-1 ring-black/10 z-50"
                        >
                            <div class="p-4 text-sm font-semibold border-b">Notifications</div>
                            <ul class="max-h-60 overflow-y-auto text-sm divide-y">
                                <li v-for="n in notifications" :key="n.id" class="px-4 py-2 hover:bg-gray-100">
                                    {{ n.message }}
                                </li>
                            </ul>
                            <div class="px-4 py-2 text-xs text-right text-blue-600 hover:underline cursor-pointer">
                                View all
                            </div>
                        </div>
                    </div>

                    <!-- Avatar Dropdown -->
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="inline-flex items-center gap-2 rounded-full focus:outline-none">
                                <img :src="'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQbsPu8KU3tAvJXHWPpoVKpeBl7v_GcEV1fyQ&s'" alt="avatar" class="h-11 w-11 rounded-lg border" />
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto px-6 py-6 bg-gray-900">
                <slot />
            </main>

            <!-- Footer -->
            <footer class="fixed left-0 bottom-0 w-full bg-gray-800 text-white border-t border-white/10 px-6 py-4 flex items-center justify-between shadow-md z-30">
                <div class="flex items-center gap-4 text-sm">
                    <Calendar class="w-4 h-4 text-white" />
                    <span>{{ currentDate }}</span>

                    <Clock class="w-4 h-4 text-white" />
                    <span>{{ currentTime }}</span>
                </div>

                <div class="flex items-center gap-4 text-sm">
                    <CheckCircle class="w-4 h-4 text-green-400" />
                    <span>Tasks done: {{ tasksCompletedToday }}</span>

                    <Users class="w-4 h-4 text-blue-300" />
                    <span>Total teams: {{ totalTeams }}</span>
                </div>
            </footer>
        </div>
    </div>
</template>

<style scoped>
input::placeholder {
    color: #94a3b8;
}
</style>
