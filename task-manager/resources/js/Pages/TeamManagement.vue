<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const teams = ref([]);
const teamName = ref('');
const members = ref([{ name: '', email: '', role: '' }]);

onMounted(async () => {
    const res = await axios.get('/teams');
    teams.value = res.data.props.teams;
});

const addMember = () => {
    members.value.push({ name: '', email: '', role: '' });
};

const createTeam = () => {
    router.post('/teams', {
        name: teamName.value,
        members: members.value
    }, {
        onSuccess: () => {
            teamName.value = '';
            members.value = [{ name: '', email: '', role: '' }];
            location.reload();
        }
    });
};

const props = defineProps({
  users: Array,
  teams: Array 
});
</script>

<template>
  <pre>{{ props.teams }}</pre>

  <div class="p-6 space-y-6">
    <h2 class="text-2xl font-bold text-[#1e144f]">Create Team</h2>

    <input v-model="teamName" placeholder="Team Name" class="border p-2 rounded w-full" />

    <h3 class="mt-4 font-semibold">Team Members</h3>
    <div v-for="(member, i) in members" :key="i" class="flex gap-2 mb-2">
      <input v-model="member.name" placeholder="Name" class="border p-2 rounded w-1/3" />
      <input v-model="member.email" placeholder="Email" class="border p-2 rounded w-1/3" />
      <input v-model="member.role" placeholder="Role" class="border p-2 rounded w-1/3" />
    </div>
    <button @click="addMember" class="text-sm text-blue-600">+ Add Member</button>
    <br />
    <button @click="createTeam" class="bg-blue-600 text-white px-4 py-2 rounded">Create Team</button>

    <hr class="my-6" />

    <h2 class="text-2xl font-bold text-[#1e144f]">Teams</h2>
    <div v-for="team in teams" :key="team.id" class="bg-white p-4 rounded shadow mb-4">
      <h3 class="text-lg font-semibold">{{ team.name }}</h3>
      <ul class="list-disc ml-6">
        <li v-for="member in team.members" :key="member.id">{{ member.name }} - {{ member.role }}</li>
      </ul>
    </div>
  </div>
</template>
