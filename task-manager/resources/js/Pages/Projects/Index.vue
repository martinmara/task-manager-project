<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    projects: Array,
    teams: Array,
    user: Object, // Make sure user is passed as a prop!
})

// Admin check (for your user structure)
const isAdmin = computed(() =>
    props.user && props.user.role && props.user.role.toLowerCase() === 'admin'
)

const form = ref({
    name: '',
    description: '',
    team_id: ''
})

function submit() {
    router.post('/projects', form.value, {
        onSuccess: () => {
            form.value.name = ''
            form.value.description = ''
            form.value.team_id = ''
        }
    })
}

function deleteProject(id) {
    if (confirm('Are you sure you want to delete this project?')) {
        router.delete(`/projects/${id}`)
    }
}
</script>

<template>
    <AuthenticatedLayout :user="user" :tasks="tasks" :teams="teams" :projects="projects">
        <div class="p-6 text-white space-y-8">
            <!-- Page Title -->
            <h1 class="text-3xl font-bold">Projects</h1>

            <!-- Project Form (admin only) -->
            <div v-if="isAdmin" class="bg-gray-900 border border-white/10 p-6 rounded-xl shadow max-w-xl">
                <h2 class="text-xl font-semibold mb-4">Add Project</h2>
                <input
                    v-model="form.name"
                    placeholder="Project Name"
                    class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white placeholder-gray-400 focus:outline-none focus:ring focus:border-yellow-400 mb-3"
                />
                <textarea
                    v-model="form.description"
                    placeholder="Description"
                    class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white placeholder-gray-400 focus:outline-none focus:ring focus:border-yellow-400 mb-3"
                ></textarea>
                <select
                    v-model="form.team_id"
                    class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white focus:outline-none focus:ring focus:border-yellow-400 mb-4"
                >
                    <option disabled value="">Select Team</option>
                    <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.name }}</option>
                </select>
                <button
                    @click="submit"
                    class="bg-yellow-400 text-[#1e144f] px-4 py-2 rounded hover:bg-yellow-300 font-semibold transition"
                >
                    Add
                </button>
            </div>

            <!-- Project List -->
            <div>
                <h2 class="text-2xl font-semibold mb-4">All Projects</h2>
                <ul class="space-y-4">
                    <li
                        v-for="project in projects"
                        :key="project.id"
                        class="bg-gray-800 border border-white/10 p-4 rounded-lg flex justify-between items-start"
                    >
                        <div>
                            <div class="text-lg font-semibold text-yellow-300">{{ project.name }}</div>
                            <div class="text-sm text-gray-300">Team: {{ project.team.name }}</div>
                            <p class="text-sm text-gray-400 mt-1">{{ project.description }}</p>
                        </div>
                        <button
                            v-if="isAdmin"
                            @click="deleteProject(project.id)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs"
                        >
                            Delete
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
input::placeholder,
textarea::placeholder,
select::placeholder {
    color: #94a3b8;
}
</style>
