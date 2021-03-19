<!-- =========================================================================================
    File Name: Foprgot.vue
    Description: Forgot Page
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div
    class="h-screen flex w-full vx-row no-gutter items-center justify-center"
  >
    <div class="vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 sm:m-0 m-4">
      <vx-card>
        <div slot="no-body">
          <div class="vx-row no-gutter">
            <div
              class="vx-col sm:w-full md:w-full lg:w-1/2 mx-auto self-center d-theme-dark-bg"
            >
              <div class="px-8 pt-8 forgot-tabs-container">
                <div class="vx-card__title mt-8">
                  <img
                    :src="require(`@assets/images/logo/logo.png`)"
                    class="rounded w-full mx-auto brand-logo"
                  />
                  <h4 class="text-center mt-3">Let's change your account password</h4>
                </div>
                <vs-tabs>
                  <vs-input
                    ref="password"
                    type="password"
                    data-vv-validate-on="blur"
                    v-validate="'required|min:6|max:10'"
                    name="password"
                    label-placeholder="New Password"
                    placeholder="New Password"
                    v-model="password"
                    class="w-full mt-6"
                  />
                  <span class="text-danger text-sm">{{
                    errors.first("password")
                  }}</span>
                  <vs-input
                    type="password"
                    v-validate="'min:6|max:10|confirmed:password'"
                    data-vv-validate-on="blur"
                    data-vv-as="password"
                    name="confirm_password"
                    label-placeholder="Retype New Password"
                    placeholder="Retype Password"
                    v-model="confirm_password"
                    class="w-full mt-6"
                  />
                  <span class="text-danger text-sm">{{
                    errors.first("confirm_password")
                  }}</span>
                  <br />
                  <vs-button
                    class="mx-auto mt-6 mb-3 w-full"
                    @click="updatePassword"
                    :disabled="!validateForm"
                    >Submit</vs-button
                  >
                </vs-tabs>
              </div>
            </div>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      password: "",
      confirm_password: "",
    };
  },
  computed: {
    validateForm() {
      return (
        !this.errors.any() &&
        this.password !== "" &&
        this.confirm_password !== ""
      );
    },
  },
  methods: {
    //check token is exist or not
    info(token) {
      var code = {
        token: this.$route.query.token,
      };
      this.$store
        .dispatch("auth/TokenChecking", code)
        .then((res) => {
          if (!res.data.user) {
            this.$vs.loading.close();
            this.$vs.notify({
              title: "Error",
              text: res.data.message,
              iconPack: "feather",
              icon: "icon-alert-circle",
              color: "danger",
            });
            this.$router.push({ name: "page-forgot-password" });
          } else {
            // this.$vs.notify({
            //   color: "success",
            //   title: "Reset Password",
            //   text: res.data.message,
            // });
          }
        })
        .catch((err) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Error",
            text: err.message,
            iconPack: "feather",
            icon: "icon-alert-circle",
            color: "danger",
          });
        });
    },
    updatePassword() {
      if (!this.validateForm) returns;
      var user = {
        password: this.password,
        token: this.$route.query.token,
      };
      this.$store
        .dispatch("auth/ChangePassword", user)
        .then((res) => {
          if (!res.data.user) {
            this.$vs.loading.close();
            this.$vs.notify({
              title: "Error",
              text: res.data.message,
              iconPack: "feather",
              icon: "icon-alert-circle",
              color: "danger",
            });
          } else {
            this.$vs.notify({
              color: "success",
              title: "Password Updated",
              text: "Password Reset successfully!",
            });
            this.$router.push({ name: "page-login" });
          }
        })
        .catch((err) => {
          console.error(err);
        });
    },
  },
  created() {
    this.info(this.$route.query.token);
  },
};
</script>
<style lang="scss">
.forgot-tabs-container {
  min-height: 517px;

  .con-tab {
    padding-bottom: 23px;
  }
  .vs-button {
    border-radius: 0px !important;
  }
  .vs-button:not(.vs-radius):not(.includeIconOnly):not(.small):not(.large) {
    padding: 0.75rem 10rem;
  }
  .mb-4 {
    padding-left: 29px !important;
  }
  .mt-5 {
    margin-top: 6.25rem !important;
  }
  .vx-card {
    box-shadow: none;
  }
  .brand-logo {
    max-width: 150px;
  }
}
</style>
