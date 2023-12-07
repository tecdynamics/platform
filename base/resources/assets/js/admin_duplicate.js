class AdminDuplicate  {
    init(){
        jQuery('button#ok_action').on('click', function() {
            let val = jQuery('input.modal_name_imput').val();
            let url = jQuery(this).attr('_url')
            jQuery('#duplicateModal').modal('hide').remove()
            window.location.href=url+'?name='+val
        })
        jQuery('button.dismiss-action').on('click', function() {
            jQuery('#duplicateModal').modal('hide').remove()
        })
    }
    presentAlert(event){
        jQuery('body').append('<div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateModal" aria-hidden="true">'+
            '<div class="modal-dialog">'+
            '<div class="modal-content">'+
            '<div class="modal-header">'+
            '<button type="button" class="close dismiss-action" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
            '</div>  <div class="modal-body">  <label class="modal_note">New Name</label>'+
            '<input class="modal_name_imput form-control form-control-sm" placeholder="Enter Here"/>'+
            '<span class="modal_valid">Max 100 Characters</span></div>'+
            '<div class="modal-footer narr_footer">'+
            '<button type="button" class="btn btn-primary ok_btn" id="ok_action" _url="'+jQuery(event.currentTarget).attr("href")+'"   data-dismiss="modal">Create</button>'+
            '</div></div> </div></div>');

        $('#duplicateModal').modal('show')
        this.init()
    }
}


jQuery(document).ready(function(){
    jQuery('body').on('click','#duplicate',function(event){
        event.preventDefault()
        let mn = new AdminDuplicate()
        mn.presentAlert(event)
    })
})
