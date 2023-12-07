(()=>{function e(t){return e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e(t)}!function(t){t.fn.filetree=function(n){var o={animationSpeed:"slow",console:!1};if("object"==e(n)||!n)return function(e){return e=t.extend(o,e),this.each((function(){t(this).find("li").on("click",".file-opener-i",(function(e){return e.preventDefault(),t(this).hasClass("fa-plus-square")?(t(this).addClass("fa-minus-square"),t(this).removeClass("fa-plus-square")):(t(this).addClass("fa-plus-square"),t(this).removeClass("fa-minus-square")),t(this).parent().toggleClass("closed").toggleClass("open"),!1}))}))}.apply(this,arguments)}}(jQuery),function(t){t.fn.dragScroll=function(n){if("object"==e(n)||!n)return function(){var e,o,a=t(this),r=t.extend({scrollVertical:!1,scrollHorizontal:!0,cursor:null},n),l=!1;a.on({mousemove:function(n){l&&function(n,a){var l=t(a);r.scrollVertical&&l.scrollTop(l.scrollTop()+(e-n.pageY)),r.scrollHorizontal&&l.scrollLeft(l.scrollLeft()+(o-n.pageX))}(n,this)},mousedown:function(t){a.css("cursor",r.cursor?r.cursor:r.scrollVertical&&r.scrollHorizontal?"move":r.scrollVertical?"row-resize":r.scrollHorizontal?"col-resize":void 0),l=!0,e=t.pageY,o=t.pageX},mouseup:function(){l=!1,a.css("cursor","auto")},mouseleave:function(){l=!1,a.css("cursor","auto")}})}.apply(this,arguments)}}(jQuery),$((function(){var e=$(".file-tree-wrapper");e.dragScroll();var t=$(".tree-form-container").find(".tree-loading"),n=$(".tree-categories-container").find(".tree-loading");function o(t){n.removeClass("d-none"),e.filetree().removeClass("d-none").hide().slideDown("slow"),n.addClass("d-none"),t&&e.find('li[data-id="'+t+'"] .category-name:first').addClass("active")}function a(e){$(".tree-form-body").html(e),Tec.initResources(),Tec.handleCounterUp(),window.EditorManagement&&(window.EDITOR=(new EditorManagement).init()),Tec.initMediaIntegrate()}function r(n,o){t.removeClass("d-none"),e.find("a.active").removeClass("active"),o&&o.addClass("active"),$httpClient.make().get(n).then((function(e){return a(e.data.data)})).finally((function(){return t.addClass("d-none")}))}o(),$(document).on("click",".tree-categories-container .toggle-tree",(function(e){var t=$(e.currentTarget),n=$(".tree-categories-container");t.hasClass("open-tree")?(t.text(t.data("collapse")),n.find(".folder-root.closed").removeClass("closed").addClass("open")):(t.text(t.data("expand")),n.find(".folder-root.open").removeClass("open").addClass("closed")),t.toggleClass("open-tree")})),e.on("click",".fetch-data",(function(t){t.preventDefault();var n=$(t.currentTarget);n.attr("href")?r(n.attr("href"),n):(e.find("a.active").removeClass("active"),n.addClass("active"))})),$(document).on("click",".tree-categories-create",(function(e){e.preventDefault(),function(e){var n={};l.get("ref_lang")&&(n.ref_lang=l.get("ref_lang"));t.removeClass("d-none"),$httpClient.make().get(e,n).then((function(e){return a(e.data.data)})).finally((function(){return t.addClass("d-none")}))}($(e.currentTarget).attr("href"))}));var l=new URLSearchParams(window.location.search);function i(t,n){$httpClient.make().get(e.data("url")||window.location.href).then((function(a){var r=a.data;e.html(r.data),o(t),jQuery().tooltip&&$('[data-bs-toggle="tooltip"]').tooltip({placement:"top",boundary:"window"}),n&&n()}))}$(document).on("click","#list-others-language a",(function(e){e.preventDefault(),r($(e.currentTarget).prop("href"))})),$(document).on("submit",".tree-form-container form",(function(e){var n;e.preventDefault();var o=$(e.currentTarget),r=new FormData(e.currentTarget),l=null===(n=e.originalEvent)||void 0===n?void 0:n.submitter,s=!1;l&&l.name&&(s="apply"===l.value,r.append(l.name,l.value));var c=o.attr("method").toLowerCase()||"post";t.removeClass("d-none"),$httpClient.make()[c](o.attr("action"),r).then((function(e){var n=e.data;Tec.showSuccess(n.message),t.addClass("d-none");var o=$(".tree-categories-create"),r=s&&n.data&&n.data.model?n.data.model.id:null;i(r,(function(){if(r){var e=$('.folder-root[data-id="'+r+'"] > a.fetch-data');e.length?e.trigger("click"):location.reload()}else if(o.length)o.trigger("click");else{var t;a(null===(t=n.data)||void 0===t?void 0:t.form)}}))})).finally((function(){t.addClass("d-none"),o.find("button[type=submit]").prop("disabled",!1).removeClass("disabled")}))})),$(document).on("click",".deleteDialog",(function(e){e.preventDefault();var t=$(e.currentTarget);$(".delete-crud-entry").data("section",t.data("section")),$(".modal-confirm-delete").modal("show")})),$(".delete-crud-entry").on("click",(function(e){e.preventDefault();var t=$(e.currentTarget);t.addClass("button-loading");var n=t.data("section");$httpClient.make().delete(n).then((function(e){var n=e.data;Tec.showSuccess(n.message),i();var o=$(".tree-categories-create");o.length?o.trigger("click"):a(""),t.closest(".modal").modal("hide")})).finally((function(){t.removeClass("button-loading")}))}))}))})();
