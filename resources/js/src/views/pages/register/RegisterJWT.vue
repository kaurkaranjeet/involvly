<!-- =========================================================================================
File Name: RegisterJWT.vue
Description: Register Page for JWT
----------------------------------------------------------------------------------------
Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div class="clearfix">
    <vs-input
      v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="Name"
      name="displayName"
      placeholder="Name"
      v-model="displayName"
      class="w-full" />
    <span class="text-danger text-sm">{{ errors.first('displayName') }}</span>
 <vs-input
      v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="Position"
      name="Position"
      placeholder="Position"
      v-model="Position"
      class="w-full" />
    <span class="text-danger text-sm">{{ errors.first('Position') }}</span>
    <vs-input
      v-validate="'required|email'"
      data-vv-validate-on="blur"
      name="email"
      type="email"
      label-placeholder="Email"
      placeholder="Email"
      v-model="email"
      class="w-full mt-6" />
    <span class="text-danger text-sm">{{ errors.first('email') }}</span>

    <vs-input
      ref="password"
      type="password"
      data-vv-validate-on="blur"
      v-validate="'required|min:6|max:10'"
      name="password"
      label-placeholder="Password"
      placeholder="Password"
      v-model="password"
      class="w-full mt-6" />
    <span class="text-danger text-sm">{{ errors.first('password') }}</span>

    <vs-input
      type="password"
      v-validate="'min:6|max:10|confirmed:password'"
      data-vv-validate-on="blur"
      data-vv-as="password"
      name="confirm_password"
      label-placeholder="Confirm Password"
      placeholder="Confirm Password"
      v-model="confirm_password"
      class="w-full mt-6" />
    <span class="text-danger text-sm">{{ errors.first('confirm_password') }}</span>

       <vs-input
      v-validate="'required'"
      data-vv-validate-on="blur"
      name="country"
      type="country"
      label-placeholder=""
      placeholder="Country"
      v-model="country"
      class="w-full mt-6" />
    <span class="text-danger text-sm">{{ errors.first('country') }}</span>
     
   
 <v-select  :options="stateFilteroption"   :clearable="false"  v-model="stateFilter" class="w-full mt-6"   @input="getCities">
    
</v-select>
 <v-select :options="cityoptions" :clearable="false" v-model="cityFilter" class="w-full mt-6"  @input="getSchools"/>
    <span class="text-danger text-sm">{{ errors.first('cityFilter') }}</span>
<v-select  :options="schooloptions"   :clearable="false"  v-model="schoolFilter" class="w-full mt-6"  >
    
</v-select>
<span class="text-danger text-sm">{{ errors.first('schoolFilter') }}</span>



    <vs-checkbox v-model="isTermsConditionAccepted" class="mt-6">I accept the terms & conditions.</vs-checkbox>
    <vs-button  type="border" to="/pages/login" class="mt-6">Login</vs-button>
    <vs-button class="float-right mt-6" @click="registerUserJWt" :disabled="!validateForm">Register</vs-button>
  </div>
</template>
<script src="https://unpkg.com/vue-select@latest"></script>
<script>
  import vSelect from 'vue-select'
export default {
  components: {

    vSelect

  },


  data () {
    return {
      displayName: '',
      Position: '',
      email: '',
      password: '',
      confirm_password: '',
      country: 'United States',
      stateFilteroption:[],
      cityoptions:[],
      schooloptions:[],
      stateFilter: { label: 'Select State', value: '' },
     schoolFilter: { label: 'Select School', value: '' },
      cityFilter: { label: 'Select city', value: '' },      
      isTermsConditionAccepted: true
    }
  },
  computed: {
    validateForm () {
      return !this.errors.any() && this.displayName !== '' && this.email !== '' && this.password !== '' && this.confirm_password !== '' && this.isTermsConditionAccepted === true  && this.country !== ''  && (this.stateFilter.value) !== '' && (this.cityFilter.value) !== '' && this.Position !== ''  && (this.schoolFilter.value) !== ''
    }
  },
  methods: {
    checkLogin () {
      // If user is already logged in notify
      if (this.$store.state.auth.isUserLoggedIn()) {

        // Close animation if passed as payload
        // this.$vs.loading.close()

        this.$vs.notify({
          title: 'Login Attempt',
          text: 'You are already logged in!',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'warning'
        })

        return false
      }
      return true
    },
    registerUserJWt () {
      // If form is not validated or user is already login return
      if (!this.validateForm || !this.checkLogin()) return

      const payload = {
        userDetails: {
          name: this.displayName,
          email: this.email,
          password: this.password,
          confirmPassword: this.confirm_password,
          country: this.country,
          state_id: this.stateFilter.value,
          city_id: this.cityFilter.value,
          school_id: this.schoolFilter.value
        },

        notify: this.$vs.notify
      }

      console.log(payload)
  this.$store.dispatch('auth/registerUserJWT', payload)
    },

    getCities(a){
       this.cityFilter= { label: 'Select city', value: '' };
   this.cityoptions=[];
this.$http
      .post("/api/v1/get_cities",{state_id:a.value})
      .then(response => {
        var data=response.data.data;
        for ( var index in data ) {
         let newobj={}
         newobj.label=data[index].city;
         newobj.value=data[index].id;
        this.cityoptions.push( newobj );
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
        this.schooloptions.push( newobj );
       }
      })
      .catch(error => {
        console.log(error);
      }); 
    }

  }
,
  created(){

    this.$http
      .get("/api/v1/list_states")
      .then(response => {
        var data=response.data.data;
        for ( var index in data ) {
         let newobj={}
         newobj.label=data[index].state_name;
         newobj.value=data[index].id;
        this.stateFilteroption.push( newobj );
       }
      })
      .catch(error => {
        console.log(error);
      });  
    
  },
  

}
</script>
