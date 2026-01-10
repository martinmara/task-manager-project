<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    projects: Array,
    teams: Array,
    user: Object, // ensure user is passed
})

// Admin check (works with: props.user.role.name or props.user.roles[])
const isAdmin = computed(() => {
    const u = props.user
    if (!u) return false

    const single = u.role?.name ? String(u.role.name).toLowerCase() : null
    if (single === 'admin') return true

    const list = Array.isArray(u.roles) ? u.roles : []
    return list.some(r => (r?.name ? String(r.name).toLowerCase() : '') === 'admin')
})

const form = ref({
    name: '',
    description: '',
    team_id: ''
})

const safeProjects = computed(() => props.projects ?? [])
const safeTeams = computed(() => props.teams ?? [])

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
                <form @submit.prevent="submit" class="space-y-3">
                    <input
                        v-model="form.name"
                        placeholder="Project Name"
                        class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white placeholder-gray-400 focus:outline-none focus:ring focus:border-yellow-400"
                        required
                    />
                    <textarea
                        v-model="form.description"
                        placeholder="Description"
                        class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white placeholder-gray-400 focus:outline-none focus:ring focus:border-yellow-400"
                    ></textarea>
                    <select
                        v-model="form.team_id"
                        class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white focus:outline-none focus:ring focus:border-yellow-400"
                        required
                    >
                        <option disabled value="">Select Team</option>
                        <option v-for="team in safeTeams" :key="team.id" :value="team.id">{{ team.name }}</option>
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-yellow-400 text-[#1e144f] px-4 py-2 rounded hover:bg-yellow-300 font-semibold transition">Add</button>
                        <button type="button" @click="form.name=''; form.description=''; form.team_id=''" class="px-4 py-2 rounded border border-white/10">Reset</button>
                    </div>
                </form>
            </div>

            <div style="margin-bottom: 50px;">
                <h2 class="text-2xl font-semibold mb-4">All Projects</h2>

                <div v-if="safeProjects.length">
                    <ul class="space-y-4">
                        <li
                            v-for="project in safeProjects"
                            :key="project.id"
                            class="bg-gray-800 border border-white/10 p-4 rounded-lg flex justify-between items-start"
                        >
                            <div>
                                <div class="text-lg font-semibold text-yellow-300">{{ project.name }}</div>
                                <div class="text-sm text-gray-300">Team: {{ project.team?.name ?? 'No Team' }}</div>
                                <p class="text-sm text-gray-400 mt-1">{{ project.description ?? '' }}</p>
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

                <div v-else class="text-sm text-gray-200/80">No projects yet.</div>
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
