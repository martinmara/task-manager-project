<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  users: Array
});
</script>

<template>

  <AuthenticatedLayout>
  

    <div class="px-4 sm:px-6 lg:px-8 py-6 bg-[#f9f9fb] space-y-8">
      <section>
      </section>
      

    </div>
  </AuthenticatedLayout>
</template>
