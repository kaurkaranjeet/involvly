<!-- =========================================================================================
    File Name: AdminLogin.vue
    Description: Admin Login Page
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div class="h-screen flex w-full bg-img vx-row no-gutter items-center justify-center" id="page-login">
    <div class="vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 sm:m-0 m-4">
      <vx-card>
        <div slot="no-body" class="full-page-bg-color">

          <div class="vx-row no-gutter justify-center items-center">

            <div class="vx-col hidden lg:block lg:w-1/2">
              <img src="@assets/images/pages/login.png" alt="login" class="mx-auto">
            </div>

            <div class="vx-col sm:w-full md:w-full lg:w-1/2 d-theme-dark-bg">
              <div class="p-8 login-tabs-container">

                <div class="vx-card__title mb-4">
                  <h4 class="mb-4">Login</h4>
                  <p>Welcome back, please login to your account.</p>
                  <div style="visibility: hidden;">Welcome back, please login to your account.</div>
                </div>

                <div>
                   <vs-input
        v-validate="'required|email|min:3'"
        data-vv-validate-on="blur"
        name="email"
        icon-no-border
        icon="icon icon-user"
        icon-pack="feather"
        label-placeholder="Email"
        v-model="email"
        class="w-full mt-8"
        v-on:keyup.enter="loginJWT"/>
                       <span class="text-danger text-sm">{{ errors.first('email') }}</span>

                 <vs-input
        data-vv-validate-on="blur"
        v-validate="'required|min:6|max:10'"
        type="password"
        name="password"
        icon-no-border
        icon="icon icon-lock"
        icon-pack="feather"
        label-placeholder="Password"
        v-model="password"
        class="w-full mt-8"
        v-on:keyup.enter="loginJWT" />
  <span class="text-danger text-sm">{{ errors.first('password') }}</span>

                  <div class="flex flex-wrap justify-between my-5">
                      <vs-checkbox v-model="checkbox_remember_me" class="mb-3 remember-margin">Remember Me</vs-checkbox>
                      <router-link to="/pages/forgot/adminpassword">Forgot Password?</router-link>
                  </div>
                  <vs-button  type="hidden"></vs-button>
                  <vs-button  :disabled="!validateForm" @click="loginJWT" class="float-right">Login</vs-button>
                  <div class="social-login-buttons flex flex-wrap items-center mt-4">

                    <!-- facebook -->
                   
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
</template>

<script>

export default{
  data() {
    return {
      email: "",
      password: "",
      checkbox_remember_me: false,

    }
  },

  
   computed: {
    validateForm () {
      return !this.errors.any() && this.email !== '' && this.password !== ''
    }
  },
  created() {

    this.checkRemmember();

  },
  methods: {
    checkRemmember(){
      // alert(localStorage.getItem('checkbox_remember_me'))
       if (localStorage.getItem('checkbox_remember_me')) {
            // alert("if")
            if(localStorage.getItem('checkbox_remember_me') == 'true'){
            // alert("ifif")
            this.checkbox_remember_me=true;
            this.email=localStorage.getItem('email_check');
            this.password=localStorage.getItem('password_chk');
            }else{
            // alert("ifelse")
            this.checkbox_remember_me=false;
            this.email='';
            this.password='';
            }
          }
          else{
            // alert("else")
            this.checkbox_remember_me=false;
            this.email='';
            this.password='';
          }
        
        },
    checkLogin () {

    //  If user is already logged in notify
      if (this.$store.state.auth.isUserLoggedIn()) {

        // Close animation if passed as payload
        // this.$vs.loading.close()

        this.$vs.notify({
          title: 'Login Attempt',
          text: 'You are already logged in!',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'warning'
        })

      this.$router.push('/dashboard').catch(() => {})
      }
      return true
    },
    loginJWT () {

      if (!this.checkLogin()) return

      // Loading
      this.$vs.loading()

      const payload = {
        checkbox_remember_me: this.checkbox_remember_me,
        userDetails: {
          email: this.email,
          password: this.password
        }
      }
      if(this.checkbox_remember_me == true){
      // alert("if")
      localStorage.setItem('checkbox_remember_me', this.checkbox_remember_me);
      localStorage.setItem('email_check', this.email);
      localStorage.setItem('password_chk', this.password);  
      // alert(localStorage.getItem('email_check'));
      }else{
      // alert("else")
      localStorage.setItem('checkbox_remember_me', this.checkbox_remember_me);
      localStorage.setItem('email_check', '');
      localStorage.setItem('password_chk', '');
      // alert(localStorage.getItem('email_check'));   
      }
      this.$store.dispatch('auth/adminloginJWT', payload)
        .then(() => {
          this.$vs.loading.close() 
          this.$router.push('/dashboard').catch(() => {})
          })
        .catch(error => {
          this.$vs.loading.close()
          this.$vs.notify({
            title: 'Error',
            text: error.message,
            iconPack: 'feather',
            icon: 'icon-alert-circle',
            color: 'danger'
          })
        })
    },
    registerUser () {
      if (!this.checkLogin()) return
      this.$router.push('/pages/register').catch(() => {})
    }
  }
}

</script>

<style lang="scss">
#page-login {
  .social-login-buttons {
    .bg-facebook { background-color: #1551b1 }
    .bg-twitter { background-color: #00aaff }
    .bg-google { background-color: #4285F4 }
    .bg-github { background-color: #333 }
  }
}
.remember-margin {
  margin-left : 0px !important;
}
.vs-input--placeholder {
    font-size: .90rem !important;
    color: #626262 !important;
}
</style>
