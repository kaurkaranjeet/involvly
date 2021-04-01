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
      class="w-full mt-8"
    />
    <span class="text-danger text-xs">{{ errors.first("class_name") }}</span>
    </div>
  </div>
  <div class="vx-row mb-2">
    <div class="vx-col w-full">
    <vs-input
      v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="Class Code)"
      name="Class Code"
      placeholder="Class Code(4 Digits)"
      v-model="class_code"
      class="w-full mt-8"
      :minlength="4"
       :maxlength="4"
    />
    <span class="text-danger text-xs">{{ errors.first("class_code") }}</span>
    </div>
  </div>
  <div class="vx-row">
    <div class="vx-col w-full">
      <vs-button class="mr-3 mb-2" @click="editClassCode" :disabled="!validateForm" >Submit</vs-button>
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
      class_name: "",
      class_code: "",
      class_id: "",
      className: "",
      classCode: "",
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
      var n = this.class_code.length;
      //console.log(n)
      return (
        !this.errors.any() &&
        this.class_name !== "" &&
        this.class_code !== ""  &&
        n == 4
     
      );
    },
  },
  methods: {
    fetch_Class_data (classId) {
      this.$store.dispatch('classManagement/fetchClassCodeDetail', classId)
        .then(res => { 
          this.class_data = res.data.class
          console.log(this.class_data)
          this.className = this.class_data.class_name;
          this.classCode = this.class_data.class_code;
          this.class_name = this.className
          this.class_code = this.classCode;
          this.class_id = this.class_data.id;
         })
        .catch(err => {
          if (err.response.status === 404) {
            this.class_not_found = true
            return
          }
          console.error(err) 
        })
    },
    editClassCode() {
      var code = {
        class_id: this.class_id,
        class_name: this.class_name,
        class_code: this.class_code,
      };
      // If form is not validated return
      if (!this.validateForm) returns;
      // Loading
      this.$vs.loading();
      this.$store
        .dispatch("classManagement/editClassCode", code)
        .then((res) => {
          this.$vs.loading.close();
          this.$router
            .push(`/apps/class/class-list`)
            .catch(() => {});
          this.$vs.notify({
            color: "success",
            title: "Success",
            text: "Data updated successfully!",
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
     this.class_name = this.className
     this.class_code = this.classCode;
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
<style lang="scss">
.vs-input--placeholder {
    font-size: .90rem !important;
    color: #626262 !important;
}
</style>