<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    teams: Array,
    users: Array,
    user: Object // Make sure user is passed as a prop!
})

// Admin check (for your user structure)
const isAdmin = computed(() =>
    props.user && props.user.role && props.user.role.toLowerCase() === 'admin'
)

const form = ref({
    name: '',
    description: '',
    member_ids: []
})

function toggleMember(id) {
    const index = form.value.member_ids.indexOf(id)
    if (index === -1) {
        form.value.member_ids.push(id)
    } else {
        form.value.member_ids.splice(index, 1)
    }
}

function isChecked(id) {
    return form.value.member_ids.includes(id)
}

function submit() {
    router.post('/teams', form.value, {
        preserveScroll: true,
        onSuccess: () => {
            form.value.name = ''
            form.value.description = ''
            form.value.member_ids = []
        }
    })
}

function deleteTeam(teamId) {
    if (confirm('Are you sure you want to delete this team?')) {
        router.delete(`/teams/${teamId}`)
    }
}
</script>

<template>
    <AuthenticatedLayout :user="user" :tasks="tasks" :teams="teams" :projects="projects">
        <div class="space-y-6">
            <!-- Team List -->
            <div class="bg-gray-800 text-white p-6 rounded-xl shadow border border-white/10">
                <h2 class="text-2xl font-bold mb-4">Your Teams</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        v-for="team in teams"
                        :key="team.id"
                        class="bg-gray-900 p-4 rounded-lg border border-white/10 shadow"
                    >
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-semibold text-yellow-400">{{ team.name }}</h3>
                                <p class="text-sm text-gray-400">{{ team.description }}</p>
                            </div>
                            <button
                                v-if="isAdmin"
                                @click="deleteTeam(team.id)"
                                class="text-red-500 hover:text-red-700 font-semibold text-sm"
                            >
                                Delete
                            </button>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-300">Members:</h4>
                            <ul class="list-disc list-inside text-sm text-gray-400">
                                <li v-for="member in team.members" :key="member.id">
                                    {{ member.name }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Team (admin only) -->
            <div v-if="isAdmin" class="bg-gray-800 text-white p-6 rounded-xl shadow border border-white/10" style="margin-bottom: 50px;">
                <h2 class="text-xl font-semibold mb-4">Create New Team</h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Team Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Team Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full mt-1 border border-white/20 bg-gray-700 text-white px-3 py-2 rounded focus:outline-none focus:ring focus:border-yellow-400"
                        />
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Description</label>
                        <textarea
                            v-model="form.description"
                            class="w-full mt-1 border border-white/20 bg-gray-700 text-white px-3 py-2 rounded focus:outline-none focus:ring focus:border-yellow-400"
                        ></textarea>
                    </div>

                    <!-- Members -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Select Members</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <label
                                v-for="user in users"
                                :key="user.id"
                                class="flex items-center space-x-2 text-gray-200"
                            >
                                <input
                                    type="checkbox"
                                    :value="user.id"
                                    :checked="isChecked(user.id)"
                                    @change="toggleMember(user.id)"
                                    class="accent-yellow-400"
                                />
                                <span>{{ user.name }}</span>
                            </label>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="bg-yellow-400 text-[#1e144f] font-semibold px-4 py-2 rounded hover:bg-yellow-300 transition"
                    >
                        Add Team
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
input::placeholder,
textarea::placeholder {
    color: #cbd5e1;
}
</style>
