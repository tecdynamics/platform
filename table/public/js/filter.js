(()=>{function t(e){return t="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},t(e)}function e(e,r){for(var n=0;n<r.length;n++){var i=r[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,(l=i.key,o=void 0,o=function(e,r){if("object"!==t(e)||null===e)return e;var n=e[Symbol.toPrimitive];if(void 0!==n){var i=n.call(e,r||"default");if("object"!==t(i))return i;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===r?String:Number)(e)}(l,"string"),"symbol"===t(o)?o:String(o)),i)}var l,o}var r=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t)}var r,n,i;return r=t,(n=[{key:"loadData",value:function(t){$httpClient.make().get($(".filter-data-url").val(),{class:$(".filter-data-class").val(),key:t.val(),value:t.closest(".filter-item").find(".filter-column-value").val()}).then((function(e){var r=e.data,n=$.map(r.data,(function(t,e){return{id:e,name:t}}));t.closest(".filter-item").find(".filter-column-value-wrap").html(r.html);var i=t.closest(".filter-item").find(".filter-column-value");i.length&&"text"===i.prop("type")&&(i.typeahead({source:n}),i.data("typeahead").source=n),Tec.initResources()}))}},{key:"init",value:function(){var t=this;$.each($(".filter-items-wrap .filter-column-key"),(function(e,r){$(r).val()&&t.loadData($(r))})),$(document).on("change",".filter-column-key",(function(e){t.loadData($(e.currentTarget))})),$(document).on("click",".btn-reset-filter-item",(function(t){t.preventDefault();var e=$(t.currentTarget);e.closest(".filter-item").find(".filter-column-key").val("").trigger("change"),e.closest(".filter-item").find(".filter-column-operator").val("="),e.closest(".filter-item").find(".filter-column-value").val("")})),$(document).on("click",".add-more-filter",(function(){var e=$(document).find(".sample-filter-item-wrap").html();$(document).find(".filter-items-wrap").append(e.replace("<script>","").replace("<\\/script>","")),Tec.initResources();var r=$(document).find(".filter-items-wrap .filter-item:last-child").find(".filter-column-key");$(r).val()&&t.loadData(r)})),$(document).on("click",".btn-remove-filter-item",(function(t){t.preventDefault(),$(t.currentTarget).closest(".filter-item").remove()}))}}])&&e(r.prototype,n),i&&e(r,i),Object.defineProperty(r,"prototype",{writable:!1}),t}();$(document).ready((function(){(new r).init()}))})();
