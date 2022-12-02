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
    <vx-card ref="filterCard" class="user-list-filters mb-8" actionButtons @refresh="resetColFilters"
      @remove="resetColFilters">
      <div class="vx-row">

        <div class="vx-col md:w-4/4 sm:w-1/2 w-full">
          <label class="text-sm opacity-75">Select Class</label>
          <v-select :options="classOptions" :clearable="false" v-model="isclassFilter" class="mb-4 md:mb-0" />
        </div>

        <div class="vx-col md:w-4/4 sm:w-1/2 w-full">
          <label class="text-sm opacity-75">Availability</label>
          <v-select :options="AvailOptions" :clearable="false" v-model="AvailFilter" class="mb-4 md:mb-0" />
        </div>
      </div>
      <div class="vx-row">

        <div class="vx-col md:w-4/4 sm:w-1/2 w-full">
          <label class="text-sm opacity-75">Location</label>
          <v-select :options="Locationoptions" :clearable="false" v-model="locationFilter" class="mb-4 md:mb-0"
            @input="getRecord" />
        </div>
        <div class="vx-col md:w-4/4 sm:w-1/2 w-full">
          <label class="text-sm opacity-75">Select Subject</label>
          <v-select :options="Subjectoptions" :clearable="false" v-model="subjectFilter" class="mb-4 md:mb-0"
            @input="getRecord" />
        </div>

      </div>
      <div class="vx-row">
        <div class="vx-col md:w-4/4 sm:w-1/2 w-full">
          <label class="text-sm opacity-75">Preferences</label>
          <v-select :options="PreferencesOption" :clearable="false" v-model="PreferencesFilter" class="mb-4 md:mb-0" />
        </div>
        <div class="vx-col md:w-4/4 sm:w-1/2 w-full text-right">
          <br />
          <vs-button color="primary" type="filled">Find a Teacher</vs-button>
        </div>
      </div>

    </vx-card>

    <div class="vx-card p-6 ">

      <div class="flex flex-wrap items-right ">
        <!-- ITEMS PER PAGE -->
        <!-- <div class="flex-grow">
          <vs-dropdown vs-trigger-click class="cursor-pointer">
            <div v-if="usersData.length == '0'"
              class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
              <span class="mr-2">0 - {{ usersData.length - currentPage * paginationPageSize > 0 ? currentPage *
                  paginationPageSize : usersData.length
              }} of {{ usersData.length }}</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>
            <div v-if="usersData.length != '0'"
              class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
              <span class="mr-2">{{ currentPage * paginationPageSize - (paginationPageSize - 1) }} - {{ usersData.length
                  - currentPage * paginationPageSize > 0 ? currentPage * paginationPageSize : usersData.length
              }} of {{
    usersData.length
}}</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>
            <div class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
              <span class="mr-2">{{ currentPage * paginationPageSize - (paginationPageSize - 1) }} - {{ usersData.length - currentPage * paginationPageSize > 0 ? currentPage * paginationPageSize : usersData.length }} of {{ usersData.length }}</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>
             <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button>
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
        </div> -->

        <!-- TABLE ACTION COL-2: SEARCH & EXPORT AS CSV -->
        <!-- <vs-input class="sm:mr-4 mr-0 sm:w-auto w-full sm:order-normal order-3 sm:mt-0 mt-4" v-model="searchQuery"
          @input="updateSearchQuery" placeholder="Search..." /> -->
        <!-- <vs-button class="mb-4 md:mb-0"  @click="searchQuery">Export as CSV</vs-button> -->

        <!-- ACTION - DROPDOWN -->
        <!-- <vs-dropdown vs-trigger-click class="cursor-pointer" style="display:none">

          <div
            class="p-3 shadow-drop rounded-lg d-theme-dark-light-bg cursor-pointer flex items-end justify-center text-lg font-medium w-32">
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
        </vs-dropdown> -->
      </div>

      <!-- AgGrid Table -->
      <ag-grid-vue ref="agGridTable" :components="components" :gridOptions="gridOptions"
        class="ag-theme-material w-100 my-4 ag-grid-table" :columnDefs="columnDefs" :defaultColDef="defaultColDef"
        :rowData="usersData" rowSelection="multiple" colResizeDefault="shift" :animateRows="true" :floatingFilter="true"
        :pagination="true" :paginationPageSize="paginationPageSize" :suppressPaginationPanel="true"
        :enableRtl="$vs.rtl">
      </ag-grid-vue>
      <vs-pagination :total="totalPages" :max="7" v-model="currentPage" />
    </div>
  </div>

</template>

<script>
import { AgGridVue } from 'ag-grid-vue'
import '@sass/vuexy/extraComponents/agGridStyleOverride.scss'
import vSelect from 'vue-select'

// Store Module
import moduleUserManagement from '@/store/user-management/moduleUserManagement.js'

// Cell Renderer
import CellRendererLink from './cell-renderer/CellRendererLink.vue'
import CellRendererStatus from './cell-renderer/CellRendererStatus.vue'
import CellRendererVerified from './cell-renderer/CellRendererVerified.vue'
import CellRendererActions from './cell-renderer/CellRendererActions.vue'
import CellRendererPlaceReq from './cell-renderer/CellRendererPlaceReq.vue'
import { exit } from 'process'




export default {
  components: {
    AgGridVue,
    vSelect,
    // Cell Renderer
    CellRendererLink,
    CellRendererStatus,
    CellRendererVerified,
    CellRendererActions,
    CellRendererPlaceReq,
  },
  data() {
    return {

      // Filter Options
      roleFilter: { label: 'All', value: 'all' },
      roleOptions: [
        { label: 'All', value: 'all' },
        { label: 'Admin', value: 'admin' },
        { label: 'User', value: 'user' },
        { label: 'Staff', value: 'staff' }
      ],
      statusFilter: { label: 'All', value: 'all' },
      statusOptions: [
        { label: 'All', value: 'all' },
        { label: 'Active', value: 'active' },
        { label: 'Deactivated', value: 'deactivated' },
        { label: 'Blocked', value: 'blocked' }
      ],
      isVerifiedFilter: { label: 'All', value: 'all' },
      isVerifiedOptions: [
        { label: 'All', value: 'all' },
        { label: 'Yes', value: 'yes' },
        { label: 'No', value: 'no' }
      ],
      departmentFilter: { label: 'All', value: 'all' },
      departmentOptions: [
        { label: 'All', value: 'all' },
        { label: 'Sales', value: 'sales' },
        { label: 'Development', value: 'development' },
        { label: 'Management', value: 'management' }
      ],


      // Class Options
      classOptions: [
        { label: 'All', value: 'all' },
      ],
      isclassFilter: { label: 'All', value: 'all', id: '0' },

      // Availabilty Options
      AvailFilter: {
        label: 'All', value: 'all', id: '0'
      },
      AvailOptions: [
        { label: 'All', value: 'all' },
        { label: 'Part time', value: '0' },
        { label: 'Full time', value: '1' },
      ],

      // Subject Options
      subjectFilter: { 
        label: 'All', value: 'all', id: '0' 
      },
      Subjectoptions: [
        { label: 'All', value: 'all' }
      ],
      
      // Location Options
      locationFilter: { 
        label: 'All', value: 'all', id: '0' 
      },
      Locationoptions: [
        { label: 'All', value: 'all' }
      ],

      // Preferences Options
      PreferencesFilter: {
        label: 'All', value: 'all', id: '0'
      },
      PreferencesOption: [
        { label: 'All', value: 'all' },
        { label: 'On-Site', value: '0' },
        { label: 'Remote', value: '1' },
      ],

      
      searchQuery: '',

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
          headerName: 'ID',
          field: 'id',
          width: 95,
          filter: true,
        },
        {
          headerName: 'PROFILE',
          filter: true,
          width: 125,
          checkboxSelection: false,
          headerCheckboxSelectionFilteredOnly: false,
          headerCheckboxSelection: false,
          cellRendererFramework: 'CellRendererLink'
        },
        {
          headerName: 'NAME',
          field: 'name',
          filter: true,
          width: 170
        },
        {
          headerName: 'LOCATION',
          field: 'location',
          filter: true,
          width: 170
        },

        {
          headerName: 'AVAILABILITY',
          field: 'availability',
          filter: true,
          width: 160,
          //cellRendererFramework: 'CellRendererStatus'
        },

        {
          headerName: 'PREFERENCES',
          field: 'preferences',
          filter: true,
          width: 160,
          //cellRendererFramework: 'CellRendererStatus'
        },
        {
          headerName: 'SUBJECTS',
          field: 'subject_id',
          filter: true,
          width: 150,
          //cellRendererFramework: 'CellRendererStatus'
        },
        {
          headerName: 'CLASSES',
          field: 'class_id',
          filter: true,
          width: 120,
          //cellRendererFramework: 'CellRendererStatus'
        },
        {
          headerName: 'RATE',
          field: 'hourly_rate',
          filter: true,
          width: 140,
          //cellRendererFramework: 'CellRendererStatus'
        },

        {
          headerName: 'ACTIONS',
          field: 'transactions',
          width: 200,
          cellRendererFramework: 'CellRendererPlaceReq'
        },

      ],

      // Cell Renderer Components
      components: {
        CellRendererLink,
        CellRendererStatus,
        CellRendererVerified,
        CellRendererActions,
        CellRendererPlaceReq,
      }
    }
  },
  watch: {
    roleFilter(obj) {
      //  this.setColumnFilter('role', obj.value)
    },
    isclassFilter(obj) {
      // this.setColumnFilter('class_codes', obj.value)
    },

    AvailFilter(obj) {
      this.setColumnFilter('availability', obj.value)
    },


    subjectFilter(obj) {
      // this.setColumnFilter('class_codes', obj.value)
    },

    statusFilter(obj) {
      // this.setColumnFilter('status', obj.value)
    },
    isVerifiedFilter(obj) {
      const val = obj.value === 'all' ? 'all' : obj.value === 'yes' ? 'true' : 'false'
      this.setColumnFilter('is_verified', val)
    },
    departmentFilter(obj) {
      //  this.setColumnFilter('department', obj.value)
    }
  },
  computed: {

    usersData() {
      // this.subjectonload();

      return this.$store.state.userManagement.users;

    },
    paginationPageSize() {
      if (this.gridApi) return this.gridApi.paginationGetPageSize()
      else return 10
    },
    totalPages() {
      if (this.gridApi) return this.gridApi.paginationGetTotalPages()
      else return 0
    },
    currentPage: {
      get() {
        if (this.gridApi) return this.gridApi.paginationGetCurrentPage() + 1
        else return 1
      },
      set(val) {
        this.gridApi.paginationGoToPage(val - 1)
      }
    },

  },
  methods: {

    getSubjects(a) {
      this.subjectFilter = { label: 'All', value: 'all' };
      this.locationFilter = { label: 'All', value: 'all' };

      this.Subjectoptions = [{ label: 'All', value: 'all' }];
      this.Locationoptions = [{ label: 'All', value: 'all' }];

      let school_id = localStorage.getItem('school_id');
      let x = localStorage.getItem('accessToken');

      const requestOptions = {
        'school_id': school_id,
        headers: { 'Authorization': 'Bearer ' + x },

      };
      this.$http
        .post("/api/auth/manage-subjects/" + a.id, requestOptions)
        .then(response => {
          var data = response.data.subjects;
          for (var index in data) {
            let newobj = {}
            newobj.label = data[index].subject_name;
            newobj.value = data[index].id;
            this.Subjectoptions.push(newobj);
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    // function to use append subjects data.
    getSubjectss(response) {
      for (var index in response) {
        let newobj = {}
        newobj.label = response[index].subject_name;
        newobj.value = response[index].id;
        this.Subjectoptions.push(newobj);
      }
    },
    // function to use append location data.
    getLocation(response) {
      for (var index in response) {
        let newobj = {}
        newobj.label = response[index].county;
        newobj.value = response[index].id;
        this.Locationoptions.push(newobj);
      }
    },
    getRecord(a) {
    },

    setColumnFilter(column, val) {
      const filter = this.gridApi.getFilterInstance(column)
      let modelObj = null

      if (val !== 'all') {
        modelObj = { type: 'contains', filter: val }
      }

      filter.setModel(modelObj)
      this.gridApi.onFilterChanged()
    },
    resetColFilters() {
      // Reset Grid Filter
      this.gridApi.setFilterModel(null)
      this.gridApi.onFilterChanged()

      // Reset Filter Options
      this.roleFilter = this.statusFilter = this.isVerifiedFilter = this.departmentFilter = this.isclassFilter = { label: 'All', value: 'all' }

      this.$refs.filterCard.removeRefreshAnimation()
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val)
    }
  },
  mounted() {
    this.gridApi = this.gridOptions.api

    /* =================================================================
      NOTE:
      Header is not aligned properly in RTL version of agGrid table.
      However, we given fix to this issue. If you want more robust solution please contact them at gitHub
    ================================================================= */
    /*if (this.$vs.rtl) {
      const header = this.$refs.agGridTable.$el.querySelector('.ag-header-container')
      header.style.left = `-${  String(Number(header.style.transform.slice(11, -3)) + 9)  }px`
    }*/
  },
  created() {
    if (!moduleUserManagement.isRegistered) {
      this.$store.registerModule('userManagement', moduleUserManagement)
      moduleUserManagement.isRegistered = true
    }
    this.$store.dispatch('userManagement/fetchSearch').catch(err => { console.error(err) })

    // +++++++++++++++ Fetch Location List in Select++++++++++++++++++++++++

    let location_id = localStorage.getItem('school_id');
    let xsd = localStorage.getItem('accessToken');
    const requestLocOptions = {
      'location_id': location_id,
      headers: { 'Authorization': 'Bearer ' + x },
    };
    this.$http
      .post("/api/auth/select-location/1", requestLocOptions)
      .then(response => {
        var data = response.data.location;
        console.log(data);

        this.getLocation(data);
        // console.log(Subjectoptionss);
      }).catch(err => { console.error(err) })

    // +++++++++++++++ End Fetch Location List in Select+++++++++++++++++++++++

    // +++++++++++++++ Fetch subject List in Select++++++++++++++++++++++++

    let school_id = localStorage.getItem('school_id');
    let xd = localStorage.getItem('accessToken');
    const requestOptions2 = {
      'school_id': school_id,
      headers: { 'Authorization': 'Bearer ' + x },
    };
    this.$http
      .post("/api/auth/select-subjects/1", requestOptions2)
      .then(response => {
        // console.log(response);

        var data = response.data.subjects;
        // console.log(data);
        this.getSubjectss(data);
        // console.log(Subjectoptionss);
      }).catch(err => { console.error(err) })

    // +++++++++++++++ End Fetch subject List in Select+++++++++++++++++++++++



    var x = localStorage.getItem('accessToken');
    var user_id = localStorage.getItem('user_id');
    // console.warn('Help'+user_id);
    //  User Reward Card
    const requestOptions = {
      'type': 'teacher',
      headers: { 'Authorization': 'Bearer ' + x },

    };
    this.$http
      .get('/api/auth/manage-classes/' + user_id, requestOptions)
      .then(response => {
        var data = response.data.classes;
        for (var index in data) {
          let newobj = {}
          newobj.label = data[index].class_name;
          newobj.value = data[index].class_name;
          newobj.id = data[index].id;
          // console.log(newobj);

          this.classOptions.push(newobj);
        }
      })
      .catch(error => {
        console.log(error);
      });
  },


}
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

.vx-card__header {
  display: none !important;
}

[dir] .vs-button:not(.vs-radius):not(.includeIconOnly):not(.small):not(.large) {
  padding: 0.5rem 1rem;
}

.ag-header.ag-pivot-off {
  height: 50px !important;
  min-height: 0px !important;
}
</style>
