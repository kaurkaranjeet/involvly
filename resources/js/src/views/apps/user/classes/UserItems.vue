<!-- =========================================================================================
  File Name: UserList.vue
  Description: User List page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
  <div id="page-user-list">
    <vx-card
      ref="filterCard"
      title="Filters"
      class="user-list-filters mb-8"
      actionButtons
      @refresh="resetColFilters"
      @remove="resetColFilters"
    >
      <div class="vx-row">
        <div class="vx-col md:w-1/4 sm:w-1/2 w-full">
          <label class="text-sm opacity-75">Status</label>
          <v-select
            :options="statusOptions"
            :clearable="false"
            :dir="$vs.rtl ? 'rtl' : 'ltr'"
            v-model="statusFilter"
            class="mb-4 md:mb-0"
          />
        </div>
        <div class="vx-col md:w-1/4 sm:w-1/2 w-full">
          <label class="text-sm opacity-75">Stock Status</label>
          <v-select
            :options="stockOptions"
            :clearable="false"
            :dir="$vs.rtl ? 'rtl' : 'ltr'"
            v-model="stockFilter"
            class="mb-4 md:mb-0"
          />
        </div>
      </div>
    </vx-card>

    <div class="vx-card p-6">
      <div class="flex flex-wrap items-center">
        <!-- ITEMS PER PAGE -->
        <div class="flex-grow">
          <vs-dropdown vs-trigger-click class="cursor-pointer">
            <div
              class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"
            >
              <span
                class="mr-2"
              >{{ currentPage * paginationPageSize - (paginationPageSize - 1) }} - {{ usersData.length - currentPage * paginationPageSize > 0 ? currentPage * paginationPageSize : usersData.length }} of {{ usersData.length }}</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>
            <!-- <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button> -->
            <vs-dropdown-menu>
              <vs-dropdown-item @click="gridApi.paginationSetPageSize(10)">
                <span>10</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="gridApi.paginationSetPageSize(20)">
                <span>20</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="gridApi.paginationSetPageSize(25)">
                <span>25</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="gridApi.paginationSetPageSize(30)">
                <span>30</span>
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </div>

        <!-- TABLE ACTION COL-2: SEARCH & EXPORT AS CSV -->
        <vs-input
          class="sm:mr-4 mr-0 sm:w-auto w-full sm:order-normal order-3 sm:mt-0 mt-4"
          v-model="searchQuery"
          @input="updateSearchQuery"
          placeholder="Search..."
        />
        <!-- <vs-button class="mb-4 md:mb-0" @click="gridApi.exportDataAsCsv()">Export as CSV</vs-button> -->

        <!-- ACTION - DROPDOWN -->
        <vs-dropdown vs-trigger-click class="cursor-pointer">
          <div
            class="p-3 shadow-drop rounded-lg d-theme-dark-light-bg cursor-pointer flex items-end justify-center text-lg font-medium w-32"
          >
            <span class="mr-2 leading-none">Actions</span>
            <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
          </div>

          <vs-dropdown-menu>
            <vs-dropdown-item>
              <span class="flex items-center">
                <feather-icon icon="TrashIcon" svgClasses="h-4 w-4" class="mr-2" />
                <span>Delete</span>
              </span>
            </vs-dropdown-item>

            <vs-dropdown-item>
              <span class="flex items-center">
                <feather-icon icon="ArchiveIcon" svgClasses="h-4 w-4" class="mr-2" />
                <span>Archive</span>
              </span>
            </vs-dropdown-item>

            <vs-dropdown-item>
              <span class="flex items-center">
                <feather-icon icon="FileIcon" svgClasses="h-4 w-4" class="mr-2" />
                <span>Print</span>
              </span>
            </vs-dropdown-item>

            <vs-dropdown-item>
              <span class="flex items-center">
                <feather-icon icon="SaveIcon" svgClasses="h-4 w-4" class="mr-2" />
                <span>CSV</span>
              </span>
            </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>

      <!-- AgGrid Table -->
      <ag-grid-vue
        ref="agGridTable"
        :components="components"
        :gridOptions="gridOptions"
        class="ag-theme-material w-100 my-4 ag-grid-table"
        :columnDefs="columnDefs"
        :defaultColDef="defaultColDef"
        :rowData="usersData"
        rowSelection="multiple"
        colResizeDefault="shift"
        :animateRows="true"
        :floatingFilter="true"
        :pagination="true"
        :paginationPageSize="paginationPageSize"
        :suppressPaginationPanel="true"
        :enableRtl="$vs.rtl"
      ></ag-grid-vue>

      <vs-pagination :total="totalPages" :max="7" v-model="currentPage" />
    </div>
  </div>
</template>

<script>
import { AgGridVue } from "ag-grid-vue";
import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
import vSelect from "vue-select";

// Store Module
import moduleItemManagement from "@/store/item-management/moduleItemManagement.js";

// Cell Renderer
import CellRendererLink from "./cell-renderer/CellRendererLink.vue";
import CellRendererName from "./cell-renderer/CellRendererName.vue";
import CellRendererStatus from "./cell-renderer/CellRendererStatus.vue";
import CellRendererVerified from "./cell-renderer/CellRendererVerified.vue";

export default {
  components: {
    AgGridVue,
    vSelect,

    // Cell Renderer
    CellRendererLink,
    CellRendererName,
    CellRendererStatus,
    CellRendererVerified
  },
  data() {
    return {
      // Filter Options
      stockFilter: { label: "All", value: "all" },
      stockOptions: [
        { label: "All", value: "all" },
        { label: "Sold", value: "sold" },
        { label: "Unsold", value: "unsold" }
      ],

      statusFilter: { label: "All", value: "all" },
      statusOptions: [
        { label: "All", value: "all" },
        { label: "Active", value: "active" },
        { label: "Inactive", value: "inactive" }
      ],
      searchQuery: "",

      // AgGrid
      gridApi: null,
      gridOptions: {},
      defaultColDef: {
        sortable: true,
        resizable: true,
        suppressMenu: true
      },
      columnDefs: [
        {
          headerName: "ID",
          field: "id",
          width: 125,
          filter: true,
          checkboxSelection: true,
          headerCheckboxSelectionFilteredOnly: true,
          headerCheckboxSelection: true
        },
        {
          headerName: "Item Name",
          field: "item_name",
          filter: true,
          width: 210,
          cellRendererFramework: "CellRendererLink"
        },
        {
          headerName: "Location",
          field: "location",
          filter: true,
          width: 210,
          cellRendererFramework: "CellRendererName"
        },
        {
          headerName: "Price",
          field: "price",
          filter: true,
          width: 200
        },
        {
          headerName: "Quantity",
          field: "qty",
          filter: true,
          width: 225
        },
        {
          headerName: "SKU",
          field: "sku",
          filter: true,
          width: 150,
          cellRendererFramework: "CellRendererStatus"
        },
        {
          headerName: "Status",
          field: "status",
          filter: true,
          width: 150,
          cellRendererFramework: "CellRendererStatus"
        },
        {
          headerName: "Stock Status",
          field: "is_sold",
          filter: true,
          width: 150
        }
      ],

      // Cell Renderer Components
      components: {
        CellRendererLink,
        CellRendererStatus,
        CellRendererVerified
      }
    };
  },
  watch: {
    stockFilter(obj) {
      this.setColumnFilter("is_sold", obj.value);
    },
    statusFilter(obj) {
      this.setColumnFilter("status", obj.value);
    }
  },
  computed: {
    usersData() {
      console.log(this.$store.state);
      return this.$store.state.itemManagement.items;
    },
    paginationPageSize() {
      if (this.gridApi) return this.gridApi.paginationGetPageSize();
      else return 10;
    },
    totalPages() {
      if (this.gridApi) return this.gridApi.paginationGetTotalPages();
      else return 0;
    },
    currentPage: {
      get() {
        if (this.gridApi) return this.gridApi.paginationGetCurrentPage() + 1;
        else return 1;
      },
      set(val) {
        this.gridApi.paginationGoToPage(val - 1);
      }
    }
  },
  methods: {
    setColumnFilter(column, val) {
      const filter = this.gridApi.getFilterInstance(column);
      let modelObj = null;

      if (val !== "all") {
        modelObj = { type: "equals", filter: val };
      }

      filter.setModel(modelObj);
      this.gridApi.onFilterChanged();
    },
    resetColFilters() {
      // Reset Grid Filter
      this.gridApi.setFilterModel(null);
      this.gridApi.onFilterChanged();

      // Reset Filter Options
      this.roleFilter = this.statusFilter = this.isVerifiedFilter = this.departmentFilter = {
        label: "All",
        value: "all"
      };

      this.$refs.filterCard.removeRefreshAnimation();
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    }
  },
  mounted() {
    this.gridApi = this.gridOptions.api;

    /* =================================================================
      NOTE:
      Header is not aligned properly in RTL version of agGrid table.
      However, we given fix to this issue. If you want more robust solution please contact them at gitHub
    ================================================================= */
    if (this.$vs.rtl) {
      const header = this.$refs.agGridTable.$el.querySelector(
        ".ag-header-container"
      );
      header.style.left = `-${String(
        Number(header.style.transform.slice(11, -3)) + 9
      )}px`;
    }
  },
  created() {
    if (!moduleItemManagement.isRegistered) {
      this.$store.registerModule("itemManagement", moduleItemManagement);
      moduleItemManagement.isRegistered = true;
    }
    const userId = this.$route.params.userId;
    this.$store.dispatch("itemManagement/fetchItems", userId).catch(err => {
      console.error(err);
    });
  }
};
</script>

<style lang="scss">
#page-user-list {
  .user-list-filters {
    .vs__actions {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-58%);
    }
  }
}
</style>
