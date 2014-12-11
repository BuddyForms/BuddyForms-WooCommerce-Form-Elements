jQuery(document).ready(function(jQuery) {

    jQuery('.product_type_hidden').click(function(){
        var id = jQuery(this).attr('id');
        jQuery('.'+id).toggle(this.checked);
    });

});
