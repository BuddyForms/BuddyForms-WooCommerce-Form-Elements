jQuery(document).ready(function(jQuery) {
    jQuery(document.body).on('click', '.product_type_hidden' ,function(){
        var id = jQuery(this).attr('id');
        jQuery('.'+id).toggle(this.checked);
    });
    jQuery(document.body).on('click', '.product_manage_stock' ,function(){
        var id = jQuery(this).attr('id');
        jQuery('.'+id).toggle(this.unchecked);
    });
    jQuery(document.body).on('click', '.product_allow_backorders_options' ,function(){
        var id = jQuery(this).attr('id');
        jQuery('.'+id).toggle(this.checked);
    });
    jQuery(document.body).on('click', '.product_stock_status_options' ,function(){
        var id = jQuery(this).attr('id');
        jQuery('.'+id).toggle(this.checked);
    });
    jQuery(document.body).on('click', '.product_sold_individually_options' ,function(){
        var id = jQuery(this).attr('id');
        jQuery('.'+id).toggle(this.checked);
    });
    jQuery(document.body).on('click', '.product_manage_stock_qty_options' ,function(){
        var id = jQuery(this).attr('id');
        jQuery('.'+id).toggle(this.checked);
    });
});
