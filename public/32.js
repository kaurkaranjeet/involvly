(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[32],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/Dashboard.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/Dashboard.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_apexcharts__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-apexcharts */ "./node_modules/vue-apexcharts/dist/vue-apexcharts.js");
/* harmony import */ var vue_apexcharts__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_apexcharts__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_statistics_cards_StatisticsCardLine_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/components/statistics-cards/StatisticsCardLine.vue */ "./resources/js/src/components/statistics-cards/StatisticsCardLine.vue");
/* harmony import */ var _ui_elements_card_analyticsData_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ui-elements/card/analyticsData.js */ "./resources/js/src/views/ui-elements/card/analyticsData.js");
/* harmony import */ var _components_ChangeTimeDurationDropdown_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @/components/ChangeTimeDurationDropdown.vue */ "./resources/js/src/components/ChangeTimeDurationDropdown.vue");
/* harmony import */ var _components_timeline_VxTimeline__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @/components/timeline/VxTimeline */ "./resources/js/src/components/timeline/VxTimeline.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//





/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      styleObject: {
        padding: "1.4rem"
      },
      checkpointReward: {},
      subscribersGained: {
        "teachers": 0,
        "students": 0
      },
      ordersRecevied: {},
      salesBarSession: {},
      supportTracker: {},
      productsOrder: {},
      salesRadar: {},
      timelineData: [{
        color: "primary",
        icon: "PlusIcon",
        title: "Client Meeting",
        desc: "Bonbon macaroon jelly beans gummi bears jelly lollipop apple",
        time: "25 mins Ago"
      }, {
        color: "warning",
        icon: "MailIcon",
        title: "Email Newsletter",
        desc: "Cupcake gummi bears soufflé caramels candy",
        time: "15 Days Ago"
      }, {
        color: "danger",
        icon: "UsersIcon",
        title: "Plan Webinar",
        desc: "Candy ice cream cake. Halvah gummi bears",
        time: "20 days ago"
      }, {
        color: "success",
        icon: "LayoutIcon",
        title: "Launch Website",
        desc: "Candy ice cream cake. Halvah gummi bears Cupcake gummi bears soufflé caramels candy.",
        time: "25 days ago"
      }, {
        color: "primary",
        icon: "TvIcon",
        title: "Marketing",
        desc: "Candy ice cream cake. Halvah gummi bears Cupcake gummi bears.",
        time: "28 days ago"
      }],
      analyticsData: _ui_elements_card_analyticsData_js__WEBPACK_IMPORTED_MODULE_2__["default"],
      teacherRequests: [],
      studentRequests: [],
      parentRequests: []
    };
  },
  components: {
    VueApexCharts: vue_apexcharts__WEBPACK_IMPORTED_MODULE_0___default.a,
    StatisticsCardLine: _components_statistics_cards_StatisticsCardLine_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    ChangeTimeDurationDropdown: _components_ChangeTimeDurationDropdown_vue__WEBPACK_IMPORTED_MODULE_3__["default"],
    VxTimeline: _components_timeline_VxTimeline__WEBPACK_IMPORTED_MODULE_4__["default"]
  },
  created: function created() {
    var _this = this;

    var x = localStorage.getItem('accessToken'); //  User Reward Card

    var requestOptions = {
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    this.$http.get("api/auth/user", requestOptions).then(function (response) {
      //console.log('Authorization'+response.status)
      _this.checkpointReward = response.data.user;
      console.log('user_id', response.data.user.id); // localStorage.setItem('user_id',response.data.user.id);
      //   localStorage.setItem('school_id',response.data.user.school_id);
    })["catch"](function (error) {
      //   console.log(error);
      // localStorage.removeItem('userInfo')
      // auto logout if 401 response returned from api
      _this.$store.state.auth.logout(); // location.reload(true);

    }); // Subscribers gained - Statistics

    this.$http.get("api/auth/get_total_records", requestOptions).then(function (response) {
      _this.subscribersGained = response.data;
    })["catch"](function (error) {
      console.log(error);
    });
    this.GetStuRequests();
    this.GetRequests();
    this.GetparentRequests();
  },
  methods: {
    GetRequests: function GetRequests() {
      var _this2 = this;

      var x = localStorage.getItem('accessToken'); //  User Reward Card

      var requestOptions = {
        headers: {
          'Authorization': 'Bearer ' + x
        }
      };
      this.$http.get("api/auth/teacher_requests/", requestOptions).then(function (response) {
        _this2.teacherRequests = response.data.data;
      })["catch"](function (error) {
        console.log(error);
      });
    },
    GetStuRequests: function GetStuRequests() {
      var _this3 = this;

      var x = localStorage.getItem('accessToken'); //  User Reward Card

      var requestOptions = {
        headers: {
          'Authorization': 'Bearer ' + x
        }
      };
      this.$http.get("api/auth/web_school_admins/", requestOptions).then(function (response) {
        _this3.studentRequests = response.data.data;
      })["catch"](function (error) {
        console.log(error);
      });
    },
    GetparentRequests: function GetparentRequests() {
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
    Approveteacher: function Approveteacher(id, el) {
      var _this4 = this;

      var x = localStorage.getItem('accessToken'); //  User Reward Card

      var requestOptions = {
        headers: {
          'Authorization': 'Bearer ' + x
        }
      };
      this.$http.post("api/auth/approve_teacher/" + id, requestOptions).then(function (response) {
        _this4.$vs.notify({
          title: 'Approve',
          text: 'Approved Successfully',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'success'
        }); // location.reload();


        _this4.GetStuRequests(); // el.target.getElementsByClassName('tr-values').style.display='none';
        // el.parentNode.style.display='none';

      })["catch"](function (error) {
        console.log(error);
      });
    },
    Rejectteacher: function Rejectteacher(id, el) {
      var _this5 = this;

      var x = localStorage.getItem('accessToken'); //  User Reward Card

      var requestOptions = {
        headers: {
          'Authorization': 'Bearer ' + x
        }
      };
      this.$http.post("api/auth/disapprove_teacher/" + id, requestOptions).then(function (response) {
        _this5.$vs.notify({
          title: 'Rejected',
          text: 'Rejected Successfully',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'success'
        });

        location.reload(); // el.target.getElementsByClassName('tr-values').style.display='none';
        // el.parentNode.style.display='none';
      })["catch"](function (error) {
        console.log(error);
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/Dashboard.vue?vue&type=style&index=0&lang=scss&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-2!./node_modules/sass-loader/dist/cjs.js??ref--8-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/Dashboard.vue?vue&type=style&index=0&lang=scss& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "#dashboard-analytics .greet-user {\n  position: relative;\n}\n#dashboard-analytics .greet-user .decore-left {\n  position: absolute;\n  left: 0;\n  top: 0;\n}\n#dashboard-analytics .greet-user .decore-right {\n  position: absolute;\n  right: 0;\n  top: 0;\n}\n@media (max-width: 576px) {\n#dashboard-analytics .decore-left,\n#dashboard-analytics .decore-right {\n    width: 140px;\n}\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/Dashboard.vue?vue&type=style&index=0&lang=scss&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-2!./node_modules/sass-loader/dist/cjs.js??ref--8-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/Dashboard.vue?vue&type=style&index=0&lang=scss& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--8-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--8-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./Dashboard.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/Dashboard.vue?vue&type=style&index=0&lang=scss&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/Dashboard.vue?vue&type=template&id=d9e5d64c&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/Dashboard.vue?vue&type=template&id=d9e5d64c& ***!
  \***********************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { attrs: { id: "dashboard-analytics" } }, [
    _c("div", { staticClass: "vx-row" }, [
      _c(
        "div",
        { staticClass: "vx-col w-full lg:w-1/2 mb-base" },
        [
          _c(
            "vx-card",
            {
              staticClass: "text-center bg-primary-gradient greet-user",
              style: _vm.styleObject,
              attrs: { slot: "no-body" },
              slot: "no-body"
            },
            [
              _c("img", {
                staticClass: "decore-left",
                attrs: {
                  src: __webpack_require__(/*! @assets/images/elements/decore-left.png */ "./resources/assets/images/elements/decore-left.png"),
                  alt: "Decore Left",
                  width: "200"
                }
              }),
              _vm._v(" "),
              _c("img", {
                staticClass: "decore-right",
                attrs: {
                  src: __webpack_require__(/*! @assets/images/elements/decore-right.png */ "./resources/assets/images/elements/decore-right.png"),
                  alt: "Decore Right",
                  width: "175"
                }
              }),
              _vm._v(" "),
              _c("feather-icon", {
                staticClass:
                  "p-6 mb-8 bg-primary inline-flex rounded-full text-white shadow",
                attrs: { icon: "AwardIcon", svgClasses: "h-8 w-8" }
              }),
              _vm._v(" "),
              _c("h1", { staticClass: "mb-6 text-white" }, [
                _vm._v("Welcome " + _vm._s(_vm.checkpointReward.name) + ",")
              ]),
              _vm._v(" "),
              _c(
                "p",
                {
                  staticClass:
                    "xl:w-3/4 lg:w-4/5 md:w-2/3 w-4/5 mx-auto text-white"
                },
                [_c("strong")]
              )
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass:
            "vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base"
        },
        [
          _c("statistics-card-line", {
            attrs: {
              icon: "UsersIcon",
              statistic: _vm.subscribersGained.students,
              statisticTitle: "School Admin Registered",
              chartData: _vm.subscribersGained.Studentseries,
              type: "area"
            }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass:
            "vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base"
        },
        [
          _c("statistics-card-line", {
            attrs: {
              icon: "UsersIcon",
              statistic: _vm.subscribersGained.teachers,
              statisticTitle: "Independent Teachers",
              chartData: _vm.subscribersGained.Teacherseries,
              color: "warning",
              type: "area"
            }
          })
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "vx-row", staticStyle: { display: "none" } }, [
      _c(
        "div",
        { staticClass: "vx-col w-full md:w-1/2 mb-base" },
        [
          _c(
            "vx-card",
            [
              _c(
                "div",
                {
                  staticClass:
                    "vx-row flex-col-reverse md:flex-col-reverse sm:flex-row lg:flex-row"
                },
                [
                  _vm.salesBarSession.analyticsData
                    ? _c(
                        "div",
                        {
                          staticClass:
                            "vx-col w-full md:w-full sm:w-1/2 lg:w-1/2 xl:w-1/2 flex flex-col justify-between"
                        },
                        [
                          _c("div", [
                            _c("h2", { staticClass: "mb-1 font-bold" }, [
                              _vm._v(
                                _vm._s(
                                  _vm._f("k_formatter")(
                                    _vm.salesBarSession.analyticsData.session
                                  )
                                )
                              )
                            ]),
                            _vm._v(" "),
                            _c("span", { staticClass: "font-medium" }, [
                              _vm._v("Avg Sessions")
                            ]),
                            _vm._v(" "),
                            _c(
                              "p",
                              { staticClass: "mt-2 text-xl font-medium" },
                              [
                                _c(
                                  "span",
                                  {
                                    class:
                                      _vm.salesBarSession.analyticsData
                                        .comparison.result >= 0
                                        ? "text-success"
                                        : "text-danger"
                                  },
                                  [
                                    _vm.salesBarSession.analyticsData.comparison
                                      .result > 0
                                      ? _c("span", [_vm._v("+")])
                                      : _vm._e(),
                                    _vm._v(" "),
                                    _c("span", [
                                      _vm._v(
                                        _vm._s(
                                          _vm.salesBarSession.analyticsData
                                            .comparison.result
                                        )
                                      )
                                    ])
                                  ]
                                ),
                                _vm._v(" "),
                                _c("span", [_vm._v("vs")]),
                                _vm._v(" "),
                                _c("span", [
                                  _vm._v(
                                    _vm._s(
                                      _vm.salesBarSession.analyticsData
                                        .comparison.str
                                    )
                                  )
                                ])
                              ]
                            )
                          ]),
                          _vm._v(" "),
                          _c(
                            "vs-button",
                            {
                              staticClass: "shadow-md w-full lg:mt-0 mt-4",
                              attrs: {
                                "icon-pack": "feather",
                                icon: "icon-chevrons-right",
                                "icon-after": ""
                              }
                            },
                            [_vm._v("View Details")]
                          )
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _c(
                    "div",
                    {
                      staticClass:
                        "vx-col w-full md:w-full sm:w-1/2 lg:w-1/2 xl:w-1/2 flex flex-col lg:mb-0 md:mb-base sm:mb-0 mb-base"
                    },
                    [
                      _c("change-time-duration-dropdown", {
                        staticClass: "self-end"
                      }),
                      _vm._v(" "),
                      _vm.salesBarSession.series
                        ? _c("vue-apex-charts", {
                            attrs: {
                              type: "bar",
                              height: "200",
                              options: _vm.analyticsData.salesBar.chartOptions,
                              series: _vm.salesBarSession.series
                            }
                          })
                        : _vm._e()
                    ],
                    1
                  )
                ]
              ),
              _vm._v(" "),
              _c("vs-divider", { staticClass: "my-6" }),
              _vm._v(" "),
              _c("div", { staticClass: "vx-row" }, [
                _c(
                  "div",
                  { staticClass: "vx-col w-1/2 mb-3" },
                  [
                    _c("p", [_vm._v("Goal: $100000")]),
                    _vm._v(" "),
                    _c("vs-progress", {
                      staticClass: "block mt-1",
                      attrs: { percent: 50, color: "primary" }
                    })
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "vx-col w-1/2 mb-3" },
                  [
                    _c("p", [_vm._v("Users: 100K")]),
                    _vm._v(" "),
                    _c("vs-progress", {
                      staticClass: "block mt-1",
                      attrs: { percent: 60, color: "warning" }
                    })
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "vx-col w-1/2 mb-3" },
                  [
                    _c("p", [_vm._v("Retention: 90%")]),
                    _vm._v(" "),
                    _c("vs-progress", {
                      staticClass: "block mt-1",
                      attrs: { percent: 70, color: "danger" }
                    })
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "vx-col w-1/2 mb-3" },
                  [
                    _c("p", [_vm._v("Duration: 1yr")]),
                    _vm._v(" "),
                    _c("vs-progress", {
                      staticClass: "block mt-1",
                      attrs: { percent: 90, color: "success" }
                    })
                  ],
                  1
                )
              ])
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "vx-col w-full md:w-1/2 lg:w-1/2 xl:w-1/2 mb-base" },
        [
          _c(
            "vx-card",
            { attrs: { title: "Support Tracker" } },
            [
              _c(
                "template",
                { slot: "actions" },
                [_c("change-time-duration-dropdown")],
                1
              ),
              _vm._v(" "),
              _vm.supportTracker.analyticsData
                ? _c("div", { attrs: { slot: "no-body" }, slot: "no-body" }, [
                    _c("div", { staticClass: "vx-row text-center" }, [
                      _c(
                        "div",
                        {
                          staticClass:
                            "vx-col w-full lg:w-1/5 md:w-full sm:w-1/5 flex flex-col justify-between mb-4 lg:order-first md:order-last sm:order-first order-last"
                        },
                        [
                          _c(
                            "div",
                            {
                              staticClass:
                                "lg:ml-6 lg:mt-6 md:mt-0 md:ml-0 sm:ml-6 sm:mt-6"
                            },
                            [
                              _c("h1", { staticClass: "font-bold text-5xl" }, [
                                _vm._v(
                                  _vm._s(
                                    _vm.supportTracker.analyticsData.openTickets
                                  )
                                )
                              ]),
                              _vm._v(" "),
                              _c("small", [_vm._v("Tickets")])
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        {
                          staticClass:
                            "vx-col w-full lg:w-4/5 md:w-full sm:w-4/5 justify-center mx-auto lg:mt-0 md:mt-6 sm:mt-0 mt-6"
                        },
                        [
                          _c("vue-apex-charts", {
                            attrs: {
                              type: "radialBar",
                              height: "385",
                              options:
                                _vm.analyticsData.supportTrackerRadialBar
                                  .chartOptions,
                              series: _vm.supportTracker.series
                            }
                          })
                        ],
                        1
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass:
                          "flex flex-row justify-between px-8 pb-4 mt-4"
                      },
                      _vm._l(_vm.supportTracker.analyticsData.meta, function(
                        val,
                        key
                      ) {
                        return _c(
                          "p",
                          { key: key, staticClass: "text-center" },
                          [
                            _c("span", { staticClass: "block" }, [
                              _vm._v(_vm._s(key))
                            ]),
                            _vm._v(" "),
                            _c(
                              "span",
                              { staticClass: "text-2xl font-semibold" },
                              [_vm._v(_vm._s(val))]
                            )
                          ]
                        )
                      }),
                      0
                    )
                  ])
                : _vm._e()
            ],
            2
          )
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "vx-row", staticStyle: { display: "none" } }, [
      _c(
        "div",
        { staticClass: "vx-col w-full lg:w-1/3 mb-base" },
        [
          _c(
            "vx-card",
            { attrs: { title: "Product Orders" } },
            [
              _c(
                "template",
                { slot: "actions" },
                [_c("change-time-duration-dropdown")],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { attrs: { slot: "no-body" }, slot: "no-body" },
                [
                  _c("vue-apex-charts", {
                    attrs: {
                      type: "radialBar",
                      height: "420",
                      options:
                        _vm.analyticsData.productOrdersRadialBar.chartOptions,
                      series: _vm.productsOrder.series
                    }
                  })
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "ul",
                _vm._l(_vm.productsOrder.analyticsData, function(orderData) {
                  return _c(
                    "li",
                    {
                      key: orderData.orderType,
                      staticClass: "flex mb-3 justify-between"
                    },
                    [
                      _c("span", { staticClass: "flex items-center" }, [
                        _c("span", {
                          staticClass:
                            "inline-block h-4 w-4 rounded-full mr-2 bg-white border-3 border-solid",
                          class: "border-" + orderData.color
                        }),
                        _vm._v(" "),
                        _c("span", { staticClass: "font-semibold" }, [
                          _vm._v(_vm._s(orderData.orderType))
                        ])
                      ]),
                      _vm._v(" "),
                      _c("span", [_vm._v(_vm._s(orderData.counts))])
                    ]
                  )
                }),
                0
              )
            ],
            2
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "vx-col w-full lg:w-1/3 mb-base" },
        [
          _c(
            "vx-card",
            { attrs: { title: "Activity Timeline" } },
            [_c("vx-timeline", { attrs: { data: _vm.timelineData } })],
            1
          )
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "vx-row" }, [
      _c(
        "div",
        { staticClass: "vx-col w-full" },
        [
          _c("vx-card", { attrs: { title: "Requests" } }, [
            _c(
              "div",
              {
                staticClass: "mt-4",
                attrs: { slot: "no-body" },
                slot: "no-body"
              },
              [
                _c(
                  "vs-tabs",
                  { staticClass: "tab-action-btn-fill-conatiner" },
                  [
                    _c("vs-tab", { attrs: { label: "Independent Teachers" } }, [
                      _c(
                        "div",
                        { staticClass: "tab-text" },
                        [
                          _c(
                            "vs-table",
                            {
                              staticClass: "table-dark-inverted",
                              attrs: {
                                "max-items": "5",
                                pagination: "",
                                data: _vm.teacherRequests
                              },
                              scopedSlots: _vm._u([
                                {
                                  key: "default",
                                  fn: function(ref) {
                                    var data = ref.data
                                    return _vm._l(data, function(tr, indextr) {
                                      return _c(
                                        "vs-tr",
                                        { key: indextr },
                                        [
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: { data: data[indextr].id }
                                            },
                                            [
                                              _c("span", [
                                                _vm._v(
                                                  "#" + _vm._s(data[indextr].id)
                                                )
                                              ])
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: {
                                                data: data[indextr].name
                                              }
                                            },
                                            [
                                              _c("span", [
                                                _vm._v(
                                                  _vm._s(data[indextr].name)
                                                )
                                              ])
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: {
                                                data: data[indextr].email
                                              }
                                            },
                                            [
                                              _c("span", [
                                                _vm._v(
                                                  _vm._s(data[indextr].email)
                                                )
                                              ])
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: {
                                                data: data[indextr].date
                                              }
                                            },
                                            [_c("span")]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: {
                                                data: data[indextr].status
                                              }
                                            },
                                            [
                                              _c(
                                                "span",
                                                {
                                                  staticClass:
                                                    "flex items-center px-2 py-1 rounded"
                                                },
                                                [
                                                  _c(
                                                    "div",
                                                    {},
                                                    [
                                                      _c(
                                                        "vs-button",
                                                        {
                                                          on: {
                                                            click: function(
                                                              $event
                                                            ) {
                                                              return _vm.Approveteacher(
                                                                data[indextr]
                                                                  .id,
                                                                $event
                                                              )
                                                            }
                                                          }
                                                        },
                                                        [_vm._v(" Approve")]
                                                      )
                                                    ],
                                                    1
                                                  )
                                                ]
                                              )
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: {
                                                data: data[indextr].status
                                              }
                                            },
                                            [
                                              _c(
                                                "span",
                                                {
                                                  staticClass:
                                                    "flex items-center px-2 py-1 rounded"
                                                },
                                                [
                                                  _c(
                                                    "div",
                                                    {
                                                      staticClass: "bg-danger"
                                                    },
                                                    [
                                                      _c(
                                                        "vs-button",
                                                        {
                                                          staticClass:
                                                            "bg-danger",
                                                          on: {
                                                            click: function(
                                                              $event
                                                            ) {
                                                              return _vm.Rejectteacher(
                                                                data[indextr]
                                                                  .id,
                                                                $event
                                                              )
                                                            }
                                                          }
                                                        },
                                                        [_vm._v(" Reject")]
                                                      )
                                                    ],
                                                    1
                                                  )
                                                ]
                                              )
                                            ]
                                          )
                                        ],
                                        1
                                      )
                                    })
                                  }
                                }
                              ])
                            },
                            [
                              _c(
                                "template",
                                { slot: "thead" },
                                [
                                  _c("vs-th", [_vm._v("Id")]),
                                  _vm._v(" "),
                                  _c("vs-th", [_vm._v("Name")]),
                                  _vm._v(" "),
                                  _c("vs-th", [_vm._v("Email")]),
                                  _vm._v(" "),
                                  _c("vs-th", [_vm._v("View Details")]),
                                  _vm._v(" "),
                                  _c("vs-th", [_vm._v("Approve")]),
                                  _vm._v(" "),
                                  _c("vs-th", [_vm._v("Reject")])
                                ],
                                1
                              )
                            ],
                            2
                          )
                        ],
                        1
                      )
                    ]),
                    _vm._v(" "),
                    _c("vs-tab", { attrs: { label: "School Admins" } }, [
                      _c(
                        "div",
                        { staticClass: "tab-text" },
                        [
                          _c(
                            "vs-table",
                            {
                              ref: "table",
                              staticClass: "table-dark-inverted",
                              attrs: {
                                "max-items": "5",
                                pagination: "",
                                data: _vm.studentRequests
                              },
                              scopedSlots: _vm._u([
                                {
                                  key: "default",
                                  fn: function(ref) {
                                    var data = ref.data
                                    return _vm._l(data, function(tr, indextr) {
                                      return _c(
                                        "vs-tr",
                                        { key: indextr },
                                        [
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: { data: data[indextr].id }
                                            },
                                            [
                                              _c("span", [
                                                _vm._v(
                                                  "#" + _vm._s(data[indextr].id)
                                                )
                                              ])
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: {
                                                data: data[indextr].name
                                              }
                                            },
                                            [
                                              _c("span", [
                                                _vm._v(
                                                  _vm._s(data[indextr].name)
                                                )
                                              ])
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: {
                                                data: data[indextr].email
                                              }
                                            },
                                            [
                                              _c("span", [
                                                _vm._v(
                                                  _vm._s(data[indextr].email)
                                                )
                                              ])
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: {
                                                data: data[indextr].date
                                              }
                                            },
                                            [_c("span")]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: {
                                                data: data[indextr].status
                                              }
                                            },
                                            [
                                              _c(
                                                "span",
                                                {
                                                  staticClass:
                                                    "flex items-center px-2 py-1 rounded"
                                                },
                                                [
                                                  _c(
                                                    "div",
                                                    {},
                                                    [
                                                      _c(
                                                        "vs-button",
                                                        {
                                                          on: {
                                                            click: function(
                                                              $event
                                                            ) {
                                                              return _vm.Approveteacher(
                                                                data[indextr]
                                                                  .id,
                                                                $event
                                                              )
                                                            }
                                                          }
                                                        },
                                                        [_vm._v(" Approve")]
                                                      )
                                                    ],
                                                    1
                                                  )
                                                ]
                                              )
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "vs-td",
                                            {
                                              attrs: {
                                                data: data[indextr].status
                                              }
                                            },
                                            [
                                              _c(
                                                "span",
                                                {
                                                  staticClass:
                                                    "flex items-center px-2 py-1 rounded"
                                                },
                                                [
                                                  _c(
                                                    "div",
                                                    {
                                                      staticClass: "bg-danger"
                                                    },
                                                    [
                                                      _c(
                                                        "vs-button",
                                                        {
                                                          staticClass:
                                                            "bg-danger",
                                                          on: {
                                                            click: function(
                                                              $event
                                                            ) {
                                                              return _vm.Rejectteacher(
                                                                data[indextr]
                                                                  .id,
                                                                $event
                                                              )
                                                            }
                                                          }
                                                        },
                                                        [_vm._v(" Reject")]
                                                      )
                                                    ],
                                                    1
                                                  )
                                                ]
                                              )
                                            ]
                                          )
                                        ],
                                        1
                                      )
                                    })
                                  }
                                }
                              ])
                            },
                            [
                              _c(
                                "template",
                                { slot: "thead" },
                                [
                                  _c("vs-th", [_vm._v("Id")]),
                                  _vm._v(" "),
                                  _c("vs-th", [_vm._v("Name")]),
                                  _vm._v(" "),
                                  _c("vs-th", [_vm._v("Email")]),
                                  _vm._v(" "),
                                  _c("vs-th", [_vm._v("View Details")]),
                                  _vm._v(" "),
                                  _c("vs-th", [_vm._v("Approve")]),
                                  _vm._v(" "),
                                  _c("vs-th", [_vm._v("Reject")])
                                ],
                                1
                              )
                            ],
                            2
                          )
                        ],
                        1
                      )
                    ])
                  ],
                  1
                )
              ],
              1
            )
          ])
        ],
        1
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/src/views/Dashboard.vue":
/*!**********************************************!*\
  !*** ./resources/js/src/views/Dashboard.vue ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Dashboard_vue_vue_type_template_id_d9e5d64c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=template&id=d9e5d64c& */ "./resources/js/src/views/Dashboard.vue?vue&type=template&id=d9e5d64c&");
/* harmony import */ var _Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=script&lang=js& */ "./resources/js/src/views/Dashboard.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Dashboard_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=style&index=0&lang=scss& */ "./resources/js/src/views/Dashboard.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Dashboard_vue_vue_type_template_id_d9e5d64c___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Dashboard_vue_vue_type_template_id_d9e5d64c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/Dashboard.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/Dashboard.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/src/views/Dashboard.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Dashboard.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/Dashboard.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/Dashboard.vue?vue&type=style&index=0&lang=scss&":
/*!********************************************************************************!*\
  !*** ./resources/js/src/views/Dashboard.vue?vue&type=style&index=0&lang=scss& ***!
  \********************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--8-2!../../../../node_modules/sass-loader/dist/cjs.js??ref--8-3!../../../../node_modules/vue-loader/lib??vue-loader-options!./Dashboard.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/Dashboard.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/src/views/Dashboard.vue?vue&type=template&id=d9e5d64c&":
/*!*****************************************************************************!*\
  !*** ./resources/js/src/views/Dashboard.vue?vue&type=template&id=d9e5d64c& ***!
  \*****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_template_id_d9e5d64c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Dashboard.vue?vue&type=template&id=d9e5d64c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/Dashboard.vue?vue&type=template&id=d9e5d64c&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_template_id_d9e5d64c___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_template_id_d9e5d64c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);