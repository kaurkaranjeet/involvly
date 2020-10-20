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
  fetchSubjects: function fetchSubjects(_ref5, classId) {
    var commit = _ref5.commit;
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
  saveSubject: function saveSubject(_ref6, code) {
    var commit = _ref6.commit;
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
  removeSubject: function removeSubject(_ref7, id) {
    var commit = _ref7.commit;
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
  editSubject: function editSubject(_ref8, code) {
    var commit = _ref8.commit;
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
  fetchAllItems: function fetchAllItems(_ref9) {
    var commit = _ref9.commit;
    return new Promise(function (resolve, reject) {
      _axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/all-items").then(function (response) {
        commit('SET_ITEMS', response.data.response);
        resolve(response);
      })["catch"](function (error) {
        reject(error);
      });
    });
  },
  fetchItems: function fetchItems(_ref10, userId) {
    var commit = _ref10.commit;
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
  getItem: function getItem(_ref11, itemId) {
    var commit = _ref11.commit;
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
  removeRecord: function removeRecord(_ref12, ItemId) {
    var commit = _ref12.commit;
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
  },
  SET_SUBJECTS: function SET_SUBJECTS(state, subjects) {
    state.subjects = subjects;
  },
  REMOVE_SUBJECTS: function REMOVE_SUBJECTS(state, subjectId) {
    var subjectIndex = state.subjects.findIndex(function (u) {
      return u.id === subjectId;
    });
    state.subjects.splice(subjectIndex, 1);
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
  subjects: []
});

/***/ })

}]);