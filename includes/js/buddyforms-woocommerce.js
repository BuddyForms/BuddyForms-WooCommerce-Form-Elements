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

    // Add rows
    jQuery('button.add_attribute').on('click', function(){

        var size = jQuery('.product_attributes .woocommerce_attribute').size();

        var attribute_type = jQuery('select.attribute_taxonomy').val();

        if (!attribute_type) {

            var product_type = jQuery('select#product-type').val();
            if (product_type!='variable') enable_variation = 'style="display:none;"'; else enable_variation = '';

            // Add custom attribute row
            jQuery('.product_attributes').append('<div class="woocommerce_attribute wc-metabox">\
					<h3>\
						<button type="button" class="remove_row button">Remove</button>\
						<div class="handlediv" title="Click to Toggle"></div>\
						<strong class="attribute_name"></strong>\
					</h3>\
					<table cellpadding="0" cellspacing="0" class="woocommerce_attribute_data">\
						<tbody>\
							<tr>\
								<td class="attribute_name">\
									<label>Name:</label>\
									<input type="text" class="attribute_name" name="attribute_names[' + size + ']" />\
									<input type="hidden" name="attribute_is_taxonomy[' + size + ']" value="0" />\
									<input type="hidden" name="attribute_position[' + size + ']" class="attribute_position" value="' + size + '" />\
								</td>\
								<td rowspan="3">\
									<label>Values:</label>\
									<textarea name="attribute_values[' + size + ']" cols="5" rows="5" placeholder="Enter some text, or some attributes by pipe (|) separating values."></textarea>\
								</td>\
							</tr>\
							<tr>\
								<td>\
									<label><input type="checkbox" class="checkbox" name="attribute_visibility[' + size + ']" value="1" /> Visible on the product page</label>\
								</td>\
							</tr>\
							<tr>\
								<td>\
									<div class="enable_variation show_if_variable">\
									<label><input type="checkbox" class="checkbox" name="attribute_variation[' + size + ']" value="1" /> Used for variations</label>\
									</div>\
								</td>\
							</tr>\
						</tbody>\
					</table>\
				</div>');

        } else {

            // Reveal taxonomy row
            var thisrow = jQuery('.product_attributes .woocommerce_attribute.' + attribute_type);
            jQuery('.product_attributes').append( jQuery(thisrow) );
            jQuery(thisrow).show().find('.woocommerce_attribute_data').show();
            attribute_row_indexes();

        }

        jQuery('select.attribute_taxonomy').val('');
    });


});