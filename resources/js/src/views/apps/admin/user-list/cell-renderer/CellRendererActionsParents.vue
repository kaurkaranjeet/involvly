<template>
    <div :style="{'direction': $vs.rtl ? 'rtl' : 'ltr'}">
       <feather-icon icon="EyeIcon" svgClasses="h-5 w-5 mr-4 hover:text-primary cursor-pointer"  @click="$router.push(url)" />
      <feather-icon icon="Trash2Icon" svgClasses="h-5 w-5 hover:text-danger cursor-pointer" @click="confirmDeleteRecord" />
    </div>
</template>

<script>
export default {
  name: 'CellRendererActionsParents',
   computed: {
    url () {
    //return '/apps/user/user-view/268'

      // Below line will be for actual product
      // Currently it's commented due to demo purpose - Above url is for demo purpose
       return "/apps/user/adminparent-view/" + this.params.data.id
    }
  },
  methods: {


    editRecord () {
     // this.$router.push(`/apps/user/user-edit/${  268}`).catch(() => {})


      
              this.$router.push("/apps/user/user-edit/" + this.params.data.id).catch(() => {})
           
    },
    confirmDeleteRecord () {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Confirm Delete',
        text: `Are you sure you want to delete this user?`,
        accept: this.deleteRecord,
        acceptText: 'Delete'
      })
    },
    deleteRecord () {
      /* Below two lines are just for demo purpose */
      //this.showDeleteSuccess()

      /* UnComment below lines for enabling true flow if deleting user */
    //  this.$store.dispatch("userManagement/removeRecordAdmin", this.params.data.id)
    //    .then(()   => { this.showDeleteSuccess() })
    //    .catch(err => { console.error(err)       })
    /* Below two lines are just for demo purpose */
      // this.showDeleteSuccess()
      // alert("1")
      /* UnComment below lines for enabling true flow if deleting user */
      this.$store.dispatch("userManagement/removeRecord", this.params.data.id)
        .then(()   => { this.showDeleteSuccess() })
        .catch(err => { console.error(err)       })
    },
    showDeleteSuccess () {
      this.$vs.notify({
        color: 'success',
        // title: 'User Deleted',
        text: 'The selected user is deleted successfully.'
      })
    }
  }
}
</script>
