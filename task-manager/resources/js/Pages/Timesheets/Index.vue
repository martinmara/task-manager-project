<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'
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

const fetchTimesheets = async () => {
  try {
    const res = await axios.get('/timesheets')
    timesheets.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const submitTimesheet = async () => {
  try {
    if (editing.value) {
      await axios.put(`/timesheets/${editingTimesheetId.value}`, timesheet)
      message.value = 'Timesheet updated successfully!'
    } else {
      await axios.post('/timesheets', timesheet)
      message.value = 'Timesheet submitted successfully!'
    }
    resetForm()
    fetchTimesheets()
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

onMounted(fetchTimesheets)
</script>

<template>
  <AuthenticatedLayout>
    <div class="dashboard">
      <div class="card section">
        <h2 class="section-title">{{ editing ? 'Edit Timesheet' : 'Submit Timesheet' }}</h2>

        <form @submit.prevent="submitTimesheet" class="form">
          <div class="form-row">
            <label>Date</label>
            <input type="date" v-model="timesheet.date" required />
          </div>
          <div class="form-row">
            <label>Start Time</label>
            <input type="time" v-model="timesheet.start_time" required />
          </div>
          <div class="form-row">
            <label>End Time</label>
            <input type="time" v-model="timesheet.end_time" required />
          </div>
          <div class="form-row">
            <label>Notes</label>
            <textarea v-model="timesheet.notes" placeholder="Optional notes..." />
          </div>
          <button class="btn-primary">{{ editing ? 'Update' : 'Submit' }}</button>
        </form>

        <p v-if="message" class="message">{{ message }}</p>
      </div>

      <div class="card section">
        <h2 class="section-title">Your Timesheets</h2>
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>Start</th>
                <th>End</th>
                <th>Notes</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="entry in timesheets" :key="entry.id">
                <td>{{ entry.date }}</td>
                <td>{{ entry.start_time }}</td>
                <td>{{ entry.end_time }}</td>
                <td>{{ entry.notes }}</td>
                <td>
                  <button class="btn-edit" @click="editTimesheet(entry)">Edit</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.dashboard {
  padding: 2rem;
  background: #f5f6fa;
  font-family: 'Inter', sans-serif;
}

.section {
  margin-bottom: 2rem;
}

.section-title {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 1.2rem;
  color: #1e1e2f;
}

.card {
  background: white;
  border-radius: 16px;
  padding: 1.5rem 2rem;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

.form {
  display: grid;
  gap: 1rem;
}

.form-row {
  display: flex;
  flex-direction: column;
}

label {
  font-weight: 500;
  margin-bottom: 0.4rem;
}

input,
textarea {
  padding: 0.8rem;
  border: 1px solid #ddd;
  border-radius: 10px;
  font-size: 14px;
}

textarea {
  resize: none;
  min-height: 80px;
}

.btn-primary {
  margin-top: 1rem;
  background-color: #ffbd2e;
  color: #1e1e2f;
  padding: 0.8rem 1.6rem;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s ease;
}

.btn-primary:hover {
  background-color: #f9a602;
}

.message {
  margin-top: 1rem;
  background-color: #eafbea;
  border: 1px solid #c1f0c1;
  padding: 0.8rem;
  color: #317853;
  border-radius: 8px;
}

.table-wrapper {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
  text-align: center;
}

th {
  background-color: #f3f3f9;
  color: #333;
  padding: 0.8rem;
  border-bottom: 2px solid #eee;
}

td {
  padding: 0.8rem;
  border-bottom: 1px solid #eee;
}

tr:hover {
  background-color: #fef6e0;
}

.btn-edit {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  cursor: pointer;
  font-size: 13px;
}

.btn-edit:hover {
  background-color: #0056b3;
}
</style>
