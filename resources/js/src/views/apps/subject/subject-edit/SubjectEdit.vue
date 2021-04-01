<!-- =========================================================================================
  File Name: SubjectEdit.vue
  Description: Subject View Page
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
    />
    <span class="text-danger text-xs">{{ errors.first("Subject Name") }}</span>
    </div>
  </div>
  <div class="vx-row">
    <div class="vx-col w-full">
      <vs-button class="mr-3 mb-2" @click="editSubject" :disabled="!validateForm" >Submit</vs-button>
      <vs-button color="warning" type="border" class="mb-2" @click="reset_data">Reset</vs-button>
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
      subject_name: "",
      subject_id: "",
      subjectName: "",
      class_id: "",
      activeTab: 0
    }
  },
  watch: {
    activeTab () {
      this.fetch_user_data(this.$route.params.userId)
    }
  },
  computed: {
    validateForm() {
      this.$vs.loading.close();
      return (
        !this.errors.any() &&
        this.subject_name !== "" 
      );
    },
  },
  methods: {
    fetch_subject_data (subjectId) {
      this.$store.dispatch('classManagement/fetchSubjectDetail', subjectId)
        .then(res => { 
          this.subject_data = res.data.subject
          this.subjectName = this.subject_data.subject_name;
          this.subject_name = this.subjectName
          this.subject_id = this.subject_data.id;
          this.class_id = this.subject_data.class_id;
         })
        .catch(err => {
          if (err.response.status === 404) {
            this.class_not_found = true
            return
          }
          console.error(err) 
        })
    },
    editSubject() {
      var code = {
        subject_id: this.subject_id,
        subject_name: this.subject_name,
      };
      // If form is not validated return
      if (!this.validateForm) returns;
      // Loading
      this.$vs.loading();
      this.$store
        .dispatch("classManagement/editSubject", code)
        .then((res) => {
          this.$vs.loading.close();
          this.$router
            .push(`/apps/subject/subject-list/`)
            .catch(() => {});
          this.$vs.notify({
            color: "success",
            title: "Success",
            text: "Subject updated successfully!",
          });
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Error",
            text: error,
            iconPack: "feather",
            icon: "icon-alert-circle",
            color: "danger",
          });
        });
    },
    reset_data(){
     this.subject_name = this.subjectName
    }
  },
  created () {
    // Register Module UserManagement Module
    if (!moduleClassManagement.isRegistered) {
      this.$store.registerModule('classManagement', moduleClassManagement)
      moduleClassManagement.isRegistered = true
    }
    this.fetch_subject_data(this.$route.params.subjectId)
  }
}

</script>
<style lang="scss">
.vs-input--placeholder {
    font-size: .90rem !important;
    color: #626262 !important;
}
</style>