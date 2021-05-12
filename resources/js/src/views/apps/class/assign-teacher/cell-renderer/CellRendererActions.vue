<template>
    <div :style="{'direction': $vs.rtl ? 'rtl' : 'ltr'}">
    <!--<feather-icon icon="EyeIcon" svgClasses="h-5 w-5 mr-4 hover:text-primary cursor-pointer" @click="viewRecord" /> -->
      <feather-icon icon="Edit3Icon" svgClasses="h-5 w-5 mr-4 hover:text-primary cursor-pointer" @click="editRecord" />
      <feather-icon icon="Trash2Icon" svgClasses="h-5 w-5 hover:text-danger cursor-pointer" @click="confirmDeleteRecord" />
    </div>
</template>

<script>
import axios from "@/axios.js";
export default {
  name: 'CellRendererActions',
  methods: {
    viewRecord () {
      this.$router.push(`/apps/class/subject-view/${this.params.data.id}`).catch(() => {})
    },
    editRecord () {
      this.$router.push(`/apps/class/subject-edit/${this.params.data.id}`).catch(() => {})
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
      this.$store.dispatch("classManagement/removeSubject", this.params.data.id)
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
