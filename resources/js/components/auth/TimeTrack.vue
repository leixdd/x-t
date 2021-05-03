<template>
  <v-app>
    <v-container>
       <v-row>
         <v-col cols="12">
           <h1 class="grey--text darken-4">Time Tracker</h1>
           <v-divider></v-divider>

           <v-card>
              <v-card-title >Time Logs</v-card-title>
              <v-divider />
              <br />
              <v-btn color="primary" :loading="stamping_loading" @click="toggleStamp">Toggle Time Stamp [{{ getStatusTL(stamp_status) }}]</v-btn>
              <br />
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
  </v-app>
</template>

<script>
export default {
  data: () => ({
    status_timelog : {
      1: 'In',
      2: 'Out',
      0: 'Loading.. Please wait'
    },

    target_user_time_logs: [],
    table_headers_timelogs: [
      { text: 'Status', value: 'status_stamp' },
      { text: 'Time', value: 'created_at' }
    ],
    target_user_time_logs_loading: false,

    stamping_loading: false,

    stamp_status: 0
  }),

  methods: {
    getStatusTL(status) {
      return this.status_timelog[status];
    },

    async getTimeLogs() {
      await axios.get('/api/student/time_logs').catch(err => console.log(err)).then(data => {
        this.target_user_time_logs = data.data;
      })
    },

    async getLastStamp() {
      await axios.get('/api/student/getLastStamp').catch(err => console.log(err)).then(data => {
       this.stamp_status = (data.data.status_stamp == 1 ? 2 : 1)
      })
    },


    async toggleStamp() {
      this.stamping_loading = true
      await axios.post('/api/student/toggleStamp').catch(err => console.log(err)).then(data => {
        this.stamp_status = (data.data.message.status_stamp == 1 ? 2 : 1)
      }).finally( () => {
        this.stamping_loading = false;
        this.getTimeLogs();
      })
    },
  },

  mounted() {
    this.getTimeLogs();
    this.getLastStamp();
  }
}
</script>