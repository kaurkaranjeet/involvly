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
      v-validate="'required|min:4|max:15'"
      data-vv-validate-on="blur"
      label-placeholder="Name*"
      name="displayName"
      placeholder="Name*"
      v-model="displayName"
      class="w-full"

 v-on:keypress="isLetter($event)"
       />
    <span class="text-danger text-sm">{{ errors.first('displayName') }}</span>


 <vs-input
      v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="Position*"
      name="Position"
      placeholder="Position*"
      v-model="Position"
      class="w-full" />
    <span class="text-danger text-sm">{{ errors.first('Position') }}</span>
    <vs-input
      v-validate="'required|email'"
      data-vv-validate-on="blur"
      name="email"
      type="email"
      label-placeholder="Email*"
      placeholder="Email*"
      v-model="email"
      class="w-full mt-6" />
    <span class="text-danger text-sm">{{ errors.first('email') }}</span>

    <vs-input
      ref="password"
      type="password"
      data-vv-validate-on="blur"
      v-validate="'required|min:6|max:10'"
      name="password"
      label-placeholder="Password*"
      placeholder="Password*"
      v-model="password"
      class="w-full mt-6" />
    <span class="text-danger text-sm">{{ errors.first('password') }}</span>

    <vs-input
      type="password"
      v-validate="'min:6|max:10|confirmed:password'"
      data-vv-validate-on="blur"
      data-vv-as="password"
      name="confirm_password"
      label-placeholder="Confirm Password*"
      placeholder="Confirm Password*"
      v-model="confirm_password"
      class="w-full mt-6" />
    <span class="text-danger text-sm">{{ errors.first('confirm_password') }}</span>

       <vs-input
      v-validate="'required'"
      data-vv-validate-on="change"
      name="country"
      type="country"
      label-placeholder="Country"
      placeholder="Country"
      v-model="country"
      class="w-full mt-6"
      readonly="true"
       />
    <span class="text-danger text-sm">{{ errors.first('country') }}</span>
     
   
 <v-select  :options="stateFilteroption"  name="state_id"  :clearable="false"  v-model="stateFilter" class="w-full mt-6"   @input="getCities"   v-validate="'required'"
      data-vv-validate-on="change">
      <span class="text-danger text-sm">{{ errors.first('stateFilter') }}</span>
</v-select>
 <v-select :options="cityoptions" :clearable="false" v-model="cityFilter" class="w-full mt-6"  @input="getSchools"  name="city"  v-validate="'required'"
      data-vv-validate-on="change"/>
    <span class="text-danger text-sm">{{ errors.first('cityFilter') }}</span>
<v-select  :options="schooloptions"   :clearable="false"  v-model="schoolFilter" class="w-full mt-6"  >
    
</v-select>
<span class="text-danger text-sm">{{ errors.first('schoolFilter') }}</span>
    <div class="flex items-start flex-col sm:flex-row">
 <input type="file" class="hidden" ref="update_avatar_input" @change="selectFile" accept="image/*" multiple >

            <!-- Toggle comment of below buttons as one for actual flow & currently shown is only for demo -->
            <vs-button type="border" class="w-full mt-6" @click="$refs.update_avatar_input.click()">Upload Documents</vs-button> 
</div>
    <vs-checkbox v-model="isTermsConditionAccepted" class="mt-6">I accept the terms & conditions.</vs-checkbox>
  
    <vs-button class="w-full mt-6" @click="registerUserJWt" :disabled="!validateForm">Submit</vs-button>

      <vs-button class="w-full mt-6" type="border" to="/pages/login" >Login</vs-button>
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
       documents: '',
      confirm_password: '',
        state_id: '',
      city: '',
      country: 'United States',
      stateFilteroption:[],
      cityoptions:[],
      schooloptions:[],
      stateFilter: { label: 'Select State*', value: '0' },
     schoolFilter: { label: 'Select School*', value: '0' },
      cityFilter: { label: 'Select city*', value: '0' },      
      isTermsConditionAccepted: true
    }
  },
  computed: {
    validateForm () {
      return !this.errors.any() && this.displayName !== '' && this.email !== '' && this.password !== '' && this.confirm_password !== '' && this.isTermsConditionAccepted === true  && this.country !== ''  && this.stateFilter.value !== '0' && this.cityFilter.value !== '0' && this.Position !== ''  && this.schoolFilter.value !== '0'
    }
  },
  methods: {

reset_data () {
    this.displayName= ''
      this.Position=''
      this.email= ''
      this.password= ''
       this.documents= ''
      this.confirm_password= ''
        this.state_id= ''
      this.city=''
      this.country= 'United States'
      
      this.cityoptions=[]
      this.schooloptions=[]
      this.stateFilter= { label: 'Select State', value: '0' }
     this.schoolFilter={ label: 'Select School', value: '0' }
      this.cityFilter={ label: 'Select city', value: '0' }     
      this.isTermsConditionAccepted= true
    },
isLetter(e) {
  let char = String.fromCharCode(e.keyCode); // Get the character
  if(/^[a-zA-Z\s]*$/.test(char)) return true; // Match with regex 
  else e.preventDefault(); // If not match, don't add to input text
},
     selectFile(event) {
     // console.log(event.target.files)
            // `files` is always an array because the file input may be in multiple mode
            this.documents = event.target.files;


        },
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

       this.$vs.loading;
      // If form is not validated or user is already login return
      if (!this.validateForm || !this.checkLogin()) return
      //  console.log(this.stateFilter.value);
//    console.log( this.stateFilter);
        let formData = new FormData();
      formData.append('documents', this.documents);  
        formData.append('position', this.Position); 
      formData.append('name', this.displayName);
      formData.append('email', this.email);
      formData.append('password', this.password);
      formData.append('confirmPassword', this.confirmPassword);
      formData.append('country', this.country);
      formData.append('state_id', this.stateFilter.value);
      formData.append('city', this.cityFilter.value);
      formData.append('school_id', this.schoolFilter.value);
      this.$http
      .post( '/api/auth/register',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }
        ).then((response) => {
         if(response.data.error==false){
        this.$vs.notify({
          title: 'Successfully registered',
          text: 'Your request is under Process',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'success'
        })
        this.reset_data()

         } else{
          this.$vs.notify({
          title: 'Error',
          text: response.data.message,
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'danger'
        })
         }
        })

        .catch((err) => {
          console.log(err.error)

          this.$vs.notify({
          title: 'Something went wrong',
          text: 'Please try later',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'danger'
        })
        //  console.log('FAILURE!!');
        });
    
    //  data.append('data', payload);
    //  console.log(payload)
  /*  this.$http
    .post('/api/auth/register', data) .then(response => {
      var data=response.data.data;

   })
    .catch(error => {
      console.log(error);
    });*/
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
    },

   

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
