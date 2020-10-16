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
    <span class="text-danger text-xs">{{ errors.first("Subject Name") }}</span>
    </div>
  </div>
  <div class="vx-row">
    <div class="vx-col w-full">
      <vs-button class="mr-3 mb-2" @click="saveSubject" :disabled="!validateForm" >Submit</vs-button>
      <vs-button color="warning" type="border" class="mb-2" @click="subject_name =''; check5 = false;">Cancel</vs-button>
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
      class_id: "",
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
        class_id: this.$route.params.classId
      };
      console.log(code);
      // If form is not validated return
      if (!this.validateForm) returns;
      // Loading
      this.$vs.loading();
      this.$store
        .dispatch("classManagement/saveSubject", code)
        .then((res) => {
          this.$vs.loading.close();
          this.$router
            .push(`/apps/class/class-view/` + this.$route.params.classId)
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
     if (!moduleClassManagement.isRegistered) {
      this.$store.registerModule('classManagement', moduleClassManagement)
      moduleClassManagement.isRegistered = true
    }
  }
}

</script>
