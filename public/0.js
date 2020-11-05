(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

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
        //  console.log(response.data.users);
        commit('SET_USERS', response.data.users);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchClassCode: function fetchClassCode(_ref2) {
    var commit = _ref2.commit;
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
        // console.log(response.data.classes);
        commit('SET_CLASSES', response.data.classes);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchStudents: function fetchStudents(_ref3) {
    var commit = _ref3.commit;
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
  fetchParents: function fetchParents(_ref4) {
    var commit = _ref4.commit;
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
  UpdateUser: function UpdateUser(_ref5, data) {
    var commit = _ref5.commit;
    console.log(data);
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/auth/update-profile/", data).then(function (response) {
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  SaveStudent: function SaveStudent(_ref6, data) {
    var commit = _ref6.commit;
    console.log(data);
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/v1/signup_student/", data).then(function (response) {
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  removeRecord: function removeRecord(_ref7, userId) {
    var commit = _ref7.commit;
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
  fetchAdminUsers: function fetchAdminUsers(_ref8) {
    var commit = _ref8.commit;
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
  fetchSchoolAdmins: function fetchSchoolAdmins(_ref9) {
    var commit = _ref9.commit;
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
  fetchAdminStudents: function fetchAdminStudents(_ref10) {
    var commit = _ref10.commit;
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
  fetchAdminParents: function fetchAdminParents(_ref11) {
    var commit = _ref11.commit;
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
  },
  SET_CLASSES: function SET_CLASSES(state, classes) {
    state.classes = classes;
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

/***/ })

}]);