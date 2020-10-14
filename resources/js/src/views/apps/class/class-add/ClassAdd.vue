<!-- =========================================================================================
  File Name: UserEdit.vue
  Description: User Edit Page
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
    />
    <span class="text-danger text-xs">{{ errors.first("Class Name") }}</span>
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
    />
    <span class="text-danger text-xs">{{ errors.first("Class Code") }}</span>
    </div>
  </div>
  <div class="vx-row">
    <div class="vx-col w-full">
      <vs-button class="mr-3 mb-2" @click="saveClassCode" :disabled="!validateForm" >Submit</vs-button>
      <vs-button color="warning" type="border" class="mb-2" @click="class_name = class_code =''; check5 = false;">Cancle</vs-button>
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
    }
  },
  computed: {
    validateForm() {
      this.$vs.loading.close();
      return (
        !this.errors.any() &&
        this.class_name !== "" &&
        this.class_code !== "" 
      );
    },
  },
  methods: {
    saveClassCode() {
      var code = {
        class_name: this.class_name,
        class_code: this.class_code,
      };
      // If form is not validated return
      if (!this.validateForm) returns;
      // Loading
      this.$vs.loading();
      this.$store
        .dispatch("classManagement/saveClassCode", code)
        .then((res) => {
          this.$vs.loading.close();
          this.$router
            .push(`/apps/class/class-list`)
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
