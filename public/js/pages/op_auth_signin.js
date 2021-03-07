/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************!*\
  !*** ./resources/js/pages/op_auth_signin.js ***!
  \**********************************************/
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/*
 *  Document   : op_auth_signin.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Sign In Page
 */
// Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
var OpAuthSignIn = /*#__PURE__*/function () {
  function OpAuthSignIn() {
    _classCallCheck(this, OpAuthSignIn);
  }

  _createClass(OpAuthSignIn, null, [{
    key: "initValidationSignIn",
    value:
    /*
     * Init Sign In Form Validation
     *
     */
    function initValidationSignIn() {
      jQuery('.js-validation-signin').validate({
        errorClass: 'invalid-feedback animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function errorPlacement(error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function highlight(e) {
          jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
        },
        success: function success(e) {
          jQuery(e).closest('.form-group').removeClass('is-invalid');
          jQuery(e).remove();
        },
        rules: {
          'login-username': {
            required: true,
            minlength: 3
          },
          'login-password': {
            required: true,
            minlength: 5
          }
        },
        messages: {
          'login-username': {
            required: 'Please enter a username',
            minlength: 'Your username must consist of at least 3 characters'
          },
          'login-password': {
            required: 'Please provide a password',
            minlength: 'Your password must be at least 5 characters long'
          }
        }
      });
    }
    /*
     * Init functionality
     *
     */

  }, {
    key: "init",
    value: function init() {
      this.initValidationSignIn();
    }
  }]);

  return OpAuthSignIn;
}(); // Initialize when page loads


jQuery(function () {
  OpAuthSignIn.init();
});
/******/ })()
;