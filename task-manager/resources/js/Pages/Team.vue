<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  users: Array
});

const updateTeamRole = (userId, roleId) => {
  router.put(`/users/${userId}/role`, { role_id: roleId });
};
</script>

<template>
  <div class="bg-white p-4 rounded-xl shadow space-y-4">
    <h3 class="text-lg font-bold text-[#1e144f] mb-4">Team Management</h3>

    <table class="w-full text-left">
      <thead>
        <tr class="border-b text-[#1e144f]">
          <th class="py-2">Team Member</th>
          <th class="py-2">Email</th>
          <th class="py-2">Team Role</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in props.users" :key="user.id" class="border-b">
          <td class="py-2">{{ user.name }}</td>
          <td class="py-2">{{ user.email }}</td>
          <td class="py-2">
            <select
              :value="user.roles[0]?.id"
              @change="e => updateTeamRole(user.id, e.target.value)"
              class="border rounded px-2 py-1"
            >
              <option value="1">Team Lead</option>
              <option value="2">Developer</option>
              <option value="3">Support</option>
              <option value="4">Member</option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
