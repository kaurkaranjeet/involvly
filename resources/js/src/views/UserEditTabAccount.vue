<!-- =========================================================================================
  File Name: UserEditTabInformation.vue
  Description: User Edit Information Tab content
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
 <div class="vx-row">
      <!-- CARD 9: DISPATCHED ORDERS -->
      <div class="vx-col w-full">
        <vx-card title="Teachers Requests">
          <div slot="no-body" class="mt-4">
            <vs-table max-items="5" pagination :data="teacherRequests" class="table-dark-inverted" >
              <template slot="thead">
                <vs-th>Id</vs-th>
                <vs-th>Name</vs-th>
                <vs-th>Email</vs-th>                            
                <vs-th>Registration Date</vs-th>
                <vs-th>Approve</vs-th>  
                <vs-th>Reject</vs-th>  
              </template>

              <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].id">
                    <span>#{{data[indextr].id}}</span>
                  </vs-td>

                    <vs-td :data="data[indextr].name">
                    <span>{{data[indextr].name}}</span>
                  </vs-td>

                  <vs-td :data="data[indextr].email">
                    <span>{{data[indextr].email}}</span>
                  </vs-td>
                  <vs-td :data="data[indextr].date">
                    <span>{{data[indextr].date}}</span>
                  </vs-td>
                  <vs-td :data="data[indextr].status">
                    <span class="flex items-center px-2 py-1 rounded">
                    
                      
                     <div class="">
                      
             <vs-button @click="Approveteacher(data[indextr].id,$event)"> Approve</vs-button>
                     </div>
                   
                     
                     
                    </span>
                  </vs-td>

                   <vs-td :data="data[indextr].status">
                    <span class="flex items-center px-2 py-1 rounded">
                    
                      
                     <div class="bg-danger">
                      
             <vs-button class="bg-danger" @click="Rejectteacher(data[indextr].id,$event)"> Reject</vs-button>
                     </div>
                   
                     
                     
                    </span>
                  </vs-td>
                  
              
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </vx-card>
      </div>
    </div>
</template>

<script>
import vSelect from 'vue-select'

export default {
  components: {
    vSelect
  },
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  data () {

       // console.log(teacherRequests)
    return {


     // teacherRequests: JSON.parse(JSON.stringify(this.teacherRequests)),

      statusOptions: [
        { label: 'Active',  value: 'active' },
        { label: 'Blocked',  value: 'blocked' },
        { label: 'Deactivated',  value: 'deactivated' }
      ],
      roleOptions: [
        { label: 'Admin',  value: 'admin' },
        { label: 'User',  value: 'user' },
        { label: 'Staff',  value: 'staff' }
      ]
    }
  },
  computed: {
    status_local: {
      get () {
        return { label: this.capitalize(this.data_local.status),  value: this.data_local.status  }
      },
      set (obj) {
        this.data_local.status = obj.value
      }
    },
    role_local: {
      get () {
        return { label: this.capitalize(this.data_local.role),  value: this.data_local.role  }
      },
      set (obj) {
        this.data_local.role = obj.value
      }
    },
    validateForm () {
      return !this.errors.any()
    }
  },
  methods: {
    capitalize (str) {
      return str.slice(0, 1).toUpperCase() + str.slice(1, str.length)
    },
    save_changes () {
      /* eslint-disable */
      if (!this.validateForm) return

      // Here will go your API call for updating data
      // You can get data in "this.data_local"

      /* eslint-enable */
    },
    reset_data () {
      this.data_local = JSON.parse(JSON.stringify(this.data))
    },
    update_avatar () {
      // You can update avatar Here
      // For reference you can check dataList example
      // We haven't integrated it here, because data isn't saved in DB
    }
  }
}
</script>
