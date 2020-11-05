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
      v-validate="'required|min:3'"
      data-vv-validate-on="blur"
      label-placeholder="First Name"
      name="First Name"
      v-model="first_name"
      class="w-full"
    />
    <span class="text-danger text-xs">{{ errors.first("First Name") }}</span>
    </div>
  </div>
  <div class="vx-row mb-2">
    <div class="vx-col w-full">
    <vs-input
      v-validate="'required|min:3'"
      data-vv-validate-on="blur"
      label-placeholder="Last Name"
      name="Last Name"
      v-model="last_name"
      class="w-full"
    />
    <span class="text-danger text-xs">{{ errors.first("Last Name") }}</span>
    </div>
  </div>
  <div class="vx-row mb-2">
    <div class="vx-col w-full">
    <vs-input
      v-validate="'required|email|min:3'"
      data-vv-validate-on="blur"
      label-placeholder="Email"
      name="Email"
      v-model="email"
      class="w-full"
    />
    <span class="text-danger text-xs">{{ errors.first("Email") }}</span>
    </div>
  </div>
  <div class="vx-row mb-2">
    <div class="vx-col w-full">
    <vs-input
      v-validate="'required|min:6|max:10'"
      data-vv-validate-on="blur"
      label-placeholder="Password"
      name="Password"
      v-model="password"
      class="w-full"
    />
    <span class="text-danger text-xs">{{ errors.first("Password") }}</span>
    </div>
  </div>
  <div class="vx-row mb-2">
    <div class="vx-col w-full">
    <vs-input
      v-validate="'min:6|max:10|confirmed:password'"
      data-vv-validate-on="blur"
      label-placeholder="Confirm Password"
      name="Confirm Password"
      v-model="confirm_password"
      class="w-full"
    />
    <span class="text-danger text-xs">{{ errors.first("Confirm Password") }}</span>
    </div>
  </div>
  <div class="vx-row mb-2">
    <div class="vx-col w-full">
    <vue-select
      v-model="schooling_type"
      :clearable="false"
      :options="schoolingOptions"
      v-validate="'required'"
      name="Schooling Type"
      placeholder="Schooling Type"
      class="w-full mt-6"
    />
    <span class="text-danger text-xs">{{ errors.first("Schooling Type") }}</span>
    </div>
  </div>
  <div class="vx-row mb-2">
    <div class="vx-col w-full">
    <vue-select
      v-model="school"
      :clearable="false"
      :options="schoolOptions"
      v-validate="'required'"
      name="Select School"
      placeholder="Select School"
      class="w-full mt-6"
    />
    <span class="text-danger text-xs">{{ errors.first("Select School") }}</span>
    </div>
  </div>
  <div class="vx-row mb-2">
    <div class="vx-col w-full">
    <vue-select
      v-model="classes"
      :clearable="false"
      :options="classOptions"
      v-validate="'required'"
      name="Select School"
      placeholder="Select School"
      class="w-full mt-6"
      v-if="this.school.value == 'home'"
    />
    <span class="text-danger text-xs">{{ errors.first("Select School") }}</span>
    </div>
  </div>
  <div class="vx-row">
    <div class="vx-col w-full">
      <vs-button class="mr-3 mb-2" @click="saveSubject" :disabled="!validateForm" >Submit</vs-button>
      <vs-button color="warning" type="border" class="mb-2" @click="first_name =''; 
      last_name =''; email =''; password =''; confirm_password =''; check5 = false;">Cancel</vs-button>
    </div>
  </div>
  </vx-card>
  </div>
</template>


<script>
// Store Module
import moduleSubjectManagement from '@/store/subject-management/moduleSubjectManagement.js'
import vueSelect from 'vue-select'

export default {
  components: {
    vueSelect
  },
  data () {
    return {
      first_name: "",
      last_name: "",
      email: "",
      password: "",
      confirm_password: "",
      schooling_type: "",
      school:"",
      classes:"",
      schoolingOptions: [
        { label: "Home", value: "home" },
        { label: "School", value: "school" },
      ],
      classOptions: [
        { label: "Home", value: "home" },
        { label: "School", value: "school" },
      ],
    }
  },
  computed: {
    validateForm() {
      this.$vs.loading.close();
      return (
        !this.errors.any() &&
        this.first_name !== "" &&
        this.last_name !== "" &&
        this.email !== "" &&
        this.password !== "" &&
        this.confirm_password !== "" &&
        this.schooling_type !== ""
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
            text: "Data add successfully!",
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
