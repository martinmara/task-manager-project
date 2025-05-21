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

// Admin check (by role name)
const isAdmin = computed(() =>
  props.user && (
    (props.user.role && props.user.role.name && props.user.role.name.toLowerCase() === 'admin') ||
    (props.user.roles && props.user.roles.some(r => r.name && r.name.toLowerCase() === 'admin'))
  )
);

// Only show tasks assigned to the user, or all if admin
const safeTasks = computed(() =>
  isAdmin.value
    ? (props.tasks ?? [])
    : (props.tasks ?? []).filter(t => t.user_id === props.user.id)
);

const safeProjects = computed(() => props.projects ?? []);
const safeTeams = computed(() => props.teams ?? []);

const today = new Date();
const year = today.getFullYear();
const month = today.getMonth(); // 0-indexed

const firstDay = new Date(year, month, 1);
const lastDay = new Date(year, month + 1, 0);
const daysInMonth = lastDay.getDate();
const startingDay = firstDay.getDay(); // Sunday = 0

// Calendar: group tasks by due_date
const days = computed(() => {
  const totalBoxes = 42; // 6 weeks
  const boxes = [];

  for (let i = 0; i < totalBoxes; i++) {
    const dateNum = i - startingDay + 1;
    if (dateNum > 0 && dateNum <= daysInMonth) {
      const fullDate = new Date(year, month, dateNum).toISOString().split('T')[0];
      const tasksForDay = safeTasks.value.filter(t => {
        if (!t.due_date) return false;
        return t.due_date.slice(0, 10) === fullDate;
      });
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
  <AuthenticatedLayout :user="user" :tasks="tasks" :teams="teams" :projects="projects">
    
      <!-- Foreground Content -->
      <div class="relative z-1 p-6 space-y-8 text-gray-100">
        <!-- Header -->
        <div class="flex justify-between items-center">
          <h1 class="text-3xl font-bold drop-shadow-lg">Hello, {{ user.name }} 👋</h1>
        </div>

        <!-- Grid Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Recent Tasks -->
          <div class="lg:col-span-2 space-y-6">
            <section>
              <h2 class="text-2xl font-semibold mb-4">Recent Tasks</h2>
              <div class="grid md:grid-cols-2 gap-4">
                <div
                  v-for="task in safeTasks.slice(0, 4)"
                  :key="task.id"
                  @click="goTo(`/tasks`)"
                  class="bg-white/20 p-5 rounded-2xl shadow-lg hover:shadow-2xl transition-transform transform hover:-translate-y-1 cursor-pointer"
                >
                  <h3 class="font-semibold text-lg mb-1">{{ task.title }}</h3>
                  <p class="text-sm">{{ task.description }}</p>
                  <div class="mt-3 text-xs">
                    <i class="fas fa-folder-open text-yellow-400 mr-1"></i>
                    {{ task.project?.name ?? 'No Project' }}
                  </div>
                </div>
              </div>
            </section>
          </div>

          <!-- Projects & Teams -->
          <div class="space-y-6">
            <!-- Projects -->
            <section class="bg-white/20 p-5 rounded-2xl shadow-lg">
              <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
                <ClipboardList class="w-6 h-6 text-yellow-400" /> Projects
              </h2>
              <div class="space-y-3">
                <div
                  v-for="project in safeProjects.slice(0, 3)"
                  :key="project.id"
                  @click="goTo(`/projects`)"
                  class="p-3 rounded-lg hover:bg-white/30 transition cursor-pointer flex justify-between items-center"
                >
                  <span>{{ project.name }}</span>
                  <span class="text-sm">{{ project.tasks?.length || 0 }} tasks</span>
                </div>
              </div>
            </section>

            <!-- Teams -->
            <section class="bg-white/20 p-5 rounded-2xl shadow-lg">
              <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
                <Users class="w-6 h-6 text-yellow-400" /> Teams
              </h2>
              <div class="space-y-3">
                <div
                  v-for="team in safeTeams.slice(0, 4)"
                  :key="team.id"
                  @click="goTo(`/teams`)"
                  class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/30 transition cursor-pointer"
                >
                  <div>
                    <div class="font-medium">{{ team.name }}</div>
                    <div class="text-sm">{{ team.users?.length ?? 0 }} members</div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>

        <!-- Calendar -->
        <section class="bg-white/20 p-5 rounded-2xl shadow-lg mt-10">
          <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
            <CalendarDays class="w-6 h-6 text-yellow-400" />
            Calendar — {{ today.toLocaleString('default', { month: 'long' }) }} {{ year }}
          </h2>
          <div class="grid grid-cols-7 text-sm font-semibold mb-2 text-gray-200">
            <div v-for="d in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="d" class="text-center">{{ d }}</div>
          </div>
          <div class="grid grid-cols-7 gap-1 text-sm text-gray-100">
            <div
              v-for="(day, i) in days"
              :key="i"
              class="min-h-[60px] border border-white/20 rounded p-1 relative"
              :class="day ? 'bg-white/10' : ''"
            >
              <div v-if="day">
                <div class="font-semibold text-xs">{{ day.date }}</div>
                <div
                  v-for="task in day.tasks"
                  :key="task.id"
                  @click="goTo(`/tasks/${task.id}`)"
                  class="mt-1 truncate text-xs bg-yellow-400/30 text-yellow-100 rounded px-1 cursor-pointer hover:bg-yellow-400/50 transition"
                  title="View Task"
                >
                  {{ task.title }}
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
  </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');

body {
  font-family: 'Inter', sans-serif;
}

/* Fade-in for the whole page */
.relative.z-10 {
  animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>
