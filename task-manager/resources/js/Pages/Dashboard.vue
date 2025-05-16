<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TeamDashboard from './components/TeamDashboard.vue';
const props = defineProps({
  users: Array,
   teamsSummary: Array,

});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

       
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                  
            </div>
        </div>
    </AuthenticatedLayout>
</template>