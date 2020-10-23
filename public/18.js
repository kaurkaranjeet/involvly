(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[18],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/store/user-management/moduleUserManagement.js */ "./resources/js/src/store/user-management/moduleUserManagement.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
      user_data: null,
      user_not_found: false
    };
  },
  computed: {
    userAddress: function userAddress() {
      var str = '';

      for (var field in this.user_data.location) {
        str += "".concat(field, " ");
      }

      return str;
    }
  },
  methods: {
    confirmDeleteRecord: function confirmDeleteRecord() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Confirm Delete',
        text: "You are about to delete \"".concat(this.user_data.name, "\""),
        accept: this.deleteRecord,
        acceptText: 'Delete'
      });
    },
    deleteRecord: function deleteRecord() {
      var _this = this;

      /* Below two lines are just for demo purpose */
      // this.$router.push({name:'app-parent-list'})
      // this.showDeleteSuccess()

      /* UnComment below lines for enabling true flow if deleting user */
      this.$store.dispatch("userManagement/removeRecord", this.user_data.id).then(function () {
        _this.$router.push({
          name: 'list-admins-school'
        });

        _this.showDeleteSuccess();
      })["catch"](function (err) {
        console.error(err);
      });
    },
    showDeleteSuccess: function showDeleteSuccess() {
      this.$vs.notify({
        color: 'success',
        title: 'User Deleted',
        text: 'The selected user was successfully deleted'
      });
    },
    Approveteacher: function Approveteacher(id, el) {
      var _this2 = this;

      var x = localStorage.getItem('accessToken'); //  User Reward Card

      var requestOptions = {
        headers: {
          'Authorization': 'Bearer ' + x
        }
      };
      this.$http.post("/api/auth/approve_teacher/" + id, requestOptions).then(function (response) {
        _this2.$vs.notify({
          title: 'Approve',
          text: 'Approved Successfully',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'success'
        });

        location.reload(); // el.target.getElementsByClassName('tr-values').style.display='none';
        // el.parentNode.style.display='none';
      })["catch"](function (error) {
        console.log(error);
      });
    },
    Rejectteacher: function Rejectteacher(id, el) {
      var _this3 = this;

      var x = localStorage.getItem('accessToken'); //  User Reward Card

      var requestOptions = {
        headers: {
          'Authorization': 'Bearer ' + x
        }
      };
      this.$http.post("/api/auth/disapprove_teacher/" + id, requestOptions).then(function (response) {
        _this3.$vs.notify({
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
  },
  created: function created() {
    var _this4 = this;

    // Register Module UserManagement Module
    if (!_store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegistered) {
      this.$store.registerModule('userManagement', _store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"]);
      _store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegistered = true;
    }

    var userId = this.$route.params.userId;
    this.$store.dispatch('userManagement/fetchUser', userId).then(function (res) {
      _this4.user_data = res.data.data;
    })["catch"](function (err) {
      if (err.response.status === 404) {
        _this4.user_not_found = true;
        return;
      }

      console.error(err);
    });
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=style&index=0&lang=scss&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-2!./node_modules/sass-loader/dist/cjs.js??ref--8-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=style&index=0&lang=scss& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "#avatar-col {\n  width: 10rem;\n}\n#page-user-view table td {\n  vertical-align: top;\n  min-width: 140px;\n  word-break: break-all;\n}\n[dir] #page-user-view table td {\n  padding-bottom: 0.8rem;\n}\n@media screen and (max-width: 370px) {\n#page-user-view table:not(.permissions-table) td {\n    display: block;\n}\n}\n@media screen and (min-width: 1201px) and (max-width: 1211px), only screen and (min-width: 636px) and (max-width: 991px) {\n#account-info-col-1 {\n    width: calc(100% - 12rem) !important;\n}\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=style&index=0&lang=scss&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-2!./node_modules/sass-loader/dist/cjs.js??ref--8-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=style&index=0&lang=scss& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--8-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--8-3!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SchoolAdminsView.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=style&index=0&lang=scss&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=template&id=39197624&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=template&id=39197624& ***!
  \****************************************************************************************************************************************************************************************************************************/
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
  return _c(
    "div",
    { attrs: { id: "page-user-view" } },
    [
      _c(
        "vs-alert",
        {
          attrs: {
            color: "danger",
            title: "User Not Found",
            active: _vm.user_not_found
          },
          on: {
            "update:active": function($event) {
              _vm.user_not_found = $event
            }
          }
        },
        [
          _c("span", [
            _vm._v(
              "User record with id: " +
                _vm._s(_vm.$route.params.userId) +
                " not found. "
            )
          ]),
          _vm._v(" "),
          _c(
            "span",
            [
              _c("span", [_vm._v("Check ")]),
              _c(
                "router-link",
                {
                  staticClass: "text-inherit underline",
                  attrs: { to: { name: "page-user-list" } }
                },
                [_vm._v("All Users")]
              )
            ],
            1
          )
        ]
      ),
      _vm._v(" "),
      _vm.user_data
        ? _c(
            "div",
            { attrs: { id: "user-data" } },
            [
              _c(
                "vx-card",
                { staticClass: "mb-base", attrs: { title: "Account" } },
                [
                  _c("div", { staticClass: "vx-row" }, [
                    _c(
                      "div",
                      {
                        staticClass: "vx-col flex-1",
                        attrs: { id: "account-info-col-1" }
                      },
                      [
                        _c("table", [
                          _c("tr", [
                            _c("td", { staticClass: "font-semibold" }, [
                              _vm._v("Name")
                            ]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(_vm.user_data.name))])
                          ]),
                          _vm._v(" "),
                          _c("tr", [
                            _c("td", { staticClass: "font-semibold" }, [
                              _vm._v("Email")
                            ]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(_vm.user_data.email))])
                          ]),
                          _vm._v(" "),
                          _c("tr", [
                            _c("td", { staticClass: "font-semibold" }, [
                              _vm._v("City")
                            ]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(_vm.user_data.city))])
                          ]),
                          _vm._v(" "),
                          _c("tr", [
                            _vm.user_data.position != null
                              ? _c("td", { staticClass: "font-semibold" }, [
                                  _vm._v("Position")
                                ])
                              : _vm._e(),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(_vm.user_data.position))])
                          ])
                        ])
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass: "vx-col flex-1",
                        attrs: { id: "account-info-col-2" }
                      },
                      [
                        _c("table", [
                          _c("tr", [
                            _c("td", { staticClass: "font-semibold" }, [
                              _vm._v("Status")
                            ]),
                            _vm._v(" "),
                            _vm.user_data.status == 1
                              ? _c("td", [_vm._v(" ACTIVE")])
                              : _vm._e(),
                            _vm._v(" "),
                            _vm.user_data.status == 0
                              ? _c("td", [_vm._v(" INACTIVE")])
                              : _vm._e()
                          ]),
                          _vm._v(" "),
                          _vm.user_data.school_id > 0
                            ? _c("tr", [
                                _c("td", { staticClass: "font-semibold" }, [
                                  _vm._v("School Name")
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _vm._v(
                                    _vm._s(
                                      _vm.user_data.school_detail.school_name
                                    )
                                  )
                                ])
                              ])
                            : _vm._e(),
                          _vm._v(" "),
                          _c("tr", [
                            _c("td", { staticClass: "font-semibold" }, [
                              _vm._v("State")
                            ]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(_vm.user_data.state_name))])
                          ]),
                          _vm._v(" "),
                          _vm.user_data.is_document == 1
                            ? _c("tr", [
                                _c("td", { staticClass: "font-semibold" }, [
                                  _vm._v("Documents")
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "ul",
                                    { attrs: { id: "example-1" } },
                                    _vm._l(_vm.user_data.documents, function(
                                      item
                                    ) {
                                      return _c("li", { key: item.id }, [
                                        _c(
                                          "a",
                                          {
                                            attrs: {
                                              href: item.document_url,
                                              target: "_blank"
                                            }
                                          },
                                          [_vm._v(_vm._s(item.document_name))]
                                        )
                                      ])
                                    }),
                                    0
                                  )
                                ])
                              ])
                            : _vm._e()
                        ])
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass: "vx-col w-full flex",
                        attrs: { id: "account-manage-buttons" }
                      },
                      [
                        _c(
                          "vs-button",
                          {
                            staticClass: " mr-4",
                            attrs: {
                              type: "border",
                              color: "danger",
                              "icon-pack": "feather",
                              icon: "icon-trash"
                            },
                            on: { click: _vm.confirmDeleteRecord }
                          },
                          [_vm._v("Delete")]
                        ),
                        _vm._v(" "),
                        _c(
                          "vs-button",
                          {
                            staticClass: "bg-primary mr-4 flex",
                            on: {
                              click: function($event) {
                                return _vm.Approveteacher(
                                  _vm.user_data.id,
                                  $event
                                )
                              }
                            }
                          },
                          [_vm._v(" Approve")]
                        ),
                        _vm._v(" "),
                        _c(
                          "vs-button",
                          {
                            staticClass: "bg-danger mr-4 flex",
                            on: {
                              click: function($event) {
                                return _vm.Rejectteacher(
                                  _vm.user_data.id,
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
                  ])
                ]
              )
            ],
            1
          )
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/src/store/user-management/moduleUserManagement.js":
/*!************************************************************************!*\
  !*** ./resources/js/src/store/user-management/moduleUserManagement.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _moduleUserManagementState_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./moduleUserManagementState.js */ "./resources/js/src/store/user-management/moduleUserManagementState.js");
/* harmony import */ var _moduleUserManagementMutations_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./moduleUserManagementMutations.js */ "./resources/js/src/store/user-management/moduleUserManagementMutations.js");
/* harmony import */ var _moduleUserManagementActions_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./moduleUserManagementActions.js */ "./resources/js/src/store/user-management/moduleUserManagementActions.js");
/* harmony import */ var _moduleUserManagementGetters_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./moduleUserManagementGetters.js */ "./resources/js/src/store/user-management/moduleUserManagementGetters.js");
/*=========================================================================================
  File Name: moduleUserManagement.js
  Description: Calendar Module
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/




/* harmony default export */ __webpack_exports__["default"] = ({
  isRegistered: false,
  namespaced: true,
  state: _moduleUserManagementState_js__WEBPACK_IMPORTED_MODULE_0__["default"],
  mutations: _moduleUserManagementMutations_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  actions: _moduleUserManagementActions_js__WEBPACK_IMPORTED_MODULE_2__["default"],
  getters: _moduleUserManagementGetters_js__WEBPACK_IMPORTED_MODULE_3__["default"]
});

/***/ }),

/***/ "./resources/js/src/store/user-management/moduleUserManagementActions.js":
/*!*******************************************************************************!*\
  !*** ./resources/js/src/store/user-management/moduleUserManagementActions.js ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _axios_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/axios.js */ "./resources/js/src/axios.js");
/*=========================================================================================
  File Name: moduleCalendarActions.js
  Description: Calendar Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

/* harmony default export */ __webpack_exports__["default"] = ({
  // addItem({ commit }, item) {
  //   return new Promise((resolve, reject) => {
  //     axios.post("/api/data-list/products/", {item: item})
  //       .then((response) => {
  //         commit('ADD_ITEM', Object.assign(item, {id: response.data.id}))
  //         resolve(response)
  //       })
  //       .catch((error) => { reject(error) })
  //   })
  // },
  fetchUsers: function fetchUsers(_ref) {
    var commit = _ref.commit;
    var x = localStorage.getItem('accessToken');
    var school_id = localStorage.getItem('school_id'); //  User Reward Card

    var requestOptions = {
      'type': 'teacher',
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/manage-users/' + school_id, requestOptions).then(function (response) {
        console.log(response.data.users);
        commit('SET_USERS', response.data.users);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchStudents: function fetchStudents(_ref2) {
    var commit = _ref2.commit;
    var x = localStorage.getItem('accessToken');
    var school_id = localStorage.getItem('school_id'); //  User Reward Card

    var requestOptions = {
      'type': 'student',
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/manage-users/' + school_id, requestOptions).then(function (response) {
        console.log(response.data.users);
        commit('SET_USERS', response.data.users);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchParents: function fetchParents(_ref3) {
    var commit = _ref3.commit;
    var x = localStorage.getItem('accessToken');
    var school_id = localStorage.getItem('school_id'); //  User Reward Card

    var requestOptions = {
      'type': 'parents',
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/manage-users/' + school_id, requestOptions).then(function (response) {
        console.log(response.data.users);
        commit('SET_USERS', response.data.users);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchUser: function fetchUser(context, userId) {
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/auth/fetch-user/".concat(userId)).then(function (response) {
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  UpdateUser: function UpdateUser(_ref4, data) {
    var commit = _ref4.commit;
    console.log(data);
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/update-profile/", data).then(function (response) {
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  removeRecord: function removeRecord(_ref5, userId) {
    var commit = _ref5.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/auth/delete-user/".concat(userId)).then(function (response) {
        commit('REMOVE_RECORD', userId);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  //admin functions 
  fetchAdminUsers: function fetchAdminUsers(_ref6) {
    var commit = _ref6.commit;
    var x = localStorage.getItem('accessToken'); //  User Reward Card

    var requestOptions = {
      'type': 'teacher',
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/manage-admin-users/', requestOptions).then(function (response) {
        console.log(response.data.users);
        commit('SET_ADMIN_USERS', response.data.users);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchSchoolAdmins: function fetchSchoolAdmins(_ref7) {
    var commit = _ref7.commit;
    var x = localStorage.getItem('accessToken'); //  User Reward Card

    var requestOptions = {
      'type': 'school_admins',
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/manage-admin-users/', requestOptions).then(function (response) {
        console.log(response.data.users);
        commit('SET_SCHOOL_ADMINS', response.data.users);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchAdminStudents: function fetchAdminStudents(_ref8) {
    var commit = _ref8.commit;
    var x = localStorage.getItem('accessToken'); //  User Reward Card

    var requestOptions = {
      'type': 'student',
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/manage-admin-users/', requestOptions).then(function (response) {
        console.log(response.data.users);
        commit('SET_ADMIN_USERS', response.data.users);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchAdminParents: function fetchAdminParents(_ref9) {
    var commit = _ref9.commit;
    var x = localStorage.getItem('accessToken'); //  User Reward Card

    var requestOptions = {
      'type': 'parent',
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/manage-admin-users/', requestOptions).then(function (response) {
        console.log(response.data.users);
        commit('SET_ADMIN_USERS', response.data.users);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  }
});

/***/ }),

/***/ "./resources/js/src/store/user-management/moduleUserManagementGetters.js":
/*!*******************************************************************************!*\
  !*** ./resources/js/src/store/user-management/moduleUserManagementGetters.js ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*=========================================================================================
  File Name: moduleCalendarGetters.js
  Description: Calendar Module Getters
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
/* harmony default export */ __webpack_exports__["default"] = ({});

/***/ }),

/***/ "./resources/js/src/store/user-management/moduleUserManagementMutations.js":
/*!*********************************************************************************!*\
  !*** ./resources/js/src/store/user-management/moduleUserManagementMutations.js ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*=========================================================================================
  File Name: moduleCalendarMutations.js
  Description: Calendar Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
/* harmony default export */ __webpack_exports__["default"] = ({
  SET_USERS: function SET_USERS(state, users) {
    state.users = users;
  },
  REMOVE_RECORD: function REMOVE_RECORD(state, itemId) {
    var userIndex = state.users.findIndex(function (u) {
      return u.id === itemId;
    });
    state.users.splice(userIndex, 1);
  },
  SET_ADMIN_USERS: function SET_ADMIN_USERS(state, adminusers) {
    state.adminusers = adminusers;
  },
  SET_SCHOOL_ADMINS: function SET_SCHOOL_ADMINS(state, schooladmins) {
    state.school_admins = schooladmins;
  }
});

/***/ }),

/***/ "./resources/js/src/store/user-management/moduleUserManagementState.js":
/*!*****************************************************************************!*\
  !*** ./resources/js/src/store/user-management/moduleUserManagementState.js ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*=========================================================================================
  File Name: moduleCalendarState.js
  Description: Calendar Module State
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
/* harmony default export */ __webpack_exports__["default"] = ({
  users: [],
  adminusers: [],
  school_admins: []
});

/***/ }),

/***/ "./resources/js/src/views/apps/user/SchoolAdminsView.vue":
/*!***************************************************************!*\
  !*** ./resources/js/src/views/apps/user/SchoolAdminsView.vue ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SchoolAdminsView_vue_vue_type_template_id_39197624___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SchoolAdminsView.vue?vue&type=template&id=39197624& */ "./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=template&id=39197624&");
/* harmony import */ var _SchoolAdminsView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SchoolAdminsView.vue?vue&type=script&lang=js& */ "./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _SchoolAdminsView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SchoolAdminsView.vue?vue&type=style&index=0&lang=scss& */ "./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _SchoolAdminsView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SchoolAdminsView_vue_vue_type_template_id_39197624___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SchoolAdminsView_vue_vue_type_template_id_39197624___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/apps/user/SchoolAdminsView.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=script&lang=js&":
/*!****************************************************************************************!*\
  !*** ./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SchoolAdminsView.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--8-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--8-3!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SchoolAdminsView.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=template&id=39197624&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=template&id=39197624& ***!
  \**********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_template_id_39197624___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SchoolAdminsView.vue?vue&type=template&id=39197624& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/SchoolAdminsView.vue?vue&type=template&id=39197624&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_template_id_39197624___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SchoolAdminsView_vue_vue_type_template_id_39197624___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);