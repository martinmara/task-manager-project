<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    teams: Array,
    users: Array
});

const form = ref({
    name: '',
    description: '',
    member_ids: []
});

function toggleMember(id) {
    const index = form.value.member_ids.indexOf(id);
    if (index === -1) {
        form.value.member_ids.push(id);
    } else {
        form.value.member_ids.splice(index, 1);
    }
}

function isChecked(id) {
    return form.value.member_ids.includes(id);
}

function submit() {
    router.post('/teams', form.value, {
        preserveScroll: true,
        onSuccess: () => {
            form.value.name = '';
            form.value.description = '';
            form.value.member_ids = [];
        }
    });
}

function deleteTeam(teamId) {
    if (confirm('Are you sure you want to delete this team?')) {
        router.delete(`/teams/${teamId}`);
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-6">
            <!-- Team List -->
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-4">Your Teams</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        v-for="team in teams"
                        :key="team.id"
                        class="bg-[#f9f9fb] p-4 rounded-lg border border-gray-200 shadow"
                    >
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-lg font-semibold text-[#1e144f]">{{ team.name }}</h3>
                                <p class="text-sm text-gray-500">{{ team.description }}</p>
                            </div>
                            <button
                                @click="deleteTeam(team.id)"
                                class="text-red-600 hover:text-red-800 font-semibold text-sm"
                            >
                                Delete
                            </button>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold">Members:</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600">
                                <li v-for="member in team.members" :key="member.id">
                                    {{ member.name }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Team -->
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">Create New Team</h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Team Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full mt-1 border px-3 py-2 rounded focus:outline-none focus:ring"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea
                            v-model="form.description"
                            class="w-full mt-1 border px-3 py-2 rounded focus:outline-none focus:ring"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Select Members</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <label
                                v-for="user in users"
                                :key="user.id"
                                class="flex items-center space-x-2"
                            >
                                <input
                                    type="checkbox"
                                    :value="user.id"
                                    :checked="isChecked(user.id)"
                                    @change="toggleMember(user.id)"
                                />
                                <span>{{ user.name }}</span>
                            </label>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="bg-[#ffcc00] text-[#1e144f] font-semibold px-4 py-2 rounded hover:bg-yellow-400"
                    >
                        Add Team
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
