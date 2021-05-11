<template>
  <div class="the-navbar__user-meta flex items-center">

    <div class="text-right leading-tight hidden sm:block" v-if="checkpointReward.name">
      <p class="font-semibold">{{ checkpointReward.name }}</p>
      <small>Available</small>
    </div>

    <vs-dropdown vs-custom-content vs-trigger-click class="cursor-pointer" id="custom_profile">

      <div class="con-img ml-3">
        <img key="onlineImg" v-if="activeUserImage" :src="activeUserImage"  alt="user-img1" width="40" height="40" class="rounded-full shadow-md cursor-pointer block" />
      </div>

      <vs-dropdown-menu class="vx-navbar-dropdown">
        <ul style="min-width: 9rem">

          <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="profile">
            <feather-icon icon="UserIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Profile</span>
          </li>
          
          <vs-divider class="m-1" />

          <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="changepassword">
            <feather-icon icon="KeyIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Change Password</span>
          </li>

        <!--   <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white">
            <feather-icon icon="CheckSquareIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Tasks</span>
          </li> -->

          <!-- <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white">
            <feather-icon icon="MessageSquareIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Chat</span>
          </li> -->

          <!-- <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white">
            <feather-icon icon="HeartIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Wish List</span>
          </li> -->

          <vs-divider class="m-1" />

          <li
            class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
            @click="logout">
            <feather-icon icon="LogOutIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Logout</span>
          </li>
        </ul>
      </vs-dropdown-menu>
    </vs-dropdown>
  </div>
</template>

<script>
export default {
  data () {
    return {
     checkpointReward: "", 
    }
  },
  computed: {
    activeUserInfo () {
      return this.$store.state.AppActiveUser
    },
    activeUserImage () {
    let image=localStorage.getItem('profile_image');
      if(image=='null' || image=='' || image==null){
      return require('@assets/images/logo/logo.png');
      }else{
      return localStorage.getItem('profile_image');
      } 
    }
  },
  created() {
    var x = localStorage.getItem('accessToken');
    //  User Reward Card
    const requestOptions = {
        
        headers: { 'Authorization': 'Bearer '+x }
    };
    this.$http
      .get(`/api/auth/user`, requestOptions).then(response => {

      //console.log('Authorization'+response.status)
   this.checkpointReward = response.data.user;
     console.log('user_id',response.data.user.id);

     // localStorage.setItem('user_id',response.data.user.id);
    //   localStorage.setItem('school_id',response.data.user.school_id);
      })
      .catch(error => {
     //   console.log(error);

         // localStorage.removeItem('userInfo')
                // auto logout if 401 response returned from api
                this.$store.state.auth.logout();
               // location.reload(true);
            
      });

  },
  methods: {
    logout () {
      localStorage.removeItem('userInfo')
       localStorage.removeItem('accessToken');
  localStorage.removeItem('school_id');
   localStorage.removeItem('user_id');
   localStorage.removeItem('profile_image');
  
   if(localStorage.getItem('role_id')==5){
     localStorage.removeItem('role_id')
        this.$router.push('/pages/login').catch(() => {})
      }
      else{
         localStorage.removeItem('role_id')
         this.$router.push('/pages/admin/login').catch(() => {})
      }
      // This is just for demo Purpose. If user clicks on logout -> redirect
     // this.$router.push('/pages/login').catch(() => {})
    },
    changepassword () {
           document.getElementById('custom_profile').click();
      if(localStorage.getItem('role_id')==5){
   
      this.$router.push('/apps/profile/changepassword')
      }else{
      this.$router.push('/apps/admin/profile/changepassword')
      }
    },
    profile () {
       document.getElementById('custom_profile').click();
      if(localStorage.getItem('role_id')==5){
      this.$router.push('/apps/profile/editprofile');
      }else{
      this.$router.push('/apps/admin/profile/editprofile');
      }
    }
  }
}
</script>
