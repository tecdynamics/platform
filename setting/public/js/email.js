$(document).ready((function(){$('[data-bb-toggle="test-email-send"]').on("click",(function(t){t.preventDefault();var e=$(t.currentTarget),n=new FormData(e.closest("form")[0]);Tec.showButtonLoading(e),$httpClient.make().postForm(e.data("url"),n).then((function(t){var e=t.data;Tec.showSuccess(e.message),$("#send-test-email-modal").modal("show")})).finally((function(){Tec.hideButtonLoading(e)}))})),$("#send-test-email-btn").on("click",(function(t){t.preventDefault();var e=$(t.currentTarget);Tec.showButtonLoading(e),$httpClient.make().post(e.data("url"),{email:e.closest(".modal-content").find("input[name=email]").val()}).then((function(t){var n=t.data;Tec.showSuccess(n.message),e.closest(".modal").modal("hide")})).finally((function(){Tec.hideButtonLoading(e)}))}))}));
