<template>
  <v-app>
    <v-container>
        <v-col cols="12">
          <h1 class="grey--text darken-4">Tasks Tracker</h1>
          <v-divider></v-divider>

           <v-card>
            <v-card-title >List of Tasks</v-card-title>
            <br />
            <v-divider />
            <v-card-text>
              
            <v-data-table :headers="table_headers_tasks" :items="tasks" :loading="target_user_tasks_loading">
                <template v-slot:item.status="{ item, index }">
                  {{ getStatusEQ(item.status) }}
                </template>
                <template v-slot:item.actions="{ item, index }">
                  <v-btn color="green white--text" x-small @click="editTask(item, index)">Edit</v-btn>
                </template>
            </v-data-table>

            </v-card-text>
          </v-card>
          
        </v-col>

        <v-dialog v-model="task_edit_dialog" persistent>
          <v-card>
            <v-card-title>Edit Task</v-card-title>
            <v-card-text>
              <v-autocomplete
                v-model="target_edit_status"
                :items="status_quo_items"
                outlined
                label="Task Status"
              ></v-autocomplete>
            </v-card-text>
            <v-card-actions class="d-flex justify-between">
              <v-spacer />
              <div>
                <v-btn :loading="task_form_loading" @click="editTaskSubmit" color="success">Submit</v-btn>
                <v-btn @click="task_edit_dialog = false" color="grey" dark>Cancel</v-btn>
              </div>
            </v-card-actions>
          </v-card>
        </v-dialog>
    </v-container>
  </v-app>
</template>

<script>
import Vue from 'vue'
import { Form, HasError, AlertError } from 'vform'

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

export default {
  
  data: () => ({
    tasks: [],
     table_headers_tasks: [
      { text: 'Task Name', value: 'task_name' },
      { text: 'Status', value: 'status' },
      { text: 'Actions', value: 'actions' },
    ],
    target_tasks_index: 0,
    target_user_tasks_loading: false,
    status_quo : {
      1: 'Pending',
      2: 'On going',
      3: 'Complete'
    },

    status_quo_items : [
      { text: 'Pending', value: 1},
      { text: 'On going', value: 2},
      { text: 'Complete', value: 3}
    ],

    status_timelog : {
      1: 'In',
      2: 'Out'
    },

    task_edit_dialog: false,
    target_id: 0,
    target_edit_status: 1,
    task_form_loading: false
  }),

  methods: {
    getStatusEQ(status) {
      return this.status_quo[status];
    },

    getStatusTL(status) {
      return this.status_timelog[status];
    },

    async getTasks() {
      await axios.get('/api/student/tasks').catch(err => console.log(err)).then(data => {
        this.tasks = data.data;
      })
    },

    editTask(item, index) {
      this.target_edit_status = item.status;
      this.target_id = item.id;
      this.task_edit_dialog = true;
    },

    async editTaskSubmit() {
      this.task_form_loading = true;
      await axios.post('/api/student/editTask', {
        task_status: this.target_edit_status,
        task_id: this.target_id
      }).catch(err => console.log(err)).then(data => {
       if(data.data.success) {
          Swal.fire('Success', data.data.message, 'success');
       }
      }).finally( () => {
        this.task_form_loading = false;
        this.getTasks();
      })
    }
  },

  mounted() {
    this.getTasks()
  }
}
</script>