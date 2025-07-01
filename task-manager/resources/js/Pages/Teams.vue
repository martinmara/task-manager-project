<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({ teams: Array });

const editingTeam = ref(null);
const form = useForm({
    name: '',
    description: ''
});

const submit = () => {
    if (editingTeam.value) {
        form.put(route('teams.update', editingTeam.value.id), {
            onSuccess: () => {
                editingTeam.value = null;
                form.reset();
            }
        });
    } else {
        form.post(route('teams.store'), {
            onSuccess: () => form.reset()
        });
    }
};

const editTeam = (team) => {
    editingTeam.value = team;
    form.name = team.name;
    form.description = team.description || '';
};
</script>

<template>
    <Head title="Teams" />
    <div class="p-6 space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-[#1e144f]">Teams</h1>
            <button @click="editingTeam = null; form.reset()"
                    class="bg-[#f8be36] text-white px-4 py-2 rounded-full font-semibold hover:opacity-90">
                + Add Team
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div v-for="team in teams" :key="team.id"
                 class="p-4 rounded-xl bg-white shadow border-l-4 border-[#1e144f] flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-[#1e144f]">{{ team.name }}</h2>
                    <p class="text-sm text-gray-500">{{ team.description }}</p>
                </div>
                <button @click="editTeam(team)"
                        class="text-sm text-blue-600 mt-2 hover:underline self-start">
                    Edit
                </button>
            </div>
        </div>

        <div v-if="form.isDirty || editingTeam" class="mt-10">
            <h3 class="text-lg font-semibold text-[#1e144f] mb-2">
                {{ editingTeam ? 'Edit Team' : 'Create New Team' }}
            </h3>
            <form @submit.prevent="submit" class="space-y-4 max-w-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Team Name</label>
                    <input type="text" v-model="form.name" class="mt-1 block w-full px-4 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="form.description" class="mt-1 block w-full px-4 py-2 border rounded"></textarea>
                </div>
                <button type="submit"
                        class="bg-[#1e144f] text-white px-4 py-2 rounded-full hover:bg-opacity-90">
                    {{ editingTeam ? 'Update Team' : 'Create Team' }}
                </button>
            </form>
        </div>
    </div>
</template>
