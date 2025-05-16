<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { LogOut, Home, Users, Calendar, BarChart3, Settings } from 'lucide-vue-next';
import { Bell } from 'lucide-vue-next';
import { Search } from 'lucide-vue-next';


const showingNavigationDropdown = ref(false);
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
            <Link href="#" class="p-2 hover:bg-[#f8c400] hover:text-white rounded-lg">
                <Settings />
            </Link>
            
                <button>
                    <DropdownLink class="p-2 hover:bg-red-500 hover:text-white rounded-lg" :href="route('logout')" method="post" >
                                 <LogOut />
                            </DropdownLink>
                   
                </button>
            
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Nav -->
            <nav class="flex items-center justify-between bg-white px-6 py-4 shadow-sm">
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
                <div class="flex items-center gap-4">
                   <button
    type="button"
    class="inline-flex items-center gap-2 rounded-full text-sm font-medium text-gray-700 focus:outline-none hover:bg-gray-100 p-2"
>
    <Bell class="w-5 h-5 text-[#1e144f]" />
</button>

                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-full text-sm font-medium text-gray-700 focus:outline-none"
                            >
                                <img :src="$page.props.auth.user.profile_photo_url || 'https://i.pravatar.cc/150?img=3'" alt="avatar" class="h-9 w-9 rounded-full border" />
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
        </div>
    </div>
</template>

<style scoped>
input::placeholder {
    color: #a0aec0;
}
</style>
