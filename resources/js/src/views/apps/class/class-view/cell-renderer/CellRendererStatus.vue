<template>
    <div>
     <vs-button @click="assignedTeacherToClass()"> Assign/Re-assign</vs-button>
                     </div>
    </div>
</template>

<script>
import axios from "@/axios.js";
export default {
  name: 'CellRendererActions',
  methods: {
    assignedTeacherToClass(){
      var classId = this.$route.params.classId;
      var subjectId = this.params.data.subject_id;
           console.log("classId",classId) 
           console.log("subjectId",subjectId) 
           this.$router
                .push(`/apps/class/assign-teacher/` + classId + `/` + subjectId)
                .catch(() => {});
        },
  }
}
</script>
