<?php
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 20/03/2017
 * Time: 14:36
 */

	class bf_woo_elem_requirements extends WP_Requirements {
		public function __construct( $text_domain = 'WP_Requirements' ) {
			parent::__construct( $text_domain );
		}
		/**
		 * Set the plugins requirements
		 *
		 * @return array
		 */
		function getRequirements() {
			$requirements                = array();
			$requirement                 = new WP_PHP_Requirement();
			$requirement->minimumVersion = '5.3.0';
			array_push( $requirements, $requirement );
			$requirement                 = new WP_WordPress_Requirement();
			$requirement->minimumVersion = '4.6.2';
			array_push( $requirements, $requirement );
			$requirement          = new WP_Plugins_Requirement();
			$requirement->plugins = array(
				array( 'id' => 'woocommerce/woocommerce.php', 'name' => 'WooCommerce', 'min_version' => '2.0.0' )
			);
			array_push( $requirements, $requirement );

			$requirement          = new WP_Plugins_Requirement();
			$requirement->plugins = array(
				array( 'id' => 'budyforms-premium/BuddyForms.php', 'name' => 'BuddyForms', 'min_version' => '2.0.0' )
			);
			array_push( $requirements, $requirement );

			return $requirements;
		}

	}