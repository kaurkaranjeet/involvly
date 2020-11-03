<template>
    <div>
     <vs-button v-if="params.value==0" @click="AddTeacher(params.value)">Assign</vs-button>
     <vs-button v-if="params.value==1" @click="RemoveTeacher(params.value)"> Remove</vs-button>
                     </div>
    </div>
</template>

<script>
import axios from "@/axios.js";
export default {
  name: 'CellRendererActions',
  methods: {
    AddTeacher () {
      const payload={
      teacher_id: this.params.data.id,
      class_id: this.$route.params.classId,
      subject_id: this.$route.params.subjectId,
      school_id:localStorage.getItem('school_id'),
    }
      this.$store.dispatch("classManagement/AddTeacherToClassSubjects", payload)
        .then(()   => { this.showAddSuccess(); this.$router.push('/apps/class/class-view/'+this.$route.params.classId).catch(() => {}) })
        .catch(err => { console.error(err)       })
    },
     RemoveTeacher () {
      const payload={
      teacher_id: this.params.data.id,
      class_id: this.$route.params.classId,
      subject_id: this.$route.params.subjectId,
      school_id:localStorage.getItem('school_id'),
    }
      this.$store.dispatch("classManagement/RemoveTeacherToClassSubjects", payload)
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
        text: 'Teacher Assigned successfully'
      })
    }
    ,
     showRemoveSuccess () {
      this.$vs.notify({
        color: 'danger',
        title: 'Removed',
        text: 'Teacher Removed successfully'
      })
    }
  }
}
</script>
