jQuery(document).ready(function(jQuery) {
    jQuery('.product_attributes').on('click', 'button.remove_row', function() {
        var answer = confirm('Are you sure');
        if (answer){
            var $parent = jQuery(this).parent().parent();

            if ($parent.is('.taxonomy')) {
                $parent.find('select, input[type=text]').val('');
                $parent.hide();
            } else {
                $parent.find('select, input[type=text]').val('');
                $parent.hide();
                attribute_row_indexes();
            }
        }
        return false;
    });
});