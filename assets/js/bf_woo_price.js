function BFWooPrice(){

    return{
        init: function(){
            var priceElements = jQuery('.bf_woo_price');
            if(priceElements.length > 0){
                jQuery.each(priceElements, function (i, currentElement) {
                    jQuery(currentElement).priceFormat();
                });
            }
        }
    }
}

var fncBFWooPrice = BFWooPrice();
jQuery(document).ready(function () {
    fncBFWooPrice.init();
});