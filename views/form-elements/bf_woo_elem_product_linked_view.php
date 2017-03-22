<div id="linked_product_data">

	<div class="options_group">

		<?php if ( ! isset( $customfield['product_up_sales'] ) ) { ?>
			<p class="form-field">
				<label for="upsell_ids"><?php _e( 'Up-Sells', 'woocommerce' ); ?></label>
				<input type="hidden" class="wc-product-search" style="width: 50%;" id="upsell_ids"
				       name="upsell_ids"
				       data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>"
				       data-action="woocommerce_json_search_products" data-multiple="true"
				       data-exclude="<?php echo intval( $post->ID ); ?>" data-selected="<?php
				$product_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_upsell_ids', true ) ) );
				$json_ids    = array();

				foreach ( $product_ids as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						$json_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name(), ENT_QUOTES, get_bloginfo( 'charset' ) ) );
					}
				}

				echo esc_attr( json_encode( $json_ids ) );
				?>"
				       value="<?php echo implode( ',', array_keys( $json_ids ) ); ?>"/> <?php echo wc_help_tip( __( 'Up-sells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'woocommerce' ) ); ?>
			</p>
		<?php } ?>

		<?php if ( ! isset( $customfield['product_cross_sales'] ) ) { ?>
			<p class="form-field">
				<label for="crosssell_ids"><?php _e( 'Cross-Sells', 'woocommerce' ); ?></label>
				<input type="hidden" class="wc-product-search" style="width: 50%;" id="crosssell_ids"
				       name="crosssell_ids"
				       data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>"
				       data-action="woocommerce_json_search_products" data-multiple="true"
				       data-exclude="<?php echo intval( $post->ID ); ?>" data-selected="<?php
				$product_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_crosssell_ids', true ) ) );
				$json_ids    = array();

				foreach ( $product_ids as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						$json_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name(), ENT_QUOTES, get_bloginfo( 'charset' ) ) );
					}
				}

				echo esc_attr( json_encode( $json_ids ) );
				?>"
				       value="<?php echo implode( ',', array_keys( $json_ids ) ); ?>"/> <?php echo wc_help_tip( __( 'Cross-sells are products which you promote in the cart, based on the current product.', 'woocommerce' ) ); ?>
			</p>
		<?php } ?>
	</div>


	<div class="options_group grouping show_if_simple show_if_external">
		<?php

		// List Grouped products
		$post_parents     = array();
		$post_parents[''] = __( 'Choose a grouped product&hellip;', 'woocommerce' );

		if ( $grouped_term = get_term_by( 'slug', 'grouped', 'product_type' ) ) {

			$posts_in = array_unique( (array) get_objects_in_term( $grouped_term->term_id, 'product_type' ) );

			if ( sizeof( $posts_in ) > 0 ) {

				$args = array(
					'post_type'        => 'product',
					'post_status'      => 'any',
					'numberposts'      => - 1,
					'orderby'          => 'title',
					'order'            => 'asc',
					'post_parent'      => 0,
					'suppress_filters' => 0,
					'include'          => $posts_in,
				);

				$grouped_products = get_posts( $args );

				if ( $grouped_products ) {

					foreach ( $grouped_products as $product ) {

						if ( $product->ID == $thepostid ) {
							continue;
						}

						$post_parents[ $product->ID ] = $product->post_title;
					}
				}
			}

		}
		if ( ! isset( $customfield['product_grouping'] ) ) {
			woocommerce_wp_select( array(
				'id'          => 'post_parent',
				'label'       => __( 'Grouping', 'woocommerce' ) . '<br>',
				'value'       => absint( $post->post_parent ),
				'options'     => $post_parents,
				'desc_tip'    => true,
				'description' => __( 'Set this option to make this product part of a grouped product.', 'woocommerce' )
			) );
			woocommerce_wp_hidden_input( array(
				'id'    => 'previous_parent_id',
				'value' => absint( $post->post_parent )
			) );
			do_action( 'woocommerce_product_options_grouping' );
		}

		echo '</div>';
		?>

		<?php do_action( 'woocommerce_product_options_related' ); ?>

	</div>