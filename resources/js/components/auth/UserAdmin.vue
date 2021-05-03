<template>
  <v-app>
    <v-container>
      <v-col cols="12">
        <h1 class="grey--text darken-4">User Admin List</h1>
        <v-btn color="green accent-4" dark small @click="dialog_add_user = true">Add Admin User</v-btn>
        <v-divider class="mt-4"></v-divider>
        <v-data-table
          :headers="headers"
          :items="admins"
          :items-per-page="10"
          class="elevation-1"
        >
          <template v-slot:item.actions="{ item, index }">
            <v-btn color="green white--text" x-small @click="editUser(item, index)">Edit</v-btn>
            <v-btn color="red white--text" x-small @click="deleteAdmin(item.id)">Delete</v-btn>
          </template>

        </v-data-table>
      </v-col>

      <v-dialog
        v-model="dialog_add_user"
        width="800"
        persistent
      >
        <v-card>
          <v-card-title>Add Admin</v-card-title>
          <v-divider />
          <v-card-text>
            <form  @submit.prevent="addUser" @keydown="admin.onKeydown($event)" class="pt-5">
              <v-text-field
                  v-model="admin.fullname"
                  color="success"
                  label="Fullname"
                  :error="admin.errors.has('fullname')"
                  :error-messages="formHasError(admin, 'fullname')"
                  outlined
              >
              </v-text-field>
              <v-text-field
                  v-model="admin.username"
                  color="success"
                  label="Username"
                  :error="admin.errors.has('username')"
                  :error-messages="formHasError(admin, 'username')"
                  outlined
              >
              </v-text-field>
              <v-text-field
                  v-model="admin.email"
                  color="success"
                  type="email"
                  label="E-mail Address"
                  :error="admin.errors.has('email')"
                  :error-messages="formHasError(admin, 'email')"
                  outlined
              >
              </v-text-field>

              <v-text-field
                  v-model="admin.password"
                  color="success"
                  type="password"
                  label="Password"
                  :error="admin.errors.has('password')"
                  :error-messages="formHasError(admin, 'password')"
                  outlined
              >
              </v-text-field>
            </form>
          </v-card-text>
          <v-divider />
          <v-card-actions class="d-flex justify-between">
            <v-spacer />
            <div>
              <v-btn :disabled="admin.busy" :loading="admin.busy" @click="addUser" color="success">Submit</v-btn>
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
                  v-model="target_user_edit.fullname"
                  color="success"
                  label="Fullname"
                  :error="target_user_edit.errors.has('fullname')"
                  :error-messages="formHasError(target_user_edit, 'fullname')"
                  outlined
              >
              </v-text-field>
              
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
    dialog_add_user: false,
    dialog_edit_user: false,
    admin: new Form({
      fullname: "",
      username: "",
      email: "",
      user_img: "user.png",
      password: ""
    }),
    admins: [],
    headers: [
      { text: 'Full Name', value: 'fullname' },
      { text: 'User Name', value: 'username' },
      { text: 'E-mail', value: 'email' },
      { text: 'Actions', value: 'actions' },
    ],

    target_user_edit: new Form({
      id: 0,
      fullname: "",
      email: "",
      username: "",
    }),

    target_user_edit_index : 0
  }),

  mounted(){
    this.getAdmins();
  },

  methods: {
    async addUser() {
      await this.admin.post('/api/admin/new-admin').catch(err => console.log(err)).then(data => {
        if(data.data.success) {
          Swal.fire('Success', data.data.message, 'success');
          this.dialog_add_user = false;
          this.getAdmins();
        }
      })
    },

    editUser(item, i) {
      this.target_user_edit.id = item.id;
      this.target_user_edit.fullname = item.fullname;
      this.target_user_edit.username = item.username;
      this.target_user_edit.email = item.email;
      this.dialog_edit_user = true;
    },

    async finalizeEdit() {
      await this.target_user_edit.post('/api/admin/edit-admin').catch(err => console.log(err)).then(data => {
        if(data.data.success) {
          Swal.fire('Success', data.data.message, 'success');
          this.dialog_edit_user = false;
          this.getAdmins();
        }
      })
    },

    deleteAdmin(id) {
      Swal.fire({
        'title': 'Wait',
        'text': 'Do you want to continue to delete this user?',
        'icon' : 'warning',
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Continue"
      }).then(async data => {
        if(data.value) {
          await axios.post(`/api/admin/delete-admin/${ id }`).catch(err => console.log(err)).then(data => {
            if(data.data.success) {
              Swal.fire('Success', data.data.message, 'success');
              this.getAdmins();
            }
          })
        }
      })
    },

    async getAdmins() {
      await axios.get('/api/admin/getAdmins').catch(err => console.log(err)).then(data => {
        this.admins = data.data;
      })
    },

    formHasError(form, key) {
        return form.errors.has(key) ? form.errors.get(key) : "";
    }
  },
}
</script>