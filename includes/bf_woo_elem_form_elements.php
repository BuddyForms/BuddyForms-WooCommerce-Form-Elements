<?php


	class bf_woo_elem_form_element {

		public function __construct() {
			add_filter( 'buddyforms_create_edit_form_display_element', array(
				$this,
				'buddyforms_woocommerce_create_frontend_form_element'
			), 1, 2 );
			$this->helpTip();
		}


		public function helpTip() {
			if ( ! is_admin() && ! function_exists( 'wc_help_tip' ) ) {

				/**
				 * Display a WooCommerce help tip.
				 *
				 * @since  2.5.0
				 *
				 * @param  string $tip Help tip text
				 * @param  bool $allow_html Allow sanitized HTML if true or escape
				 *
				 * @return string
				 */
				function wc_help_tip( $tip, $allow_html = false ) {
					if ( $allow_html ) {
						$tip = wc_sanitize_tooltip( $tip );
					} else {
						$tip = esc_attr( $tip );
					}

					return '<span class="woocommerce-help-tip" data-tip="' . $tip . '"></span>';
				}

			}
		}

		public function buddyforms_woocommerce_create_frontend_form_element( $form, $form_args ) {
			global $thepostid, $post;
			extract( $form_args );
			if ( ! isset( $customfield['type'] ) ) {
				return $form;
			}
			$thepostid = $post->ID;//TODO check that the post id is assigned to this identifier.
			$post      = get_post( $thepostid );
			switch ( $customfield['type'] ) {

				case 'woocommerce':

					$form->addElement( new Element_HTML( '<div id="woocommerce-product-data" class="form-field ">' ) );

					ob_start();
					bf_wc_product_type( $thepostid, $customfield );
					$get_contents = ob_get_contents();
					ob_clean();
					$form->addElement( new Element_HTML( $get_contents ) );

					ob_start();
					bf_wc_product_general( $thepostid, $customfield );
					$get_contents = ob_get_contents();
					ob_clean();
					$form->addElement( new Element_HTML( $get_contents ) );

					ob_start();
					bf_wc_downloadable( $thepostid, $customfield );
					$get_contents = ob_get_contents();
					ob_clean();
					$form->addElement( new Element_HTML( $get_contents ) );

//                ob_start();
//                    bf_wc_variations_custom($post_id, $customfield);
//                    $get_contents = ob_get_contents();
//                ob_clean();
//                $form->addElement(  new Element_HTML($get_contents) );

					$form->addElement( new Element_HTML( '</div>' ) );

					// Inventory

					ob_start();
					bf_wc_product_inventory( $thepostid, $customfield );
					$get_contents = ob_get_contents();
					ob_clean();

					$form->addElement( new Element_HTML( $get_contents ) );

					// 'Shipping':

					ob_start();
					bf_wc_shipping( $thepostid, $customfield );
					$get_contents = ob_get_contents();
					ob_clean();

					$form->addElement( new Element_HTML( $get_contents ) );

					// Linked-Products':

					ob_start();
					bf_wc_product_linked( $thepostid, $customfield );
					$get_contents = ob_get_contents();
					ob_clean();

					$form->addElement( new Element_HTML( $get_contents ) );

					break;

				case 'attributes':


					ob_start();
					bf_wc_attrebutes_custom( $thepostid, $customfield );
					$get_contents = ob_get_contents();
					ob_clean();

					$form->addElement( new Element_HTML( $get_contents ) );

					break;

				case 'product-gallery':
					// Product Gallery

					ob_start();
					$post = get_post( $thepostid );
					BF_WC_Meta_Box_Product_Images::output( $post, $customfield );
					$get_contents = ob_get_contents();
					ob_clean();

					$form->addElement( new Element_HTML( $get_contents ) );

					break;

			}

			return $form;
		}

	}