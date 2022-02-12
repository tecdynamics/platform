(()=>{"use strict";function e(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}const t=function(){function t(e,n,o){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),this.loader=e,this.url=n,this.t=o}var n,o;return n=t,(o=[{key:"upload",value:function(){var e=this;return this.loader.file.then((function(t){return new Promise((function(n,o){e._initRequest(),e._initListeners(n,o,t),e._sendRequest(t)}))}))}},{key:"abort",value:function(){this.xhr&&this.xhr.abort()}},{key:"_initRequest",value:function(){var e=this.xhr=new XMLHttpRequest;e.open("POST",this.url,!0),e.responseType="json"}},{key:"_initListeners",value:function(e,t,n){var o=this.xhr,i=this.loader,a=(0,this.t)("Cannot upload file:")+" ".concat(n.name,".");o.addEventListener("error",(function(){return t(a)})),o.addEventListener("abort",(function(){return t()})),o.addEventListener("load",(function(){var n=o.response;if(!n||!n.uploaded)return t(n&&n.error&&n.error.message?n.error.message:a);e({default:n.url})})),o.upload&&o.upload.addEventListener("progress",(function(e){e.lengthComputable&&(i.uploadTotal=e.total,i.uploaded=e.loaded)}))}},{key:"_sendRequest",value:function(e){var t=new FormData;t.append("upload",e),t.append("_token",$('meta[name="csrf-token"]').attr("content")),this.xhr.send(t)}}])&&e(n.prototype,o),Object.defineProperty(n,"prototype",{writable:!1}),t}();function n(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function o(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function i(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}var a=function(){function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),this.CKEDITOR={},this.shortcodes=[]}var a,r;return a=e,r=[{key:"initCkEditor",value:function(e,i){var a=this;if(this.CKEDITOR[e]||!$("#"+e).is(":visible"))return!1;var r=document.querySelector("#"+e);ClassicEditor.create(r,function(e){for(var t=1;t<arguments.length;t++){var i=null!=arguments[t]?arguments[t]:{};t%2?n(Object(i),!0).forEach((function(t){o(e,t,i[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(i)):n(Object(i)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(i,t))}))}return e}({fontSize:{options:[9,11,13,"default",17,16,18,19,21,22,23,24]},alignment:{options:["left","right","center","justify"]},shortcode:{onEdit:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:function(){},n=null;a.shortcodes.forEach((function(e){if(e.key===t)return n=e.description,!0})),a.shortcodeCallback({key:t,href:route("short-codes.ajax-get-admin-config",t),data:{code:e},description:n,update:!0})},shortcodes:this.getShortcodesAvailable(r),onCallback:function(e,t){a.shortcodeCallback({key:e,href:t.url})}},heading:{options:[{model:"paragraph",title:"Paragraph",class:"ck-heading_paragraph"},{model:"heading1",view:"h1",title:"Heading 1",class:"ck-heading_heading1"},{model:"heading2",view:"h2",title:"Heading 2",class:"ck-heading_heading2"},{model:"heading3",view:"h3",title:"Heading 3",class:"ck-heading_heading3"},{model:"heading4",view:"h4",title:"Heading 4",class:"ck-heading_heading4"}]},placeholder:" ",toolbar:{items:["heading","|","fontColor","fontSize","fontBackgroundColor","fontFamily","bold","italic","underline","link","strikethrough","bulletedList","numberedList","|","alignment","shortcode","outdent","indent","|","htmlEmbed","imageInsert","blockQuote","insertTable","mediaEmbed","undo","redo","findAndReplace","removeFormat","sourceEditing","codeBlock"]},language:"en",image:{toolbar:["imageTextAlternative","imageStyle:inline","imageStyle:block","imageStyle:side","toggleImageCaption","ImageResize"],upload:{types:["jpeg","png","gif","bmp","webp","tiff","svg+xml"]}},link:{defaultProtocol:"http://",decorators:{openInNewTab:{mode:"manual",label:"Open in a new tab",attributes:{target:"_blank",rel:"noopener noreferrer"}}}},table:{contentToolbar:["tableColumn","tableRow","mergeTableCells","tableCellProperties","tableProperties"]},htmlSupport:{allow:[{name:/.*/,attributes:!0,classes:!0,styles:!0}]}},i)).then((function(n){n.plugins.get("FileRepository").createUploadAdapter=function(e){return new t(e,RV_MEDIA_URL.media_upload_from_editor,n.t)},n.insertHtml=function(e){var t=n.data.processor.toView(e),o=n.data.toModel(t);n.model.insertContent(o)},window.editor=n,a.CKEDITOR[e]=n;var o,i=90*$("#"+e).prop("rows"),r="ckeditor-".concat(e,"-inline");$(n.ui.view.editable.element).addClass(r).after("\n                    <style>\n                        .ck-editor__editable_inline {\n                            min-height: ".concat(i-100,"px;\n                            max-height: ").concat(i+100,"px;\n                        }\n                    </style>\n                ")),n.model.document.on("change:data",(function(){clearTimeout(o),o=setTimeout((function(){n.updateSourceElement()}),150)})),n.commands._commands.get("mediaEmbed").execute=function(e){n.insertHtml('[media url="'.concat(e,'"][/media]'))}})).catch((function(e){console.error(e)}))}},{key:"getShortcodesAvailable",value:function(e){var t,n=null===(t=$(e).parents(".form-group").find(".add_shortcode_btn_trigger"))||void 0===t?void 0:t.next(".dropdown-menu"),o=[];return n&&n.find("> li").each((function(){var e=$(this).find("> a");o.push({key:e.data("key"),hasConfig:e.data("has-admin-config"),name:e.text(),url:e.attr("href"),description:e.data("description")})})),this.shortcodes=o,o}},{key:"uploadImageFromEditor",value:function(e,t){var n=new FormData;"function"==typeof e.blob?n.append("upload",e.blob(),e.filename()):n.append("upload",e),$.ajax({type:"POST",data:n,url:RV_MEDIA_URL.media_upload_from_editor,processData:!1,contentType:!1,cache:!1,success:function(e){e.uploaded&&t(e.url)}})}},{key:"initTinyMce",value:function(e){var t=this;tinymce.init({menubar:!0,selector:"#"+e,min_height:110*$("#"+e).prop("rows"),resize:"vertical",plugins:"code autolink advlist visualchars link image media table charmap hr pagebreak nonbreaking hanbiroclip anchor insertdatetime lists textcolor wordcount imagetools  contextmenu  visualblocks",extended_valid_elements:"input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick]",toolbar:"formatselect | bold italic strikethrough forecolor backcolor | link image table | alignleft aligncenter alignright alignjustify  | numlist bullist indent  |  visualblocks code",convert_urls:!1,image_caption:!0,image_advtab:!0,image_title:!0,placeholder:"",contextmenu:"link image inserttable | cell row column deletetable",images_upload_url:RV_MEDIA_URL.media_upload_from_editor,automatic_uploads:!0,block_unsupported_drop:!1,file_picker_types:"file image media",images_upload_handler:this.uploadImageFromEditor.bind(this),file_picker_callback:function(e){$('<input type="file" accept="image/*" />').click().on("change",(function(n){t.uploadImageFromEditor(n.target.files[0],e)}))}})}},{key:"initEditor",value:function(e,t,n){if(!e.length)return!1;var o=this;switch(n){case"ckeditor":$.each(e,(function(e,n){o.initCkEditor($(n).prop("id"),t)}));break;case"tinymce":$.each(e,(function(e,t){o.initTinyMce($(t).prop("id"))}))}}},{key:"init",value:function(){var e=this,t=$(document).find(".editor-ckeditor"),n=$(document).find(".editor-tinymce"),o=this;return t.length>0&&o.initEditor(t,{},"ckeditor"),n.length>0&&o.initEditor(n,{},"tinymce"),$(document).on("click",".show-hide-editor-btn",(function(t){t.preventDefault();var n=$(t.currentTarget).data("result"),i=$("#"+n);i.hasClass("editor-ckeditor")?e.CKEDITOR[n]&&void 0!==e.CKEDITOR[n]?(e.CKEDITOR[n].destroy(),e.CKEDITOR[n]=null,$(".editor-action-item").not(".action-show-hide-editor").hide()):(o.initCkEditor(n,{},"ckeditor"),$(".editor-action-item").not(".action-show-hide-editor").show()):i.hasClass("editor-tinymce")&&tinymce.execCommand("mceToggleEditor",!1,n)})),this.manageShortCode(),this}},{key:"shortcodeCallback",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=e.href,n=e.key,o=e.description,i=void 0===o?null:o,a=e.data,r=void 0===a?{}:a,l=e.update,d=void 0!==l&&l;$(".short-code-admin-config").html("");var c=$(".short_code_modal .add_short_code_btn");d?c.text(c.data("update-text")):c.text(c.data("add-text")),""!==i&&null!=i&&$(".short_code_modal .modal-title strong").text(i),$(".short_code_modal").modal("show"),$(".half-circle-spinner").show(),$.ajax({type:"GET",data:r,url:t,success:function(e){if(e.error)return Tec.showError(e.message),!1;$(".short-code-data-form").trigger("reset"),$(".short_code_input_key").val(n),$(".half-circle-spinner").hide(),$(".short-code-admin-config").html(e.data),Tec.initResources(),Tec.initMediaIntegrate()},error:function(e){Tec.handleError(e)}})}},{key:"manageShortCode",value:function(){var e=this;$(".list-shortcode-items li a").on("click",(function(t){if(t.preventDefault(),"1"==$(this).data("has-admin-config"))e.shortcodeCallback({href:$(this).prop("href"),key:$(this).data("key"),description:$(this).data("description")});else{var n=$(".add_shortcode_btn_trigger").data("result"),o="["+$(this).data("key")+"][/"+$(this).data("key")+"]";$(".editor-ckeditor").length>0?e.CKEDITOR[n].commands.execute("shortcode",o):tinymce.get(n).execCommand("mceInsertContent",!1,o)}})),$.fn.serializeObject=function(){var e={},t=this.serializeArray();return $.each(t,(function(){e[this.name]?(e[this.name].push||(e[this.name]=[e[this.name]]),e[this.name].push(this.value||"")):e[this.name]=this.value||""})),e},$(".add_short_code_btn").on("click",(function(t){t.preventDefault();var n=$(".short_code_modal").find(".short-code-data-form"),o=n.serializeObject(),i="";$.each(o,(function(e,t){var o=n.find('*[name="'+e+'"]'),a=o.data("shortcode-attribute");a&&"content"===a||!t||(e=e.replace("[]",""),"content"!==o.data("shortcode-attribute")&&(e=e.replace("[]",""),i+=" "+e+'="'+t+'"'))}));var a="",r=n.find("*[data-shortcode-attribute=content]");null!=r&&null!=r.val()&&""!==r.val()&&(a=r.val());var l=$(this).closest(".short_code_modal").find(".short_code_input_key").val(),d=$(".add_shortcode_btn_trigger").data("result"),c="["+l+i+"]"+a+"[/"+l+"]";$(".editor-ckeditor").length>0?e.CKEDITOR[d].commands.execute("shortcode",c):tinymce.get(d).execCommand("mceInsertContent",!1,c),$(this).closest(".modal").modal("hide")}))}}],r&&i(a.prototype,r),Object.defineProperty(a,"prototype",{writable:!1}),e}();$(document).ready((function(){window.EDITOR=(new a).init(),window.EditorManagement=window.EditorManagement||a}))})();
