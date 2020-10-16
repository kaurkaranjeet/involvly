(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[10],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _store_class_management_moduleClassManagement_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/store/class-management/moduleClassManagement.js */ "./resources/js/src/store/class-management/moduleClassManagement.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
// Store Module

/* harmony default export */ __webpack_exports__["default"] = ({
  components: {},
  data: function data() {
    return {
      class_name: "",
      class_code: ""
    };
  },
  computed: {
    validateForm: function validateForm() {
      this.$vs.loading.close();
      return !this.errors.any() && this.class_name !== "" && this.class_code !== "";
    }
  },
  methods: {
    saveClassCode: function saveClassCode() {
      var _this = this;

      var code = {
        class_name: this.class_name,
        class_code: this.class_code
      }; // If form is not validated return

      if (!this.validateForm) returns; // Loading

      this.$vs.loading();
      this.$store.dispatch("classManagement/saveClassCode", code).then(function (res) {
        _this.$vs.loading.close();

        _this.$router.push("/apps/class/class-list")["catch"](function () {});

        _this.$vs.notify({
          color: "success",
          title: "Success",
          text: "Data add successfully!"
        });
      })["catch"](function (error) {
        _this.$vs.loading.close();

        _this.$vs.notify({
          title: "Error",
          text: error,
          iconPack: "feather",
          icon: "icon-alert-circle",
          color: "danger"
        });
      });
    }
  },
  watch: {
    activeTab: function activeTab() {
      this.fetch_user_data(this.$route.params.userId);
    }
  },
  created: function created() {
    if (!_store_class_management_moduleClassManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegistered) {
      this.$store.registerModule('classManagement', _store_class_management_moduleClassManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"]);
      _store_class_management_moduleClassManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegistered = true;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=template&id=0ade0866&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=template&id=0ade0866& ***!
  \*******************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { attrs: { id: "page-user-edit" } }, [
    _c("div", { staticClass: "vx-row mb-2" }, [
      _c(
        "div",
        { staticClass: "vx-col w-full" },
        [
          _c("vs-input", {
            directives: [
              {
                name: "validate",
                rawName: "v-validate",
                value: "required",
                expression: "'required'"
              }
            ],
            staticClass: "w-full",
            attrs: {
              "data-vv-validate-on": "blur",
              "label-placeholder": "Class Name",
              name: "Class Name"
            },
            model: {
              value: _vm.class_name,
              callback: function($$v) {
                _vm.class_name = $$v
              },
              expression: "class_name"
            }
          }),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("Class Name")))
          ])
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "vx-row mb-2" }, [
      _c(
        "div",
        { staticClass: "vx-col w-full" },
        [
          _c("vs-input", {
            directives: [
              {
                name: "validate",
                rawName: "v-validate",
                value: "required",
                expression: "'required'"
              }
            ],
            staticClass: "w-full",
            attrs: {
              "data-vv-validate-on": "blur",
              "label-placeholder": "Class Code",
              name: "Class Code",
              placeholder: "Class Code"
            },
            model: {
              value: _vm.class_code,
              callback: function($$v) {
                _vm.class_code = $$v
              },
              expression: "class_code"
            }
          }),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("Class Code")))
          ])
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
          _c(
            "vs-button",
            {
              staticClass: "mr-3 mb-2",
              attrs: { disabled: !_vm.validateForm },
              on: { click: _vm.saveClassCode }
            },
            [_vm._v("Submit")]
          ),
          _vm._v(" "),
          _c(
            "vs-button",
            {
              staticClass: "mb-2",
              attrs: { color: "warning", type: "border" },
              on: {
                click: function($event) {
                  _vm.class_name = _vm.class_code = ""
                  _vm.check5 = false
                }
              }
            },
            [_vm._v("Cancel")]
          )
        ],
        1
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/src/store/class-management/moduleClassManagement.js":
/*!**************************************************************************!*\
  !*** ./resources/js/src/store/class-management/moduleClassManagement.js ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _moduleClassManagementState_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./moduleClassManagementState.js */ "./resources/js/src/store/class-management/moduleClassManagementState.js");
/* harmony import */ var _moduleClassManagementMutations_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./moduleClassManagementMutations.js */ "./resources/js/src/store/class-management/moduleClassManagementMutations.js");
/* harmony import */ var _moduleClassManagementActions_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./moduleClassManagementActions.js */ "./resources/js/src/store/class-management/moduleClassManagementActions.js");
/* harmony import */ var _moduleClassManagementGetters_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./moduleClassManagementGetters.js */ "./resources/js/src/store/class-management/moduleClassManagementGetters.js");
/*=========================================================================================
  File Name: moduleUserManagement.js
  Description: Calendar Module
  ----------------------------------------------------------------------------------------
  Class Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/




/* harmony default export */ __webpack_exports__["default"] = ({
  isRegistered: false,
  namespaced: true,
  state: _moduleClassManagementState_js__WEBPACK_IMPORTED_MODULE_0__["default"],
  mutations: _moduleClassManagementMutations_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  actions: _moduleClassManagementActions_js__WEBPACK_IMPORTED_MODULE_2__["default"],
  getters: _moduleClassManagementGetters_js__WEBPACK_IMPORTED_MODULE_3__["default"]
});

/***/ }),

/***/ "./resources/js/src/store/class-management/moduleClassManagementActions.js":
/*!*********************************************************************************!*\
  !*** ./resources/js/src/store/class-management/moduleClassManagementActions.js ***!
  \*********************************************************************************/
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
  fetchClassCode: function fetchClassCode(_ref) {
    var commit = _ref.commit;
    var x = localStorage.getItem('accessToken');
    var user_id = localStorage.getItem('user_id'); //  User Reward Card

    var requestOptions = {
      'type': 'teacher',
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/manage-classes/' + user_id, requestOptions).then(function (response) {
        console.log(response.data.classes);
        commit('SET_CLASSES', response.data.classes);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  saveClassCode: function saveClassCode(_ref2, code) {
    var commit = _ref2.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/save-class-code", code).then(function (response) {
        if (response.data["class"]) {
          resolve(response);
        } else {
          reject(response.data.message);
        }
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  removeClassCode: function removeClassCode(_ref3, id) {
    var commit = _ref3.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"]["delete"]("/api/auth/delete-class-code/".concat(id)).then(function (response) {
        commit('REMOVE_RECORD', id);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchClassCodeDetail: function fetchClassCodeDetail(context, id) {
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/auth/fetch-class-detail/".concat(id)).then(function (response) {
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  editClassCode: function editClassCode(_ref4, code) {
    var commit = _ref4.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/edit-class-code", code).then(function (response) {
        console.log("reee", response);

        if (response) {
          resolve(response);
        } else {
          reject(response.data.message);
        }
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchAllItems: function fetchAllItems(_ref5) {
    var commit = _ref5.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/all-items").then(function (response) {
        commit('SET_ITEMS', response.data.response);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchItems: function fetchItems(_ref6, userId) {
    var commit = _ref6.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/view-items/".concat(userId)).then(function (response) {
        commit('SET_ITEMS', response.data.response);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchItem: function fetchItem(context, userId) {
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/item-user/".concat(userId)).then(function (response) {
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  getItem: function getItem(_ref7, itemId) {
    var commit = _ref7.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/get-item/".concat(itemId)).then(function (response) {
        commit('SET_ITEMS', response.data.response);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  saveItem: function saveItem(context, userId) {
    console.log(userId);
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/update-item/".concat(userId.id), userId).then(function (response) {
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  uploadImage: function uploadImage(context, userId) {
    console.log(userId);
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/update-image", userId).then(function (response) {
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  createItem: function createItem(context, userId) {
    console.log('userId');
    console.log(userId);
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/create-item", userId).then(function (response) {
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  removeRecord: function removeRecord(_ref8, ItemId) {
    var commit = _ref8.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"]["delete"]("/api/delete-item/".concat(ItemId)).then(function (response) {
        commit('REMOVE_RECORD', ItemId);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  }
});

/***/ }),

/***/ "./resources/js/src/store/class-management/moduleClassManagementGetters.js":
/*!*********************************************************************************!*\
  !*** ./resources/js/src/store/class-management/moduleClassManagementGetters.js ***!
  \*********************************************************************************/
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

/***/ "./resources/js/src/store/class-management/moduleClassManagementMutations.js":
/*!***********************************************************************************!*\
  !*** ./resources/js/src/store/class-management/moduleClassManagementMutations.js ***!
  \***********************************************************************************/
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
  // SET_ITEMS(state, items) {
  //     state.items = items
  // },
  SET_CLASSES: function SET_CLASSES(state, classes) {
    state.classes = classes;
  },
  REMOVE_RECORD: function REMOVE_RECORD(state, classId) {
    var classIndex = state.classes.findIndex(function (u) {
      return u.id === classId;
    });
    state.classes.splice(classIndex, 1);
  }
});

/***/ }),

/***/ "./resources/js/src/store/class-management/moduleClassManagementState.js":
/*!*******************************************************************************!*\
  !*** ./resources/js/src/store/class-management/moduleClassManagementState.js ***!
  \*******************************************************************************/
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
  classes: []
});

/***/ }),

/***/ "./resources/js/src/views/apps/class/class-add/ClassAdd.vue":
/*!******************************************************************!*\
  !*** ./resources/js/src/views/apps/class/class-add/ClassAdd.vue ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ClassAdd_vue_vue_type_template_id_0ade0866___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ClassAdd.vue?vue&type=template&id=0ade0866& */ "./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=template&id=0ade0866&");
/* harmony import */ var _ClassAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ClassAdd.vue?vue&type=script&lang=js& */ "./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ClassAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ClassAdd_vue_vue_type_template_id_0ade0866___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ClassAdd_vue_vue_type_template_id_0ade0866___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/apps/class/class-add/ClassAdd.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ClassAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ClassAdd.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ClassAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=template&id=0ade0866&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=template&id=0ade0866& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ClassAdd_vue_vue_type_template_id_0ade0866___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ClassAdd.vue?vue&type=template&id=0ade0866& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/class/class-add/ClassAdd.vue?vue&type=template&id=0ade0866&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ClassAdd_vue_vue_type_template_id_0ade0866___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ClassAdd_vue_vue_type_template_id_0ade0866___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);