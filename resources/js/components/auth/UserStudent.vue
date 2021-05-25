<template>
  <v-app>
    <v-container>
      <v-col cols="12">
        <h1 class="grey--text darken-4">User Student List</h1>
        <v-btn color="green accent-4" dark small @click="dialog_add_user = true">Add Student User</v-btn>
        <v-btn color="red accent-4" dark small @click="viewArch">View Archived Students</v-btn>
        <v-divider class="mt-4"></v-divider>
        <v-data-table
          :headers="headers"
          :items="students"
          :items-per-page="10"
          class="elevation-1"
        >
          <template v-slot:item.actions="{ item, index }">
            <v-btn color="primary white--text" x-small @click="viewUser(item, index)">View</v-btn>
            <v-btn color="green white--text" x-small @click="editUser(item, index)">Edit</v-btn>
            <v-btn color="red white--text" x-small @click="deleteUser(item.id)">Archive</v-btn>
          </template>

        </v-data-table>
      </v-col>

      <v-dialog
        v-model="dialog_add_user"
        width="800"
        persistent
      >
        <v-card>
          <v-card-title>Add Student</v-card-title>
          <v-divider />
          <v-card-text>
            <form  @submit.prevent="addUser" @keydown="student.onKeydown($event)" class="pt-5">
              <v-text-field
                  v-model="student.username"
                  color="success"
                  label="Username"
                  :error="student.errors.has('username')"
                  :error-messages="formHasError(student, 'username')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="student.email"
                  color="success"
                  type="email"
                  label="E-mail Address"
                  :error="student.errors.has('email')"
                  :error-messages="formHasError(student, 'email')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="student.password"
                  color="success"
                  type="password"
                  label="Password"
                  :error="student.errors.has('password')"
                  :error-messages="formHasError(student, 'password')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="student.fname"
                  color="success"
                  label="Firstname"
                  :error="student.errors.has('fname')"
                  :error-messages="formHasError(student, 'fname')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="student.mname"
                  color="success"
                  label="Middlename"
                  :error="student.errors.has('mname')"
                  :error-messages="formHasError(student, 'mname')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="student.lname"
                  color="success"
                  label="Lastname"
                  :error="student.errors.has('lname')"
                  :error-messages="formHasError(student, 'lname')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="student.school"
                  color="success"
                  label="School"
                  :error="student.errors.has('school')"
                  :error-messages="formHasError(student, 'school')"
                  outlined
              >
              </v-text-field>

              <v-radio-group 
                  v-model="student.gender"
                  color="success"
                  label="Gender"
                  :error="student.errors.has('gender')"
                  :error-messages="formHasError(student, 'gender')"
                >
                <v-radio
                  label="Male"
                  value="Male"
                ></v-radio>
                <v-radio
                  label="Female"
                  value="Female"
                ></v-radio>
              </v-radio-group>

              <v-text-field
                  v-model="student.address"
                  color="success"
                  label="Address"
                  :error="student.errors.has('address')"
                  :error-messages="formHasError(student, 'address')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="student.phone"
                  color="success"
                  label="Contact Number"
                  :error="student.errors.has('phone')"
                  :error-messages="formHasError(student, 'phone')"
                  outlined
              >
              </v-text-field>

            </form>
          </v-card-text>
          <v-divider />
          <v-card-actions class="d-flex justify-between">
            <v-spacer />
            <div>
              <v-btn :disabled="student.busy" :loading="student.busy" @click="addUser" color="success">Submit</v-btn>
              <v-btn @click="dialog_add_user = false" color="grey" dark>Cancel</v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog
        v-model="dialog_edit_user"
        width="800"
        persistent
      >
        <v-card>
          <v-card-title>Edit {{ target_user_edit.username }}</v-card-title>
          <v-divider />
          <v-card-text>
            <form  @submit.prevent="finalizeEdit" @keydown="target_user_edit.onKeydown($event)" class="pt-5">
              <v-text-field
                  v-model="target_user_edit.email"
                  color="success"
                  type="email"
                  label="E-mail Address"
                  :error="target_user_edit.errors.has('email')"
                  :error-messages="formHasError(target_user_edit, 'email')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="target_user_edit.fname"
                  color="success"
                  label="Firstname"
                  :error="target_user_edit.errors.has('fname')"
                  :error-messages="formHasError(target_user_edit, 'fname')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="target_user_edit.mname"
                  color="success"
                  label="Middlename"
                  :error="target_user_edit.errors.has('mname')"
                  :error-messages="formHasError(target_user_edit, 'mname')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="target_user_edit.lname"
                  color="success"
                  label="Lastname"
                  :error="target_user_edit.errors.has('lname')"
                  :error-messages="formHasError(target_user_edit, 'lname')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="target_user_edit.school"
                  color="success"
                  label="School"
                  :error="target_user_edit.errors.has('school')"
                  :error-messages="formHasError(target_user_edit, 'school')"
                  outlined
              >
              </v-text-field>

              <v-radio-group 
                  v-model="target_user_edit.gender"
                  color="success"
                  label="Gender"
                  :error="target_user_edit.errors.has('gender')"
                  :error-messages="formHasError(target_user_edit, 'gender')"
                >
                <v-radio
                  label="Male"
                  value="Male"
                ></v-radio>
                <v-radio
                  label="Female"
                  value="Female"
                ></v-radio>
              </v-radio-group>

              <v-text-field
                  v-model="target_user_edit.address"
                  color="success"
                  label="Address"
                  :error="target_user_edit.errors.has('address')"
                  :error-messages="formHasError(target_user_edit, 'address')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="target_user_edit.phone"
                  color="success"
                  label="Contact Number"
                  :error="target_user_edit.errors.has('phone')"
                  :error-messages="formHasError(target_user_edit, 'phone')"
                  outlined
              >
              </v-text-field>

            </form>
          </v-card-text>
          <v-divider />
          <v-card-actions class="d-flex justify-between">
            <v-spacer />
            <div>
              <v-btn :disabled="target_user_edit.busy" :loading="target_user_edit.busy" @click="finalizeEdit" color="success">Submit</v-btn>
              <v-btn @click="dialog_edit_user = false" color="grey" dark>Cancel</v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog persistent v-model="dialog_view_user" width="1000">
        <v-card>
          <v-card-title >View Student</v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" md="6">
                  <v-card>
                    <v-card-title >List of Tasks</v-card-title>
                    <v-btn color="primary white--text" x-small @click="dialog_add_tasks = true">Add Task</v-btn>
                    <br />
                    <v-divider />
                    <v-card-text>
                      
                    <v-data-table :headers="table_headers_tasks" :items="target_user_tasks" :loading="target_user_tasks_loading">
                        <template v-slot:item.status="{ item, index }">
                          {{ getStatusEQ(item.status) }}
                        </template>
                        <template v-slot:item.actions="{ item, index }">
                          <v-btn color="red white--text" x-small @click="deleteTasks(item.id)">Delete</v-btn>
                        </template>
                    </v-data-table>

                    </v-card-text>
                  </v-card>
                </v-col>
                
                <v-col cols="12" md="6">
                  <v-card>
                    <v-card-title >Total accumulated time: {{ total_time }} hours</v-card-title>
                    <v-divider />
                    <v-card-text>
                    <v-data-table :headers="table_headers_timelogs" :items="target_user_time_logs" :loading="target_user_time_logs_loading">
                        <template v-slot:item.status_stamp="{ item, index }">
                          {{ getStatusTL(item.status_stamp) }}
                        </template>
                    </v-data-table>

                    </v-card-text>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
           <v-card-actions class="d-flex justify-between">
            <v-spacer />
            <div>
              <v-btn @click="dialog_view_user = false" color="grey" dark>Close</v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog persistent v-model="dialog_arch_students" width="1000">
        <v-card>
          <v-card-title >View Archived Students</v-card-title>
          <v-card-text>
            <v-data-table :headers="headers_f" :items="students_arch" :loading="students_arch_load" 
            :items-per-page="10"
            class="elevation-1">
            </v-data-table>
          </v-card-text>
           <v-card-actions class="d-flex justify-between">
            <v-spacer />
            <div>
              <v-btn @click="dialog_arch_students = false" color="grey" dark>Close</v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog persistent v-model="dialog_add_tasks" width="1000">
        <v-card>
          <v-card-title >Add Tasks</v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <form  @submit.prevent="addTask" @keydown="task.onKeydown($event)" class="pt-5">
                    <v-text-field
                        v-model="task.task_name"
                        color="success"
                        label="Task Name"
                        :error="task.errors.has('task_name')"
                        :error-messages="formHasError(task, 'task_name')"
                        outlined
                    >
                    </v-text-field>
                  </form>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions class="d-flex justify-between">
            <v-spacer />
            <div>
              <v-btn :disabled="task.busy" :loading="task.busy" @click="addTask" color="success">Submit</v-btn>
              <v-btn @click="dialog_add_tasks = false" color="grey" dark>Cancel</v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-dialog>

    </v-container>
  </v-app>
</template>
<script>

import moment from 'moment';
import Vue from 'vue'
import { Form, HasError, AlertError } from 'vform'

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

export default {
  data: () => ({
  
    dialog_add_user: false,
    dialog_edit_user: false,
    dialog_view_user: false,

    dialog_add_tasks: false,

    student: new Form({
      username: "",
      email: "",
      user_img: "user.png",
      password: "",
      fname: "",
      mname: "",
      lname: "",
      school: "",
      gender: "Male",
      address: "",
      phone: ""
    }),

    task: new Form({
      task_name: "",
      assigned_id: "",
    }),

    students: [],
    headers: [
      { text: 'Full Name', value: 'fullname' },
      { text: 'User Name', value: 'username' },
      { text: 'E-mail', value: 'email' },
      { text: 'Actions', value: 'actions' },
    ],

    headers_f: [
      { text: 'Full Name', value: 'fullname' },
      { text: 'User Name', value: 'username' },
      { text: 'E-mail', value: 'email' },
      { text: 'Archived Date', value: 'updated_at' },
    ],

    target_user_edit: new Form({
      id: 0,
      fullname: "",
      email: "",
      username: "",
      fname: "",
      mname: "",
      lname: "",
      school: "",
      gender: "Male",
      address: "",
      phone: ""
    }),

    target_user_edit_index : 0,

    target_user_tasks: [],
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

    status_timelog : {
      1: 'In',
      2: 'Out'
    },

    target_user_time_logs: [],
    table_headers_timelogs: [
      { text: 'Status', value: 'status_stamp' },
      { text: 'Time', value: 'created_at' }
    ],
    target_user_time_logs_loading: false,

    total_time: 0,
    
    students_arch: [],
    dialog_arch_students: false,
    students_arch_load: false

  }),

  mounted() {
    this.getStudents();
  },

  methods: {

    getStatusEQ(status) {
      return this.status_quo[status];
    },

    getStatusTL(status) {
      return this.status_timelog[status];
    },


    async addUser() {
      await this.student.post('/api/students/new-student').catch(err => console.log(err)).then(data => {
        if(data.data.success) {
          Swal.fire('Success', data.data.message, 'success');
          this.dialog_add_user = false;
          this.getStudents();
        }
      })
    },

    viewUser(item, index) {
      this.dialog_view_user = true
      this.target_tasks_index = item.id;
      this.getTasks(item.id);
      this.getTimeLogs(item.id);
    },

    async getTasks(id) {
      this.target_user_time_logs_loading = true;
      await axios.get(`/api/students/tasks/${ id }`)
      .catch(err => console.log(err))
      .then(response => this.target_user_tasks = response.data)
      .finally( () => this.target_user_time_logs_loading = false);
    },

    viewArch()
    {
      this.dialog_arch_students = true;
      this.getStudentsArch();
    },

    async getStudentsArch()
    {
      this.students_arch_load = true;
      await axios.get('/api/students/getArchivedStudents').catch(err => console.log(err)).then(data => {
        this.students_arch = data.data;
      }).finally(f => {
        this.students_arch_load = false;
      })
    },

    async getTimeLogs(id) {
      this.target_user_tasks_loading = true;
      await axios.get(`/api/students/time_logs/${ id }`)
      .catch(err => console.log(err))
      .then(response => {
        
        this.target_user_time_logs = response.data

        let f = this.target_user_time_logs[this.target_user_time_logs.length - 1];
        let last_out = f.status_stamp == 2 ? f :  this.target_user_time_logs[this.target_user_time_logs.length - 2];
        this.total_time = ( ((moment(last_out.created_at).diff(moment(this.target_user_time_logs[0].created_at), 'seconds')) / 60) / 60).toFixed(2);

      })
      .finally( () => this.target_user_tasks_loading = false);
    },

    editUser(item, i) {
      this.target_user_edit.id = item.id;
      this.target_user_edit.fullname = item.fullname;
      this.target_user_edit.username = item.username;
      this.target_user_edit.email = item.email;
      
      this.target_user_edit.fname = item.info.fname;
      this.target_user_edit.mname = item.info.mname;
      this.target_user_edit.lname = item.info.lname;
      this.target_user_edit.school = item.info.school;
      this.target_user_edit.gender = item.info.gender;
      this.target_user_edit.address = item.info.address;
      this.target_user_edit.phone = item.info.phone;
      this.dialog_edit_user = true;
    },

    async finalizeEdit() {
      await this.target_user_edit.post('/api/students/edit-student').catch(err => console.log(err)).then(data => {
        if(data.data.success) {
          Swal.fire('Success', data.data.message, 'success');
          this.dialog_edit_user = false;
          this.getStudents();
        }
      })
    },

    deleteUser(id) {
      Swal.fire({
        'title': 'Wait',
        'text': 'Do you want to continue to delete this student?',
        'icon' : 'warning',
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Continue"
      }).then(async data => {
        if(data.value) {
          await axios.post(`/api/students/delete-student/${ id }`).catch(err => console.log(err)).then(data => {
            if(data.data.success) {
              Swal.fire('Success', data.data.message, 'success');
              this.getStudents();
            }
          })
        }
      })
    },

    async getStudents() {
      await axios.get('/api/students/getStudents').catch(err => console.log(err)).then(data => {
        this.students = data.data;
      })
    },

    deleteTasks(id) {
      Swal.fire({
        'title': 'Wait',
        'text': 'Do you want to continue to delete this task?',
        'icon' : 'warning',
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Continue"
      }).then(async data => {
        if(data.value) {
          await axios.post(`/api/students/delete-task/${ id }`).catch(err => console.log(err)).then(data => {
            if(data.data.success) {
              Swal.fire('Success', data.data.message, 'success');
              this.getTasks(this.target_tasks_index);
            }
          })
        }
      })
    },

    formHasError(form, key) {
        return form.errors.has(key) ? form.errors.get(key) : "";
    },

    async addTask() {
      this.task.assigned_id = this.target_tasks_index;
      await this.task.post('/api/students/addTask').catch(err => console.log(err)).then(data => {
        if(data.data.success) {
          Swal.fire('Success', data.data.message, 'success');
          this.dialog_add_tasks = false;
          this.getTasks(this.task.assigned_id);
        }
      })
    }

  },
}
</script>