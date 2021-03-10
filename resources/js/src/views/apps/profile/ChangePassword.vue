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
      v-validate="'required|min:6|max:10'"
      type="password"
      data-vv-validate-on="blur"
      label-placeholder="Current Password"
      name="Current Password"
      v-model="current_password"
      class="w-full"
    />
    <span class="text-danger text-xs">{{ errors.first("Current Password") }}</span>
    <vs-input
      v-validate="'required|min:6|max:10'"
      type="password"
      data-vv-validate-on="blur"
      label-placeholder="New Password"
      name="New Password"
      v-model="new_password"
      class="w-full"
    />
    <span class="text-danger text-xs">{{ errors.first("New Password") }}</span>

    <vs-input
      v-validate="'required|min:6|max:10'"
      type="password"
      data-vv-validate-on="blur"
      label-placeholder="Confirm Password"
      name="Confirm Password"
      v-model="confirm_password"
      class="w-full"
    />
    <span class="text-danger text-xs">{{ errors.first("Confirm Password") }}</span>
  
   
    </div>
  </div>
  <div class="vx-row">
    <div class="vx-col w-full">
      <vs-button class="mr-3 mb-2" @click="changepassword" :disabled="!validateForm" >Update</vs-button>
      <vs-button color="warning" type="border" class="mb-2" @click="reset_data">Reset</vs-button>
    </div>
  </div>
  </vx-card>
  </div>
</template>

<script>
import axios from '@/axios.js'


export default {
  components: {
  },
  data () {
    return {
      current_password: "",
      new_password: "",
      confirm_password: "",
    }
  },
  watch: {
    // activeTab () {
    //   this.fetch_user_data(this.$route.params.userId)
    // }
    
  },
  computed: {
    validateForm() {
      this.$vs.loading.close();
      return (
        !this.errors.any() &&
        this.current_password !== "" &&
        this.new_password !== "" &&
        this.confirm_password !== ""
      );
    },
  },
  methods: {
    changepassword() {
       const formData = new FormData();
       formData.append("user_id", localStorage.getItem('user_id'));
       formData.append("current_password", this.current_password);
       formData.append("new_password", this.new_password);
       formData.append("confirm_password", this.confirm_password);
      // If form is not validated return
      if (!this.validateForm) returns;
      // Loading
      this.$vs.loading();
      this.$store
        .dispatch("auth/changepassword", formData)
        .then((res) => {
          this.$vs.loading.close();
          this.$router
            .push(`/`)
            .catch(() => {});
          this.$vs.notify({
            color: "success",
            title: "Success",
            text: "Password changed successfully!",
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
          this.current_password = '';
          this.new_password = '';
          this.confirm_password = '';
    },
  },
}

</script>

<style lang="scss">
.w-full{
  margin-top: 26px !important;
}
</style>