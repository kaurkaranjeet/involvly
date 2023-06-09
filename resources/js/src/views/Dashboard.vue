<!-- =========================================================================================
  File Name: DashboardAnalytics.vue
  Description: Dashboard Analytics
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
  <div id="dashboard-analytics">
    <div class="vx-row">
      <!-- CARD 1: CONGRATS -->
      <div class="vx-col w-full lg:w-1/2 mb-base">
        <vx-card class="text-center greet-user" v-bind:style="styleObject">
          <!-- <img src="@assets/images/elements/decore-left.png" class="decore-left" alt="Decore Left" width="200" />
          <img src="@assets/images/elements/decore-right.png" class="decore-right" alt="Decore Right" width="175" /> -->
          <!-- <feather-icon icon="AwardIcon" class="p-6 mb-8 bg-primary inline-flex rounded-full text-white shadow"
            svgClasses="h-8 w-8" style="display: hidden;"> </feather-icon> -->

          <!-- content inside body(with padding) -->
          <vx-card__body class="vx-row" :style="welcomePadding" >
            <slot>
              <div class="wrapper">   
                <h4 class="mb-1 text-dark text-left demo-1"><img src="@assets/images/elements/Group.svg" alt="Group svg"
                    width="35px" /> </h4>

                <h4 class="mb-1 text-dark text-left demo-1">Welcome </h4>
                <h1 class="mb-4 text-left" style="margin-top:-10px;"><b class="h1-checkpoint">{{
                  checkpointReward.name
                }}</b><img src="@assets/images/elements/party.png" alt="Decore Right" width="40px" /></h1>
              </div>

            </slot>
          </vx-card__body>

        </vx-card>
      </div>

      <!-- CARD 2: SUBSCRIBERS GAINED 
          :chartData="subscribersGained.Studentseries"-->
      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base" @click="redirectschooladmins"
        style="cursor: pointer">

        <statistics-card-line 
          icon="EyeIcon" 
          :statistic="subscribersGained.students" 
          statisticTitle="School Admins"
          type="area"
          statisticImage="/images/elements/Group1.svg"
          CardBackground="'/images/elements/card1.png'"
          >      
        </statistics-card-line>
      </div>

      <!-- CARD 3: ORDER RECIEVED 
          :chartData="subscribersGained.Teacherseries"-->
      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base"  @click="redirectteacheradmins"
        style="cursor: pointer">

        <statistics-card-line 
          icon="UsersIcon" 
          :statistic="subscribersGained.teachers"
          statisticTitle="Independent Teachers" 
          color="warning" 
          statisticImage="/images/elements/indvidual.svg"
          CardBackground="'/images/elements/card2.png'"
          type="area"
          ></statistics-card-line>
      </div>
    </div>

    <div class="vx-row" style="display: none;">
      <!-- CARD 4: SESSION -->
      <div class="vx-col w-full md:w-1/2 mb-base">
        <vx-card>
          <div class="vx-row flex-col-reverse md:flex-col-reverse sm:flex-row lg:flex-row">
            <!-- LEFT COL -->
            <div class="vx-col w-full md:w-full sm:w-1/2 lg:w-1/2 xl:w-1/2 flex flex-col justify-between"
              v-if="salesBarSession.analyticsData">
              <div>
                <h2 class="mb-1 font-bold">{{ salesBarSession.analyticsData.session | k_formatter }}</h2>
                <span class="font-medium">Avg Sessions</span>
                <!-- Previous Data Comparison -->
                <p class="mt-2 text-xl font-medium">
                  <span :class="salesBarSession.analyticsData.comparison.result >= 0 ? 'text-success' : 'text-danger'">
                    <span v-if="salesBarSession.analyticsData.comparison.result > 0">+</span>
                    <span>{{ salesBarSession.analyticsData.comparison.result }}</span>
                  </span>
                  <span>vs</span>
                  <span>{{ salesBarSession.analyticsData.comparison.str }}</span>
                </p>
              </div>
              <vs-button icon-pack="feather" icon="icon-chevrons-right" icon-after
                class="shadow-md w-full lg:mt-0 mt-4">View Details</vs-button>
            </div>
            <!-- RIGHT COL -->
            <div
              class="vx-col w-full md:w-full sm:w-1/2 lg:w-1/2 xl:w-1/2 flex flex-col lg:mb-0 md:mb-base sm:mb-0 mb-base">
              <change-time-duration-dropdown class="self-end" />
              <vue-apex-charts type="bar" height="200" :options="analyticsData.salesBar.chartOptions"
                :series="salesBarSession.series" v-if="salesBarSession.series" />
            </div>
          </div>
          <vs-divider class="my-6"></vs-divider>
          <div class="vx-row">
            <div class="vx-col w-1/2 mb-3">
              <p>Goal: $100000</p>
              <vs-progress class="block mt-1" :percent="50" color="primary"></vs-progress>
            </div>
            <div class="vx-col w-1/2 mb-3">
              <p>Users: 100K</p>
              <vs-progress class="block mt-1" :percent="60" color="warning"></vs-progress>
            </div>
            <div class="vx-col w-1/2 mb-3">
              <p>Retention: 90%</p>
              <vs-progress class="block mt-1" :percent="70" color="danger"></vs-progress>
            </div>
            <div class="vx-col w-1/2 mb-3">
              <p>Duration: 1yr</p>
              <vs-progress class="block mt-1" :percent="90" color="success"></vs-progress>
            </div>
          </div>
        </vx-card>
      </div>

      <!-- CARD 5: SUPPORT TRACKER -->
      <div class="vx-col w-full md:w-1/2 lg:w-1/2 xl:w-1/2 mb-base">
        <vx-card title="Support Tracker">
          <!-- CARD ACTION -->
          <template slot="actions">
            <change-time-duration-dropdown />
          </template>

          <div slot="no-body" v-if="supportTracker.analyticsData">
            <div class="vx-row text-center">
              <!-- Open Tickets Heading -->
              <div
                class="vx-col w-full lg:w-1/5 md:w-full sm:w-1/5 flex flex-col justify-between mb-4 lg:order-first md:order-last sm:order-first order-last">
                <div class="lg:ml-6 lg:mt-6 md:mt-0 md:ml-0 sm:ml-6 sm:mt-6">
                  <h1 class="font-bold text-5xl">{{ supportTracker.analyticsData.openTickets }}</h1>
                  <small>Tickets</small>
                </div>
              </div>

              <!-- Chart -->
              <div
                class="vx-col w-full lg:w-4/5 md:w-full sm:w-4/5 justify-center mx-auto lg:mt-0 md:mt-6 sm:mt-0 mt-6">
                <vue-apex-charts type="radialBar" height="385"
                  :options="analyticsData.supportTrackerRadialBar.chartOptions" :series="supportTracker.series" />
              </div>
            </div>

            <!-- Support Tracker Meta Data -->
            <div class="flex flex-row justify-between px-8 pb-4 mt-4">
              <p class="text-center" v-for="(val, key) in supportTracker.analyticsData.meta" :key="key">
                <span class="block">{{ key }}</span>
                <span class="text-2xl font-semibold">{{ val }}</span>
              </p>
            </div>
          </div>
        </vx-card>
      </div>
    </div>

    <div class="vx-row" style="display: none;">
      <!-- CARD 6: Product Orders -->
      <div class="vx-col w-full lg:w-1/3 mb-base">
        <vx-card title="Product Orders">
          <!-- CARD ACTION -->
          <template slot="actions">
            <change-time-duration-dropdown />
          </template>

          <!-- Chart -->
          <div slot="no-body">
            <vue-apex-charts type="radialBar" height="420" :options="analyticsData.productOrdersRadialBar.chartOptions"
              :series="productsOrder.series" />
          </div>

          <ul>
            <li v-for="orderData in productsOrder.analyticsData" :key="orderData.orderType"
              class="flex mb-3 justify-between">
              <span class="flex items-center">
                <span class="inline-block h-4 w-4 rounded-full mr-2 bg-white border-3 border-solid"
                  :class="`border-${orderData.color}`"></span>
                <span class="font-semibold">{{ orderData.orderType }}</span>
              </span>
              <span>{{ orderData.counts }}</span>
            </li>
          </ul>
        </vx-card>
      </div>

      <!-- CARD 7: Sales Stats -->
      <!--  <div class="vx-col w-full lg:w-1/3 mb-base">
        <vx-card title="Sales Stats" subtitle="Last 6 Months">
          <template slot="actions">
            <feather-icon icon="MoreVerticalIcon" svgClasses="w-6 h-6 text-grey"></feather-icon>
          </template>
          <div class="flex">
            <span class="flex items-center">
              <div class="h-3 w-3 rounded-full mr-1 bg-primary"></div>
              <span>Sales</span>
            </span>
            <span class="flex items-center ml-4">
              <div class="h-3 w-3 rounded-full mr-1 bg-success"></div>
              <span>Visits</span>
            </span>
          </div>
          <div slot="no-body-bottom">
            <vue-apex-charts
              type="radar"
              height="385"
              :options="analyticsData.statisticsRadar.chartOptions"
              :series="salesRadar.series"
            />
          </div>
        </vx-card>
      </div>
 -->
      <!-- CARD 8: Activity Timeline -->
      <div class="vx-col w-full lg:w-1/3 mb-base">
        <vx-card title="Activity Timeline">
          <vx-timeline :data="timelineData" />
        </vx-card>
      </div>
    </div>

    <div class="vx-row">
      <!-- CARD 9: DISPATCHED ORDERS -->
      <div class="vx-col w-full">
        <vx-card title="Requests">
          <div slot="no-body" class="mt-4">
            <vs-tabs class="tab-action-btn-fill-conatiner">
              <vs-tab :label="'Independent Teachers (' + teacherCount + ')'">
                <div class="tab-text">
                  <vs-table max-items="5" :pagination="teacherinde" :data="teacherRequests" class="table-dark-inverted"
                    id="table">
                    <template slot="thead">
                      <vs-th>Id</vs-th>
                      <vs-th>Name</vs-th>
                      <vs-th>Email</vs-th>
                      <!--<vs-th>Approve</vs-th>  
                   <vs-th>Reject</vs-th>  -->
                      <vs-th>View Details</vs-th>

                    </template>

                    <template slot-scope="{data}">
                      <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                        <vs-td :data="data[indextr].id">
                          <span>#{{ data[indextr].id }}</span>
                        </vs-td>

                        <vs-td :data="data[indextr].name">
                          <span>{{ data[indextr].name }}</span>
                        </vs-td>

                        <vs-td :data="data[indextr].email">
                          <span>{{ data[indextr].email }}</span>
                        </vs-td>


                        <!-- <vs-td :data="data[indextr].status">
                    <span class="flex items-center px-2 py-1 rounded">
                    
                      
                     <div class="">
                      
             <vs-button @click="Approveteacher(data[indextr].id,$event)"> Approve</vs-button>
                     </div>
                   
                     
                     
                    </span>   
                  </vs-td>

                   <vs-td :data="data[indextr].status">
                    <span class="flex items-center px-2 py-1 rounded">
                    
                      
                     <div class="">
                      
             <vs-button class="bg-danger" @click="Rejectteacher(data[indextr].id,$event)"> Reject</vs-button>
                     </div>
                   
                     
                     
                    </span>
                  </vs-td>-->
                        <vs-td :data="data[indextr].date">
                          <router-link :to="'apps/admin/teacher-view/' + data[indextr].id">View Details</router-link>

                        </vs-td>

                      </vs-tr>
                    </template>
                  </vs-table>
                </div>
              </vs-tab>

              <vs-tab :label="'School Admins (' + inteacherCount + ')'">
                <div class="tab-text">

                  <vs-table max-items="5" :pagination="paginationteacher" :data="studentRequests"
                    class="table-dark-inverted" ref="table">
                    <template slot="thead">
                      <vs-th>Id</vs-th>
                      <vs-th>Name</vs-th>
                      <vs-th>Email</vs-th>
                      <vs-th>School Name</vs-th>
                      <vs-th>Approve</vs-th>
                      <vs-th>Reject</vs-th>
                      <vs-th>View Details</vs-th>
                    </template>

                    <template slot-scope="{data}">
                      <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                        <vs-td :data="data[indextr].id">
                          <span>#{{ data[indextr].id }}</span>
                        </vs-td>

                        <vs-td :data="data[indextr].name">
                          <span>{{ data[indextr].name }}</span>
                        </vs-td>

                        <vs-td :data="data[indextr].email">
                          <span>{{ data[indextr].email }}</span>
                        </vs-td>
                        <vs-td :data="data[indextr].email">
                          <span>{{ data[indextr].school_detail.school_name }}</span>
                        </vs-td>

                        <vs-td :data="data[indextr].status">
                          <span class="flex items-center px-2 py-1 rounded">


                            <div class="">

                              <vs-button @click="Approveteacher(data[indextr].id, $event)"> Approve</vs-button>
                            </div>



                          </span>
                        </vs-td>

                        <vs-td :data="data[indextr].status">
                          <span class="flex items-center px-2 py-1 rounded">


                            <div class="">

                              <vs-button class="bg-danger" @click="Rejectteacher(data[indextr].id, $event)"> Reject
                              </vs-button>
                            </div>



                          </span>
                        </vs-td>

                        <vs-td :data="data[indextr].date">
                          <router-link :to="'apps/user/schooladmin-view/' + data[indextr].id">View Details</router-link>

                        </vs-td>


                      </vs-tr>
                    </template>
                  </vs-table>
                </div>
              </vs-tab>

            </vs-tabs>
          </div>
        </vx-card>
      </div>
    </div>
  </div>
</template>

<script>
import VueApexCharts from "vue-apexcharts";
import StatisticsCardLine from "@/components/statistics-cards/StatisticsCardLine.vue";
import analyticsData from "./ui-elements/card/analyticsData.js";
import ChangeTimeDurationDropdown from "@/components/ChangeTimeDurationDropdown.vue";
import VxTimeline from "@/components/timeline/VxTimeline";

export default {
  data() {


    return {

      styleObject: {
        padding: "1.4rem"
      },
      welcomePadding:{
        padding: "1.8rem !important"
      },
      checkpointReward: {},
      subscribersGained: { "teachers": 0, "students": 0 },
      ordersRecevied: {},
      salesBarSession: {},
      supportTracker: {},
      productsOrder: {},
      salesRadar: {},

      timelineData: [
        {
          color: "primary",
          icon: "PlusIcon",
          title: "Client Meeting",
          desc: "Bonbon macaroon jelly beans gummi bears jelly lollipop apple",
          time: "25 mins Ago"
        },
        {
          color: "warning",
          icon: "MailIcon",
          title: "Email Newsletter",
          desc: "Cupcake gummi bears soufflé caramels candy",
          time: "15 Days Ago"
        },
        {
          color: "danger",
          icon: "UsersIcon",
          title: "Plan Webinar",
          desc: "Candy ice cream cake. Halvah gummi bears",
          time: "20 days ago"
        },
        {
          color: "success",
          icon: "LayoutIcon",
          title: "Launch Website",
          desc:
            "Candy ice cream cake. Halvah gummi bears Cupcake gummi bears soufflé caramels candy.",
          time: "25 days ago"
        },
        {
          color: "primary",
          icon: "TvIcon",
          title: "Marketing",
          desc: "Candy ice cream cake. Halvah gummi bears Cupcake gummi bears.",
          time: "28 days ago"
        }
      ],

      analyticsData,
      teacherRequests: [],
      inteacherCount: 0,
      paginationteacher: false,
      studentRequests: [],
      teacherCount: 0,
      teacherinde: false,
      parentRequests: []
    };
  },
  components: {
    VueApexCharts,
    StatisticsCardLine,
    ChangeTimeDurationDropdown,
    VxTimeline
  },
  created() {
    var x = localStorage.getItem('accessToken');
    //  User Reward Card
    const requestOptions = {

      headers: { 'Authorization': 'Bearer ' + x }
    };
    this.$http
      .get(`api/auth/user`, requestOptions).then(response => {

        //console.log('Authorization'+response.status)
        this.checkpointReward = response.data.user;
        console.log('user_id', response.data.user.id);

        // localStorage.setItem('user_id',response.data.user.id);
        //   localStorage.setItem('school_id',response.data.user.school_id);
      })
      .catch(error => {
        //   console.log(error);

        // localStorage.removeItem('userInfo')
        // auto logout if 401 response returned from api
        this.$store.state.auth.logout();
        // location.reload(true);

      });


    // Subscribers gained - Statistics
    this.$http
      .get("api/auth/get_total_records", requestOptions)
      .then(response => {
        this.subscribersGained = response.data;
      })
      .catch(error => {
        console.log(error);
      });

    this.GetStuRequests();
    this.GetRequests();
    this.GetparentRequests();

  },

  methods: {
    redirectschooladmins() {

      this.$router
        .push(`/apps/user/listofschooladmins`)
        .catch(() => { });
    },
    
    redirectteacheradmins()
    {
      this.$router
        .push(`/apps/user/allteachers`)
        .catch(() => { });
    },

    GetRequests() {
      var x = localStorage.getItem('accessToken');
      //  User Reward Card
      const requestOptions = {

        headers: { 'Authorization': 'Bearer ' + x }
      };
      this.$http
        .get("api/auth/teacher_requests", requestOptions)
        .then(response => {
          this.teacherRequests = response.data.data;
          this.teacherCount = response.data.count;
          if (this.teacherCount > 0) {
            this.teacherinde = true;
          }

        })
        .catch(error => {
          console.log(error);
        });
    },
    GetStuRequests() {
      var x = localStorage.getItem('accessToken');
      //  User Reward Card
      const requestOptions = {

        headers: { 'Authorization': 'Bearer ' + x }
      };
      this.$http
        .get("api/auth/web_school_admins", requestOptions)
        .then(response => {
          this.studentRequests = response.data.data;
          this.inteacherCount = response.data.count;
          if (this.inteacherCount > 0) {
            this.paginationteacher = true;
          }

        })
        .catch(error => {
          console.log(error);
        });
    },
    GetparentRequests() {
      /* var x = localStorage.getItem('accessToken');
          //  User Reward Card
          const requestOptions = {
              
              headers: { 'Authorization': 'Bearer '+x }
          };
             this.$http
            .get("api/auth/parent_requests/"+school_id,requestOptions)
            .then(response => {
              this.parentRequests = response.data.data;
            })
            .catch(error => {
              console.log(error);
            });*/
    },
    Approveteacher(id, el) {

      var x = localStorage.getItem('accessToken');
      //  User Reward Card
      const requestOptions = {

        headers: { 'Authorization': 'Bearer ' + x }
      };
      this.$http
        .post("api/auth/approve_teacher/" + id, requestOptions)
        .then(response => {
          this.$vs.notify({
            title: 'Approve',
            text: 'Approved Successfully',
            iconPack: 'feather',
            icon: 'icon-alert-circle',
            color: 'success'
          })

          const userIndex = this.teacherRequests.findIndex((u) => u.id === id)
          if (userIndex != "-1") {
            this.teacherRequests.splice(userIndex, 1);
            this.teacherCount = (this.teacherCount) - 1;
          }
          const userIndex2 = this.studentRequests.findIndex((u) => u.id === id)
          if (userIndex2 != "-1") {
            this.studentRequests.splice(userIndex2, 1);
            this.inteacherCount = (this.inteacherCount) - 1;
          }
        })
        .catch(error => {
          console.log(error);
        });




    },


    Rejectteacher(id, el) {

      var x = localStorage.getItem('accessToken');
      //  User Reward Card
      const requestOptions = {

        headers: { 'Authorization': 'Bearer ' + x }
      };
      this.$http
        .post("api/auth/disapprove_teacher/" + id, requestOptions)
        .then(response => {
          this.$vs.notify({
            title: 'Rejected',
            text: 'Rejected Successfully',
            iconPack: 'feather',
            icon: 'icon-alert-circle',
            color: 'success'
          })
          const userIndex = this.teacherRequests.findIndex((u) => u.id === id)
          if (userIndex != "-1") {
            this.teacherRequests.splice(userIndex, 1);
            this.teacherCount = (this.teacherCount) - 1;
          }
          const userIndex2 = this.studentRequests.findIndex((u) => u.id === id)
          if (userIndex2 != "-1") {
            this.studentRequests.splice(userIndex2, 1);
            this.inteacherCount = (this.inteacherCount) - 1;
          }
          // el.target.getElementsByClassName('tr-values').style.display='none';
          // el.parentNode.style.display='none';
        })
        .catch(error => {
          console.log(error);
        });

    }
  }
};
</script>

<style lang="scss">
/*! rtl:begin:ignore */
#dashboard-analytics {
  .greet-user {
    position: relative;

    .decore-left {
      position: absolute;
      left: 0;
      top: 0;
    }

    .decore-right {
      position: absolute;
      right: 0;
      top: 0;
    }
  }

  @media (max-width: 576px) {

    .decore-left,
    .decore-right {
      width: 140px;
    }
  }
}


.demo-1 {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  max-width: 1590px;
}

.vx-card .vx-card__collapsible-content img {
  display: unset;
}

.vs-tabs-primary .con-ul-tabs .activeChild button,
.vs-tabs-primary .con-ul-tabs button:not(:disabled):hover {
  color: #CADC4F !important;
  font-weight: 1000;
}

/*! rtl:end:ignore */
</style>
