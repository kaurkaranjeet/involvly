 
<template>
  <div :style="{ 'direction': $vs.rtl ? 'rtl' : 'ltr' }"> 
    <vs-button @click="activePrompt2 = true" color="primary" type="filled">Place Request</vs-button>
    
        
    <div class="modelx">
      {{ val == null ? 'null' : val }}
    </div>
    <div class="modelx">
      {{ valMultipe.value1 }}
      {{ valMultipe.value2 }}
    </div>
    
    <vs-prompt color="#CADC4F" @cancel="valMultipe.value1 = ''" @accept="acceptAlert" @close="valMultipe.value1 = ''" :is-valid="validName" :active.sync="activePrompt2">
      <div class="con-exemple-prompt">
        By clicking here, I state that I have read and understood the terms and conditions and accept the request to hire a teacher.
        <!-- <vs-input placeholder="Name" v-model="valMultipe.value1" />  --><br />
        <br />
        <vs-checkbox v-model="valMultipe.value1" >I agree with terms & condition.</vs-checkbox>
      </div>
    </vs-prompt>
  </div>

</template>

<script>

export default {
  data() {
    return {
      activePrompt2: false,
      val: '',
     
      valMultipe: {
        value1: '',
      },
    }
  },
  name: 'CellRendererActions',
  computed: {
    validName() {
      return (this.valMultipe.value1 == true )
    },
    url() {
      //return '/apps/user/user-view/268'
      // Below line will be for actual product
      // Currently it's commented due to demo purpose - Above url is for demo purpose
      return "/apps/user/user-view/" + this.params.data.id
    }
  },
  methods: {
    acceptAlert(color) {
      this.placeRecord();
      this.$vs.notify({
        color: 'success',
        title: 'Accept Selected',
        text: 'User Place request has been sent!',
      })
    },
   
    placeRecord() {
      /* Below two lines are just for demo purpose */
      // this.showPlaceSuccess()
      var request_status =1; 

      /* UnComment below lines for enabling true flow if deleting user */
      // console.log(this.params.data.id);
      this.$store.dispatch("userManagement/placeRecord", this.params.data.id, request_status)
        .then(() => { this.showPlaceSuccess() })
        .catch(err => { console.error(err) })
    },
    showPlaceSuccess () {
       console.log('now i am in success');
    }
  }
}
</script>
<style type="text/css">
.vs-dialog-cancel-button,
.vs-dialog-cancel-button:hover {
  background: rgba(var(--vs-dark), 1) !important;
  color: #fff !important;
}

.vs-dialog-primary .vs-dialog .vs-dialog-header {
  color: black !important;
}

.con-exemple-prompt {
  padding: 10px;
  padding-bottom: 0px;

}

.vs-input {
  width: 100%;
  margin-top: 10px;
}
</style>


