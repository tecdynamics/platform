(()=>{function t(n){return t="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},t(n)}function n(n,e){for(var r=0;r<e.length;r++){var o=e[r];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(n,(i=o.key,a=void 0,a=function(n,e){if("object"!==t(n)||null===n)return n;var r=n[Symbol.toPrimitive];if(void 0!==r){var o=r.call(n,e||"default");if("object"!==t(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(n)}(i,"string"),"symbol"===t(a)?a:String(a)),o)}var i,a}var e=function(){function t(){!function(t,n){if(!(t instanceof n))throw new TypeError("Cannot call a class as a function")}(this,t)}var e,r,o;return e=t,(r=[{key:"init",value:function(){$(document).on("click",".btn-clear-cache",(function(t){t.preventDefault();var n=$(t.currentTarget);n.addClass("button-loading"),$httpClient.make().post(n.data("url"),{type:n.data("type")}).then((function(t){var n=t.data;return Tec.showSuccess(n.message)})).finally((function(){return n.removeClass("button-loading")}))}))}}])&&n(e.prototype,r),o&&n(e,o),Object.defineProperty(e,"prototype",{writable:!1}),t}();$(document).ready((function(){(new e).init()}))})();
