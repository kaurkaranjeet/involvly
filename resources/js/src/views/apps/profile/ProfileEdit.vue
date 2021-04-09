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
      v-validate="'required|min:3'"
      data-vv-validate-on="blur"
      label-placeholder="Name"
      name="Name"
      v-model="first_name"
      class="w-full mt-8"
        :maxlength="20"
        v-on:keypress="isLetter($event)"

    />
    <span class="text-danger text-xs">{{ errors.first("Name") }}</span>

   

    <vs-input
      v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="Email"
      name="Email"
      v-model="email"
      class="w-full mt-8"
      readonly
    />
    <span class="text-danger text-xs">{{ errors.first("Email") }}</span>

    <vs-input
      data-vv-validate-on="blur"
      label-placeholder="Position"
      name="Position"
      v-model="position"
      class="w-full mt-8"
      v-if = "this.role_id == '5'"
      v-validate="'required'"
    />
    <span class="text-danger text-xs">{{ errors.first("Position") }}</span>

    <vs-input
      data-vv-validate-on="blur"
      label-placeholder="Country"
      name="Country"
      v-model="country"
      class="w-full mt-8"
       v-if = "this.role_id == '5'"
      readonly
    />
    <span class="text-danger text-xs">{{ errors.first("Country") }}</span>

    <vs-input
    v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="State"
      name="State"
      v-model="state"
      class="w-full mt-8"
       v-if = "this.role_id == '5'"
      readonly
    />
    <span class="text-danger text-xs">{{ errors.first("State") }}</span>

    <vs-input
    v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="City"
      name="City"
      v-model="city"
      class="w-full mt-8"
       v-if = "this.role_id == '5'"
      readonly
    />
    <span class="text-danger text-xs">{{ errors.first("City") }}</span>

    <vs-input
    v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="School"
      name="School"
      v-model="school"
      class="w-full mt-8"
       v-if = "this.role_id == '5'"
      readonly
    />
    <span class="text-danger text-xs">{{ errors.first("School") }}</span>

    <!--<v-select  :options="stateOptions"  name="state"  :clearable="false"  v-model="state" class="w-full mt-6"   @input="getCities"   v-validate="'required'"
      data-vv-validate-on="change" readonly>
      <span class="text-danger text-sm">{{ errors.first('state') }}</span>
    </v-select readonly>
 <v-select :options="cityOptions" :clearable="false" v-model="city" class="w-full mt-6"  @input="getSchools"  name="city"  v-validate="'required'"
      data-vv-validate-on="change" readonly/>
    <span class="text-danger text-sm">{{ errors.first('city') }}</span>
<v-select  :options="schoolOptions"   :clearable="false"  v-model="school" class="w-full mt-6"  >
    
</v-select>
  
    <vue-select
      v-model="state"
      :clearable="false"
      :options="stateOptions"
      v-validate="'required'"
      name="State"
      placeholder="State"
      class="w-full mt-6"
      :input="getCities"
    />
    <span class="text-danger text-xs">{{ errors.first("State") }}</span>

    <vue-select
      v-model="city"
      :clearable="false"
      :options="cityOptions"
      v-validate="'required'"
      name="City"
      placeholder="City"
      class="w-full mt-6"
    />
    <span class="text-danger text-xs">{{ errors.first("City") }}</span>

    <vue-select
      v-model="school"
      :clearable="false"
      :options="schoolOptions"
      v-validate="'required'"
      name="School"
      placeholder="School"
      class="w-full mt-6"
    />
    <span class="text-danger text-xs">{{ errors.first("School") }}</span>-->

    </div>
  </div>
  <div class="vx-row">
    <div class="vx-col w-full">
      <vs-button class="mr-3 mb-2" @click="editUser" :disabled="!validateForm">Update</vs-button>
      <vs-button color="warning" type="border" class="mb-2" @click="reset_data">Reset</vs-button>
    </div>
  </div>
  </vx-card>
  </div>
</template>

<script>
// Store Module
import moduleUserManagement from '@/store/user-management/moduleUserManagement.js'
// import vueSelect from 'vue-select'
import vSelect from 'vue-select'

import axios from '@/axios.js'


export default {
  components: {
    // vueSelect,
    vSelect
  },
  data () {
    return {
      first_name: "",

      email: "",
      position: "",
      country: "",
      state:"",
      city:"",
      school:"",
      stateOptions: [],
      cityOptions: [],
      schoolOptions: [],
      activeTab: 0,
      profile_data: '',
      first_name_reset: "",
     // last_name_reset: "",
      email_reset: "",
      position_reset: "",
      country_reset: "",
      state_reset:"",
      city_reset:"",
      school_reset:"",
      state_val:"",
      city_val: "",
      school_val: "",
      state_id:"",
      city_id:"",
      school_id: "",
      role_id: ""
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
      if(localStorage.getItem("role_id")== 1){
      return (
        !this.errors.any() &&
        this.first_name !== "" &&
        this.email !== "" 
      );
      }else{
      return (
        !this.errors.any() &&
        this.first_name !== "" &&
        this.email !== "" &&
        this.position !== "" &&
        this.country !== "" &&
        this.state !== "" &&
        this.city !== "" &&
        this.school !== "" 
      );
      }
    },
  },
  methods: {
    getCities(a){
       this.city= { label: 'Select city', value: '' };
       this.$http
      .post("/api/v1/get_cities",{state_id:a.value})
      .then(response => {
        var data=response.data.data;
        for ( var index in data ) {
         let newobj={}
         newobj.label=data[index].city;
         newobj.value=data[index].id;
        this.cityOptions.push( newobj );
       }
      })
      .catch(error => {
        console.log(error);
      });
    },

    getSchools(a){
        this.$http
      .post("/api/v1/list_schools",{city_id:a.value})
      .then(response => {
        var data=response.data.data;
        for ( var index in data ) {
         let newobj={}
         newobj.label=data[index].school_name;
         newobj.value=data[index].id;
        this.schoolOptions.push( newobj );
       }
      })
      .catch(error => {
        console.log(error);
      }); 
    },
    fetch_user_data (userId) {
      this.$store.dispatch("auth/fetchSchoolUser", userId)
        .then(res => { 
          this.profile_data = res.data.data;
          console.log(this.profile_data);
          if(localStorage.getItem("role_id")== 1){
          this.first_name = this.profile_data.name;
          this.email = this.profile_data.email;
          this.role_id = localStorage.getItem('role_id');
         //reset values
          this.first_name_reset = this.profile_data.name;
          this.email_reset = this.profile_data.email;
          }else{
          this.first_name = this.profile_data.name;
          this.email = this.profile_data.email;
          this.position  = this.profile_data.position;
          this.country = this.profile_data.country;
          this.state = this.profile_data.state_detail.state_name;
          this.state_id = this.profile_data.state_detail.id;
          this.city = this.profile_data.city_detail.city;
          this.city_id = this.profile_data.city_detail.id;
          this.school = this.profile_data.school_detail.school_name;
          this.school_id = this.profile_data.school_detail.id;
          this.role_id = localStorage.getItem('role_id');
          
         //reset values
          this.first_name_reset = this.profile_data.name;
          this.email_reset = this.profile_data.email;
          this.position_reset  = this.profile_data.position;
          this.country_reset = this.profile_data.country;
          this.state_reset = this.profile_data.state_detail.state_name;
          this.city_reset = this.profile_data.city_detail.city;
          this.school_reset = this.profile_data.school_detail.school_name;
          }
         })
        .catch(err => {
          console.error(err) 
        })
    },
    editUser() {
       if(this.state != null){
            if (this.state.value == null) {
              this.state_val = this.state_id;
            } else {
              this.state_val = this.state.value;
            }
        }else{
          this.state_val = this.state.value;
        }

        if(this.city != null){
            if (this.city.value == null) {
              this.city_val = this.city_id;
            } else {
              this.city_val = this.city.value;
            }
        }else{
          this.city_val = this.city.value;
        }

        if(this.school != null){
            if (this.school.value == null) {
              this.school_val = this.school_id;
            } else {
              this.school_val = this.school.value;
            }
        }else{
          this.school_val = this.school.value;
        }
       const formData = new FormData();
       if(localStorage.getItem("role_id")== 1){
       formData.append("user_id", localStorage.getItem('user_id'));
       formData.append("first_name", this.first_name);
       }else{
       formData.append("user_id", localStorage.getItem('user_id'));
       formData.append("first_name", this.first_name);
     //  formData.append("last_name", this.last_name);
       formData.append("position", this.position);
       formData.append("country", this.country);
       formData.append("state", this.state_val);
       formData.append("city", this.city_val);
       formData.append("school", this.school_val);
       }
      // If form is not validated return
      if (!this.validateForm) returns;
      // Loading
      this.$vs.loading();
      this.$store
        .dispatch("auth/UpdateUser", formData)
        .then((res) => {
          this.$vs.loading.close();
          this.$router
            .push(`/`)
            .catch(() => {});
          this.$vs.notify({
            color: "success",
            title: "Success",
            text: "Admin details updated successfully!",
          });
          location.reload();
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
      if(localStorage.getItem("role_id")== 1){
          this.first_name = this.first_name_reset;
      }else{
          this.first_name = this.first_name_reset;
          this.email = this.email_reset;
          this.position = this.position_reset;
          this.country = this.country_reset;
          this.state = this.state_reset ;
          this.city = this.city_reset ;
          this.school = this.school_reset;
      }
    },
    isLetter(e) {
  let char = String.fromCharCode(e.keyCode); // Get the character
  if(/^[a-zA-Z\s]*$/.test(char)) return true; // Match with regex 
  else e.preventDefault(); // If not match, don't add to input text
}
  },
  created () {
    // Register Module UserManagement Module
    if (!moduleUserManagement.isRegistered) {
      this.$store.registerModule('UserManagement', moduleUserManagement)
      moduleUserManagement.isRegistered = true
    }
    this.fetch_user_data(localStorage.getItem('user_id'))
    this.$http
      .get("/api/v1/list_states")
      .then(response => {
        var data=response.data.data;
        for ( var index in data ) {
         let newobj={}
         newobj.label=data[index].state_name;
         newobj.value=data[index].id;
        this.stateOptions.push( newobj );
       }
      })
      .catch(error => {
        console.log(error);
      });  
  }
}

</script>
<style lang="scss">
.vs-input--placeholder {
    font-size: .90rem !important;
    color: #626262 !important;
}
</style>
