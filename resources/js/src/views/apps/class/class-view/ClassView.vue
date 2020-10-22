<!-- =========================================================================================
  File Name: ClassList.vue
  Description: Class List page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
    <div id="page-user-list">
        <div class="vx-row mb-2">
            <div class="vx-col w-full">
                <vs-input v-validate="'required'" data-vv-validate-on="blur" label-placeholder="Class Name" name="Class Name" v-model="class_name" class="w-full" readonly />
                <span class="text-danger text-xs">{{ errors.first("Class Name") }}</span>
            </div>
        </div>
        <div class="vx-row mb-2">
            <div class="vx-col w-full">
                <vs-input v-validate="'required'" data-vv-validate-on="blur" label-placeholder="Class Code" name="Class Code" placeholder="Class Code" v-model="class_code" class="w-full" readonly />
                <span class="text-danger text-xs">{{ errors.first("Class Code") }}</span>
            </div>
        </div>
        <div class="col-md-12 bg-light text-right pd-bt">
            <vs-button color="primary" type="filled" @click="addSubjectdata">Add New Subject</vs-button>
        </div>
        <vx-card ref="filterCard" title="Filters" class="user-list-filters mb-8" actionButtons @refresh="resetColFilters" @remove="resetColFilters" style="display: none">
            <div class="vx-row">
                <!--  <div class="vx-col md:w-1/4 sm:w-1/2 w-full">
              <label class="text-sm opacity-75">Role</label>
              <v-select :options="roleOptions" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'" v-model="roleFilter" class="mb-4 md:mb-0" />
            </div> -->
                <div class="vx-col md:w-1/4 sm:w-1/2 w-full">
                    <label class="text-sm opacity-75">Status</label>
                    <v-select :options="statusOptions" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'" v-model="statusFilter" class="mb-4 md:mb-0" />
                </div>
                <!-- <div class="vx-col md:w-1/4 sm:w-1/2 w-full">
              <label class="text-sm opacity-75">Verified</label>
              <v-select :options="isVerifiedOptions" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'" v-model="isVerifiedFilter" class="mb-4 sm:mb-0" />
            </div> -->
                <!--   <div class="vx-col md:w-1/4 sm:w-1/2 w-full">
              <label class="text-sm opacity-75">Department</label>
              <v-select :options="departmentOptions" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'" v-model="departmentFilter" />
            </div> -->
            </div>
        </vx-card>
    
        <div class="vx-card p-6">
    
            <div class="flex flex-wrap items-center">
    
                <!-- ITEMS PER PAGE -->
                <div class="flex-grow">
                    <vs-dropdown vs-trigger-click class="cursor-pointer">
                        <div class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
                            <span class="mr-2">{{ currentPage * paginationPageSize - (paginationPageSize - 1) }} - {{ usersData.length - currentPage * paginationPageSize > 0 ? currentPage * paginationPageSize : usersData.length }} of {{ usersData.length }}</span>
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
                <vs-input class="sm:mr-4 mr-0 sm:w-auto w-full sm:order-normal order-3 sm:mt-0 mt-4" v-model="searchQuery" @input="updateSearchQuery" placeholder="Search..." />
                <!-- <vs-button class="mb-4 md:mb-0" @click="gridApi.exportDataAsCsv()">Export as CSV</vs-button> -->
    
            </div>
    
    
           <div class="vx-row">
      <!-- CARD 9: DISPATCHED ORDERS -->
      <div class="vx-col w-full">
        <vx-card title="Subject Lists">
          <div slot="no-body" class="mt-4">
            <vs-table max-items="5" pagination :data="usersData" class="table-dark-inverted" >
              <template slot="thead">
                <vs-th>Id</vs-th>
                <vs-th>Name</vs-th>
                <vs-th>Assign/Re-assign Teacher</vs-th>  
               
              </template>

              <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].id">
                    <span>#{{data[indextr].id}}</span>
                  </vs-td>

                    <vs-td :data="data[indextr].subject_name">
                    <span>{{data[indextr].subject_name}}</span>
                  </vs-td>                                  
                  <vs-td>
                    <span class="flex items-center px-2 py-1 rounded"> 
                     <vs-button @click="assignedTeacherToClass($route.params.classId,data[indextr].id)"> Assign/Re-assign</vs-button>
                                        </span>
                 
                  </vs-td>
              
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </vx-card>
      </div>
    </div>
            
    
        </div>
    </div>
</template>

<script>
import { AgGridVue } from 'ag-grid-vue'
import '@sass/vuexy/extraComponents/agGridStyleOverride.scss'
import vSelect from 'vue-select'

// Store Module
import moduleClassManagement from '@/store/class-management/moduleClassManagement.js'

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
    data() {
        return {
            class_name: "",
            class_code: "",
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

            searchQuery: '',

            // AgGrid
            gridApi: null,
            gridOptions: {},
            defaultColDef: {
                sortable: true,
                resizable: true,
                suppressMenu: true
            },
            columnDefs: [{
                    headerName: 'ID',
                    field: 'id',
                    width: 125,
                    filter: true,
                    checkboxSelection: true,
                    headerCheckboxSelectionFilteredOnly: true,
                    headerCheckboxSelection: true
                },
                {
                    headerName: 'Subject Name',
                    field: 'subject_name',
                    filter: true,
                    width: 210,
                    cellRendererFramework: 'CellRendererLink'
                },

                 {
                    headerName: 'Assign/Reassign',
                    field: 'assign/reassign',
                    filter: false,
                    width: 210,
                   // cellRendererFramework: 'CellRendererLink'
                },
                // {
                //   headerName: 'Status',
                //   field: 'approved',
                //   filter: true,
                //   width: 150,
                //   cellRendererFramework: 'CellRendererStatus'
                // },
                /*{
                    headerName: 'Actions',
                    field: 'transactions',
                    width: 150,
                    cellRendererFramework: 'CellRendererActions'
                }*/
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
    watch: {
        roleFilter(obj) {
            this.setColumnFilter('role', obj.value)
        },
        statusFilter(obj) {
            this.setColumnFilter('status', obj.value)
        },
        isVerifiedFilter(obj) {
            const val = obj.value === 'all' ? 'all' : obj.value === 'yes' ? 'true' : 'false'
            this.setColumnFilter('is_verified', val)
        },
        departmentFilter(obj) {
            this.setColumnFilter('department', obj.value)
        }
    },
    computed: {

        usersData() {
            return this.$store.state.classManagement.subjects
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
        }
    },
    methods: {
        fetch_Class_data(classId) {
            this.$store.dispatch('classManagement/fetchClassCodeDetail', classId)
                .then(res => {
                    this.class_data = res.data.class
                    console.log(this.class_data)
                    this.class_name = this.class_data.class_name;
                    this.class_code = this.class_data.class_code;
                })
                .catch(err => {
                    if (err.response.status === 404) {
                        this.class_not_found = true
                        return
                    }
                    console.error(err)
                })
        },
        addSubjectdata() {
            this.$router
                .push(`/apps/class/subject-add/` + this.$route.params.classId)
                .catch(() => {});
        },
        assignedTeacherToClass(classId , subjectId){
           console.log("classId",classId) 
           console.log("subjectId",subjectId) 
           this.$router
                .push(`/apps/class/assign-teacher/` + classId + `/` + subjectId)
                .catch(() => {});
        },
        setColumnFilter(column, val) {
            const filter = this.gridApi.getFilterInstance(column)
            let modelObj = null

            if (val !== 'all') {
                modelObj = { type: 'equals', filter: val }
            }

            filter.setModel(modelObj)
            this.gridApi.onFilterChanged()
        },
        resetColFilters() {
            // Reset Grid Filter
            this.gridApi.setFilterModel(null)
            this.gridApi.onFilterChanged()

            // Reset Filter Options
            this.roleFilter = this.statusFilter = this.isVerifiedFilter = this.departmentFilter = { label: 'All', value: 'all' }

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
        if (this.$vs.rtl) {
            const header = this.$refs.agGridTable.$el.querySelector('.ag-header-container')
            header.style.left = `-${  String(Number(header.style.transform.slice(11, -3)) + 9)  }px`
        }
    },
    created() {
        if (!moduleClassManagement.isRegistered) {
            this.$store.registerModule('classManagement', moduleClassManagement)
            moduleClassManagement.isRegistered = true
        }
        this.fetch_Class_data(this.$route.params.classId)
        this.$store.dispatch('classManagement/fetchSubjects', this.$route.params.classId).catch(err => { console.error(err) })
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
.pd-bt{
  padding-bottom: 10px;
}
</style>
