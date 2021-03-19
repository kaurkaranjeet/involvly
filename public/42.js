(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[42],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _axios_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/axios.js */ "./resources/js/src/axios.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  components: {},
  data: function data() {
    return {
      current_password: "",
      new_password: "",
      confirm_password: ""
    };
  },
  watch: {// activeTab () {
    //   this.fetch_user_data(this.$route.params.userId)
    // }
  },
  computed: {
    validateForm: function validateForm() {
      this.$vs.loading.close();
      return !this.errors.any() && this.current_password !== "" && this.new_password !== "" && this.confirm_password !== "";
    }
  },
  methods: {
    changepassword: function changepassword() {
      var _this = this;

      var formData = new FormData();
      formData.append("user_id", localStorage.getItem('user_id'));
      formData.append("current_password", this.current_password);
      formData.append("new_password", this.new_password);
      formData.append("confirm_password", this.confirm_password); // If form is not validated return

      if (!this.validateForm) returns; // Loading

      this.$vs.loading();
      this.$store.dispatch("auth/changepassword", formData).then(function (res) {
        _this.$vs.loading.close();

        _this.$router.push("/")["catch"](function () {});

        _this.$vs.notify({
          color: "success",
          title: "Success",
          text: "Password changed successfully!"
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
    },
    reset_data: function reset_data() {
      this.current_password = '';
      this.new_password = '';
      this.confirm_password = '';
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=template&id=cf5b0458&":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=template&id=cf5b0458& ***!
  \*****************************************************************************************************************************************************************************************************************************/
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
                value: "required|min:6|max:10",
                expression: "'required|min:6|max:10'"
              }
            ],
            staticClass: "w-full mt-8",
            attrs: {
              type: "password",
              "data-vv-validate-on": "blur",
              "label-placeholder": "Current Password",
              name: "Current Password"
            },
            model: {
              value: _vm.current_password,
              callback: function($$v) {
                _vm.current_password = $$v
              },
              expression: "current_password"
            }
          }),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("Current Password")))
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
            staticClass: "w-full mt-8",
            attrs: {
              type: "password",
              "data-vv-validate-on": "blur",
              "label-placeholder": "New Password",
              name: "New Password"
            },
            model: {
              value: _vm.new_password,
              callback: function($$v) {
                _vm.new_password = $$v
              },
              expression: "new_password"
            }
          }),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("New Password")))
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
            staticClass: "w-full mt-8",
            attrs: {
              type: "password",
              "data-vv-validate-on": "blur",
              "label-placeholder": "Confirm Password",
              name: "Confirm Password"
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
            _vm._v(_vm._s(_vm.errors.first("Confirm Password")))
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
              on: { click: _vm.changepassword }
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

/***/ "./resources/js/src/views/apps/profile/ChangePassword.vue":
/*!****************************************************************!*\
  !*** ./resources/js/src/views/apps/profile/ChangePassword.vue ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ChangePassword_vue_vue_type_template_id_cf5b0458___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ChangePassword.vue?vue&type=template&id=cf5b0458& */ "./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=template&id=cf5b0458&");
/* harmony import */ var _ChangePassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ChangePassword.vue?vue&type=script&lang=js& */ "./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ChangePassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ChangePassword_vue_vue_type_template_id_cf5b0458___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ChangePassword_vue_vue_type_template_id_cf5b0458___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/apps/profile/ChangePassword.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ChangePassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ChangePassword.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ChangePassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=template&id=cf5b0458&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=template&id=cf5b0458& ***!
  \***********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChangePassword_vue_vue_type_template_id_cf5b0458___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ChangePassword.vue?vue&type=template&id=cf5b0458& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/profile/ChangePassword.vue?vue&type=template&id=cf5b0458&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChangePassword_vue_vue_type_template_id_cf5b0458___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChangePassword_vue_vue_type_template_id_cf5b0458___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);