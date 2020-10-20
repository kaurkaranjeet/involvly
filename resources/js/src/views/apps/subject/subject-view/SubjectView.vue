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
      class="w-full"
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
    activeTab () {
      this.fetch_user_data(this.$route.params.userId)
    }
  },
  methods: {
    fetch_subject_data (subjectId) {
      this.$store.dispatch('subjectManagement/fetchSchoolSubjectDetail', subjectId)
        .then(res => { 
          this.subject_data = res.data.subject
          console.log(this.subject_data)
          this.subject_name = this.subject_data.subject_name;
         })
        .catch(err => {
          console.error(err) 
        })
    }
  },
  created () {
    // Register Module UserManagement Module
    if (!moduleSubjectManagement.isRegistered) {
      this.$store.registerModule('SubjectManagement', moduleSubjectManagement)
      moduleSubjectManagement.isRegistered = true
    }
    this.fetch_subject_data(localStorage.getItem('school_id'))
  }
}

</script>
