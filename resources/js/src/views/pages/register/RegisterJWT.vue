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
      v-validate="'required|min:3'"
      data-vv-validate-on="blur"
      label-placeholder="First Name*"
      name="First Name"
      placeholder="First Name*"
      v-model="first_name"
      class="w-full mt-8"
      :maxlength="20"

 v-on:keypress="isLetter($event)"
       />
    <span class="text-danger text-sm">{{ errors.first('First Name') }}</span>
    <vs-input
      v-validate="'required|min:3'"
      data-vv-validate-on="blur"
      label-placeholder="Last Name*"
      name="Last Name"
      placeholder="Last Name*"
      v-model="last_name"
      class="w-full mt-8"
      :maxlength="20"

 v-on:keypress="isLetter($event)"
       />
    <span class="text-danger text-sm">{{ errors.first('Last Name') }}</span>


 <vs-input
      v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="Position*"
      name="Position"
      placeholder="Position*"
      v-model="Position"
      class="w-full mt-8" />
    <span class="text-danger text-sm">{{ errors.first('Position') }}</span>
    <vs-input
      v-validate="'required|email'"
      data-vv-validate-on="blur"
      name="email"
      type="email"
      label-placeholder="Email*"
      placeholder="Email*"
      v-model="email"
      class="w-full mt-8" />
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
      class="w-full mt-8" />
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
      class="w-full mt-8" />
    <span class="text-danger text-sm">{{ errors.first('confirm_password') }}</span>

       <vs-input
      v-validate="'required'"
      data-vv-validate-on="change"
      name="country"
      type="country"
      label-placeholder="Country"
      placeholder="Country"
      v-model="country"
      class="w-full mt-8"
      readonly="true"
       />
    <span class="text-danger text-sm">{{ errors.first('country') }}</span>
     
   
 <v-select  :options="stateFilteroption"  name="state_id"  :clearable="false"  v-model="stateFilter" class="w-full mt-8"   @input="getCities"   v-validate="'required'"
      data-vv-validate-on="change">
      <span class="text-danger text-sm">{{ errors.first('stateFilter') }}</span>
</v-select>
 <v-select :options="cityoptions" :clearable="false" v-model="cityFilter" class="w-full mt-8"  @input="getSchools"  name="city"  v-validate="'required'"
      data-vv-validate-on="change"/>
    <span class="text-danger text-sm">{{ errors.first('cityFilter') }}</span>
<v-select  :options="schooloptions"   :clearable="false"  v-model="schoolFilter" class="w-full mt-8"  >
    
</v-select>
<span class="text-danger text-sm">{{ errors.first('schoolFilter') }}</span>
    <div class="flex items-start flex-col sm:flex-row ">
       <div v-for="(image, key) in images" class="mt-3">
  
        <img class="mr-5 rounded h-12 w-12" :ref="'image'"  /> 
     
    
</div>
<div v-for="(pdfs, key) in pdf" class="mt-3">
 <a :ref="'pdfs'" class="mr-5" target="_blank" >{{pdfs.name}}</a>
 </div>
</div>
  <div class="flex items-start flex-col sm:flex-row">
 <input type="file" class="hidden" ref="update_avatar_input" @change="selectFile" accept="image/*" multiple >


            <!-- Toggle comment of below buttons as one for actual flow & currently shown is only for demo -->
            <vs-button type="border" class="w-full mt-8 mb-8" @click="$refs.update_avatar_input.click()">Upload Documents</vs-button> 
</div>
    <div class="vx-col flex-1">
            <table>
              <tr class="mt-8">
                <td><vs-checkbox v-model="isTermsConditionAccepted"></vs-checkbox></td>
                <td><a href="https://involvvelyback.dev.devserver.in/pages/terms-and-conditions.html" class="" target="_blank">I accept the terms & conditions.</a></td>
              </tr>
            </table>
    </div>
  
    <vs-button class="w-full mt-8" @click="registerUserJWt" :disabled="!validateForm">Submit</vs-button>

      <vs-button class="w-full mt-8" type="border" to="/pages/login"  style="margin-bottom: 15px;">Login</vs-button>
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
      countimages:0,
      first_name: '',
      last_name: '',
      Position: '',
      email: '',
      password: '',
      images: [],
      pdf: [],
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
      isTermsConditionAccepted: false
    }
  },
  computed: {
    validateForm () {
      return !this.errors.any() && this.first_name !== '' && this.last_name !== '' && this.email !== '' && this.password !== '' && this.confirm_password !== '' && this.isTermsConditionAccepted === true  && this.country !== ''  && this.stateFilter.value !== '0' && this.cityFilter.value !== '0' && this.Position !== ''  && this.schoolFilter.value !== '0'
    }
  },
  methods: {

reset_data () {
    this.first_name= ''
    this.last_name= ''
      this.Position=''
      this.email= ''
      this.password= ''
       this.documents= ''
      this.confirm_password= ''
        this.state_id= ''
      this.city=''
      this.country= 'United States'
      this.images=[]
       this.pdf=[]
      this.cityoptions=[]
      this.schooloptions=[]
      this.stateFilter= { label: 'Select State', value: '0' }
     this.schoolFilter={ label: 'Select School', value: '0' }
      this.cityFilter={ label: 'Select city', value: '0' }     
      this.isTermsConditionAccepted= false
    },
isLetter(e) {
  let char = String.fromCharCode(e.keyCode); // Get the character
  if(/^[a-zA-Z\s]*$/.test(char)) return true; // Match with regex 
  else e.preventDefault(); // If not match, don't add to input text
},
     selectFile(event) {
    let selectedFiles= event.target.files;
            // `files` is always an array because the file input may be in multiple mode
            this.documents = selectedFiles;
if(selectedFiles.length>5){
  alert('You can upload 5 documents');
  return false;
}

for (var i=0; i < selectedFiles.length; i++){
  console.log(selectedFiles[i])
  if(selectedFiles[i].type=='image/jpg'|| selectedFiles[i].type=='image/png'|| selectedFiles[i].type=='image/jpeg'){
    this.images.push(selectedFiles[i]);
    this.countimages++;
  }
  else{
     if(selectedFiles[i].type=='application/doc'|| selectedFiles[i].type=='application/ms-doc'|| selectedFiles[i].type=='application/msword' ||  selectedFiles[i].type=='application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||  selectedFiles[i].type=='application/pdf'){
      this.countimages++;
     this.pdf.push(selectedFiles[i]);
   }
   else{
  alert('Only pdf,jpg,png,docs files are allowed');
  return false;

   }
  }
}

for (let i = 0; i < this.images.length; i++) {
        let reader = new FileReader();
       reader.onload = (e) => {
         this.$refs.image[i].src = reader.result;

          //console.log(this.$refs.image[i].src);
        };

        reader.readAsDataURL(this.images[i]);
      }
for (let i = 0; i < this.pdf.length; i++) {
        let reader = new FileReader();
       reader.onload = (e) => {
         this.$refs.pdfs[i].href = reader.result;

          //console.log(this.$refs.image[i].src);
        };

        reader.readAsDataURL(this.pdf[i]);
      }

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
       if(this.countimages>5){
        this.$vs.notify({
          title: 'Error',
          text: 'Only 5 files are allowed at a time',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'danger'
        })
       }
      //  console.log(this.stateFilter.value);
//    console.log( this.stateFilter);
        let formData = new FormData();
      formData.append('documents', this.documents);  
        formData.append('position', this.Position); 
      formData.append('first_name', this.first_name);
      formData.append('last_name', this.last_name);
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
           this.$router.push('/').catch(() => {})
        this.$vs.notify({
          title: 'Successfully registered',
          text: 'Your account is registered',
          // text: 'Your request is under Process',
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
      .post("/api/auth/admin_list_schools",{city_id:a.value})
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
<style lang="scss">
.vs-input--placeholder {
    font-size: .90rem !important;
    color: #626262 !important;
}
</style>
