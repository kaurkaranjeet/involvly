<!-- =========================================================================================
  File Name: UserView.vue
  Description: User View page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
  <div id="page-user-view">
    <vs-alert color="danger" title="User Not Found" :active.sync="user_not_found">
      <span>User record with id: {{ $route.params.id }} not found.</span>
      <span>
        <span>Check</span>
        <router-link :to="{name:'page-user-list'}" class="text-inherit underline">All Users</router-link>
      </span>
    </vs-alert>

    <div id="user-data" v-if="user_data">
      <vx-card title="Account" class="mb-base">
        <!-- Avatar -->
        <div class="vx-row">
          <!-- Avatar Col -->
          <div class="vx-col" id="avatar-col">
            <div class="img-container mb-4">
              <img
                :src="user_data.avatar ? user_data.avatar : require(`@assets/images/logo/placeholder.jpg`) "
                class="rounded w-full"
              />
            </div>
          </div>

          <!-- Information - Col 1 -->
          <div class="vx-col flex-1" id="account-info-col-1">
            <table>
              <tr>
                <td class="font-semibold">First Name</td>
                <td>{{ user_data.first_name }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Last Name</td>
                <td>{{ user_data.last_name }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Email</td>
                <td>{{ user_data.email }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Company</td>
                <td>{{ user_data.company_name }}</td>
              </tr>
            </table>
          </div>
          <!-- /Information - Col 1 -->

          <!-- Information - Col 2 -->
          <div class="vx-col flex-1" id="account-info-col-2">
            <table>
              <!--<tr>
                <td class="font-semibold">Status</td>
                <td>{{ user_data.status }}</td>
              </tr>-->
              <tr>
                <td class="font-semibold">Role</td>
                <td>{{ user_data.user_type }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Blocked</td>
                <td>{{ user_data.is_blocked }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Items</td>
                <td>
                  <router-link :to="'/items/'+user_data.id">
                    <vs-chip class="ag-grid-cell-chip vs-chip-success">
                      <span>View Items</span>
                    </vs-chip>
                  </router-link>
                </td>
              </tr>
            </table>
          </div>
          <!-- /Information - Col 2 -->
          <div class="vx-col w-full flex" id="account-manage-buttons">
            <vs-button
              icon-pack="feather"
              icon="icon-edit"
              class="mr-4"
              :to="{name: 'user-edit', params: { userId: $route.params.userId }}"
            >Edit</vs-button>
            <vs-button
              type="border"
              color="danger"
              icon-pack="feather"
              icon="icon-trash"
              @click="confirmDeleteRecord"
            >Delete</vs-button>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
</template>

<script>
import moduleItemManagement from "@/store/item-management/moduleItemManagement.js";
import moduleUserManagement from "@/store/user-management/moduleUserManagement.js";
export default {
  data() {
    return {
      user_data: null,
      user_not_found: false,
      item_data: null
    };
  },
  computed: {
    userAddress() {
      let str = "";
      for (const field in this.user_data.location) {
        str += `${field} `;
      }
      return str;
    }
  },
  methods: {
    confirmDeleteRecord() {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: "Confirm Delete",
        text: `You are about to delete "${this.user_data.first_name} ${this.user_data.last_name}"`,
        accept: this.deleteRecord,
        acceptText: "Delete"
      });
    },
    fetch_item_data(itemId) {
      // console.error(this.$route.params);
      this.$store
        .dispatch("itemManagement/getItem", itemId)
        .then(res => {
          console.log(itemId);
          this.item_data = res.data.response;
        })
        .catch(err => {
          console.error(err);
        });
    },
    deleteRecord() {
      /* Below two lines are just for demo purpose */
      // this.$router.push({ name: "manage-users" });
      // this.showDeleteSuccess();

      /* UnComment below lines for enabling true flow if deleting user */
      this.$store
        .dispatch("userManagement/removeRecord", this.user_data.id)
        .then(() => {
          this.$router.push({ name: "manage-users" });
          this.showDeleteSuccess();
        })
        .catch(err => {
          console.error(err);
        });
    },
    showDeleteSuccess() {
      this.$vs.notify({
        color: "success",
        title: "User Deleted",
        text: "The selected user was successfully deleted"
      });
    }
  },
  created() {
    // Register Module UserManagement Module
    if (!moduleUserManagement.isRegistered) {
      this.$store.registerModule("userManagement", moduleUserManagement);
      moduleUserManagement.isRegistered = true;
    }
    if (!moduleItemManagement.isRegistered) {
      console.log(this.$route.params);
      this.$store.registerModule("itemManagement", moduleItemManagement);
      moduleItemManagement.isRegistered = true;
    }
    const userId = this.$route.params.userId;
    this.fetch_item_data(this.$route.params.itemId);
  }
};
</script>
<style lang="scss">
#avatar-col {
  width: 10rem;
}

#page-user-view {
  table {
    td {
      vertical-align: top;
      min-width: 140px;
      padding-bottom: 0.8rem;
      word-break: break-all;
    }

    &:not(.permissions-table) {
      td {
        @media screen and (max-width: 370px) {
          display: block;
        }
      }
    }
  }
}

// #account-info-col-1 {
//   // flex-grow: 1;
//   width: 30rem !important;
//   @media screen and (min-width:1200px) {
//     & {
//       flex-grow: unset !important;
//     }
//   }
// }

@media screen and (min-width: 1201px) and (max-width: 1211px),
  only screen and (min-width: 636px) and (max-width: 991px) {
  #account-info-col-1 {
    width: calc(100% - 12rem) !important;
  }

  // #account-manage-buttons {
  //   width: 12rem !important;
  //   flex-direction: column;

  //   > button {
  //     margin-right: 0 !important;
  //     margin-bottom: 1rem;
  //   }
  // }
}
</style>
<style lang="scss" scpoped>
.ag-grid-cell-chip {
  &.vs-chip-success {
    background: rgba(var(--vs-success), 0.15);
    color: rgba(var(--vs-success), 1) !important;
    font-weight: 500;
  }
  &.vs-chip-warning {
    background: rgba(var(--vs-warning), 0.15);
    color: rgba(var(--vs-warning), 1) !important;
    font-weight: 500;
  }
  &.vs-chip-danger {
    background: rgba(var(--vs-danger), 0.15);
    color: rgba(var(--vs-danger), 1) !important;
    font-weight: 500;
  }
}
</style>