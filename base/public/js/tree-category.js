/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************************************!*\
  !*** ./platform/core/base/resources/assets/js/tree-category.js ***!
  \*****************************************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
!function ($) {
  $.fn.filetree = function (i) {
    var options = {
      animationSpeed: 'slow',
      console: false
    };
    function init(i) {
      i = $.extend(options, i);
      return this.each(function () {
        $(this).find('li').on('click', '.file-opener-i', function (e) {
          return e.preventDefault(), $(this).hasClass('fa-plus-square') ? ($(this).addClass('fa-minus-square'), $(this).removeClass('fa-plus-square')) : ($(this).addClass('fa-plus-square'), $(this).removeClass('fa-minus-square')), $(this).parent().toggleClass('closed').toggleClass('open'), !1;
        });
      });
    }
    if ('object' == _typeof(i) || !i) {
      return init.apply(this, arguments);
    }
  };
}(jQuery);
(function ($) {
  $.fn.dragScroll = function (options) {
    function init() {
      var $el = $(this);
      var settings = $.extend({
        scrollVertical: false,
        scrollHorizontal: true,
        cursor: null
      }, options);
      var clicked = false,
        clickY,
        clickX;
      var getCursor = function getCursor() {
        if (settings.cursor) return settings.cursor;
        if (settings.scrollVertical && settings.scrollHorizontal) return 'move';
        if (settings.scrollVertical) return 'row-resize';
        if (settings.scrollHorizontal) return 'col-resize';
      };
      var updateScrollPos = function updateScrollPos(e, el) {
        var $el = $(el);
        settings.scrollVertical && $el.scrollTop($el.scrollTop() + (clickY - e.pageY));
        settings.scrollHorizontal && $el.scrollLeft($el.scrollLeft() + (clickX - e.pageX));
      };
      $el.on({
        mousemove: function mousemove(e) {
          clicked && updateScrollPos(e, this);
        },
        mousedown: function mousedown(e) {
          $el.css('cursor', getCursor());
          clicked = true;
          clickY = e.pageY;
          clickX = e.pageX;
        },
        mouseup: function mouseup() {
          clicked = false;
          $el.css('cursor', 'auto');
        },
        mouseleave: function mouseleave() {
          clicked = false;
          $el.css('cursor', 'auto');
        }
      });
    }
    if ('object' == _typeof(options) || !options) {
      return init.apply(this, arguments);
    }
  };
})(jQuery);
$(function () {
  var $treeWrapper = $('.file-tree-wrapper');
  $treeWrapper.dragScroll();
  var $formLoading = $('.tree-form-container').find('.tree-loading');
  var $treeLoading = $('.tree-categories-container').find('.tree-loading');
  function loadTree(activeId) {
    $treeLoading.removeClass('d-none');
    $treeWrapper.filetree().removeClass('d-none').hide().slideDown('slow');
    $treeLoading.addClass('d-none');
    if (activeId) {
      $treeWrapper.find('li[data-id="' + activeId + '"] .category-name:first').addClass('active');
    }
  }
  loadTree();
  function reloadForm(data) {
    $('.tree-form-body').html(data);
    Tec.initResources();
    Tec.handleCounterUp();
    if (window.EditorManagement) {
      window.EDITOR = new EditorManagement().init();
    }
    Tec.initMediaIntegrate();
  }
  $(document).on('click', '.tree-categories-container .toggle-tree', function (e) {
    var $this = $(e.currentTarget);
    var $treeCategoryContainer = $('.tree-categories-container');
    if ($this.hasClass('open-tree')) {
      $this.text($this.data('collapse'));
      $treeCategoryContainer.find('.folder-root.closed').removeClass('closed').addClass('open');
    } else {
      $this.text($this.data('expand'));
      $treeCategoryContainer.find('.folder-root.open').removeClass('open').addClass('closed');
    }
    $this.toggleClass('open-tree');
  });
  function fetchData(url, $el) {
    $formLoading.removeClass('d-none');
    $treeWrapper.find('a.active').removeClass('active');
    if ($el) {
      $el.addClass('active');
    }
    $httpClient.make().get(url).then(function (_ref) {
      var data = _ref.data;
      return reloadForm(data.data);
    })["finally"](function () {
      return $formLoading.addClass('d-none');
    });
  }
  $treeWrapper.on('click', '.fetch-data', function (event) {
    event.preventDefault();
    var $this = $(event.currentTarget);
    if ($this.attr('href')) {
      fetchData($this.attr('href'), $this);
    } else {
      $treeWrapper.find('a.active').removeClass('active');
      $this.addClass('active');
    }
  });
  $(document).on('click', '.tree-categories-create', function (event) {
    event.preventDefault();
    var $this = $(event.currentTarget);
    loadCreateForm($this.attr('href'));
  });
  var searchParams = new URLSearchParams(window.location.search);
  function loadCreateForm(url) {
    var data = {};
    if (searchParams.get('ref_lang')) {
      data.ref_lang = searchParams.get('ref_lang');
    }
    $formLoading.removeClass('d-none');
    $httpClient.make().get(url, data).then(function (_ref2) {
      var data = _ref2.data;
      return reloadForm(data.data);
    })["finally"](function () {
      return $formLoading.addClass('d-none');
    });
  }
  function reloadTree(activeId, callback) {
    $httpClient.make().get($treeWrapper.data('url') || window.location.href).then(function (_ref3) {
      var data = _ref3.data;
      $treeWrapper.html(data.data);
      loadTree(activeId);
      if (jQuery().tooltip) {
        $('[data-bs-toggle="tooltip"]').tooltip({
          placement: 'top',
          boundary: 'window'
        });
      }
      if (callback) {
        callback();
      }
    });
  }
  $(document).on('click', '#list-others-language a', function (event) {
    event.preventDefault();
    fetchData($(event.currentTarget).prop('href'));
  });
  $(document).on('submit', '.tree-form-container form', function (event) {
    var _event$originalEvent;
    event.preventDefault();
    var $form = $(event.currentTarget);
    var formData = new FormData(event.currentTarget);
    var submitter = (_event$originalEvent = event.originalEvent) === null || _event$originalEvent === void 0 ? void 0 : _event$originalEvent.submitter;
    var saveAndEdit = false;
    if (submitter && submitter.name) {
      saveAndEdit = submitter.value === 'apply';
      formData.append(submitter.name, submitter.value);
    }
    var method = $form.attr('method').toLowerCase() || 'post';
    $formLoading.removeClass('d-none');
    $httpClient.make()[method]($form.attr('action'), formData).then(function (_ref4) {
      var data = _ref4.data;
      Tec.showSuccess(data.message);
      $formLoading.addClass('d-none');
      var $createButton = $('.tree-categories-create');
      var activeId = saveAndEdit && data.data && data.data.model ? data.data.model.id : null;
      reloadTree(activeId, function () {
        if (activeId) {
          var fetchDataButton = $('.folder-root[data-id="' + activeId + '"] > a.fetch-data');
          if (fetchDataButton.length) {
            fetchDataButton.trigger('click');
          } else {
            location.reload();
          }
        } else if ($createButton.length) {
          $createButton.trigger('click');
        } else {
          var _data$data;
          reloadForm((_data$data = data.data) === null || _data$data === void 0 ? void 0 : _data$data.form);
        }
      });
    })["finally"](function () {
      $formLoading.addClass('d-none');
      $form.find('button[type=submit]').prop('disabled', false).removeClass('disabled');
    });
  });
  $(document).on('click', '.deleteDialog', function (event) {
    event.preventDefault();
    var _self = $(event.currentTarget);
    $('.delete-crud-entry').data('section', _self.data('section'));
    $('.modal-confirm-delete').modal('show');
  });
  $('.delete-crud-entry').on('click', function (event) {
    event.preventDefault();
    var _self = $(event.currentTarget);
    _self.addClass('button-loading');
    var deleteURL = _self.data('section');
    $httpClient.make()["delete"](deleteURL).then(function (_ref5) {
      var data = _ref5.data;
      Tec.showSuccess(data.message);
      reloadTree();
      var $createButton = $('.tree-categories-create');
      if ($createButton.length) {
        $createButton.trigger('click');
      } else {
        reloadForm('');
      }
      _self.closest('.modal').modal('hide');
    })["finally"](function () {
      _self.removeClass('button-loading');
    });
  });
});
/******/ })()
;