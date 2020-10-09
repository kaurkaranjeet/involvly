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
            :src="(data_local.item_img) ? data_local.item_img : require(`@assets/images/logo/placeholder.jpg`)"
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
          <vs-input-number min="1" name="qty" v-model="data_local.qty" />
        </div>
      </div>
      <div class="vx-col md:w-1/2 w-full">
        <vs-input
          class="w-full mt-4"
          label="Item Name"
          v-validate="'required|alpha_spaces'"
          name="item_name"
          v-model="data_local.item_name"
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('item_name')"
        >{{ errors.first('item_name') }}</span>

        <vs-input
          class="w-full mt-4"
          label="Price"
          v-validate="'required|alpha_spaces'"
          name="price"
          v-model="data_local.price"
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
        />
        <span class="text-danger text-sm" v-show="errors.has('price')">{{ errors.first('price') }}</span>
        <vs-input
          class="w-full mt-4"
          label="Item SKU"
          v-validate="'required|alpha_spaces'"
          name="sku"
          v-model="data_local.sku"
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
            v-model="stock_local"
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
            v-model="user_local"
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
    </div>

    <!-- Save & Reset Button -->
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="mt-8 flex flex-wrap items-center justify-end">
          <vs-button
            class="ml-auto mt-2"
            @click="save_changes"
            :disabled="!validateForm"
          >Save Changes</vs-button>
          <!-- <vs-button class="ml-4 mt-2" type="border" color="warning" @click="reset_data">Reset</vs-button> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import vSelect from "vue-select";
import moduleItemManagement from "@/store/item-management/moduleItemManagement.js";
import moduleUserManagement from "@/store/user-management/moduleUserManagement.js";
export default {
  components: {
    vSelect
  },
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      data_local: JSON.parse(JSON.stringify(this.data)),
      statusOptions: [
        { label: "Active", value: "active" },
        { label: "Inactive", value: "inactive" }
      ],
      stockOptions: [
        { label: "Sold", value: "sold" },
        { label: "Unsold", value: "unsold" }
      ],
      users: this.user_data(this.data.user_id),
      options: []
    };
  },
  computed: {
    validateForm() {
      return !this.errors.any();
    },
    user_local: {
      get() {
        return {
          label: this.capitalize(this.data_local.label),
          value: this.data_local.value
        };
      },
      set(obj) {
        this.data_local.user_id = obj.value;
      }
    },
    stock_local: {
      get() {
        return {
          label: this.capitalize(this.data_local.is_sold),
          value: this.data_local.is_sold
        };
      },
      set(obj) {
        this.data_local.is_sold = obj.value;
      }
    },
    status_local: {
      get() {
        return {
          label: this.capitalize(this.data_local.status),
          value: this.data_local.status
        };
      },
      set(obj) {
        this.data_local.status = obj.value;
      }
    }
  },
  methods: {
    capitalize(str) {
      return str.slice(0, 1).toUpperCase() + str.slice(1, str.length);
    },
    save_changes() {
      /* eslint-disable */
      if (!this.validateForm) return;
      this.$store
        .dispatch("itemManagement/saveItem", this.data_local)
        .then(res => {
          console.log("Notifications");
          this.$vs.notify({
            color: "success",
            title: "Information Updated",
            text: "Item information updated successfully!"
          });
        })
        .catch(err => {
          if (err.response.status === 404) {
            this.user_not_found = true;
            return;
          }
          console.error(err);
        });
      // Here will go your API call for updating data
      // You can get data in "this.data_local"
      /* eslint-enable */
    },
    fetch_user_data() {
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
    user_data(userId) {
      this.$store
        .dispatch("userManagement/fetchUser", userId)
        .then(res => {
          // this.users = res.data.response;
          const user_data = res.data.response;
          this.data_local.label = `${user_data.first_name} ${user_data.last_name}`;
          this.data_local.value = `${user_data.id}`;
        })
        .catch(err => {
          console.error(err);
        });
    },
    reset_data() {
      this.data_local = JSON.parse(JSON.stringify(this.data));
    },
    update_avatar() {},
    onImageChange(e) {
      this.image = e.target.files[0];
      this.data_local.item_img = URL.createObjectURL(this.image);

      const formData = new FormData();
      formData.append("myFile", this.image, this.image.name);
      formData.append("id", this.data_local.id);
      this.$store
        .dispatch("itemManagement/uploadImage", formData)
        .then(res => {
          this.$vs.notify({
            color: "success",
            title: "Image created",
            text: "Image updated successfully!"
          });
        })
        .catch(err => {
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
    this.fetch_user_data(this.data.user_id);
    this.user_data(this.data.user_id);
  }
};
</script>
