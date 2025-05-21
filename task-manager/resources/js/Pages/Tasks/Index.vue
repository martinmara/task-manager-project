<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    tasks: Array,
    projects: Array, // Each project has a `team` object with `users` array
    user: Object
})

// Admin check (by role name)
const isAdmin = computed(() =>
    props.user && (
        (props.user.role && props.user.role.toLowerCase() === 'admin') ||
        (props.user.roles?.some(r => r.name?.toLowerCase() === 'admin'))
    )
)

// Only show tasks assigned to the user, or all if admin
const visibleTasks = computed(() =>
    isAdmin.value
        ? (props.tasks ?? [])
        : (props.tasks ?? []).filter(t => t.user_id === props.user.id)
)

const form = ref({
    title: '',
    description: '',
    project_id: '',
    user_id: '',
    due_date: '',
    comments: ''
})

// Compute users assigned to the team of the selected project
const filteredUsers = computed(() => {
    const project = props.projects.find(p => p.id === form.value.project_id)
    return project?.team?.users ?? []
})

function submit() {
    router.post('/tasks', form.value, {
        onSuccess: () => {
            form.value.title = ''
            form.value.description = ''
            form.value.project_id = ''
            form.value.user_id = ''
            form.value.due_date = ''
            form.value.comments = ''
        }
    })
}

function deleteTask(id) {
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(`/tasks/${id}`)
    }
}

function completeTask(id) {
    router.put(`/tasks/${id}/complete`)
}
</script>

<template>
    <AuthenticatedLayout  :user="user" :tasks="tasks" :teams="teams" :projects="projects">
        <div class="space-y-6 text-white">
            <div class="bg-gray-800 p-6 rounded-xl shadow border border-white/10">
                <h1 class="text-3xl font-bold mb-6">Tasks</h1>

                <!-- Show form only for admin -->
                <div v-if="isAdmin" class="bg-gray-900 p-6 rounded-lg border border-white/10 mb-8">
                    <h2 class="text-xl font-semibold mb-4">Add Task</h2>
                    <div class="space-y-4">
                        <input v-model="form.title" placeholder="Task Title"
                            class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white placeholder-gray-400 focus:outline-none focus:ring focus:border-yellow-400" />
                        <textarea v-model="form.description" placeholder="Description"
                            class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white placeholder-gray-400 focus:outline-none focus:ring focus:border-yellow-400"></textarea>
                        <input v-model="form.due_date" type="date"
                            class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white focus:outline-none focus:ring focus:border-yellow-400" />
                        <select v-model="form.project_id"
                            class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white focus:outline-none focus:ring focus:border-yellow-400">
                            <option disabled value="">Select Project</option>
                            <option v-for="project in projects" :key="project.id" :value="project.id">{{ project.name }}</option>
                        </select>
                        <select v-model="form.user_id" :disabled="!form.project_id"
                            class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white focus:outline-none focus:ring focus:border-yellow-400">
                            <option disabled value="">Assign to User</option>
                            <option v-for="user in filteredUsers" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                        <textarea v-model="form.comments" placeholder="Comments"
                            class="w-full px-4 py-2 bg-gray-800 border border-white/20 rounded text-white placeholder-gray-400 focus:outline-none focus:ring focus:border-yellow-400"></textarea>
                        <button @click="submit"
                            class="bg-yellow-400 text-[#1e144f] px-4 py-2 rounded hover:bg-yellow-300 transition font-semibold">
                            Add Task
                        </button>
                    </div>
                </div>

                <!-- Task List -->
                <div>
                    <h2 class="text-2xl font-semibold mb-4">All Tasks</h2>
                    <ul class="space-y-4">
                        <li v-for="task in visibleTasks" :key="task.id"
                            class="bg-gray-900 border border-white/10 rounded-lg p-4 flex justify-between items-start gap-4">
                            <div class="space-y-1">
                                <div class="text-lg font-semibold text-yellow-400">{{ task.title }}</div>
                                <div class="text-sm text-gray-300">
                                    Project: <span class="font-medium">{{ task.project?.name ?? 'Unknown' }}</span> |
                                    Assigned to: <span class="font-medium">{{ task.user?.name ?? 'Unassigned' }}</span>
                                </div>
                                <div class="text-xs text-gray-400">Due: {{ task.due_date ?? 'N/A' }}</div>
                                <p class="text-sm text-gray-200">{{ task.description }}</p>
                                <p v-if="task.comments" class="text-xs text-gray-400">Comments: {{ task.comments }}</p>
                                <span v-if="task.status === 'done'"
                                    class="inline-block mt-2 px-2 py-0.5 bg-green-600 text-white rounded text-xs">Completed</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <button
                                    v-if="task.status !== 'done' && task.user_id === user.id"
                                    @click="completeTask(task.id)"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs"
                                >
                                    Complete
                                </button>
                                <button v-if="isAdmin"
                                    @click="deleteTask(task.id)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                    Delete
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
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
