<script setup>
import { defineProps } from 'vue';
import UserManagement from './Team.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const props = defineProps({
  users: Array
});
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="text-2xl font-bold text-[#1e144f]">Dashboard</div>
    </template>

    <div class="px-4 sm:px-6 lg:px-8 py-6 bg-[#f9f9fb] space-y-8">
      <!-- User Management Section -->
      <section>
        <UserManagement :users="props.users" />
      </section>
    </div>
  </AuthenticatedLayout>
</template>
