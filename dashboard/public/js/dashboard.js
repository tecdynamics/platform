(()=>{"use strict";var e={3744:(e,t)=>{t.Z=(e,t)=>{const n=e.__vccOpts||e;for(const[e,o]of t)n[e]=o;return n}}},t={};function n(o){var r=t[o];if(void 0!==r)return r.exports;var a=t[o]={exports:{}};return e[o](a,a.exports,n),a.exports}(()=>{const e=Vue;var t={key:0,class:"note note-warning"},o=["href"];const r={props:{verifyUrl:{type:String,default:function(){return null},required:!0},settingUrl:{type:String,default:function(){return null},required:!0}},data:function(){return{verified:!0}},mounted:function(){this.verifyLicense()},methods:{verifyLicense:function(){var e=this;axios.get(this.verifyUrl).then((function(t){t.data.error&&(e.verified=!1)})).catch((function(){}))}}};var a=n(3744);const l=(0,a.Z)(r,[["render",function(n,r,a,l,i,s){return i.verified?(0,e.createCommentVNode)("",!0):((0,e.openBlock)(),(0,e.createElementBlock)("div",t,[(0,e.createElementVNode)("p",null,[(0,e.createTextVNode)(" Your license is invalid, please contact support. If you didn't setup license code, please go to "),(0,e.createElementVNode)("a",{href:a.settingUrl},"Settings",8,o),(0,e.createTextVNode)(" to activate license! ")])]))}]]);var i={key:0,class:"note note-warning"},s=["href"];const d={props:{checkUpdateUrl:{type:String,default:function(){return null},required:!0},settingUrl:{type:String,default:function(){return null},required:!0}},data:function(){return{hasNewVersion:!1,message:null}},mounted:function(){this.checkUpdate()},methods:{checkUpdate:function(){var e=this;axios.get(this.checkUpdateUrl).then((function(t){!t.data.error&&t.data.data.has_new_version&&(e.hasNewVersion=!0,e.message=t.data.message)})).catch((function(){}))}}},c=(0,a.Z)(d,[["render",function(t,n,o,r,a,l){return a.hasNewVersion?((0,e.openBlock)(),(0,e.createElementBlock)("div",i,[(0,e.createElementVNode)("p",null,[(0,e.createTextVNode)((0,e.toDisplayString)(a.message)+", please go to ",1),(0,e.createElementVNode)("a",{href:o.settingUrl},"System Updater",8,s),(0,e.createTextVNode)(" to upgrade to the latest version!")])])):(0,e.createCommentVNode)("",!0)}]]);function u(e){return u="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},u(e)}function p(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,(r=o.key,a=void 0,a=function(e,t){if("object"!==u(e)||null===e)return e;var n=e[Symbol.toPrimitive];if(void 0!==n){var o=n.call(e,t||"default");if("object"!==u(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(r,"string"),"symbol"===u(a)?a:String(a)),o)}var r,a}"undefined"!=typeof vueApp&&vueApp.booting((function(e){e.component("verify-license-component",l),e.component("check-update-component",c)}));var f={},g=function(){function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e)}var t,n,o;return t=e,o=[{key:"loadWidget",value:function(t,n,o,r){var a=t.closest(".widget_item"),l=a.attr("id");void 0!==r&&(f[l]=r);var i=a.find("a.collapse-expand");if(!i.length||!i.hasClass("collapse")){Tec.blockUI({target:t,iconOnly:!0,overlayColor:"none"}),void 0!==o&&null!=o||(o={});var s=a.find("select[name=predefined_range]");s.length&&(o.predefined_range=s.val()),$httpClient.makeWithoutErrorHandler().get(n,o).then((function(n){var o=n.data;t.html(o.data),void 0!==r?r():f[l]&&f[l](),0!==t.find(".scroller").length&&Tec.callScroll(t.find(".scroller")),$(".equal-height").equalHeights(),e.initSortable()})).catch((function(e){t.html('<div class="dashboard_widget_msg col-12"><p>'+e.response.data.message+"</p>")})).finally((function(){Tec.unblockUI(t)}))}}},{key:"initSortable",value:function(){if($("#list_widgets").length>0){var e=document.getElementById("list_widgets");Sortable.create(e,{group:"widgets",sort:!0,delay:0,disabled:!1,store:null,animation:150,handle:".portlet-title",ghostClass:"sortable-ghost",chosenClass:"sortable-chosen",dataIdAttr:"data-id",forceFallback:!1,fallbackClass:"sortable-fallback",fallbackOnBody:!1,scroll:!0,scrollSensitivity:30,scrollSpeed:10,onUpdate:function(){var e=[];$.each($(".widget_item"),(function(t,n){e.push($(n).prop("id"))})),$httpClient.makeWithoutErrorHandler().post(route("dashboard.update_widget_order"),{items:e}).then((function(e){var t=e.data;Tec.showSuccess(t.message)}))}})}}}],(n=[{key:"init",value:function(){var t=$("#list_widgets");$(document).on("click",".portlet > .portlet-title .tools > a.remove",(function(e){e.preventDefault(),$("#hide-widget-confirm-bttn").data("id",$(e.currentTarget).closest(".widget_item").prop("id")),$("#hide_widget_modal").modal("show")})),t.on("click",".page_next, .page_previous",(function(t){t.preventDefault();var n=$(t.currentTarget),o=n.prop("href");o&&e.loadWidget(n.closest(".portlet").find(".portlet-body"),o)})),t.on("change",".number_record .numb",(function(t){t.preventDefault();var n=$(t.currentTarget),o=n.closest(".number_record").find(".numb").val();!isNaN(o)&&o>0?e.loadWidget(n.closest(".portlet").find(".portlet-body"),n.closest(".widget_item").attr("data-url"),{paginate:o}):Tec.showError("Please input a number!")})),t.on("click",".btn_change_paginate",(function(e){e.preventDefault();var t=$(e.currentTarget),n=t.closest(".number_record").find(".numb"),o=parseInt(n.prop("min")||5),r=parseInt(n.prop("max")||100),a=parseInt(n.prop("step")||5),l=parseInt(n.val());t.hasClass("btn_up")?l<r&&(l+=a):t.hasClass("btn_down")&&(l-a>0?l-=a:l=a,l<o&&(l=o)),l!=parseInt(n.val())&&n.val(l).trigger("change")})),$("#hide-widget-confirm-bttn").on("click",(function(e){e.preventDefault();var t=$(e.currentTarget).data("id");$httpClient.makeWithoutErrorHandler().get(route("dashboard.hide_widget",{name:t})).then((function(e){var n=e.data;$("#"+t).fadeOut(),Tec.showSuccess(n.message)})).finally((function(){$("#hide_widget_modal").modal("hide");var t=$(e.currentTarget).closest(".portlet");$(document).hasClass("page-portlet-fullscreen")&&$(document).removeClass("page-portlet-fullscreen"),t.find("[data-bs-toggle=tooltip]").tooltip("destroy"),t.remove()}))})),$(document).on("click",".portlet:not(.widget-load-has-callback) > .portlet-title .tools > a.reload",(function(t){t.preventDefault();var n=$(t.currentTarget);e.loadWidget(n.closest(".portlet").find(".portlet-body"),n.closest(".widget_item").attr("data-url"))})),$(document).on("click",".portlet > .portlet-title .tools > .collapse, .portlet .portlet-title .tools > .expand",(function(t){t.preventDefault();var n=$(t.currentTarget),o=n.closest(".portlet"),r=$.trim(n.data("state"));"expand"===r?(o.find(".portlet-body").removeClass("collapse").addClass("expand"),e.loadWidget(o.find(".portlet-body"),n.closest(".widget_item").attr("data-url"))):o.find(".portlet-body").removeClass("expand").addClass("collapse"),$httpClient.makeWithoutErrorHandler().post(route("dashboard.edit_widget_setting_item"),{name:n.closest(".widget_item").prop("id"),setting_name:"state",setting_value:r}).then((function(){"collapse"===r?(n.data("state","expand"),o.find(".predefined-ranges").addClass("d-none"),o.find("a.reload").addClass("d-none"),o.find("a.fullscreen").addClass("d-none")):(n.data("state","collapse"),o.find(".predefined-ranges").removeClass("d-none"),o.find("a.reload").removeClass("d-none"),o.find("a.fullscreen").removeClass("d-none"))}))})),$(document).on("change",".portlet select[name=predefined_range]",(function(t){t.preventDefault();var n=$(t.currentTarget);e.loadWidget(n.closest(".portlet").find(".portlet-body"),n.closest(".widget_item").attr("data-url"),{changed_predefined_range:1})}));var n=$("#manage_widget_modal");$(document).on("click",".manage-widget",(function(e){e.preventDefault(),n.modal("show")})),n.on("change",".swc_wrap input",(function(e){$(e.currentTarget).closest("section").find("i").toggleClass("widget_none_color")}))}}])&&p(t.prototype,n),o&&p(t,o),Object.defineProperty(t,"prototype",{writable:!1}),e}();$(document).ready((function(){(new g).init(),window.BDashboard=g}))})()})();
