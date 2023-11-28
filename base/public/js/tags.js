/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************************!*\
  !*** ./platform/core/base/resources/assets/js/tags.js ***!
  \********************************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var TagsManager = /*#__PURE__*/function () {
  function TagsManager() {
    _classCallCheck(this, TagsManager);
  }
  _createClass(TagsManager, [{
    key: "init",
    value: function init() {
      $(document).find('.tags').each(function (index, element) {
        var tagify = new Tagify(element, {
          keepInvalidTags: $(element).data('keep-invalid-tags') !== undefined ? $(element).data('keep-invalid-tags') : true,
          enforceWhitelist: $(element).data('enforce-whitelist') !== undefined ? $(element).data('enforce-whitelist') : false,
          delimiters: $(element).data('delimiters') !== undefined ? $(element).data('delimiters') : ',',
          whitelist: element.value.trim().split(/\s*,\s*/),
          userInput: $(element).data('user-input') !== undefined ? $(element).data('user-input') : true
        });
        if ($(element).data('url')) {
          tagify.on('input', function (e) {
            tagify.settings.whitelist.length = 0; // reset current whitelist
            tagify.loading(true).dropdown.hide.call(tagify); // show the loader animation

            $httpClient.make().get($(element).data('url')).then(function (_ref) {
              var data = _ref.data;
              tagify.settings.whitelist = data;
              tagify.loading(false).dropdown.show.call(tagify, e.detail.value);
            });
          });
        }
      });
      document.querySelectorAll('.list-tagify').forEach(function (element) {
        var list = JSON.parse(element.dataset.list);
        var whiteList = [];
        for (var _i = 0, _Object$entries = Object.entries(list); _i < _Object$entries.length; _i++) {
          var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
            key = _Object$entries$_i[0],
            value = _Object$entries$_i[1];
          whiteList.push({
            value: key,
            name: value
          });
        }
        var listChosen = String(element.value).split(',');
        var arrayChosen = whiteList.filter(function (obj) {
          if (listChosen.includes(String(obj.value))) {
            return {
              value: obj.id,
              name: obj.name
            };
          }
        });
        var tagTemplate = function tagTemplate(tagData) {
          return "\n                <tag title=\"".concat(tagData.title || tagData.name, "\"\n                        contenteditable='false'\n                        spellcheck='false'\n                        tabIndex=\"-1\"\n                        class=\"").concat(this.settings.classNames.tag, " ").concat(tagData["class"] ? tagData["class"] : '', "\"\n                        ").concat(this.getAttributes(tagData), ">\n                    <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>\n                    <div class=\"d-flex align-items-center\">\n                        <span class='tagify__tag-text'>").concat(tagData.name, "</span>\n                    </div>\n                </tag>\n            ");
        };
        var suggestionTemplate = function suggestionTemplate(tagData) {
          return "\n                <div ".concat(this.getAttributes(tagData), "\n                    class=\"tagify__dropdown__item d-flex align-items-center ").concat(tagData["class"] ? tagData["class"] : '', "\"\n                    tabindex=\"0\"\n                    role=\"option\">\n\n                    <div class=\"d-flex flex-column\">\n                        <strong>").concat(tagData.name, "</strong>\n                    </div>\n                </div>\n            ");
        };
        var tagify = new Tagify(element, {
          tagTextProp: 'name',
          enforceWhitelist: true,
          skipInvalid: true,
          // do not temporarily add invalid tags
          dropdown: {
            closeOnSelect: false,
            enabled: 0,
            classname: 'users-list',
            searchKeys: ['value', 'name']
          },
          templates: {
            tag: tagTemplate,
            dropdownItem: suggestionTemplate
          },
          whitelist: whiteList,
          originalInputValueFormat: function originalInputValueFormat(valuesArr) {
            return valuesArr.map(function (item) {
              return item.value;
            }).join(',');
          }
        });
        tagify.loadOriginalValues(arrayChosen);
      });
    }
  }]);
  return TagsManager;
}();
$(document).ready(function () {
  new TagsManager().init();
});
/******/ })()
;