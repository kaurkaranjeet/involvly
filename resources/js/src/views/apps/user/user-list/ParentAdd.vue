<!-- =========================================================================================
  File Name: ClassAsd.vue
  Description: Class Add Page
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
      label-placeholder="First Name*"
      name="First Name"
      placeholder=" First Name*"
      v-model="firstname"
      class="w-full"
      :maxlength="50"
      v-on:keypress="isLetter($event)"
       />
    <span class="text-danger text-xs">{{ errors.first('First Name') }}</span>
   
  
    <vs-input
      v-validate="'required'"
      data-vv-validate-on="blur"
      label-placeholder="Last Name*"
      name="Last Name"
      placeholder="Last Name*"
      v-model="lastname"
      class="w-full"
        :maxlength="50"
        v-on:keypress="isLetter($event)"
       />
    <span class="text-danger text-xs">{{ errors.first("Last Name") }}</span>
  

   <vs-input
      v-validate="'required|email'"
      data-vv-validate-on="blur"
      name="email"
      type="email"
      label-placeholder="Email*"
      placeholder="Email*"
      v-model="email"
      class="w-full mt-6" 

      />
    <span class="text-danger text-xs">{{ errors.first("email") }}</span>
   
 
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
    <span class="text-danger text-xs">{{ errors.first("password") }}</span>
    
 
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
    <span class="text-danger text-xs">{{ errors.first("confirm_password") }}</span>
   
  

   <vue-select  :options="stateFilteroption"  name="state_id"  :clearable="false"  v-model="stateFilter" class="w-full mt-6"   @input="getCities"   v-validate="'required'"
      data-vv-validate-on="change"></vue-select>
      <span class="text-danger text-sm">{{ errors.first('stateFilter') }}</span>

 
   
  

  <vue-select :options="cityoptions" :clearable="false" v-model="cityFilter" class="w-full mt-6"   name="city"  v-validate="'required'"
      data-vv-validate-on="change"/>
    <span class="text-danger text-sm">{{ errors.first('cityFilter') }}</span>
    <vue-select  :options="ChildFilteroption"  name="student_id[]"  :clearable="true"   v-model="ChildFilter" class="w-full mt-6"    v-validate="'required'"   placeholder=" Select Associated Child"
    data-vv-validate-on="change" multiple></vue-select>
    <span class="text-danger text-sm">{{ errors.first('ChildFilter') }}</span>
    <vue-select :options="relationshipOptions" :clearable="false" v-model="relationFilter" class="w-full mt-6"  v-validate="'required'"
    data-vv-validate-on="change"/>
    <span class="text-danger text-sm">{{ errors.first('relationFilter') }}</span>
  <div class="vx-row">
      <div class="vx-col w-full">
        <div class="mt-8 flex flex-wrap items-center justify-end">
      <vs-button class="mt-2" @click="SaveParent"  :disabled="!validateForm" >Submit</vs-button>
      <vs-button color="warning" type="border" class="ml-4 mt-2" @click="reset_data">Reset</vs-button>
   </div>
 </div>
</div>
  </vx-card>
</div>


</template>

<script src="https://unpkg.com/vue-select@latest"></script>
<script>
// Store Module
import moduleUserManagement from '@/store/user-management/moduleUserManagement.js'
import vueSelect from 'vue-select'
import { Validator } from 'vee-validate'
export default {
  components: {
    vueSelect
  },
  data () {
    return {
      firstname: "",
      lastname: "",
      email: "",
      password: "",
      confirm_password: "",
     
     
      classes:"",
      stateFilteroption:[],
      cityoptions:[],
      ChildFilteroption:[],
      ChildFilter: [],
      
    relationshipOptions: [
        { label: 'Father', value: 'Father' },
        { label: 'Mother', value: 'Mother' }
      ],
 relationFilter: { label: 'Select Relationship*', value: '0' },

      stateFilter: { label: 'Select State*', value: '0' },
       classFilter: { label: 'Select Class', value: '0' },
      cityFilter: { label: 'Select city*', value: '0' }, 
    }
  },
  computed: {
    validateForm() {
      //console.log(this.errors)
      this.$vs.loading.close();
       return !this.errors.any() && this.firstname !== '' && this.lastname !== '' && this.email !== '' && this.password !== '' && this.confirm_password !== ''  && this.stateFilter.value !== '0' && this.cityFilter.value !== '0'

    },
  },
  methods: {
    isLetter(e) {
  let char = String.fromCharCode(e.keyCode); // Get the character
  if(/^[a-zA-Z\s]*$/.test(char)) return true; // Match with regex 
  else e.preventDefault(); // If not match, don't add to input text
},

    reset_data () {
      this.firstname= ''
      this.lastname=''
      this.email= ''
      this.password= ''
      this.confirm_password= ''
      this.cityoptions=[]
      // this.relationshipOptions=[]
      this.schooloptions=[]
      this.stateFilter= { label: 'Select State', value: '0' }
      this.cityFilter={ label: 'Select city', value: '0' }   
      this.relationFilter={ label: 'Select Relationship', value: '0' }   
      this.ChildFilter=[]   
    },
    SaveParent() {
      let x=''
      //console.log(this.ChildFilter)
      let person=this.ChildFilter;
      let student=[];
      for (x in person) {
        student.push(person[x].value);
        
      };
      var code = {
        first_name: this.firstname,
        last_name: this.lastname,
        email: this.email,
        password: this.password,
        type_of_schooling: 'school',
        country:'United States',
        relationship:this.relationFilter.value,
        school_id: localStorage.getItem('school_id'),
        city:this.cityFilter.value,
        state_id:this.stateFilter.value,
        student_id:student.join(),
        role_id:3
      };
     // console.log("adddata",code);
      // If form is not validated return
      if (!this.validateForm) return;
      // Loading
     this.$vs.loading();
      this.$store
        .dispatch("userManagement/SaveParent", code)
        .then((res) => {
          this.$vs.loading.close();

         
            if(res.data.error){
          this.$vs.notify({
            title: "Error",
            text: res.data.message,
            iconPack: "feather",
            icon: "icon-alert-circle",
            color: "danger",
          });

            }else{
             


          this.$vs.notify({
            color: "success",
            title: "Success",
            text: "Parents added successfully!",
            iconPack: "feather",
            icon: "icon-alert-circle",
          });
          
           this.$router
            .push(`/apps/user/listofparents`)
            .catch(() => {});
        }
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

  

  },
  created() {
     if (!moduleUserManagement.isRegistered) {
      this.$store.registerModule('userManagement', moduleUserManagement)
      moduleUserManagement.isRegistered = true
    }


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



      // Fetch class
 var x = localStorage.getItem('accessToken');
        var user_id = localStorage.getItem('user_id');
         var school_id = localStorage.getItem('school_id');
        //  User Reward Card
        const requestOptions = {
            headers: { 'Authorization': 'Bearer ' + x },
         school_id:school_id

        };
     /* this.$http
      .post('/api/auth/manage-classes/' + user_id,requestOptions)
      .then(response => {
        var data=response.data.classes;
        for ( var index in data ) {
         let newobj={}
         newobj.label=data[index].class_name;
         newobj.value=data[index].class_code;
        this.classoptions.push( newobj );
       }
      })
      .catch(error => {
        console.log(error);
      });  */

       this.$http
   .post("/api/auth/list_students",requestOptions)
   .then(response => {
    var data=response.data.data;
    for ( var index in data ) {
     let newobj={}
     newobj.label=data[index].name;
     newobj.value=data[index].id;
     this.ChildFilteroption.push( newobj );
   }
 })
   .catch(error => {
    console.log(error);
  });


  }

 
}

</script>
