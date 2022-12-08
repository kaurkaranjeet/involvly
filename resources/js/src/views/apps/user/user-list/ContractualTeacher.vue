<!-- =========================================================================================
  File Name: ContractualTeacher.vue
  Description: Contractual Teacher List page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>

    <div id="page-user-list">
      <div class="col-md-12 bg-light text-right mb-5">
              <vs-button color="dark" type="filled" @click="addStudentdata">Find New Teachers</vs-button>
          </div>

  
      <div class="vx-card p-6">
  
        <div class="flex flex-wrap items-center">
             <!-- ACTION - DROPDOWN -->
            <vs-dropdown vs-trigger-click class="cursor-pointer" style="display:none">
  
              <div class="p-3 shadow-drop rounded-lg d-theme-dark-light-bg cursor-pointer flex items-end justify-center text-lg font-medium w-32">
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
          :enableRtl="$vs.rtl">
        </ag-grid-vue>
  
        <vs-pagination
          :total="totalPages"
          :max="7"
          v-model="currentPage" />
  
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
  
  
  export default {
    components: {
      AgGridVue,
      vSelect,
  
      // Cell Renderer
      CellRendererLink,
      CellRendererStatus,
      CellRendererVerified,
      CellRendererActions
    },
    data () {
      return {
   
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
          field: 'subject_pr',
          filter: true,
          width: 150,
          //cellRendererFramework: 'CellRendererStatus'
        },
        {
          headerName: 'CLASSES',
          field: 'class_name',
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
            headerName: 'Actions',
            field: 'transactions',
            width: 150,
            cellRendererFramework: 'CellRendererActions'
          },
  
         /* {
            headerName: 'Verified',
            field: 'is_verified',
            filter: true,
            width: 125,
            cellRendererFramework: 'CellRendererVerified',
            cellClass: 'hidden'
          },*/
        ],
  
        // Cell Renderer Components
        components: {
          CellRendererLink,
          CellRendererStatus,
          CellRendererVerified,
          CellRendererActions
        }
      }
    },
    computed: {
      usersData () {
        return this.$store.state.userManagement.users
      },
      paginationPageSize () {
        if (this.gridApi) return this.gridApi.paginationGetPageSize()
        else return 14
      },
      totalPages () {
        if (this.gridApi) return this.gridApi.paginationGetTotalPages()
        else return 0
      },
      currentPage: {
        get () {
          if (this.gridApi) return this.gridApi.paginationGetCurrentPage() + 1
          else return 1
        },
        set (val) {
          this.gridApi.paginationGoToPage(val - 1)
        }
      }
    },
    methods: {
      getRecord(a){
          var x = localStorage.getItem('accessToken');
          var school_id = localStorage.getItem('school_id');
          //  User Reward Card
          const requestOptions = {
              'subject_pr': a.value,
              'class_name': this.isclassFilter.id,
              'school_id': school_id,
              headers: { 'Authorization': 'Bearer ' + x },
          };
       this.$http
       .post("/api/auth/get_record",requestOptions)
       .then(response => {
     // this. rowDataresponse.data.users;
     })
       .catch(error => {
        console.log(error);
      });
     },
        addStudentdata() {
              this.$router
                  .push(`/apps/user/teacher-list`)
                  .catch(() => {});
          },
      setColumnFilter (column, val) {
        const filter = this.gridApi.getFilterInstance(column)
        let modelObj = null
  
        if (val !== 'all') {
          modelObj = { type: 'contains', filter: val }
        }
  
        filter.setModel(modelObj)
        this.gridApi.onFilterChanged()
      },
      resetColFilters () {
        // Reset Grid Filter
        this.gridApi.setFilterModel(null)
        this.gridApi.onFilterChanged()
  
        // Reset Filter Options
        this.roleFilter = this.statusFilter = this.isVerifiedFilter = this.departmentFilter =this.isclassFilter= { label: 'All', value: 'all' }
  
        this.$refs.filterCard.removeRefreshAnimation()
      },
      updateSearchQuery (val) {
        this.gridApi.setQuickFilter(val)
      }
    },
    mounted () {
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
    created () {
      if (!moduleUserManagement.isRegistered) {
        this.$store.registerModule('userManagement', moduleUserManagement)
        moduleUserManagement.isRegistered = true
      }
      this.$store.dispatch('userManagement/contractualUsers').catch(err => { console.error(err) })
      var x = localStorage.getItem('accessToken');
          var user_id = localStorage.getItem('user_id');
          //  User Reward Card
          const requestOptions = {
              'type': 'teacher',
              headers: { 'Authorization': 'Bearer ' + x },
  
          };
       this.$http
        .get('/api/auth/manage-classes/' + user_id, requestOptions)
        .then(response => {
          var data=response.data.classes;
          for ( var index in data ) {
           let newobj={}
           newobj.label=data[index].class_name;
           newobj.value=data[index].class_name;
           newobj.id=data[index].id;
          this.classOptions.push( newobj );
         }
        })
        .catch(error => {
          console.log(error);
        }); 
  
    }
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
  [dir] .vs-button:not(.vs-radius):not(.includeIconOnly):not(.small):not(.large) {
  padding: 0.8rem 1rem;
}
  .ag-header.ag-pivot-off {
  height: 50px !important;
  min-height: 0px !important;
}
.ag-grid-table {
  
      height: 625px !important;
   
  }
  </style>
  