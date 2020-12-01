<!-- =========================================================================================
  File Name: ClassView.vue
  Description: CLass View Page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
  <div id="page-user-edit">
  <div class="vx-row mb-2">
    <div class="vx-col w-full">
    <vs-input
      v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="Class Name"
      name="Class Name"
      v-model="class_name"
      class="w-full"
      readonly
    />
 
    </div>
  </div>
  <div class="vx-row mb-2">
    <div class="vx-col w-full">
    <vs-input
      v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="Class Code"
      name="Class Code"
      placeholder="Class Code"
      v-model="class_code"
      class="w-full"
      readonly
    />
    </div>
  </div>
  </vx-card>
  </div>
</template>

<script>
// Store Module
import moduleClassManagement from '@/store/class-management/moduleClassManagement.js'

export default {
  components: {
  },
  data () {
    return {
      class_name: "",
      class_code: "",
      activeTab: 0
    }
  },
  watch: {
    activeTab () {
      this.fetch_user_data(this.$route.params.userId)
    }
  },
  methods: {
    fetch_Class_data (classId) {
      this.$store.dispatch('classManagement/fetchClassCodeDetail', classId)
        .then(res => { 
          this.class_data = res.data.class
          console.log(this.class_data)
          this.class_name = this.class_data.class_name;
          this.class_code = this.class_data.class_code;
         })
        .catch(err => {
          if (err.response.status === 404) {
            this.class_not_found = true
            return
          }
          console.error(err) 
        })
    }
  },
  created () {
    // Register Module UserManagement Module
    if (!moduleClassManagement.isRegistered) {
      this.$store.registerModule('classManagement', moduleClassManagement)
      moduleClassManagement.isRegistered = true
    }
    this.fetch_Class_data(this.$route.params.classId)
  }
}

</script>
