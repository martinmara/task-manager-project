<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    tasks: Array,
    projects: Array, // Each project has a `team` object with `users` array
});

const form = ref({
    title: '',
    description: '',
    project_id: '',
    user_id: ''
});

// Compute users assigned to the team of the selected project
const filteredUsers = computed(() => {
    const project = props.projects.find(p => p.id === form.value.project_id);
    // Return users from the project's team
    return project?.team?.users ?? [];
});

function submit() {
    router.post('/tasks', form.value);
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Tasks</h1>

            <!-- Task Form -->
            <div class="bg-white p-4 rounded shadow max-w-xl mb-6">
                <h2 class="text-lg font-semibold mb-2">Add Task</h2>

                <input v-model="form.title" placeholder="Task Title" class="border p-2 w-full mb-2 rounded" />

                <textarea v-model="form.description" placeholder="Description" class="border p-2 w-full mb-2 rounded"></textarea>

                <!-- Project Select -->
                <select v-model="form.project_id" class="border p-2 w-full mb-2 rounded">
                    <option disabled value="">Select Project</option>
                    <option v-for="project in projects" :key="project.id" :value="project.id">{{ project.name }}</option>
                </select>

                <!-- User Select filtered by project's team users -->
                <select v-model="form.user_id" class="border p-2 w-full mb-2 rounded" :disabled="!form.project_id">
                    <option disabled value="">Assign to User</option>
                    <option v-for="user in filteredUsers" :key="user.id" :value="user.id">{{ user.name }}</option>
                </select>

                <button @click="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add</button>
            </div>

            <!-- Task List -->
            <div>
                <h2 class="text-xl font-semibold mb-3">All Tasks</h2>
                <ul>
                    <li v-for="task in tasks" :key="task.id" class="bg-gray-100 p-3 rounded mb-2">
                        <strong>{{ task.title }}</strong> - 
                        Project: {{ task.project?.name ?? 'Unknown' }} | 
                        Assigned to: {{ task.user?.name ?? 'Unassigned' }}
                        <p class="text-sm">{{ task.description }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
