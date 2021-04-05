<!-- =========================================================================================
  File Name: UserEditTabInformation.vue
  Description: User Edit Information Tab content
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
  <div id="user-edit-tab-info">

    <!-- Avatar Row -->
    <div class="vx-row" style="display:none">
      <div class="vx-col w-full">
        <div class="flex items-start flex-col sm:flex-row">
          <img :src="data.avatar" class="mr-8 rounded h-24 w-24" />
          <!-- <vs-avatar :src="data.avatar" size="80px" class="mr-4" /> -->
          <div>
            <p class="text-lg font-medium mb-2 mt-4 sm:mt-0">{{ data.name  }}</p>
            <input type="file" class="hidden" ref="update_avatar_input" @change="update_avatar" accept="image/*">

            <!-- Toggle comment of below buttons as one for actual flow & currently shown is only for demo -->
            <vs-button class="mr-4 mb-4">Change Avatar</vs-button>
            <!-- <vs-button type="border" class="mr-4" @click="$refs.update_avatar_input.click()">Change Avatar</vs-button> -->

            <vs-button type="border" color="danger">Remove Avatar</vs-button>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Row -->
    <div class="vx-row">
      <div class="vx-col md:w-1/2 w-full">
       

        <vs-input class="w-full mt-4" label="First name" v-model="data_local.first_name" v-validate="'required|min:3'" name="First Name" :maxlength="20"  v-on:keypress="isLetter($event)" />

        <span class="text-danger text-sm"    v-show="errors.has('First Name')"  >{{ errors.first('First Name') }}</span>
         <vs-input class="w-full mt-4" label="Email" v-model="data_local.email" type="email" v-validate="'required|email'" name="email" readonly  />
        <span class="text-danger text-sm"  disabled v-show="errors.has('email')">{{ errors.first('email') }}</span>
         <div class="vs-component vs-con-input-label vs-input w-full mt-4 vs-input-primary">
<label for="" class="vs-input--label">City</label>
<v-select :options="cityoptions" :clearable="false" v-model="cityFilter"  id="cityFilter"  name="city"  v-validate="'required'"
      data-vv-validate-on="change" disabled/>
    <span class="text-danger text-sm">{{ errors.first('cityFilter') }}</span>
       </div>
      </div>

      <div class="vx-col md:w-1/2 w-full">

 <vs-input class="w-full mt-4" label="last name" v-model="data_local.last_name" v-validate="'required|min:3'" name="last_name"  :maxlength="20" v-on:keypress="isLetter($event)" />
        <span class="text-danger text-sm"  v-show="errors.has('last_name')">{{ errors.first('last_name') }}</span>
         <!--div class="w-full mt-4">
          <label class="vs-input--label">Status</label>
          <v-select v-model="status_local" :clearable="false" :options="statusOptions" v-validate="'required'" name="status" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
          <span class="text-danger text-sm"  v-show="errors.has('status')">{{ errors.first('status') }}</span>
        </div -->
        <div class="vs-component vs-con-input-label vs-input w-full mt-4 vs-input-primary">
<label for="" class="vs-input--label">State</label>
         <v-select  :options="stateFilteroption"  name="state_id"  :clearable="false"  v-model="stateFilter"    @input="getCities"   v-validate="'required'"
      data-vv-validate-on="change" id="stateFilter" disabled>
      <span class="text-danger text-sm">{{ errors.first('stateFilter') }}</span>
</v-select>
</div>

<!-- <v-select  :options="schooloptions"   :clearable="false"  v-model="schoolFilter" class="w-full mt-6"  >
    
</v-select> -->
      
       
      </div>
    </div>

    <!-- Permissions -->
    <!-- <vx-card class="mt-base" no-shadow card-border>

      <div class="vx-row">
        <div class="vx-col w-full">
          <div class="flex items-end px-3">
            <feather-icon svgClasses="w-6 h-6" icon="LockIcon" class="mr-2" />
            <span class="font-medium text-lg leading-none">Permissions</span>
          </div>
          <vs-divider />
        </div>
      </div>

      <div class="block overflow-x-auto">
        <table class="w-full">
          <tr>
           
          
            <th class="font-semibold text-base text-left px-3 py-2" v-for="heading in ['Module', 'Read', 'Write', 'Create', 'Delete']" :key="heading">{{ heading }}</th>
          </tr>

          <tr v-for="(val, name) in data_local.permissions" :key="name">
            <td class="px-3 py-2">{{ name }}</td>
            <td v-for="(permission, name) in val" class="px-3 py-2" :key="name+permission">
              <vs-checkbox v-model="val[name]" />
            </td>
          </tr>
        </table>
      </div>

    </vx-card> -->

    <!-- Save & Reset Button -->
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="mt-8 flex flex-wrap items-center justify-end">
          <vs-button class="ml-auto mt-2" @click="save_changes" :disabled="!validateForm">Save Changes</vs-button>
          <vs-button class="ml-4 mt-2" type="border" color="warning" @click="reset_data">Reset</vs-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import vSelect from 'vue-select'

export default {
  components: {
    vSelect
  },
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  data () {
    return {

      data_local: JSON.parse(JSON.stringify(this.data)),
 cityoptions:[],
 stateFilteroption:[
 ],
      statusOptions: [
        { label: 'ACTIVE',  value: '1' },
        { label: 'INACTIVE',  value: '0' },
        
      ],
      roleOptions: [
        { label: 'Admin',  value: 'admin' },
        { label: 'User',  value: 'user' },
     /*   { label: 'Staff',  value: 'staff' }*/
      ]
    }
  },
  computed: {
    status_local: {

      get () {
          let status='INACTIVE';
          if(this.data_local.status==1){
            status='ACTIVE';
          }
        return { label: status,  value: this.data_local.status  }
      },
      set (obj) {
        this.data_local.status = obj.value
      }
    },

    cityFilter: {

      get () {
        let obj=this.cityoptions;
        let lebeltext='';
        if(this.data_local.city_detail!=null) {
        let city=this.data_local.city_detail.id;
        let city_obj=this.data_local.city;
        if(city_obj==city){

          Object.keys(obj).forEach(function(key) {

            if (obj[key].value == city) {

              lebeltext=obj[key].label;
            }
          });
        }
        else{
          Object.keys(obj).forEach(function(key) {

            if (obj[key].value == city_obj) {

              lebeltext=obj[key].label;
            }
          });
        }
        return    { label: lebeltext,  value:city_obj}
}

      },
      set (obj) {

        this.data_local.city = obj.value
      }
    },


     stateFilter: {

      get () {
let obj=this.stateFilteroption;
let state_id=this.data_local.state_id;
 let lebeltext='';
      Object.keys(this.stateFilteroption).forEach(function(key) {
     //   console.log(obj[key].value)
        if (obj[key].value == state_id) {

  lebeltext=obj[key].label;
 }
});

   return    { label: lebeltext,  value:state_id}
       
      },
      set (obj) {
      
        this.data_local.state_id = obj.value
      }
    },

    role_local: {
      get () {
       let name='user';

      // return { label: this.capitalize(name),  value:name }
      },
      set (obj) {
        this.data_local.role = obj.value
      }
    },
    validateForm () {
      return !this.errors.any()
    }
  },


  created(){
      
    this.$http
    .post("/api/v1/get_cities",{state_id:this.data_local.state_id})
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
  methods: {
    capitalize (str) {
    //  return str.slice(0, 1).toUpperCase() + str.slice(1, str.length)
    },
    save_changes () {
      /* eslint-disable */
      if (!this.validateForm) return
        var local= this.data_local;
        this.$store.dispatch('userManagement/UpdateUser',local)
        .then(res => { this.user_data = res.data.data

this.$vs.notify({
          title: 'Success',
          text: 'Updated Successfully' ,
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'success'
        })

if(res.data.data.role_id=='4'){
     this.$router.push('/apps/user/user-list').catch(() => {})
}
if(res.data.data.role_id=='3'){
     this.$router.push('/apps/user/listofparents').catch(() => {})
}
if(res.data.data.role_id=='2'){
     this.$router.push('/apps/user/listofstudents').catch(() => {})
}
         })
        .catch(err => {
          if (err.response.status === 404) {
            this.user_not_found = true
            return
          }
          console.error(err) 
        })

      // Here will go your API call for updating data
      // You can get data in "this.data_local"

      /* eslint-enable */
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
    reset_data () {
      this.data_local = JSON.parse(JSON.stringify(this.data))
      let elem=document.getElementById("stateFilter");
      let obj=this.stateFilteroption;

      let state_id=this.data_local.state_id;
      let lebeltext='';
      Object.keys(this.stateFilteroption).forEach(function(key) {
      if (obj[key].value == state_id) {
      lebeltext=obj[key].label;
      }
      });
      elem.value = this.data_local.state_id;
      elem.label=lebeltext;
       this.getCities(elem);
      let element=document.getElementById("cityFilter");
    
     this.data_local.city = this.data_local.city_detail.id;
      element.value= this.data_local.city ;
        //alert(  this.data_local.city_detail.id)



          },
    update_avatar () {
      // You can update avatar Here
      // For reference you can check dataList example
      // We haven't integrated it here, because data isn't saved in DB
    },
    isLetter(e) {
  let char = String.fromCharCode(e.keyCode); // Get the character
  if(/^[a-zA-Z\s]*$/.test(char)) return true; // Match with regex 
  else e.preventDefault(); // If not match, don't add to input text
}
  }
}
</script>
