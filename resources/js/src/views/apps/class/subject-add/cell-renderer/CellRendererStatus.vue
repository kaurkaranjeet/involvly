<template>
    <div>
     <vs-button :class = "(params.data.is_added==0)?'':'hidden'" @click="AddClass()" > Add</vs-button>
     <vs-button :class = "(params.data.is_added==1)?'':'hidden'" @click="RemoveClass()" > Remove</vs-button>
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
        .then((response)   => {     this.params.data.is_added=1; this.showAddSuccess(); 
    

         
    })
        .catch(err => { console.error(err)       })
    },
     RemoveClass () {
      const payload={
      subject_id:this.params.data.id,
      class_id:this.$route.params.classId,
    }
      this.$store.dispatch("classManagement/RemoveClassSubjects", payload)
        .then(()   => { this.showRemoveSuccess() ; 
              this.params.data.is_added=0
        
       })
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
