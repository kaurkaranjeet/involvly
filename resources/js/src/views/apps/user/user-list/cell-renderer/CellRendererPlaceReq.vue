 <template>
  <div :style="{ 'direction': $vs.rtl ? 'rtl' : 'ltr' }">
    <vs-button  :class="{ 'primary': isActive, 'danger': hasError , 'success': Connec }" type="filled" >
      <span @click="activePrompt1 = true" v-if=place>Place Request</span>
      <span v-else-if=conn>Connected</span>
      <span @click="activePrompt2 = true" v-else>Cancel</span>
    </vs-button>

    <div class="modelx">
      {{ val == null ? 'null' : val }}
    </div>
    <div class="modelx">
      {{ valMultipe.value1 }}
      {{ valMultipe.value2 }}
    </div>

    <!-- Modal for Accept -->
    <vs-prompt color="#CADC4F" @cancel="valMultipe.value1 = ''" @accept="acceptAlert" @close="valMultipe.value1 = ''"
      :is-valid="validName" :active.sync="activePrompt1">
      <div class="con-exemple-prompt">
        By clicking here, I state that I have read and understood the terms and conditions and accept the request to
        hire a teacher.
        <br />
        <br />
        <vs-checkbox v-model="valMultipe.value1">I agree with terms & condition.</vs-checkbox>
      </div>
    </vs-prompt>

    <vs-prompt color="danger" @cancel="valMultipe.value1 = ''" @accept="rejectAlert" @close="valMultipe.value1 = ''"
      :active.sync="activePrompt2">
      <div class="con-exemple-prompt">
        Do you really want to Cancel a Request?  
        <br /> 
      </div>
    </vs-prompt>
  </div>
</template>

<script>
import axios from '@/axios.js'
import moduleUserManagement from '@/store/user-management/moduleUserManagement.js'

export default {
  name: 'CellRendererActions',
  data() {
    return {
      place: false,
      conn:false,
      activePrompt1: false,
      activePrompt2: false,
      val: '',
      valMultipe: {
        value1: '',
      },
      isActive: false,
      hasError: true,
      Connec: false,
    }
  },
  computed: {
    validName() {
      return (this.valMultipe.value1 == true)
    },
    url() {
      return "/apps/user/user-view/" + this.params.data.id
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
        color: 'danger',
        title: 'Request rejected',
        text: 'User Place request has been Cancelled!',
      })
    },
    // Place a Request fucntion 
    placeRecord(id) {
      new Promise((resolve, reject) => {
      
        var arr = {
          'id':this.params.data.id,
          'request_status' :id,
          'from_user':localStorage.user_id,
        }; 
        axios.post(`/api/auth/place-user`,arr)
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
    },
  },
  mounted() { 
   
  console.log('here',   localStorage.user_id);
    if (this.params.data.request_status == 0 || this.params.data.request_status == null) {
      this.place = true;
      this.isActive = true;
      this.hasError = false;
    }
    if (this.params.data.request_status == 2) {
      this.conn = true;
      this.isActive = false;
      this.hasError = false;
      this.Connec = true;
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
button.vs-component.vs-button.vs-button-primary.vs-button-filled.danger
{
  background:rgba(var(--vs-danger),1)!important;
  color:#fff !important;

}
button.vs-component.vs-button.vs-button-primary.vs-button-filled.success
{
  background:rgba(var(--vs-success),10)!important;
  color:#fff !important;
  pointer-events:none !important;
}
 
</style>


