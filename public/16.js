(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[16],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/Register.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/pages/register/Register.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _RegisterJWT_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RegisterJWT.vue */ "./resources/js/src/views/pages/register/RegisterJWT.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  components: {
    RegisterJwt: _RegisterJWT_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-select */ "./node_modules/vue-select/dist/vue-select.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_select__WEBPACK_IMPORTED_MODULE_0__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  components: {
    vSelect: vue_select__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  data: function data() {
    return {
      countimages: 0,
      name: '',
      Position: '',
      email: '',
      password: '',
      images: [],
      pdf: [],
      documents: '',
      confirm_password: '',
      state_id: '',
      city: '',
      country: 'United States',
      stateFilteroption: [],
      cityoptions: [],
      schooloptions: [],
      stateFilter: {
        label: 'Select State*',
        value: '0'
      },
      schoolFilter: {
        label: 'Select School*',
        value: '0'
      },
      cityFilter: {
        label: 'Select city*',
        value: '0'
      },
      isTermsConditionAccepted: false
    };
  },
  computed: {
    validateForm: function validateForm() {
      return !this.errors.any() && this.name !== '' && this.email !== '' && this.password !== '' && this.confirm_password !== '' && this.isTermsConditionAccepted === true && this.country !== '' && this.stateFilter.value !== '0' && this.cityFilter.value !== '0' && this.Position !== '' && this.schoolFilter.value !== '0';
    }
  },
  methods: {
    reset_data: function reset_data() {
      this.name = '';
      this.Position = '';
      this.email = '';
      this.password = '';
      this.documents = '';
      this.confirm_password = '';
      this.state_id = '';
      this.city = '';
      this.country = 'United States';
      this.images = [];
      this.pdf = [];
      this.cityoptions = [];
      this.schooloptions = [];
      this.stateFilter = {
        label: 'Select State',
        value: '0'
      };
      this.schoolFilter = {
        label: 'Select School',
        value: '0'
      };
      this.cityFilter = {
        label: 'Select city',
        value: '0'
      };
      this.isTermsConditionAccepted = false;
    },
    isLetter: function isLetter(e) {
      var _char = String.fromCharCode(e.keyCode); // Get the character


      if (/^[a-zA-Z\s]*$/.test(_char)) return true; // Match with regex 
      else e.preventDefault(); // If not match, don't add to input text
    },
    selectFile: function selectFile(event) {
      var _this = this;

      var selectedFiles = event.target.files; // `files` is always an array because the file input may be in multiple mode

      this.documents = selectedFiles;

      if (selectedFiles.length > 5) {
        alert('You can upload 5 documents');
        return false;
      }

      for (var i = 0; i < selectedFiles.length; i++) {
        console.log(selectedFiles[i]);

        if (selectedFiles[i].type == 'image/jpg' || selectedFiles[i].type == 'image/png' || selectedFiles[i].type == 'image/jpeg') {
          this.images.push(selectedFiles[i]);
          this.countimages++;
        } else {
          if (selectedFiles[i].type == 'application/doc' || selectedFiles[i].type == 'application/ms-doc' || selectedFiles[i].type == 'application/msword' || selectedFiles[i].type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || selectedFiles[i].type == 'application/pdf') {
            this.countimages++;
            this.pdf.push(selectedFiles[i]);
          } else {
            alert('Only pdf,jpg,png,docs files are allowed');
            return false;
          }
        }
      }

      var _loop = function _loop(_i) {
        var reader = new FileReader();

        reader.onload = function (e) {
          _this.$refs.image[_i].src = reader.result; //console.log(this.$refs.image[i].src);
        };

        reader.readAsDataURL(_this.images[_i]);
      };

      for (var _i = 0; _i < this.images.length; _i++) {
        _loop(_i);
      }

      var _loop2 = function _loop2(_i2) {
        var reader = new FileReader();

        reader.onload = function (e) {
          _this.$refs.pdfs[_i2].href = reader.result; //console.log(this.$refs.image[i].src);
        };

        reader.readAsDataURL(_this.pdf[_i2]);
      };

      for (var _i2 = 0; _i2 < this.pdf.length; _i2++) {
        _loop2(_i2);
      }
    },
    checkLogin: function checkLogin() {
      // If user is already logged in notify
      if (this.$store.state.auth.isUserLoggedIn()) {
        // Close animation if passed as payload
        // this.$vs.loading.close()
        this.$vs.notify({
          title: 'Login Attempt',
          text: 'You are already logged in!',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'warning'
        });
        return false;
      }

      return true;
    },
    registerUserJWt: function registerUserJWt() {
      var _this2 = this;

      this.$vs.loading; // If form is not validated or user is already login return

      if (!this.validateForm || !this.checkLogin()) return;

      if (this.countimages > 5) {
        this.$vs.notify({
          title: 'Error',
          text: 'Only 5 files are allowed at a time',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'danger'
        });
      } //  console.log(this.stateFilter.value);
      //    console.log( this.stateFilter);


      var formData = new FormData();
      formData.append('documents', this.documents);
      formData.append('position', this.Position);
      formData.append('name', this.name);
      formData.append('email', this.email);
      formData.append('password', this.password);
      formData.append('confirmPassword', this.confirmPassword);
      formData.append('country', this.country);
      formData.append('state_id', this.stateFilter.value);
      formData.append('city', this.cityFilter.value);
      formData.append('school_id', this.schoolFilter.value);
      this.$http.post('/api/auth/register', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(function (response) {
        if (response.data.error == false) {
          _this2.$router.push('/')["catch"](function () {});

          _this2.$vs.notify({
            title: 'Successfully registered',
            text: 'Your request is under Process',
            iconPack: 'feather',
            icon: 'icon-alert-circle',
            color: 'success'
          });

          _this2.reset_data();
        } else {
          _this2.$vs.notify({
            title: 'Error',
            text: response.data.message,
            iconPack: 'feather',
            icon: 'icon-alert-circle',
            color: 'danger'
          });
        }
      })["catch"](function (err) {
        console.log(err.error);

        _this2.$vs.notify({
          title: 'Something went wrong',
          text: 'Please try later',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'danger'
        }); //  console.log('FAILURE!!');

      }); //  data.append('data', payload);
      //  console.log(payload)

      /*  this.$http
        .post('/api/auth/register', data) .then(response => {
          var data=response.data.data;
        })
        .catch(error => {
          console.log(error);
        });*/
    },
    getCities: function getCities(a) {
      var _this3 = this;

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

          _this3.cityoptions.push(newobj);
        }
      })["catch"](function (error) {
        console.log(error);
      });
    },
    getSchools: function getSchools(a) {
      var _this4 = this;

      this.$http.post("/api/v1/list_schools", {
        city_id: a.value
      }).then(function (response) {
        var data = response.data.data;

        for (var index in data) {
          var newobj = {};
          newobj.label = data[index].school_name;
          newobj.value = data[index].id;

          _this4.schooloptions.push(newobj);
        }
      })["catch"](function (error) {
        console.log(error);
      });
    }
  },
  created: function created() {
    var _this5 = this;

    this.$http.get("/api/v1/list_states").then(function (response) {
      var data = response.data.data;

      for (var index in data) {
        var newobj = {};
        newobj.label = data[index].state_name;
        newobj.value = data[index].id;

        _this5.stateFilteroption.push(newobj);
      }
    })["catch"](function (error) {
      console.log(error);
    });
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/Register.vue?vue&type=style&index=0&lang=scss&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-2!./node_modules/sass-loader/dist/cjs.js??ref--8-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/pages/register/Register.vue?vue&type=style&index=0&lang=scss& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".register-tabs-container {\n  min-height: 517px;\n}\n[dir] .register-tabs-container .con-tab {\n  padding-bottom: 23px;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/Register.vue?vue&type=style&index=0&lang=scss&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-2!./node_modules/sass-loader/dist/cjs.js??ref--8-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/pages/register/Register.vue?vue&type=style&index=0&lang=scss& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--8-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--8-3!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Register.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/Register.vue?vue&type=style&index=0&lang=scss&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/Register.vue?vue&type=template&id=59b7090a&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/pages/register/Register.vue?vue&type=template&id=59b7090a& ***!
  \*************************************************************************************************************************************************************************************************************************/
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
    {
      staticClass:
        "h-screen flex w-full bg-img vx-row no-gutter items-center justify-center"
    },
    [
      _c(
        "div",
        {
          staticClass: "vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 sm:m-0 m-4"
        },
        [
          _c("vx-card", [
            _c(
              "div",
              {
                staticClass: "full-page-bg-color",
                attrs: { slot: "no-body" },
                slot: "no-body"
              },
              [
                _c("div", { staticClass: "vx-row no-gutter" }, [
                  _c(
                    "div",
                    {
                      staticClass:
                        "vx-col hidden sm:hidden md:hidden lg:block lg:w-1/2 mx-auto self-center"
                    },
                    [
                      _c("img", {
                        staticClass: "mx-auto",
                        attrs: {
                          src: __webpack_require__(/*! @assets/images/pages/register.jpg */ "./resources/assets/images/pages/register.jpg"),
                          alt: "register"
                        }
                      })
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    {
                      staticClass:
                        "vx-col sm:w-full md:w-full lg:w-1/2 mx-auto self-center d-theme-dark-bg"
                    },
                    [
                      _c(
                        "div",
                        { staticClass: "px-8 pt-8 register-tabs-container" },
                        [
                          _c("div", { staticClass: "vx-card__title mb-4" }, [
                            _c("h4", { staticClass: "mb-4" }, [
                              _vm._v("Create Account")
                            ]),
                            _vm._v(" "),
                            _c("p", [
                              _vm._v(
                                "Fill the below form to create a new account."
                              )
                            ])
                          ]),
                          _vm._v(" "),
                          _c("register-jwt")
                        ],
                        1
                      )
                    ]
                  )
                ])
              ]
            )
          ])
        ],
        1
      )
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=template&id=653637e6&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=template&id=653637e6& ***!
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
    { staticClass: "clearfix" },
    [
      _c("vs-input", {
        directives: [
          {
            name: "validate",
            rawName: "v-validate",
            value: "required|min:6",
            expression: "'required|min:6'"
          }
        ],
        staticClass: "w-full mt-8",
        attrs: {
          "data-vv-validate-on": "blur",
          "label-placeholder": "Name*",
          name: "name",
          placeholder: "Name*",
          maxlength: 50
        },
        on: {
          keypress: function($event) {
            return _vm.isLetter($event)
          }
        },
        model: {
          value: _vm.name,
          callback: function($$v) {
            _vm.name = $$v
          },
          expression: "name"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-sm" }, [
        _vm._v(_vm._s(_vm.errors.first("name")))
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
          "label-placeholder": "Position*",
          name: "Position",
          placeholder: "Position*"
        },
        model: {
          value: _vm.Position,
          callback: function($$v) {
            _vm.Position = $$v
          },
          expression: "Position"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-sm" }, [
        _vm._v(_vm._s(_vm.errors.first("Position")))
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
        staticClass: "w-full mt-8",
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
      _c("span", { staticClass: "text-danger text-sm" }, [
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
        staticClass: "w-full mt-8",
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
      _c("span", { staticClass: "text-danger text-sm" }, [
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
        staticClass: "w-full mt-8",
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
      _c("span", { staticClass: "text-danger text-sm" }, [
        _vm._v(_vm._s(_vm.errors.first("confirm_password")))
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
          "data-vv-validate-on": "change",
          name: "country",
          type: "country",
          "label-placeholder": "Country",
          placeholder: "Country",
          readonly: "true"
        },
        model: {
          value: _vm.country,
          callback: function($$v) {
            _vm.country = $$v
          },
          expression: "country"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-sm" }, [
        _vm._v(_vm._s(_vm.errors.first("country")))
      ]),
      _vm._v(" "),
      _c(
        "v-select",
        {
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
        },
        [
          _c("span", { staticClass: "text-danger text-sm" }, [
            _vm._v(_vm._s(_vm.errors.first("stateFilter")))
          ])
        ]
      ),
      _vm._v(" "),
      _c("v-select", {
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
        on: { input: _vm.getSchools },
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
      _c("v-select", {
        staticClass: "w-full mt-6",
        attrs: { options: _vm.schooloptions, clearable: false },
        model: {
          value: _vm.schoolFilter,
          callback: function($$v) {
            _vm.schoolFilter = $$v
          },
          expression: "schoolFilter"
        }
      }),
      _vm._v(" "),
      _c("span", { staticClass: "text-danger text-sm" }, [
        _vm._v(_vm._s(_vm.errors.first("schoolFilter")))
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "flex items-start flex-col sm:flex-row " },
        [
          _vm._l(_vm.images, function(image, key) {
            return _c("div", { staticClass: "mt-3" }, [
              _c("img", {
                ref: "image",
                refInFor: true,
                staticClass: "mr-5 rounded h-12 w-12"
              })
            ])
          }),
          _vm._v(" "),
          _vm._l(_vm.pdf, function(pdfs, key) {
            return _c("div", { staticClass: "mt-3" }, [
              _c(
                "a",
                {
                  ref: "pdfs",
                  refInFor: true,
                  staticClass: "mr-5",
                  attrs: { target: "_blank" }
                },
                [_vm._v(_vm._s(pdfs.name))]
              )
            ])
          })
        ],
        2
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "flex items-start flex-col sm:flex-row" },
        [
          _c("input", {
            ref: "update_avatar_input",
            staticClass: "hidden",
            attrs: { type: "file", accept: "image/*", multiple: "" },
            on: { change: _vm.selectFile }
          }),
          _vm._v(" "),
          _c(
            "vs-button",
            {
              staticClass: "w-full mt-6",
              attrs: { type: "border" },
              on: {
                click: function($event) {
                  return _vm.$refs.update_avatar_input.click()
                }
              }
            },
            [_vm._v("Upload Documents")]
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "vs-checkbox",
        {
          staticClass: "mt-6",
          model: {
            value: _vm.isTermsConditionAccepted,
            callback: function($$v) {
              _vm.isTermsConditionAccepted = $$v
            },
            expression: "isTermsConditionAccepted"
          }
        },
        [_vm._v("I accept the terms & conditions.")]
      ),
      _vm._v(" "),
      _c(
        "vs-button",
        {
          staticClass: "w-full mt-6",
          attrs: { disabled: !_vm.validateForm },
          on: { click: _vm.registerUserJWt }
        },
        [_vm._v("Submit")]
      ),
      _vm._v(" "),
      _c(
        "vs-button",
        {
          staticClass: "w-full mt-6",
          attrs: { type: "border", to: "/pages/login" }
        },
        [_vm._v("Login")]
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/assets/images/pages/register.jpg":
/*!****************************************************!*\
  !*** ./resources/assets/images/pages/register.jpg ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/register.jpg?cfd9ef099bfc1f99c6f2970ae05a6a4a";

/***/ }),

/***/ "./resources/js/src/views/pages/register/Register.vue":
/*!************************************************************!*\
  !*** ./resources/js/src/views/pages/register/Register.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Register_vue_vue_type_template_id_59b7090a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Register.vue?vue&type=template&id=59b7090a& */ "./resources/js/src/views/pages/register/Register.vue?vue&type=template&id=59b7090a&");
/* harmony import */ var _Register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Register.vue?vue&type=script&lang=js& */ "./resources/js/src/views/pages/register/Register.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Register_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Register.vue?vue&type=style&index=0&lang=scss& */ "./resources/js/src/views/pages/register/Register.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Register_vue_vue_type_template_id_59b7090a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Register_vue_vue_type_template_id_59b7090a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/pages/register/Register.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/pages/register/Register.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/js/src/views/pages/register/Register.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Register.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/Register.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/pages/register/Register.vue?vue&type=style&index=0&lang=scss&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/src/views/pages/register/Register.vue?vue&type=style&index=0&lang=scss& ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--8-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--8-3!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Register.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/Register.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_2_node_modules_sass_loader_dist_cjs_js_ref_8_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/src/views/pages/register/Register.vue?vue&type=template&id=59b7090a&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/src/views/pages/register/Register.vue?vue&type=template&id=59b7090a& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_template_id_59b7090a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Register.vue?vue&type=template&id=59b7090a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/Register.vue?vue&type=template&id=59b7090a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_template_id_59b7090a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_template_id_59b7090a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/src/views/pages/register/RegisterJWT.vue":
/*!***************************************************************!*\
  !*** ./resources/js/src/views/pages/register/RegisterJWT.vue ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _RegisterJWT_vue_vue_type_template_id_653637e6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RegisterJWT.vue?vue&type=template&id=653637e6& */ "./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=template&id=653637e6&");
/* harmony import */ var _RegisterJWT_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RegisterJWT.vue?vue&type=script&lang=js& */ "./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _RegisterJWT_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _RegisterJWT_vue_vue_type_template_id_653637e6___WEBPACK_IMPORTED_MODULE_0__["render"],
  _RegisterJWT_vue_vue_type_template_id_653637e6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/pages/register/RegisterJWT.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=script&lang=js&":
/*!****************************************************************************************!*\
  !*** ./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RegisterJWT_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./RegisterJWT.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RegisterJWT_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=template&id=653637e6&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=template&id=653637e6& ***!
  \**********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RegisterJWT_vue_vue_type_template_id_653637e6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./RegisterJWT.vue?vue&type=template&id=653637e6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/pages/register/RegisterJWT.vue?vue&type=template&id=653637e6&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RegisterJWT_vue_vue_type_template_id_653637e6___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RegisterJWT_vue_vue_type_template_id_653637e6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);