<!-- =========================================================================================
  File Name: UserEdit.vue
  Description: User Edit Page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
  <div id="page-user-edit">
    <vs-alert color="danger" title="User Not Found" :active.sync="user_not_found">
      <span>Item record with id: {{ $route.params.id }} not found.</span>
      <span>
        <span>Check</span>
        <router-link :to="{name:'page-user-list'}" class="text-inherit underline">All Items</router-link>
      </span>
    </vs-alert>

    <vx-card>
      <div slot="no-body" class="tabs-container px-6 pt-6">
        <vs-tabs v-model="activeTab" class="tab-action-btn-fill-conatiner">
          <vs-tab label="Enter Details" icon-pack="feather" icon="icon-user">
            <div class="tab-text">
              <item-create-tab class="mt-4" />
            </div>
          </vs-tab>
        </vs-tabs>
      </div>
    </vx-card>
  </div>
</template>

<script>
import ItemCreateTab from "./ItemCreateTab.vue";

// Store Module
import moduleItemManagement from "@/store/item-management/moduleItemManagement.js";
import moduleUserManagement from "@/store/user-management/moduleUserManagement.js";
export default {
  components: {
    ItemCreateTab
  },
  data() {
    return {
      user_data: null,
      user_not_found: false,
      data: [],
      activeTab: 0
    };
  },
  watch: {
    activeTab() {
      this.fetch_user_data(this.$route.params.userId);
    }
  },
  methods: {
    fetch_user_data(userId) {
      this.$store
        .dispatch("userManagement/fetchUsers")
        .then(res => {
          this.users = res.data.response;
          console.log("this.users");
          console.log(this.users);
        })
        .catch(err => {
          if (err.response.status === 404) {
            this.user_not_found = true;
            return;
          }
          console.error(err);
        });
    }
  },
  created() {
    // Register Module UserManagement Module
    if (!moduleItemManagement.isRegistered) {
      this.$store.registerModule("itemManagement", moduleItemManagement);
      moduleItemManagement.isRegistered = true;
    }
    if (!moduleUserManagement.isRegistered) {
      this.$store.registerModule("userManagement", moduleUserManagement);
      moduleUserManagement.isRegistered = true;
    }
  }
};
</script>
