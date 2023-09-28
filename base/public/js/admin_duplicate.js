/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************************************************!*\
  !*** ./platform/core/base/resources/assets/js/admin_duplicate.js ***!
  \*******************************************************************/
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var AdminDuplicate = /*#__PURE__*/function () {
  function AdminDuplicate() {
    _classCallCheck(this, AdminDuplicate);
  }
  _createClass(AdminDuplicate, [{
    key: "init",
    value: function init() {
      jQuery('button#ok_action').on('click', function () {
        var val = jQuery('input.modal_name_imput').val();
        var url = jQuery(this).attr('_url');
        jQuery('#duplicateModal').modal('hide').remove();
        window.location.href = url + '?name=' + val;
      });
      jQuery('button.dismiss-action').on('click', function () {
        jQuery('#duplicateModal').modal('hide').remove();
      });
    }
  }, {
    key: "presentAlert",
    value: function presentAlert(event) {
      jQuery('body').append('<div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateModal" aria-hidden="true">' + '<div class="modal-dialog">' + '<div class="modal-content">' + '<div class="modal-header">' + '<button type="button" class="close dismiss-action" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + '</div>  <div class="modal-body">  <label class="modal_note">New Name</label>' + '<input class="modal_name_imput form-control form-control-sm" placeholder="Enter Here"/>' + '<span class="modal_valid">Max 100 Characters</span></div>' + '<div class="modal-footer narr_footer">' + '<button type="button" class="btn btn-primary ok_btn" id="ok_action" _url="' + jQuery(event.currentTarget).attr("url") + '"   data-dismiss="modal">Create</button>' + '</div></div> </div></div>');
      $('#duplicateModal').modal('show');
      this.init();
    }
  }]);
  return AdminDuplicate;
}();
jQuery(document).ready(function () {
  jQuery('body').on('click', '#duplicate', function (event) {
    event.preventDefault;
    var mn = new AdminDuplicate();
    mn.presentAlert(event);
  });
});
/******/ })()
;