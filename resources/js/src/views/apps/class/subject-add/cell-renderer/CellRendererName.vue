<template>
  <div class="flex items-center">
    <router-link
      :to="url"
      @click.stop.prevent
      class="text-inherit hover:text-primary"
    >{{ params.value }}</router-link>
  </div>
</template>

<script>
export default {
  name: "CellRendererName",
  computed: {
    url() {
      return "/user-detail/" + this.params.data.user_id;
    }
  }
};
</script>
