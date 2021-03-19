<!-- =========================================================================================
  File Name: SubjectAdd.vue
  Description: Subject Add Page
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
    />
    <span class="text-danger text-xs">{{ errors.first("subject_name") }}</span>
    </div>
  </div>
  <div class="vx-row">
    <div class="vx-col w-full">
      <vs-button class="mr-3 mb-2" @click="saveSubject" :disabled="!validateForm" >Submit</vs-button>
      <vs-button color="warning" type="border" class="mb-2" @click="subject_name =''; check5 = false;">Reset</vs-button>
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
      school_id: "",
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
    saveSubject() {
      var code = {
        subject_name: this.subject_name,
        school_id: localStorage.getItem('school_id')
      };
      console.log("adddata",code);
      // If form is not validated return
      if (!this.validateForm) returns;
      // Loading
      this.$vs.loading();
      this.$store
        .dispatch("subjectManagement/saveSchoolSubject", code)
        .then((res) => {
          this.$vs.loading.close();
          this.$router
            .push(`/apps/subject/subject-list`)
            .catch(() => {});
          this.$vs.notify({
            color: "success",
            title: "Success",
            text: "Subject added successfully!",
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
  },
  watch: {
    activeTab () {
      this.fetch_user_data(this.$route.params.userId)
    }
  },
  created() {
     if (!moduleSubjectManagement.isRegistered) {
      this.$store.registerModule('subjectManagement', moduleSubjectManagement)
      moduleSubjectManagement.isRegistered = true
    }
  }
}

</script>
