<!-- =========================================================================================
    File Name: Login.vue
    Description: Login Page
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
                  <h4 class="mb-4">Forgot Password</h4>
                  <p>Please enter your email</p>
                  <div style="visibility: hidden;">Please enter your email</div>
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
        class="w-full mt-8"/>
        <span class="text-danger text-sm">{{ errors.first('email') }}</span>

                  <div class="flex flex-wrap justify-between my-5">
                  </div>
                  <vs-button  type="hidden"></vs-button>  
                 <vs-button  :disabled="!validateForm" @click="forgotPassword" class="float-right">Send</vs-button>
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
    }
  },
  computed: {
    validateForm () {
      return !this.errors.any() &&
      this.email !== ''
    }
  },
  methods: {
    forgotPassword () {
      // Loading
      this.$vs.loading()
      const payload = {
          email: this.email,
      }
      this.$store.dispatch('auth/ForgotPassword', payload)
        .then(() => { 
          // this.$router.push('/').catch(() => {})
          this.$vs.loading.close() 
          this.$vs.notify({
            color: "success",
            title: "success",
            text: "Reset password link sent on your registered email",
          });
          this.$router.push({ name: "page-login" });
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
.vs-input--placeholder {
    font-size: .90rem !important;
    color: #626262 !important;
}
</style>
