<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const props = defineProps({
    projects: Array,
    teams: Array
});

const form = ref({
    name: '',
    description: '',
    team_id: ''
});

function submit() {
    router.post('/projects', form.value);
}
</script>

<template>
    <AuthenticatedLayout>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Projects</h1>
        <div class="bg-white p-4 rounded shadow max-w-xl mb-6">
            <h2 class="text-lg font-semibold mb-2">Add Project</h2>
            <input v-model="form.name" placeholder="Project Name" class="border p-2 w-full mb-2 rounded" />
            <textarea v-model="form.description" placeholder="Description" class="border p-2 w-full mb-2 rounded"></textarea>
            <select v-model="form.team_id" class="border p-2 w-full mb-2 rounded">
                <option disabled value="">Select Team</option>
                <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.name }}</option>
            </select>
            <button @click="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add</button>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-3">All Projects</h2>
            <ul>
                <li v-for="project in projects" :key="project.id" class="bg-gray-100 p-3 rounded mb-2">
                    <strong>{{ project.name }}</strong> - Team: {{ project.team.name }}
                    <p class="text-sm">{{ project.description }}</p>
                </li>
            </ul>
        </div>
    </div>
    </AuthenticatedLayout>
</template>
