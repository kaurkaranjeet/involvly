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
      label-placeholder="Subject Name"
      name="Subject Name"
      v-model="subject_name"
      class="w-full mt-8"
      readonly
    />
    <span class="text-danger text-xs">{{ errors.first("Subject Name") }}</span>
    </div>
  </div>
  </vx-card>
  </div>
</template>

<script>
// Store Module
import moduleSubjectManagement from '@/store/subject-management/moduleSubjectManagement.js'

export default {
  components: {
  },
  data () {
    return {
      subject_name: "",
      activeTab: 0
    }
  },
  watch: {
    
  },
  methods: {
    
  },
  created () {
    // Register Module UserManagement Module
    if (!moduleSubjectManagement.isRegistered) {
      this.$store.registerModule('subjectManagement', moduleSubjectManagement)
      moduleSubjectManagement.isRegistered = true
    }
 const subjectId = this.$route.params.subjectId
    this.$store.dispatch('subjectManagement/fetchSchoolSubjectDetail', subjectId)
        .then(res => { 
          this.subject_data = res.data.subject
          console.log(this.subject_data)
          this.subject_name = this.subject_data.subject_name;
         })
        .catch(err => {
          console.error(err) 
        })
   // this.fetch_subject_data(localStorage.getItem('school_id'))
  }
}

</script>
<style lang="scss">
.vs-input--placeholder {
    font-size: .90rem !important;
    color: #626262 !important;
}
</style>