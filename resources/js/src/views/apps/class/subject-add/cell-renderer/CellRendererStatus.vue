<template>
    <div>
     <vs-button v-if="params.value==0" @click="AddClass(params.value)"> Add</vs-button>
     <vs-button v-if="params.value==1" @click="RemoveClass(params.value)"> Remove</vs-button>
                     </div>
    </div>
</template>

<script>
import axios from "@/axios.js";
export default {
  name: 'CellRendererActions',
  methods: {
    
   
    AddClass () {
      const payload={
      subject_id:this.params.data.id,
      class_id:this.$route.params.classId,
    }
      this.$store.dispatch("classManagement/AddClassSubjects", payload)
        .then(()   => { this.showAddSuccess(); location.reload(true) })
        .catch(err => { console.error(err)       })
    },
     RemoveClass () {
      const payload={
      subject_id:this.params.data.id,
      class_id:this.$route.params.classId,
    }
      this.$store.dispatch("classManagement/RemoveClassSubjects", payload)
        .then(()   => { this.showRemoveSuccess() ; location.reload(true)})
        .catch(err => { console.error(err)       })
    },
    showDeleteSuccess () {
      this.$vs.notify({
        color: 'success',
        title: 'Deleted',
        text: 'Data deleted successfully'
      })
    }
    ,
     showAddSuccess () {
      this.$vs.notify({
        color: 'success',
        title: 'Added',
        text: 'Added successfully'
      })
    }
    ,
     showRemoveSuccess () {
      this.$vs.notify({
        color: 'danger',
        title: 'Removed',
        text: 'Removed successfully'
      })
    }
  }
}
</script>
