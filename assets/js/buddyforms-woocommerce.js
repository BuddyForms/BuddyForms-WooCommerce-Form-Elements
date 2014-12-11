jQuery(document).ready(function(jQuery) {

    jQuery(document.body).on('click', '.product_type_hidden' ,function(){
        var id = jQuery(this).attr('id');
        jQuery('.'+id).toggle(this.checked);
    });

});
