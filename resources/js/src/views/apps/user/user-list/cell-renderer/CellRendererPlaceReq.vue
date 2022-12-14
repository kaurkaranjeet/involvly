 
<template>
  <div :style="{ 'direction': $vs.rtl ? 'rtl' : 'ltr' }">

    <vs-button :class="{ 'primary': isActive, 'danger': hasError }" type="filled">

      <span @click="activePrompt1 = true" v-if=show>Place Request</span>
      <span @click="activePrompt2 = true" v-else>Cancel</span>


    </vs-button>

    <div class="modelx">
      {{ val == null ? 'null' : val }}
    </div>
    <div class="modelx">
      {{ valMultipe.value1 }}
      {{ valMultipe.value2 }}
    </div>

    <vs-prompt color="#CADC4F" @cancel="valMultipe.value1 = ''" @accept="acceptAlert" @close="valMultipe.value1 = ''"
      :is-valid="validName" :active.sync="activePrompt1">
      <div class="con-exemple-prompt">
        By clicking here, I state that I have read and understood the terms and conditions and accept the request to
        hire a teacher.
        <!-- <vs-input placeholder="Name" v-model="valMultipe.value1" />  -->
        <br />
        <br />
        <vs-checkbox v-model="valMultipe.value1">I agree with terms & condition.</vs-checkbox>
      </div>
    </vs-prompt>

    <vs-prompt color="danger" @cancel="valMultipe.value1 = ''" @accept="rejectAlert" @close="valMultipe.value1 = ''"
         :active.sync="activePrompt2">
      <div class="con-exemple-prompt">
        Do you really want to Cancel a Request?
        <!-- <vs-input placeholder="Name" v-model="valMultipe.value1" />  --><br />
        <br />
        <!-- <vs-checkbox v-model="valMultipe.value1">I agree with terms & condition.</vs-checkbox> -->
      </div>
    </vs-prompt>

  </div>

</template>

<script>

import axios from '@/axios.js'
import moduleUserManagement from '@/store/user-management/moduleUserManagement.js'

export default {
  data() {

    return {
      toggle: false,
      show: false,
      activePrompt1: false,
      activePrompt2: false,
      val: '',
      valMultipe: {
        value1: '',
      },
      isActive: false,
      hasError: true
    }
  },
  name: 'CellRendererActions',
  computed: {
    validName() {
      return (this.valMultipe.value1 == true)
    },
    url() {
      //return '/apps/user/user-view/268'
      // Below line will be for actual product
      // Currently it's commented due to demo purpose - Above url is for demo purpose
      return "/apps/user/user-view/" + this.params.data.id
    },

    buttontext() {
      console.log(this.params);
    },
  },
  methods: {

    acceptAlert(color) {

      this.placeRecord(1);
      this.$vs.notify({
        color: 'success',
        title: 'Accept Selected',
        text: 'User Place request has been sent!',
      })
    },
    rejectAlert(color) {
      this.placeRecord(0);
      this.$vs.notify({
        color: 'success',
        title: 'Accept Selected',
        text: 'User Place request has been Cancelled!',
      })
    },
    // Place a Request fucntion 
    placeRecord(id) {
      new Promise((resolve, reject) => {
        let request_status = id;
        axios.get(`/api/auth/place-user/${this.params.data.id}/${request_status}`)
          .then((response) => {
            this.showPlaceSuccess();
            resolve(response)
          })
          .catch((error) => { reject(error) })
      })
    },
    showPlaceSuccess() {
      if (!moduleUserManagement.isRegistered) {
        this.$store.registerModule('userManagement', moduleUserManagement)
        moduleUserManagement.isRegistered = true
      }
      this.$store.dispatch('userManagement/fetchSearch').catch(err => { console.error(err) })

      console.log('now i am in success');
    },
  },  
  mounted() {
    if (this.params.data.request_status == 0) {
      this.show = true;
      this.isActive = true;
      this.hasError = false;
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

.red {
  color: red;
  display: none;
}

.btn {
  margin: 10px;
}
</style>


