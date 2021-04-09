<template>
    <div :style="{'direction': $vs.rtl ? 'rtl' : 'ltr'}">
       <feather-icon icon="MailIcon" title="Send warning" svgClasses="h-5 w-5 mr-4 hover:text-primary cursor-pointer" @click="activePrompt2 = true"/>
       <vs-prompt
      @cancel="valMultipe.value1='',valMultipe.value2=''"
      @accept="acceptAlert"
      @close="close"
      :is-valid="validName"
      :active.sync="activePrompt2">
       <div class="con-exemple-prompt">
        <b>Reason</b>.
         <!--<vs-input placeholder="Name" v-model="valMultipe.value1"/>-->
         <div class="vx-col w-full">
              <vs-input
                v-validate="'required|min:3'"
                data-vv-validate-on="blur"
                label-placeholder="Reason"
                name="Reason"
                v-model="valMultipe.value1"
                class="w-full mt-8"
              />
          </div>

         <!--<vs-alert :active="!validName" color="danger" icon="new_releases" >
           Fields can not be empty please enter the data
         </vs-alert>-->
       </div>
     </vs-prompt>
      <feather-icon icon="Trash2Icon" title="Delete user" svgClasses="h-5 w-5 hover:text-danger cursor-pointer" @click="confirmDeleteRecord" />
    </div>
</template>

<script>
import axios from '@/axios.js'
export default {
  data(){
    return {
    activePrompt2:false,
     valMultipe:{
        value1:'',
        value2:''
      },
    }
  },
  name: 'CellRendererActionsSchoolAdmins',
   computed: {
    url () {
       return "/apps/user/schooladmin-view/" + this.params.data.id
    },
    validName(){
      return (this.valMultipe.value1.length > 0)
    }
  },
  methods: {
    acceptAlert(color){
      return new Promise((resolve, reject) => {
        var code = {
        user_id: this.params.data.to_detail.id,
        reason: this.valMultipe.value1,
        }
            axios.post(`/api/auth/send-warning-mail`, code)
                .then((response) => {
                    this.$vs.notify({
                    color: 'success',
                    title: 'User Reported',
                    text: 'The Mail sent was successfully to reported user'
                  })
                })
                .catch((error) => { reject(error) })
        })
    },
    close(){
      this.$vs.notify({
        color:'danger',
        title:'Closed',
        text:'You close a dialog!'
      })
    },
    confirmDeleteRecord () {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Confirm Delete',
        text: `You are about to delete "${this.params.data.to_detail.name}"`,
        accept: this.deleteRecord,
        acceptText: 'Delete'
      })
    },
    deleteRecord () {
      this.$store.dispatch("userManagement/removeRecord", this.params.data.to_detail.id)
        .then(()   => { this.showDeleteSuccess()
         })
        .catch(err => { console.error(err)       })
    },
    showDeleteSuccess () {
      this.$vs.notify({
        color: 'success',
        title: 'User Deleted',
        text: 'The reported user was successfully deleted'
      })
      location.reload();
    }
  }
}
</script>
