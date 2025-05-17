<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ClipboardList, Users, CalendarDays } from 'lucide-vue-next';

const props = defineProps({
  projects: Array,
  tasks: Array,
  teams: Array,
  user: Object,
});

const safeProjects = computed(() => props.projects ?? []);
const safeTasks = computed(() => props.tasks ?? []);
const safeTeams = computed(() => props.teams ?? []);

const today = new Date();
const year = today.getFullYear();
const month = today.getMonth(); // 0-indexed

const firstDay = new Date(year, month, 1);
const lastDay = new Date(year, month + 1, 0);
const daysInMonth = lastDay.getDate();
const startingDay = firstDay.getDay(); // Sunday = 0

const days = computed(() => {
  const totalBoxes = 42; // 6 weeks
  const boxes = [];

  for (let i = 0; i < totalBoxes; i++) {
    const dateNum = i - startingDay + 1;
    if (dateNum > 0 && dateNum <= daysInMonth) {
      const fullDate = new Date(year, month, dateNum).toISOString().split('T')[0];
      const tasksForDay = safeTasks.value.filter(t => t.date?.startsWith(fullDate));
      boxes.push({ date: dateNum, tasks: tasksForDay });
    } else {
      boxes.push(null);
    }
  }

  return boxes;
});

function goTo(path) {
  router.visit(path);
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="p-6 min-h-screen bg-[#f9fafb] text-gray-900 space-y-8">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Hello, {{ user.name }} 👋</h1>
      </div>

      <!-- Grid Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tasks -->
        <div class="lg:col-span-2 space-y-6">
          <section>
            <h2 class="text-xl font-bold mb-4">Recent Tasks</h2>
            <div class="grid md:grid-cols-2 gap-4">
              <div
                v-for="task in safeTasks.slice(0, 4)"
                :key="task.id"
                @click="goTo(`/tasks`)"
                class="bg-white p-5 rounded-xl shadow hover:shadow-md cursor-pointer transition"
              >
                <h3 class="font-semibold text-lg mb-1">{{ task.title }}</h3>
                <p class="text-sm text-gray-500">{{ task.description }}</p>
                <div class="mt-3 text-xs text-gray-600">
                  📁 {{ task.project?.name ?? 'No Project' }}
                </div>
              </div>
            </div>
          </section>
        </div>

        <!-- Projects and Teams -->
        <div class="space-y-6">
          <!-- Projects -->
          <section class="bg-white p-5 rounded-xl shadow">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
              <ClipboardList class="w-5 h-5 text-green-500" /> Projects
            </h2>
            <div class="space-y-3">
              <div
                v-for="project in safeProjects.slice(0, 3)"
                :key="project.id"
                @click="goTo(`/projects`)"
                class="p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition"
              >
                <div class="font-medium">{{ project.name }}</div>
                <div class="text-sm text-gray-500">{{ project.tasks?.length || 0 }} tasks</div>
              </div>
            </div>
          </section>

          <!-- Teams -->
          <section class="bg-white p-5 rounded-xl shadow">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
              <Users class="w-5 h-5 text-purple-500" /> Teams
            </h2>
            <div class="space-y-3">
              <div
                v-for="team in safeTeams.slice(0, 4)"
                :key="team.id"
                @click="goTo(`/teams`)"
                class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 cursor-pointer"
              >
                <img
                  :src="`https://ui-avatars.com/api/?name=${team.name}&background=6366f1&color=fff`"
                  class="w-8 h-8 rounded-full"
                  alt="Team"
                />
                <div>
                  <div class="font-medium">{{ team.name }}</div>
                  <div class="text-sm text-gray-500">{{ team.users?.length ?? 0 }} members</div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>

      <!-- Custom Calendar -->
      <section class="bg-white p-5 rounded-xl shadow mt-10">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
          <CalendarDays class="w-5 h-5 text-blue-500" />
          Calendar – {{ today.toLocaleString('default', { month: 'long' }) }} {{ year }}
        </h2>
        <div class="grid grid-cols-7 text-sm text-gray-600 font-semibold mb-2">
          <div v-for="d in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="d" class="text-center">{{ d }}</div>
        </div>
        <div class="grid grid-cols-7 gap-2 text-sm">
          <div
            v-for="(day, i) in days"
            :key="i"
            class="min-h-[60px] border rounded p-1 relative"
            :class="day ? 'bg-gray-50' : ''"
          >
            <div v-if="day">
              <div class="font-semibold text-xs text-gray-700">{{ day.date }}</div>
              <div v-for="task in day.tasks" :key="task.id" class="text-xs mt-1 bg-blue-100 text-blue-800 rounded px-1 truncate">
                {{ task.title }}
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </AuthenticatedLayout>
</template>
