 
<template>
  <div :style="{'direction': $vs.rtl ? 'rtl' : 'ltr'}">
    <vs-button  type="filled" @click="PlaceRequest">Place Request</vs-button> 
  </div>
</template>

<script>
export default {
name: 'CellRendererActions',
 computed: {
  url () {
  //return '/apps/user/user-view/268'

    // Below line will be for actual product
    // Currently it's commented due to demo purpose - Above url is for demo purpose
     return "/apps/user/user-view/" + this.params.data.id
  }
},
methods: {

  PlaceRequest() {
 

    this.$vs.dialog({
      html: true,
      type: 'confirm',
      color: 'primary',
      title: 'Place Request',
      text: "By clicking here, I state that I have read and understood the terms and conditions. Request will be placed!",
      accept: this.placeRecord,
      acceptText: 'Accept',
      cancelText:'Decline'
     
    })
  },
  checkBox(){
// console.log('here');
  },
  placeRecord () {
    /* Below two lines are just for demo purpose */
    // this.showPlaceSuccess()

    /* UnComment below lines for enabling true flow if deleting user */
    // console.log(this.params.data.id);
    this.$store.dispatch("userManagement/placeRecord", this.params.data.id)
      .then(()   => { this.showPlaceSuccess() })
      .catch(err => { console.error(err)       })
  },
  showPlaceSuccess () {
    this.$vs.notify({
      color: 'success',
      // title: 'User Placed',
      text: 'The request for selected user is placed successfully.'
    })
  }
}
}
</script>
<style type="text/css">
.vs-dialog-cancel-button,.vs-dialog-cancel-button:hover{
  background: rgba(var(--vs-dark),1) !important;
  color:#fff !important;
}
.vs-dialog-primary .vs-dialog .vs-dialog-header
{
  color: black !important;
}
</style>
