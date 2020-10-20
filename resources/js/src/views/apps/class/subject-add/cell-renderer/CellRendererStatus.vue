<template>
    <div>
     <vs-button @click="AddClass(params.value)"> Add</vs-button>
     <vs-button @click="RemoveClass(params.value)"> Add</vs-button>
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
        .then(()   => { this.showDeleteSuccess() })
        .catch(err => { console.error(err)       })
    },
     RemoveClass () {
      const payload={
      subject_id:this.params.data.id,
      class_id:this.$route.params.classId,
    }
      this.$store.dispatch("classManagement/RemoveClassSubjects", payload)
        .then(()   => { this.showDeleteSuccess() })
        .catch(err => { console.error(err)       })
    },
    showDeleteSuccess () {
      this.$vs.notify({
        color: 'success',
        title: 'Deleted',
        text: 'Data deleted successfully'
      })
    }
  }
}
</script>
