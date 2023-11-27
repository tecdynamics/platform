/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************************!*\
  !*** ./platform/core/acl/resources/assets/js/profile.js ***!
  \**********************************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
/**
 * Created on 06/09/2015.
 */
var CropAvatar = /*#__PURE__*/function () {
  function CropAvatar($element) {
    _classCallCheck(this, CropAvatar);
    this.$container = $element;
    this.$avatarView = this.$container.find('.avatar-view');
    this.$triggerButton = this.$avatarView.find('.mt-overlay .btn-outline');
    this.$avatar = this.$avatarView.find('img');
    this.$avatarModal = this.$container.find('#avatar-modal');
    this.$loading = this.$container.find('.loading');
    this.$avatarForm = this.$avatarModal.find('.avatar-form');
    this.$avatarSrc = this.$avatarForm.find('.avatar-src');
    this.$avatarData = this.$avatarForm.find('.avatar-data');
    this.$avatarInput = this.$avatarForm.find('.avatar-input');
    this.$avatarSave = this.$avatarForm.find('.avatar-save');
    this.$avatarWrapper = this.$avatarModal.find('.avatar-wrapper');
    this.$avatarPreview = this.$avatarModal.find('.avatar-preview');
    this.support = {
      fileList: !!$('<input type="file">').prop('files'),
      fileReader: !!window.FileReader,
      formData: !!window.FormData
    };
  }
  _createClass(CropAvatar, [{
    key: "init",
    value: function init() {
      this.support.datauri = this.support.fileList && this.support.fileReader;
      if (!this.support.formData) {
        this.initIframe();
      }
      this.initTooltip();
      this.initModal();
      this.addListener();
    }
  }, {
    key: "addListener",
    value: function addListener() {
      this.$triggerButton.on('click', $.proxy(this.click, this));
      this.$avatarInput.on('change', $.proxy(this.change, this));
      this.$avatarForm.on('submit', $.proxy(this.submit, this));
    }
  }, {
    key: "initTooltip",
    value: function initTooltip() {
      this.$avatarView.tooltip({
        placement: 'bottom'
      });
    }
  }, {
    key: "initModal",
    value: function initModal() {
      this.$avatarModal.modal('hide');
      this.initPreview();
    }
  }, {
    key: "initPreview",
    value: function initPreview() {
      var url = this.$avatar.prop('src');
      this.$avatarPreview.empty().html(' <img  loading="lazy" src="' + url + '">');
    }
  }, {
    key: "initIframe",
    value: function initIframe() {
      var iframeName = 'avatar-iframe-' + Math.random().toString().replace('.', ''),
        $iframe = $('<iframe name="' + iframeName + '" style="display:none;"></iframe>'),
        firstLoad = true,
        _this = this;
      this.$iframe = $iframe;
      this.$avatarForm.attr('target', iframeName).after($iframe);
      this.$iframe.on('load', function () {
        var data, win, doc;
        try {
          win = this.contentWindow;
          doc = this.contentDocument;
          doc = doc ? doc : win.document;
          data = doc ? doc.body.innerText : null;
        } catch (e) {}
        if (data) {
          _this.submitDone(data);
        } else if (firstLoad) {
          firstLoad = false;
        } else {
          _this.submitFail('Image upload failed!');
        }
        _this.submitEnd();
      });
    }
  }, {
    key: "click",
    value: function click() {
      this.$avatarModal.modal('show');
    }
  }, {
    key: "change",
    value: function change() {
      var files, file;
      if (this.support.datauri) {
        files = this.$avatarInput.prop('files');
        if (files.length > 0) {
          file = files[0];
          if (CropAvatar.isImageFile(file)) {
            this.read(file);
          }
        }
      } else {
        file = this.$avatarInput.val();
        if (CropAvatar.isImageFile(file)) {
          this.syncUpload();
        }
      }
    }
  }, {
    key: "submit",
    value: function submit() {
      if (!this.$avatarSrc.val() && !this.$avatarInput.val()) {
        Tec.showError('Please select image!');
        return false;
      }
      if (this.support.formData) {
        this.ajaxUpload();
        return false;
      }
    }
  }, {
    key: "read",
    value: function read(file) {
      var _this = this,
        fileReader = new FileReader();
      fileReader.readAsDataURL(file);
      fileReader.onload = function () {
        _this.url = this.result;
        _this.startCropper();
      };
    }
  }, {
    key: "startCropper",
    value: function startCropper() {
      var _this = this;
      if (this.active) {
        this.$img.cropper('replace', this.url);
      } else {
        this.$img = $(' <img  loading="lazy" src="' + this.url + '">');
        this.$avatarWrapper.empty().html(this.$img);
        this.$img.cropper({
          aspectRatio: 1,
          rotatable: true,
          preview: this.$avatarPreview.selector,
          done: function done(data) {
            var json = ['{"x":' + data.x, '"y":' + data.y, '"height":' + data.height, '"width":' + data.width + "}"].join();
            _this.$avatarData.val(json);
          }
        });
        this.active = true;
      }
    }
  }, {
    key: "stopCropper",
    value: function stopCropper() {
      if (this.active) {
        this.$img.cropper('destroy');
        this.$img.remove();
        this.active = false;
      }
    }
  }, {
    key: "ajaxUpload",
    value: function ajaxUpload() {
      var url = this.$avatarForm.attr('action'),
        data = new FormData(this.$avatarForm[0]),
        _this = this;
      $.ajax(url, {
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function beforeSend() {
          _this.submitStart();
        },
        success: function success(data) {
          _this.submitDone(data);
        },
        error: function error(XMLHttpRequest, textStatus, errorThrown) {
          _this.submitFail(XMLHttpRequest.responseJSON, textStatus || errorThrown);
        },
        complete: function complete() {
          _this.submitEnd();
        }
      });
    }
  }, {
    key: "syncUpload",
    value: function syncUpload() {
      this.$avatarSave.trigger('click');
    }
  }, {
    key: "submitStart",
    value: function submitStart() {
      this.$loading.fadeIn();
      this.$avatarSave.attr('disabled', true).text('Saving...');
    }
  }, {
    key: "submitDone",
    value: function submitDone(data) {
      try {
        data = $.parseJSON(data);
      } catch (e) {}
      if (data && !data.error) {
        if (data.data) {
          this.url = data.data.url;
          if (this.support.datauri || this.uploaded) {
            this.uploaded = false;
            this.cropDone();
          } else {
            this.uploaded = true;
            this.$avatarSrc.val(this.url);
            this.startCropper();
          }
          this.$avatarInput.val('');
          Tec.showSuccess(data.message);
        } else {
          Tec.showError(data.message);
        }
      } else {
        Tec.showError(data.message);
      }
    }
  }, {
    key: "submitEnd",
    value: function submitEnd() {
      this.$loading.fadeOut();
      this.$avatarSave.removeAttr('disabled').text('Save');
    }
  }, {
    key: "cropDone",
    value: function cropDone() {
      this.$avatarSrc.val('');
      this.$avatarData.val('');
      this.$avatar.prop('src', this.url);
      $('.user-menu img').prop('src', this.url);
      $('.user.dropdown img').prop('src', this.url);
      this.stopCropper();
      this.initModal();
    }
  }], [{
    key: "isImageFile",
    value: function isImageFile(file) {
      if (file.type) {
        return /^image\/\w+$/.test(file.type);
      }
      return /\.(jpg|jpeg|png|gif)$/.test(file);
    }
  }, {
    key: "submitFail",
    value: function submitFail(errors) {
      Tec.handleError(errors);
    }
  }]);
  return CropAvatar;
}();
$(document).ready(function () {
  new CropAvatar($('.crop-avatar')).init();
});
/******/ })()
;