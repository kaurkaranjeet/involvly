<!-- =========================================================================================
  File Name: UserEditTabInformation.vue
  Description: User Edit Information Tab content
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
  <div id="user-edit-tab-info">
    <!-- Content Row -->
    <div class="vx-row">
      <div class="vx-col md:w-1/2 w-full">
        <div class="flex flex-wrap items-center mb-base">
          <vs-avatar
            :src="(url) ? url : require(`@assets/images/logo/placeholder.jpg`)"
            size="300px"
            class="mr-4 mb-4"
          />
          <div>
            <input type="file" name="item_img" class="form-control" v-on:change="onImageChange" />
            <!-- <vs-button class="mr-4 sm:mb-0 mb-2">Upload photo</vs-button>
            <vs-button type="border" color="danger">Remove</vs-button>-->
            <p class="text-sm mt-2">Allowed JPG, GIF or PNG. Max size of 800kB</p>
          </div>
        </div>
        <div class="mt-4">
          <label class="vs-input--label">Quantity</label>
          <vs-input-number min="1" name="qty" v-model="qty" />
        </div>
      </div>
      <div class="vx-col md:w-1/2 w-full">
        <vs-input
          class="w-full mt-4"
          label="Item Name"
          v-validate="'required|alpha_spaces'"
          name="item_name"
          v-model="item_name"
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('item_name')"
        >{{ errors.first('item_name') }}</span>
        <vs-input
          class="w-full mt-4"
          label="Price"
          v-validate="'required|numeric'"
          name="price"
          v-model="price"
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
        />
        <span class="text-danger text-sm" v-show="errors.has('price')">{{ errors.first('price') }}</span>
        <vs-input
          class="w-full mt-4"
          label="Item SKU"
          v-validate="'required|alpha'"
          name="sku"
          v-model="sku"
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
        />
        <span class="text-danger text-sm" v-show="errors.has('sku')">{{ errors.first('sku') }}</span>
        <div class="mt-4">
          <label class="vs-input--label">Status</label>
          <v-select
            :v-model="status"
            :clearable="false"
            :options="statusOptions"
            v-validate="'required'"
            name="status"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('status')"
          >{{ errors.first('status') }}</span>
        </div>
        <div class="mt-4">
          <label class="vs-input--label">Stock Status</label>
          <v-select
            v-model="is_sold"
            :clearable="false"
            :options="stockOptions"
            v-validate="'required'"
            name="is_sold"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('is_sold')"
          >{{ errors.first('is_sold') }}</span>
        </div>
        <div class="mt-4">
          <label class="vs-input--label">Select User</label>
          <v-select
            v-model="users"
            :clearable="false"
            v-validate="'required'"
            :options="options"
            name="user_id"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('user_id')"
          >{{ errors.first('user_id') }}</span>
        </div>
      </div>
      <div class="vx-col md:w-1/2 w-full mt-4">
        <div class="flex flex-wrap justify-between mb-3">
          <vs-button :disabled="!validateForm" @click="save_changes">Create Item</vs-button>
        </div>
      </div>
      <!-- <div class="vx-col md:w-1/2 w-full"></div>
      <div class="vx-col md:w-1/2 w-full"></div>
      <div class="vx-col md:w-1/2 w-full"></div>
      <div class="vx-col md:w-1/2 w-full"></div>
      <div class="vx-col mt-4 md:w-1/2 w-full"></div>

      <div class="vx-col md:w-1/2 w-full"></div>-->
    </div>
  </div>
</template>

<script>
import vSelect from "vue-select";
import moduleItemManagement from "@/store/item-management/moduleItemManagement.js";
import moduleUserManagement from "@/store/user-management/moduleUserManagement.js";
import NumberInputMinMax from "./NumberInputMinMax.vue";
export default {
  components: {
    vSelect
  },
  data() {
    return {
      statusOptions: [
        { label: "Active", value: "active" },
        { label: "Inactive", value: "inactive" }
      ],
      stockOptions: [
        { label: "Sold", value: "sold" },
        { label: "Unsold", value: "unsold" }
      ],
      item_name: null,
      price: 1.0,
      sku: null,
      qty: 1,
      stock_local: [],
      users: this.fetch_user_data(),
      options: [],
      status: "active",
      is_sold: "unsold",
      url: null,
      user_id: null
    };
  },
  computed: {
    validateForm() {
      return (
        !this.errors.any() &&
        this.item_name !== "" &&
        this.price !== "" &&
        this.sku !== "" &&
        this.qty !== "" &&
        this.status !== "" &&
        this.is_sold !== "" &&
        this.user_id !== ""
      );
    },
    activeUserInfo() {
      return this.$store.state.AppActiveUser;
    }
  },
  methods: {
    capitalize(str) {
      return str.slice(0, 1).toUpperCase() + str.slice(1, str.length);
    },
    fetch_user_data(userId) {
      this.$store
        .dispatch("userManagement/fetchUsers")
        .then(res => {
          // this.users = res.data.response;
          this.options = res.data.response.map(user => ({
            label: `${user.first_name} ${user.last_name}`,
            value: user.user_id
          }));
        })
        .catch(err => {
          console.error(err);
        });
    },
    save_changes() {
      /* eslint-disable */
      console.log("this");
      console.log(this);
      if (!this.validateForm) return;
      const formData = new FormData();
      formData.append("myFile", this.image, this.image.name);
      formData.append("image", this.image);
      formData.append("item_name", this.item_name);
      formData.append("price", this.price);
      formData.append("status", this.status);
      formData.append("is_sold", this.is_sold);
      formData.append("qty", this.qty);
      formData.append("sku", this.sku);
      formData.append("user_id", this.users.value);
      this.$store
        .dispatch("itemManagement/createItem", formData)
        .then(res => {
          this.$router.push({ name: "manage-items" });
          this.$vs.notify({
            color: "success",
            title: "Item created",
            text: "Item created successfully!"
          });
        })
        .catch(err => {
          console.error(err);
        });
      // Here will go your API call for updating data
      // You can get data in "this.data_local"
      /* eslint-enable */
    },
    reset_data() {
      // this.data_local = JSON.parse(JSON.stringify(this.data));
    },
    onImageChange(e) {
      console.log(e.target.files[0]);
      this.image = e.target.files[0];
      this.url = URL.createObjectURL(this.image);
    },
    update_avatar(e) {}
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
    this.fetch_user_data(this.$route.params.userId);
  }
};
</script>

