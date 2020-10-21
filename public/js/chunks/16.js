(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[16],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _store_subject_management_moduleSubjectManagement_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/store/subject-management/moduleSubjectManagement.js */ "./resources/js/src/store/subject-management/moduleSubjectManagement.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
      subject_name: "",
      activeTab: 0
    };
  },
  watch: {
    activeTab: function activeTab() {
      this.fetch_user_data(this.$route.params.userId);
    }
  },
  methods: {
    fetch_subject_data: function fetch_subject_data(subjectId) {
      var _this = this;

      this.$store.dispatch('subjectManagement/fetchSchoolSubjectDetail', subjectId).then(function (res) {
        _this.subject_data = res.data.subject;
        console.log(_this.subject_data);
        _this.subject_name = _this.subject_data.subject_name;
      }).catch(function (err) {
        console.error(err);
      });
    }
  },
  created: function created() {
    // Register Module UserManagement Module
    if (!_store_subject_management_moduleSubjectManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegistered) {
      this.$store.registerModule('SubjectManagement', _store_subject_management_moduleSubjectManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"]);
      _store_subject_management_moduleSubjectManagement_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegistered = true;
    }

    this.fetch_subject_data(localStorage.getItem('school_id'));
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=template&id=3f1d02ce&":
/*!***************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=template&id=3f1d02ce& ***!
  \***************************************************************************************************************************************************************************************************************************************/
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
              "label-placeholder": "Subject Name",
              name: "Subject Name",
              readonly: ""
            },
            model: {
              value: _vm.subject_name,
              callback: function($$v) {
                _vm.subject_name = $$v
              },
              expression: "subject_name"
            }
          }),
          _vm._v(" "),
          _c("span", { staticClass: "text-danger text-xs" }, [
            _vm._v(_vm._s(_vm.errors.first("Subject Name")))
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

/***/ "./resources/js/src/store/subject-management/moduleSubjectManagement.js":
/*!******************************************************************************!*\
  !*** ./resources/js/src/store/subject-management/moduleSubjectManagement.js ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _moduleSubjectManagementState_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./moduleSubjectManagementState.js */ "./resources/js/src/store/subject-management/moduleSubjectManagementState.js");
/* harmony import */ var _moduleSubjectManagementMutations_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./moduleSubjectManagementMutations.js */ "./resources/js/src/store/subject-management/moduleSubjectManagementMutations.js");
/* harmony import */ var _moduleSubjectManagementActions_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./moduleSubjectManagementActions.js */ "./resources/js/src/store/subject-management/moduleSubjectManagementActions.js");
/* harmony import */ var _moduleSubjectManagementGetters_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./moduleSubjectManagementGetters.js */ "./resources/js/src/store/subject-management/moduleSubjectManagementGetters.js");
/*=========================================================================================
  File Name: moduleSubjectManagement.js
  Description: Calendar Module
  ----------------------------------------------------------------------------------------
  Class Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/




/* harmony default export */ __webpack_exports__["default"] = ({
  isRegistered: false,
  namespaced: true,
  state: _moduleSubjectManagementState_js__WEBPACK_IMPORTED_MODULE_0__["default"],
  mutations: _moduleSubjectManagementMutations_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  actions: _moduleSubjectManagementActions_js__WEBPACK_IMPORTED_MODULE_2__["default"],
  getters: _moduleSubjectManagementGetters_js__WEBPACK_IMPORTED_MODULE_3__["default"]
});

/***/ }),

/***/ "./resources/js/src/store/subject-management/moduleSubjectManagementActions.js":
/*!*************************************************************************************!*\
  !*** ./resources/js/src/store/subject-management/moduleSubjectManagementActions.js ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_core_js_promise__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/core-js/promise */ "./node_modules/@babel/runtime/core-js/promise.js");
/* harmony import */ var _babel_runtime_core_js_promise__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_core_js_promise__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _axios_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/axios.js */ "./resources/js/src/axios.js");


/*=========================================================================================
  File Name: moduleCalendarActions.js
  Description: Calendar Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

/* harmony default export */ __webpack_exports__["default"] = ({
  fetchSchoolSubjects: function fetchSchoolSubjects(_ref, schoolId) {
    var commit = _ref.commit;
    return new _babel_runtime_core_js_promise__WEBPACK_IMPORTED_MODULE_0___default.a(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_1__["default"].post('/api/auth/manage-school-subjects/' + schoolId).then(function (response) {
        console.log(response.data.subjects);
        commit('SET_SCHOOL_SUBJECTS', response.data.subjects);
        resolve(response);
      }).catch(function (error) {
        reject(error);
      });
    });
  },
  saveSchoolSubject: function saveSchoolSubject(_ref2, code) {
    var commit = _ref2.commit;
    return new _babel_runtime_core_js_promise__WEBPACK_IMPORTED_MODULE_0___default.a(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_1__["default"].post("/api/auth/save-school-subject", code).then(function (response) {
        if (response.data.subject) {
          resolve(response);
        } else {
          reject(response.data.message);
        }
      }).catch(function (error) {
        reject(error);
      });
    });
  },
  removeSchoolSubject: function removeSchoolSubject(_ref3, id) {
    var commit = _ref3.commit;
    return new _babel_runtime_core_js_promise__WEBPACK_IMPORTED_MODULE_0___default.a(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_1__["default"].delete("/api/auth/delete-subject/".concat(id)).then(function (response) {
        commit('REMOVE_SCHOOL_SUBJECTS', id);
        resolve(response);
      }).catch(function (error) {
        reject(error);
      });
    });
  },
  fetchSchoolSubjectDetail: function fetchSchoolSubjectDetail(context, id) {
    return new _babel_runtime_core_js_promise__WEBPACK_IMPORTED_MODULE_0___default.a(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_1__["default"].get("/api/auth/fetch-subject-detail/".concat(id)).then(function (response) {
        resolve(response);
      }).catch(function (error) {
        reject(error);
      });
    });
  },
  editSchoolSubject: function editSchoolSubject(_ref4, code) {
    var commit = _ref4.commit;
    return new _babel_runtime_core_js_promise__WEBPACK_IMPORTED_MODULE_0___default.a(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_1__["default"].post("/api/auth/edit-subject", code).then(function (response) {
        if (response) {
          resolve(response);
        } else {
          reject(response.data.message);
        }
      }).catch(function (error) {
        reject(error);
      });
    });
  }
});

/***/ }),

/***/ "./resources/js/src/store/subject-management/moduleSubjectManagementGetters.js":
/*!*************************************************************************************!*\
  !*** ./resources/js/src/store/subject-management/moduleSubjectManagementGetters.js ***!
  \*************************************************************************************/
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

/***/ "./resources/js/src/store/subject-management/moduleSubjectManagementMutations.js":
/*!***************************************************************************************!*\
  !*** ./resources/js/src/store/subject-management/moduleSubjectManagementMutations.js ***!
  \***************************************************************************************/
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
  SET_SCHOOL_SUBJECTS: function SET_SCHOOL_SUBJECTS(state, schoolsubjects) {
    state.schoolsubjects = schoolsubjects;
  },
  REMOVE_SCHOOL_SUBJECTS: function REMOVE_SCHOOL_SUBJECTS(state, subjectId) {
    var subjectIndex = state.schoolsubjects.findIndex(function (u) {
      return u.id === subjectId;
    });
    state.schoolsubjects.splice(subjectIndex, 1);
  }
});

/***/ }),

/***/ "./resources/js/src/store/subject-management/moduleSubjectManagementState.js":
/*!***********************************************************************************!*\
  !*** ./resources/js/src/store/subject-management/moduleSubjectManagementState.js ***!
  \***********************************************************************************/
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
  schoolsubjects: []
});

/***/ }),

/***/ "./resources/js/src/views/apps/subject/subject-view/SubjectView.vue":
/*!**************************************************************************!*\
  !*** ./resources/js/src/views/apps/subject/subject-view/SubjectView.vue ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SubjectView_vue_vue_type_template_id_3f1d02ce___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SubjectView.vue?vue&type=template&id=3f1d02ce& */ "./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=template&id=3f1d02ce&");
/* harmony import */ var _SubjectView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SubjectView.vue?vue&type=script&lang=js& */ "./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SubjectView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SubjectView_vue_vue_type_template_id_3f1d02ce___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SubjectView_vue_vue_type_template_id_3f1d02ce___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/apps/subject/subject-view/SubjectView.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SubjectView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SubjectView.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SubjectView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=template&id=3f1d02ce&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=template&id=3f1d02ce& ***!
  \*********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SubjectView_vue_vue_type_template_id_3f1d02ce___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SubjectView.vue?vue&type=template&id=3f1d02ce& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/apps/subject/subject-view/SubjectView.vue?vue&type=template&id=3f1d02ce&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SubjectView_vue_vue_type_template_id_3f1d02ce___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SubjectView_vue_vue_type_template_id_3f1d02ce___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);