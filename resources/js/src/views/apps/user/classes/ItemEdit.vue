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
      <span>User record with id: {{ $route.params.id }} not found.</span>
      <span>
        <span>Check</span>
        <router-link :to="{name:'page-user-list'}" class="text-inherit underline">All Users</router-link>
      </span>
    </vs-alert>

    <vx-card v-if="item_data">
      <div slot="no-body" class="tabs-container px-6 pt-6">
        <vs-tabs v-model="activeTab" class="tab-action-btn-fill-conatiner">
          <vs-tab label="Edit Item" icon-pack="feather" icon="icon-user">
            <div class="tab-text">
              <item-edit-tab-account class="mt-4" :data="item_data" />
            </div>
          </vs-tab>
        </vs-tabs>
      </div>
    </vx-card>
  </div>
</template>

<script>
import ItemEditTabAccount from "./ItemEditTabAccount.vue";

// Store Module
import moduleItemManagement from "@/store/item-management/moduleItemManagement.js";
import moduleUserManagement from "@/store/user-management/moduleUserManagement.js";

export default {
  components: {
    ItemEditTabAccount
  },
  data() {
    return {
      item_data: null,
      user_not_found: false,
      activeTab: 0
    };
  },
  watch: {
    activeTab() {
      this.fetch_item_data(this.$route.params.itemId);
    }
  },
  methods: {
    fetch_item_data(itemId) {
      this.$store
        .dispatch("itemManagement/getItem", itemId)
        .then(res => {
          this.item_data = res.data.response;
        })
        .catch(err => {
          this.user_not_found = true;
          console.error(err);
        });
    }
  },
  created() {
    if (!moduleUserManagement.isRegistered) {
      this.$store.registerModule("userManagement", moduleUserManagement);
      moduleUserManagement.isRegistered = true;
    }
    // Register Module itemManagement Module
    if (!moduleItemManagement.isRegistered) {
      this.$store.registerModule("itemManagement", moduleItemManagement);
      moduleItemManagement.isRegistered = true;
    }
    this.fetch_item_data(this.$route.params.itemId);
  }
};
</script>
