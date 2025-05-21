<script setup>
import { ref, reactive } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = usePage().props
const timesheets = ref(props.timesheets || [])
const message = ref('')
const editing = ref(false)
const editingTimesheetId = ref(null)

const timesheet = reactive({
  date: '',
  start_time: '',
  end_time: '',
  notes: '',
})

const submitTimesheet = async () => {
  try {
    if (editing.value) {
      router.put(`/timesheets/${editingTimesheetId.value}`, timesheet, {
        onSuccess: () => {
          message.value = 'Timesheet updated successfully!'
          resetForm()
        }
      })
    } else {
      router.post('/timesheets', timesheet, {
        onSuccess: () => {
          message.value = 'Timesheet submitted successfully!'
          resetForm()
        }
      })
    }
  } catch (err) {
    console.error(err)
    message.value = 'Failed to submit.'
  }
}

const editTimesheet = (entry) => {
  timesheet.date = entry.date
  timesheet.start_time = entry.start_time
  timesheet.end_time = entry.end_time
  timesheet.notes = entry.notes
  editing.value = true
  editingTimesheetId.value = entry.id
}

const resetForm = () => {
  timesheet.date = ''
  timesheet.start_time = ''
  timesheet.end_time = ''
  timesheet.notes = ''
  editing.value = false
  editingTimesheetId.value = null
}
</script>

<template>
  <AuthenticatedLayout  :user="user" :tasks="tasks" :teams="teams" :projects="projects">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Form Section -->
        <div class="bg-white shadow sm:rounded-lg mb-6">
          <div class="px-4 py-5 sm:px-6">
            <h2 class="text-xl font-semibold text-gray-900">{{ editing ? 'Edit Timesheet' : 'Submit Timesheet' }}</h2>
          </div>
          <form @submit.prevent="submitTimesheet" class="space-y-6 px-4 py-5 sm:px-6">
            <div class="grid grid-cols-1 gap-6">
              <div class="col-span-1">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" v-model="timesheet.date" id="date" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>
              <div class="col-span-1">
                <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="time" v-model="timesheet.start_time" id="start_time" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>
              <div class="col-span-1">
                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="time" v-model="timesheet.end_time" id="end_time" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>
              <div class="col-span-1">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea v-model="timesheet.notes" id="notes" placeholder="Optional notes..." class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
              </div>
            </div>
            <div>
              <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                {{ editing ? 'Update' : 'Submit' }}
              </button>
            </div>
          </form>

          <p v-if="message" class="mt-4 text-sm text-green-600 text-center">{{ message }}</p>
        </div>

        <!-- Timesheet Table Section -->
        <div class="bg-white shadow sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <h2 class="text-xl font-semibold text-gray-900">Your Timesheets</h2>
          </div>
          <div class="px-4 py-5 sm:px-6">
            <div class="overflow-x-auto">
              <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Start</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">End</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Notes</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="entry in timesheets" :key="entry.id" class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ entry.date }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ entry.start_time }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ entry.end_time }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ entry.notes }}</td>
                    <td class="px-6 py-4 text-sm text-indigo-600">
                      <button @click="editTimesheet(entry)" class="hover:text-indigo-800">Edit</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
