(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[43],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/store/user-management/moduleUserManagement.js */ "./resources/js/src/store/user-management/moduleUserManagement.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-select */ "./node_modules/vue-select/dist/vue-select.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_select__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vee_validate__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vee-validate */ "./node_modules/vee-validate/dist/vee-validate.esm.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  components: {
    vueSelect: vue_select__WEBPACK_IMPORTED_MODULE_1___default.a
  },
  data: function data() {
    return {
      firstname: "",
      lastname: "",
      email: "",
      password: "",
      confirm_password: "",
      classes: "",
      stateFilteroption: [],
      cityoptions: [],
      classoptions: [],
      Subjectoptions: [],
      subjectFilter: {
        label: 'Select Subject',
        value: '0'
      },
      stateFilter: {
        label: 'Select State*',
        value: '0'
      },
      classFilter: {
        label: 'Select Class',
        value: '0'
      },
      cityFilter: {
        label: 'Select city*',
        value: '0'
      }
    };
  },
  computed: {
    validateForm: function validateForm() {
      //console.log(this.errors)
      this.$vs.loading.close();
      return !this.errors.any() && this.firstname !== '' && this.lastname !== '' && this.email !== '' && this.password !== '' && this.confirm_password !== '' && this.stateFilter.value !== '0' && this.cityFilter.value !== '0';
    }
  },
  methods: {
    reset_data: function reset_data() {
      this.firstname = '';
      this.lastname = '';
      this.email = '';
      this.password = '';
      this.confirm_password = '';
      this.Subjectoptions = [];
      this.classoptions = [];
      this.cityoptions = [];
      this.Subjectoptions = [];
      this.schooloptions = [];
      this.stateFilter = {
        label: 'Select State',
        value: '0'
      };
      this.cityFilter = {
        label: 'Select city',
        value: '0'
      };
      this.classFilter = {
        label: 'Select Class',
        value: '0'
      };
      this.subjectFilter = {
        label: 'Select Subject',
        value: '0'
      };
    },
    getCities: function getCities(a) {
      var _this = this;

      this.cityFilter = {
        label: 'Select city',
        value: ''
      };
      this.cityoptions = [];
      this.$http.post("/api/v1/get_cities", {
        state_id: a.value
      }).then(function (response) {
        var data = response.data.data;

        for (var index in data) {
          var newobj = {};
          newobj.label = data[index].city;
          newobj.value = data[index].id;

          _this.cityoptions.push(newobj);
        }
      })["catch"](function (error) {
        console.log(error);
      });
    },
    getSubjects: function getSubjects(a) {
      var _this2 = this;

      // this.subjectFilter= { label: 'Select Subject', value: '0' };
      this.Subjectoptions = [];
      this.$http.post("/api/auth/manage-subjects/" + a.value).then(function (response) {
        var data = response.data.subjects;

        for (var index in data) {
          var newobj = {};
          newobj.label = data[index].subject_name;
          newobj.value = data[index].id;

          _this2.Subjectoptions.push(newobj);
        }
      })["catch"](function (error) {
        console.log(error);
      });
    },
    SaveTeacher: function SaveTeacher() {
      var _this3 = this;

      var code = {
        first_name: this.firstname,
        last_name: this.lastname,
        email: this.email,
        password: this.password,
        type_of_schooling: 'school',
        country: 'United States',
        school_id: localStorage.getItem('school_id'),
        city_id: this.cityFilter.value,
        state_id: this.stateFilter.value,
        class_id: this.classFilter.value,
        role_id: 4,
        subject_id: this.subjectFilter.value
      }; // If form is not validated return

      if (!this.validateForm) return;

      if (this.classFilter.value != "") {
        if (this.subjectFilter.value = "") {
          alert("Please subject first");
          return false;
        }
      } // Loading


      this.$vs.loading();
      this.$store.dispatch("userManagement/SaveTeacher", code).then(function (res) {
        _this3.$vs.loading.close();

        if (res.data.error) {
          _this3.$vs.notify({
            title: "Error",
            text: res.data.message,
            iconPack: "feather",
            icon: "icon-alert-circle",
            color: "danger"
          });
        } else {
          _this3.$vs.notify({
            color: "success",
            title: "Success",
            text: "Teacher added successfully!",
            iconPack: "feather",
            icon: "icon-alert-circle"
          });

          _this3.$router.push("/apps/user/user-list")["catch"](function () {});
        }
      })["catch"](function (error) {
        _this3.$vs.loading.close();

        _this3.$vs.notify({
          title: "Error",
          text: error,
          iconPack: "feather",
          icon: "icon-alert-circle",
          color: "danger"
        });
      });
    }
  },
  created: function created() {
    var _this4 = this;

    if (!_store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegistered) {
      this.$store.registerModule('userManagement', _store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"]);
      _store_user_management_moduleUserManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegistered = true;
    }

    this.$http.get("/api/v1/list_states").then(function (response) {
      var data = response.data.data;

      for (var index in data) {
        var newobj = {};
        newobj.label = data[index].state_name;
        newobj.value = data[index].id;

        _this4.stateFilteroption.push(newobj);
      }
    })["catch"](function (error) {
      console.log(error);
    }); // Fetch class

    var x = localStorage.getItem('accessToken');
    var user_id = localStorage.getItem('user_id'); //  User Reward Card

    var requestOptions = {
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    this.$http.post('/api/auth/manage-classes/' + user_id, requestOptions).then(function (response) {
      var data = response.data.classes;

      for (var index in data) {
        var newobj = {};
        newobj.label = data[index].class_name;
        newobj.value = data[index].id;

        _this4.classoptions.push(newobj);
      }
    })["catch"](function (error) {
      console.log(error);
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=template&id=74243202&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=template&id=74243202& ***!
  \********************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "clearfix" },
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
          "label-placeholder": "First Name*",
          name: "firstname",
          placeholder: " First Name*",
          maxlength: 50
        },
        model: {
          value: _vm.firstname,
          callback: function($$v) {
            _vm.firstname = $$v
          },
          expression: "firstname"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-xs" }, [
        _vm._v(_vm._s(_vm.errors.first("firstname")))
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
        staticClass: "w-full",
        attrs: {
          "data-vv-validate-on": "blur",
          "label-placeholder": "Last Name*",
          name: "lastname",
          placeholder: "Last Name*"
        },
        model: {
          value: _vm.lastname,
          callback: function($$v) {
            _vm.lastname = $$v
          },
          expression: "lastname"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-xs" }, [
        _vm._v(_vm._s(_vm.errors.first("lastname")))
      ]),
      _vm._v(" "),
      _c("vs-input", {
        directives: [
          {
            name: "validate",
            rawName: "v-validate",
            value: "required|email",
            expression: "'required|email'"
          }
        ],
        staticClass: "w-full mt-6",
        attrs: {
          "data-vv-validate-on": "blur",
          name: "email",
          type: "email",
          "label-placeholder": "Email*",
          placeholder: "Email*"
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
        _vm._v(_vm._s(_vm.errors.first("email")))
      ]),
      _vm._v(" "),
      _c("vs-input", {
        directives: [
          {
            name: "validate",
            rawName: "v-validate",
            value: "required|min:6|max:10",
            expression: "'required|min:6|max:10'"
          }
        ],
        ref: "password",
        staticClass: "w-full mt-6",
        attrs: {
          type: "password",
          "data-vv-validate-on": "blur",
          name: "password",
          "label-placeholder": "Password*",
          placeholder: "Password*"
        },
        model: {
          value: _vm.password,
          callback: function($$v) {
            _vm.password = $$v
          },
          expression: "password"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-xs" }, [
        _vm._v(_vm._s(_vm.errors.first("password")))
      ]),
      _vm._v(" "),
      _c("vs-input", {
        directives: [
          {
            name: "validate",
            rawName: "v-validate",
            value: "min:6|max:10|confirmed:password",
            expression: "'min:6|max:10|confirmed:password'"
          }
        ],
        staticClass: "w-full mt-6",
        attrs: {
          type: "password",
          "data-vv-validate-on": "blur",
          "data-vv-as": "password",
          name: "confirm_password",
          "label-placeholder": "Confirm Password*",
          placeholder: "Confirm Password*"
        },
        model: {
          value: _vm.confirm_password,
          callback: function($$v) {
            _vm.confirm_password = $$v
          },
          expression: "confirm_password"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-xs" }, [
        _vm._v(_vm._s(_vm.errors.first("confirm_password")))
      ]),
      _vm._v(" "),
      _c("vue-select", {
        directives: [
          {
            name: "validate",
            rawName: "v-validate",
            value: "required",
            expression: "'required'"
          }
        ],
        staticClass: "w-full mt-6",
        attrs: {
          options: _vm.stateFilteroption,
          name: "state_id",
          clearable: false,
          "data-vv-validate-on": "change"
        },
        on: { input: _vm.getCities },
        model: {
          value: _vm.stateFilter,
          callback: function($$v) {
            _vm.stateFilter = $$v
          },
          expression: "stateFilter"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-sm" }, [
        _vm._v(_vm._s(_vm.errors.first("stateFilter")))
      ]),
      _vm._v(" "),
      _c("vue-select", {
        directives: [
          {
            name: "validate",
            rawName: "v-validate",
            value: "required",
            expression: "'required'"
          }
        ],
        staticClass: "w-full mt-6",
        attrs: {
          options: _vm.cityoptions,
          clearable: false,
          name: "city",
          "data-vv-validate-on": "change"
        },
        model: {
          value: _vm.cityFilter,
          callback: function($$v) {
            _vm.cityFilter = $$v
          },
          expression: "cityFilter"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-sm" }, [
        _vm._v(_vm._s(_vm.errors.first("cityFilter")))
      ]),
      _vm._v(" "),
      _c("vue-select", {
        staticClass: "w-full mt-6",
        attrs: { options: _vm.classoptions, clearable: false },
        on: { input: _vm.getSubjects },
        model: {
          value: _vm.classFilter,
          callback: function($$v) {
            _vm.classFilter = $$v
          },
          expression: "classFilter"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-sm" }, [
        _vm._v(_vm._s(_vm.errors.first("classFilter")))
      ]),
      _vm._v(" "),
      _c("vue-select", {
        staticClass: "w-full mt-6",
        attrs: { options: _vm.Subjectoptions, clearable: false },
        model: {
          value: _vm.subjectFilter,
          callback: function($$v) {
            _vm.subjectFilter = $$v
          },
          expression: "subjectFilter"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-sm" }, [
        _vm._v(_vm._s(_vm.errors.first("subjectFilter")))
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "vx-row" }, [
        _c("div", { staticClass: "vx-col w-full" }, [
          _c(
            "div",
            { staticClass: "mt-8 flex flex-wrap items-center justify-end" },
            [
              _c(
                "vs-button",
                {
                  staticClass: "mt-2",
                  attrs: { disabled: !_vm.validateForm },
                  on: { click: _vm.SaveTeacher }
                },
                [_vm._v("Submit")]
              ),
              _vm._v(" "),
              _c(
                "vs-button",
                {
                  staticClass: "ml-4 mt-2",
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
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/src/views/apps/user/user-list/TeacherAdd.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/src/views/apps/user/user-list/TeacherAdd.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _TeacherAdd_vue_vue_type_template_id_74243202___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TeacherAdd.vue?vue&type=template&id=74243202& */ "./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=template&id=74243202&");
/* harmony import */ var _TeacherAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TeacherAdd.vue?vue&type=script&lang=js& */ "./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _TeacherAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _TeacherAdd_vue_vue_type_template_id_74243202___WEBPACK_IMPORTED_MODULE_0__["render"],
  _TeacherAdd_vue_vue_type_template_id_74243202___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/apps/user/user-list/TeacherAdd.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TeacherAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./TeacherAdd.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TeacherAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=template&id=74243202&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=template&id=74243202& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TeacherAdd_vue_vue_type_template_id_74243202___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./TeacherAdd.vue?vue&type=template&id=74243202& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/user/user-list/TeacherAdd.vue?vue&type=template&id=74243202&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TeacherAdd_vue_vue_type_template_id_74243202___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TeacherAdd_vue_vue_type_template_id_74243202___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);