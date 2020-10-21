(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

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
  fetchSchoolSubjects: function fetchSchoolSubjects(_ref2, payload) {
    var commit = _ref2.commit;
    var x = localStorage.getItem('accessToken');
    var school_id = localStorage.getItem('school_id'); //  User Reward Card

    var requestOptions = {
      type: 'teacher',
      class_id: payload.class_id,
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/list_of_subjects/' + school_id, requestOptions).then(function (response) {
        //console.log(response.data.classes);
        commit('SET_SCHOOL_SUBJECTS', response.data.subjects);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  saveClassCode: function saveClassCode(_ref3, code) {
    var commit = _ref3.commit;
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
  AddClassSubjects: function AddClassSubjects(_ref4, code) {
    var commit = _ref4.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/add-subject", code).then(function (response) {
        if (response.data.data) {
          resolve(response);
        } else {
          reject(response.data.message);
        }
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  RemoveClassSubjects: function RemoveClassSubjects(_ref5, code) {
    var commit = _ref5.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/remove-subject", code).then(function (response) {
        if (response.data.data) {
          resolve(response);
        } else {
          reject(response.data.message);
        }
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  removeClassCode: function removeClassCode(_ref6, id) {
    var commit = _ref6.commit;
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
  editClassCode: function editClassCode(_ref7, code) {
    var commit = _ref7.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/edit-class-code", code).then(function (response) {
        console.log("reee", response);

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
  fetchSubjects: function fetchSubjects(_ref8, classId) {
    var commit = _ref8.commit;
    var x = localStorage.getItem('accessToken');
    var user_id = localStorage.getItem('user_id'); //  User Reward Card

    var requestOptions = {
      'type': 'teacher',
      headers: {
        'Authorization': 'Bearer ' + x
      }
    };
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/manage-subjects/' + classId).then(function (response) {
        console.log(response.data.subjects);
        commit('SET_SUBJECTS', response.data.subjects);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  saveSubject: function saveSubject(_ref9, code) {
    var commit = _ref9.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/save-subject", code).then(function (response) {
        if (response.data.subject) {
          resolve(response);
        } else {
          reject(response.data.message);
        }
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  removeSubject: function removeSubject(_ref10, id) {
    var commit = _ref10.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"]["delete"]("/api/auth/delete-subject/".concat(id)).then(function (response) {
        commit('REMOVE_SUBJECTS', id);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchSubjectDetail: function fetchSubjectDetail(context, id) {
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/auth/fetch-subject-detail/".concat(id)).then(function (response) {
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  editSubject: function editSubject(_ref11, code) {
    var commit = _ref11.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/edit-subject", code).then(function (response) {
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
  //fetch assigned teachers to classes
  fetchAssignedTeachersToClasses: function fetchAssignedTeachersToClasses(_ref12, payload) {
    var commit = _ref12.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post('/api/auth/fetch-assigned-teachers', payload).then(function (response) {
        console.log(response.data.teachers);
        commit('SET_TEACHERS', response.data.teachers);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  //AddTeacherToClassSubjects
  AddTeacherToClassSubjects: function AddTeacherToClassSubjects(_ref13, code) {
    var commit = _ref13.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/add-teacher-subject", code).then(function (response) {
        if (response.data.data) {
          resolve(response);
        } else {
          reject(response.data.message);
        }
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  RemoveTeacherToClassSubjects: function RemoveTeacherToClassSubjects(_ref14, code) {
    var commit = _ref14.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/remove-teacher-subject", code).then(function (response) {
        if (response.data.data) {
          resolve(response);
        } else {
          reject(response.data.message);
        }
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
  SET_CLASSES: function SET_CLASSES(state, classes) {
    state.classes = classes;
  },
  REMOVE_RECORD: function REMOVE_RECORD(state, classId) {
    var classIndex = state.classes.findIndex(function (u) {
      return u.id === classId;
    });
    state.classes.splice(classIndex, 1);
  },
  SET_SUBJECTS: function SET_SUBJECTS(state, subjects) {
    state.subjects = subjects;
  },
  REMOVE_SUBJECTS: function REMOVE_SUBJECTS(state, subjectId) {
    var subjectIndex = state.subjects.findIndex(function (u) {
      return u.id === subjectId;
    });
    state.subjects.splice(subjectIndex, 1);
  },
  SET_SCHOOL_SUBJECTS: function SET_SCHOOL_SUBJECTS(state, school_subjects) {
    state.school_subjects = school_subjects;
  },
  SET_TEACHERS: function SET_TEACHERS(state, teachers) {
    state.teachers = teachers;
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
  classes: [],
  subjects: [],
  teachers: [],
  school_subjects: []
});

/***/ })

}]);