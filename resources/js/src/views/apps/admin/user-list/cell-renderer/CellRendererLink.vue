<template>
  <div class="flex items-center">
    <vs-avatar :src="params.data.avatar" class="flex-shrink-0 mr-2" size="30px" @click="$router.push(url)" />
    <router-link :to="url" @click.stop.prevent class="text-inherit hover:text-primary">{{ params.value }}</router-link>
  </div>
</template>

<script>
export default {
  name: 'CellRendererLink',
  computed: {
    url () {
     // return '/apps/user/user-view/268'

      // Below line will be for actual product
      // Currently it's commented due to demo purpose - Above url is for demo purpose
      if(this.params.data.role_id == '4'){
         return "/apps/user/user-view/" + this.params.data.id
      }else if(this.params.data.role_id == '3'){
         return "/apps/user/adminparent-view/" + this.params.data.id
      }else if(this.params.data.role_id == '2'){
         return "/apps/user/admin-student-view/" + this.params.data.id
      }else if(this.params.data.role_id == '5'){
       return "/apps/user/schooladmin-view/" + this.params.data.id
      }
    }
  }
}
</script>
