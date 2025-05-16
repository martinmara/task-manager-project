<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Pencil, Eye, X } from 'lucide-vue-next';

const props = defineProps({
    teams: Array
});

const editingTeam = ref(null);
const viewingTeam = ref(null);

function startEdit(team) {
    editingTeam.value = { ...team };
    viewingTeam.value = null;
}

function viewTeam(team) {
    viewingTeam.value = team;
    editingTeam.value = null;
}

function closeView() {
    viewingTeam.value = null;
}

function saveEdit() {
    router.put(`/teams/${editingTeam.value.id}`, editingTeam.value, {
        onSuccess: () => {
            editingTeam.value = null;
        }
    });
}
</script>

<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Header -->
        <h1 class="text-3xl font-bold text-[#1e144f] mb-8">Teams</h1>

        <!-- Teams grid -->
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div 
                v-for="team in teams" 
                :key="team.id" 
                class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between hover:shadow-xl transition-shadow duration-300 cursor-pointer"
                @click="viewTeam(team)"
            >
                <div class="flex items-center gap-4">
                    <!-- Team image or placeholder -->
                    <img 
                        :src="team.image || 'https://via.placeholder.com/64?text=Team'" 
                        alt="Team image" 
                        class="w-16 h-16 rounded-full object-cover"
                    />
                    <div>
                        <h2 class="text-xl font-semibold text-[#1e144f] mb-1">{{ team.name }}</h2>
                        <p class="text-gray-500 text-sm leading-relaxed truncate max-w-xs">{{ team.description }}</p>
                    </div>
                </div>
                <button 
                    @click.stop="startEdit(team)" 
                    class="self-end mt-4 text-[#fbbf24] hover:text-yellow-500 transition-colors duration-300"
                    aria-label="Edit Team"
                >
                    <Pencil class="w-5 h-5" />
                </button>
            </div>
        </div>

        <!-- View Team Modal -->
        <div 
            v-if="viewingTeam" 
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        >
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl relative">
                <button @click="closeView" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900">
                    <X class="w-6 h-6" />
                </button>
                <div class="flex flex-col items-center gap-4">
                    <img 
                        :src="viewingTeam.image || 'https://via.placeholder.com/128?text=Team'" 
                        alt="Team image" 
                        class="w-32 h-32 rounded-full object-cover"
                    />
                    <h3 class="text-3xl font-bold text-[#1e144f]">{{ viewingTeam.name }}</h3>
                    <p class="text-gray-600 text-center">{{ viewingTeam.description }}</p>
                </div>
            </div>
        </div>

        <!-- Edit Team Modal -->
        <div 
            v-if="editingTeam" 
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        >
            <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
                <h3 class="text-2xl font-bold mb-5 text-[#1e144f]">Edit Team</h3>
                <input 
                    v-model="editingTeam.name" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                    placeholder="Team Name" 
                />
                <textarea 
                    v-model="editingTeam.description" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                    placeholder="Description"
                    rows="4"
                ></textarea>
                <input
                    v-model="editingTeam.image"
                    placeholder="Team Image URL"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-6 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                />
                <div class="flex justify-end gap-4">
                    <button @click="editingTeam = null" class="text-gray-600 hover:text-gray-800 transition-colors">Cancel</button>
                    <button @click="saveEdit" class="bg-[#1e144f] text-white px-6 py-2 rounded-lg hover:bg-[#16103a] transition-colors">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>
