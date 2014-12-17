<?php

function bf_wc_downloadable($thepostid, $customfield){
    global $post;
    $post = get_post($thepostid);


        echo '<div class="options_group show_if_downloadable">';

        ?>

            <label><?php _e( 'Downloadable Files', 'woocommerce' ); ?>:</label><br>
            <table class="downloadable_files bf-upload" style="padding: 0">
                <thead>
                <tr>
                    <th class="sort">&nbsp;</th>
                    <th><?php _e( 'Name', 'woocommerce' ); ?> <span class="tips" data-tip="<?php _e( 'This is the name of the download shown to the customer.', 'woocommerce' ); ?>">[?]</span></th>
                    <th colspan="2"><?php _e( 'File URL', 'woocommerce' ); ?> <span class="tips" data-tip="<?php _e( 'This is the URL or absolute path to the file which customers will get access to.', 'woocommerce' ); ?>">[?]</span></th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $downloadable_files = get_post_meta( $thepostid, '_downloadable_files', true );

                if ( $downloadable_files ) {
                    foreach ( $downloadable_files as $key => $file ) {
                        include( WC()->plugin_path() . '/includes/admin/meta-boxes/views/html-product-download.php' );
                    }
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="5">
                        <a href="#" class="button insert" data-row="<?php
                        $file = array(
                            'file' => '',
                            'name' => ''
                        );
                        ob_start();
                        include( WC()->plugin_path() . '/includes/admin/meta-boxes/views/html-product-download.php' );
                        echo esc_attr( ob_get_clean() );
                        ?>"><?php _e( 'Add File', 'woocommerce' ); ?></a>
                    </th>
                </tr>
                </tfoot>
            </table>

        <?php

        // Download Limit
        woocommerce_wp_text_input( array( 'id' => '_download_limit', 'label' => __( 'Download Limit', 'woocommerce' ).'<br>', 'placeholder' => __( 'Unlimited', 'woocommerce' ), 'description' => '<br>'.__( 'Leave blank for unlimited re-downloads.', 'woocommerce' ), 'type' => 'number', 'custom_attributes' => array(
            'step' 	=> '1',
            'min'	=> '0'
        ) ) );

        // Expirey
        woocommerce_wp_text_input( array( 'id' => '_download_expiry', 'label' => __( 'Download Expiry', 'woocommerce' ).'<br>', 'placeholder' => __( 'Never', 'woocommerce' ), 'description' => '<br>'.__( 'Enter the number of days before a download link expires, or leave blank.', 'woocommerce' ), 'type' => 'number', 'custom_attributes' => array(
            'step' 	=> '1',
            'min'	=> '0'
        ) ) );

        // Download Type
        woocommerce_wp_select( array( 'id' => '_download_type', 'label' => __( 'Download Type', 'woocommerce' ).'<br>', 'description' => '<br>'.sprintf( __( 'Choose a download type - this controls the <a href="%s">schema</a>.', 'woocommerce' ), 'http://schema.org/' ), 'options' => array(
            ''            => __( 'Standard Product', 'woocommerce' ),
            'application' => __( 'Application/Software', 'woocommerce' ),
            'music'       => __( 'Music', 'woocommerce' ),
        ) ) );

        do_action( 'woocommerce_product_options_downloads' );


        if ( 'yes' == get_option( 'woocommerce_calc_taxes' ) ) {

            echo '<div class="options_group show_if_simple show_if_external show_if_variable">';

            // Tax
            woocommerce_wp_select( array( 'id' => '_tax_status', 'label' => __( 'Tax Status', 'woocommerce' ), 'options' => array(
                'taxable' 	=> __( 'Taxable', 'woocommerce' ),
                'shipping' 	=> __( 'Shipping only', 'woocommerce' ),
                'none' 		=> _x( 'None', 'Tax status', 'woocommerce' )
            ) ) );

            $tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'woocommerce_tax_classes' ) ) ) );
            $classes_options = array();
            $classes_options[''] = __( 'Standard', 'woocommerce' );

            if ( $tax_classes ) {

                foreach ( $tax_classes as $class ) {
                    $classes_options[ sanitize_title( $class ) ] = esc_html( $class );
                }
            }

            woocommerce_wp_select( array( 'id' => '_tax_class', 'label' => __( 'Tax Class', 'woocommerce' ), 'options' => $classes_options ) );

            do_action( 'woocommerce_product_options_tax' );

            echo '</div>';

        }

        do_action( 'woocommerce_product_options_general_product_data' );
        ?>
    </div>
<?php
}