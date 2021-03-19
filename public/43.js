(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[43],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/store/user-management/moduleUserManagement.js */ "./resources/js/src/store/user-management/moduleUserManagement.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-select */ "./node_modules/vue-select/dist/vue-select.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_select__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _axios_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/axios.js */ "./resources/js/src/axios.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
 // import vueSelect from 'vue-select'



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    // vueSelect,
    vSelect: vue_select__WEBPACK_IMPORTED_MODULE_1___default.a
  },
  data: function data() {
    return {
      first_name: "",
      email: "",
      position: "",
      country: "",
      state: "",
      city: "",
      school: "",
      stateOptions: [],
      cityOptions: [],
      schoolOptions: [],
      activeTab: 0,
      profile_data: '',
      first_name_reset: "",
      // last_name_reset: "",
      email_reset: "",
      position_reset: "",
      country_reset: "",
      state_reset: "",
      city_reset: "",
      school_reset: "",
      state_val: "",
      city_val: "",
      school_val: "",
      state_id: "",
      city_id: "",
      school_id: "",
      role_id: ""
    };
  },
  watch: {// activeTab () {
    //   this.fetch_user_data(this.$route.params.userId)
    // }
  },
  computed: {
    validateForm: function validateForm() {
      this.$vs.loading.close();
      return !this.errors.any() && this.first_name !== "" && this.email !== "" && this.position !== "" && this.country !== "" && this.state !== "" && this.city !== "" && this.school !== "";
    }
  },
  methods: {
    getCities: function getCities(a) {
      var _this = this;

      this.city = {
        label: 'Select city',
        value: ''
      };
      this.$http.post("/api/v1/get_cities", {
        state_id: a.value
      }).then(function (response) {
        var data = response.data.data;

        for (var index in data) {
          var newobj = {};
          newobj.label = data[index].city;
          newobj.value = data[index].id;

          _this.cityOptions.push(newobj);
        }
      })["catch"](function (error) {
        console.log(error);
      });
    },
    getSchools: function getSchools(a) {
      var _this2 = this;

      this.$http.post("/api/v1/list_schools", {
        city_id: a.value
      }).then(function (response) {
        var data = response.data.data;

        for (var index in data) {
          var newobj = {};
          newobj.label = data[index].school_name;
          newobj.value = data[index].id;

          _this2.schoolOptions.push(newobj);
        }
      })["catch"](function (error) {
        console.log(error);
      });
    },
    fetch_user_data: function fetch_user_data(userId) {
      var _this3 = this;

      this.$store.dispatch("auth/fetchSchoolUser", userId).then(function (res) {
        _this3.profile_data = res.data.data;
        console.log(_this3.profile_data);
        _this3.first_name = _this3.profile_data.first_name;
        _this3.email = _this3.profile_data.email;
        _this3.position = _this3.profile_data.position;
        _this3.country = _this3.profile_data.country;
        _this3.state = _this3.profile_data.state_detail.state_name;
        _this3.state_id = _this3.profile_data.state_detail.id;
        _this3.city = _this3.profile_data.city_detail.city;
        _this3.city_id = _this3.profile_data.city_detail.id;
        _this3.school = _this3.profile_data.school_detail.school_name;
        _this3.school_id = _this3.profile_data.school_detail.id;
        _this3.role_id = localStorage.getItem('role_id'); //reset values

        _this3.first_name_reset = _this3.profile_data.first_name;
        _this3.email_reset = _this3.profile_data.email;
        _this3.position_reset = _this3.profile_data.position;
        _this3.country_reset = _this3.profile_data.country;
        _this3.state_reset = _this3.profile_data.state_detail.state_name;
        _this3.city_reset = _this3.profile_data.city_detail.city;
        _this3.school_reset = _this3.profile_data.school_detail.school_name;
      })["catch"](function (err) {
        console.error(err);
      });
    },
    editUser: function editUser() {
      var _this4 = this;

      if (this.state != null) {
        if (this.state.value == null) {
          this.state_val = this.state_id;
        } else {
          this.state_val = this.state.value;
        }
      } else {
        this.state_val = this.state.value;
      }

      if (this.city != null) {
        if (this.city.value == null) {
          this.city_val = this.city_id;
        } else {
          this.city_val = this.city.value;
        }
      } else {
        this.city_val = this.city.value;
      }

      if (this.school != null) {
        if (this.school.value == null) {
          this.school_val = this.school_id;
        } else {
          this.school_val = this.school.value;
        }
      } else {
        this.school_val = this.school.value;
      }

      var formData = new FormData();
      formData.append("user_id", localStorage.getItem('user_id'));
      formData.append("first_name", this.first_name); //  formData.append("last_name", this.last_name);

      formData.append("position", this.position);
      formData.append("country", this.country);
      formData.append("state", this.state_val);
      formData.append("city", this.city_val);
      formData.append("school", this.school_val); // If form is not validated return

      if (!this.validateForm) returns; // Loading

      this.$vs.loading();
      this.$store.dispatch("auth/UpdateUser", formData).then(function (res) {
        _this4.$vs.loading.close();

        _this4.$router.push("/")["catch"](function () {});

        _this4.$vs.notify({
          color: "success",
          title: "Success",
          text: "Admin details updated successfully!"
        });

        location.reload();
      })["catch"](function (error) {
        _this4.$vs.loading.close();

        _this4.$vs.notify({
          title: "Error",
          text: error,
          iconPack: "feather",
          icon: "icon-alert-circle",
          color: "danger"
        });
      });
    },
    reset_data: function reset_data() {
      this.name = this.name_reset;
      this.email = this.email_reset;
      this.position = this.position_reset;
      this.country = this.country_reset;
      this.state = this.state_reset;
      this.city = this.city_reset;
      this.school = this.school_reset;
    }
  },
  created: function created() {
    var _this5 = this;

    // Register Module UserManagement Module
    if (!_store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegistered) {
      this.$store.registerModule('UserManagement', _store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"]);
      _store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegistered = true;
    }

    this.fetch_user_data(localStorage.getItem('user_id'));
    this.$http.get("/api/v1/list_states").then(function (response) {
      var data = response.data.data;

      for (var index in data) {
        var newobj = {};
        newobj.label = data[index].state_name;
        newobj.value = data[index].id;

        _this5.stateOptions.push(newobj);
      }
    })["catch"](function (error) {
      console.log(error);
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=template&id=083bd3ba&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=template&id=083bd3ba& ***!
  \**************************************************************************************************************************************************************************************************************************/
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
            staticClass: "w-full mt-8",
            attrs: {
              "data-vv-validate-on": "blur",
              "label-placeholder": "Name",
              name: "Name",
              maxlength: 50
            },
            model: {
              value: _vm.first_name,
              callback: function($$v) {
                _vm.first_name = $$v
              },
              expression: "first_name"
            }
          }),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("Name")))
          ]),
          _vm._v(" "),
          _c("vs-input", {
            directives: [
              {
                name: "validate",
                rawName: "v-validate",
                value: "required",
                expression: "'required'"
              }
            ],
            staticClass: "w-full mt-8",
            attrs: {
              "data-vv-validate-on": "blur",
              "label-placeholder": "Email",
              name: "Email",
              readonly: ""
            },
            model: {
              value: _vm.email,
              callback: function($$v) {
                _vm.email = $$v
              },
              expression: "email"
            }
          }),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("Email")))
          ]),
          _vm._v(" "),
          this.role_id == "5"
            ? _c("vs-input", {
                directives: [
                  {
                    name: "validate",
                    rawName: "v-validate",
                    value: "required",
                    expression: "'required'"
                  }
                ],
                staticClass: "w-full mt-8",
                attrs: {
                  "data-vv-validate-on": "blur",
                  "label-placeholder": "Position",
                  name: "Position"
                },
                model: {
                  value: _vm.position,
                  callback: function($$v) {
                    _vm.position = $$v
                  },
                  expression: "position"
                }
              })
            : _vm._e(),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("Position")))
          ]),
          _vm._v(" "),
          this.role_id == "5"
            ? _c("vs-input", {
                staticClass: "w-full mt-8",
                attrs: {
                  "data-vv-validate-on": "blur",
                  "label-placeholder": "Country",
                  name: "Country",
                  readonly: ""
                },
                model: {
                  value: _vm.country,
                  callback: function($$v) {
                    _vm.country = $$v
                  },
                  expression: "country"
                }
              })
            : _vm._e(),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("Country")))
          ]),
          _vm._v(" "),
          this.role_id == "5"
            ? _c("vs-input", {
                directives: [
                  {
                    name: "validate",
                    rawName: "v-validate",
                    value: "required",
                    expression: "'required'"
                  }
                ],
                staticClass: "w-full mt-8",
                attrs: {
                  "data-vv-validate-on": "blur",
                  "label-placeholder": "State",
                  name: "State",
                  readonly: ""
                },
                model: {
                  value: _vm.state,
                  callback: function($$v) {
                    _vm.state = $$v
                  },
                  expression: "state"
                }
              })
            : _vm._e(),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("State")))
          ]),
          _vm._v(" "),
          this.role_id == "5"
            ? _c("vs-input", {
                directives: [
                  {
                    name: "validate",
                    rawName: "v-validate",
                    value: "required",
                    expression: "'required'"
                  }
                ],
                staticClass: "w-full mt-8",
                attrs: {
                  "data-vv-validate-on": "blur",
                  "label-placeholder": "City",
                  name: "City",
                  readonly: ""
                },
                model: {
                  value: _vm.city,
                  callback: function($$v) {
                    _vm.city = $$v
                  },
                  expression: "city"
                }
              })
            : _vm._e(),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("City")))
          ]),
          _vm._v(" "),
          this.role_id == "5"
            ? _c("vs-input", {
                directives: [
                  {
                    name: "validate",
                    rawName: "v-validate",
                    value: "required",
                    expression: "'required'"
                  }
                ],
                staticClass: "w-full mt-8",
                attrs: {
                  "data-vv-validate-on": "blur",
                  "label-placeholder": "School",
                  name: "School",
                  readonly: ""
                },
                model: {
                  value: _vm.school,
                  callback: function($$v) {
                    _vm.school = $$v
                  },
                  expression: "school"
                }
              })
            : _vm._e(),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("School")))
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
              on: { click: _vm.editUser }
            },
            [_vm._v("Update")]
          ),
          _vm._v(" "),
          _c(
            "vs-button",
            {
              staticClass: "mb-2",
              attrs: { color: "warning", type: "border" },
              on: { click: _vm.reset_data }
            },
            [_vm._v("Reset")]
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

/***/ "./resources/js/src/views/apps/profile/ProfileEdit.vue":
/*!*************************************************************!*\
  !*** ./resources/js/src/views/apps/profile/ProfileEdit.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProfileEdit_vue_vue_type_template_id_083bd3ba___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProfileEdit.vue?vue&type=template&id=083bd3ba& */ "./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=template&id=083bd3ba&");
/* harmony import */ var _ProfileEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProfileEdit.vue?vue&type=script&lang=js& */ "./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProfileEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProfileEdit_vue_vue_type_template_id_083bd3ba___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProfileEdit_vue_vue_type_template_id_083bd3ba___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/apps/profile/ProfileEdit.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfileEdit.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=template&id=083bd3ba&":
/*!********************************************************************************************!*\
  !*** ./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=template&id=083bd3ba& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileEdit_vue_vue_type_template_id_083bd3ba___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfileEdit.vue?vue&type=template&id=083bd3ba& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/profile/ProfileEdit.vue?vue&type=template&id=083bd3ba&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileEdit_vue_vue_type_template_id_083bd3ba___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileEdit_vue_vue_type_template_id_083bd3ba___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);