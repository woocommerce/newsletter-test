<?php
/**
 * Plugin Name: Newsletter Test Plugin
 * Plugin URI: https://github.com/opr/newsletter-test
 * Description: Test the i2 integration
 * Author: opr
 * Author URI: https://github.com/opr
 * Version: 2.0.0
 * Text Domain: woocommerce-blocks-newsletter-test
 * Domain Path: /languages/
 * Tested up to: 5.6.1
 * WC tested up to: 5.0
 * WC requires at least: 2.6
 *
 * Copyright: © 2021 WooCommerce
 *
 * License: GNU General Public License v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

define( 'NEWSLETTER_VERSION', '2.0.0' );
if ( class_exists( '\Automattic\WooCommerce\Blocks\Package' ) ) {
	require dirname( __FILE__ ) . '/woocommerce-blocks-integration.php';
	add_action(
		'woocommerce_blocks_checkout_block_registration',
		function( $integration_registry ) {
			$integration_registry->register( new Newsletter_Blocks_Integration() );
		},
		10,
		1
	);

	add_action(
		'woocommerce_blocks_checkout_update_order_from_request',
		function( $order, $request ) {
			$optin = $request['extensions']['automatewoo'][ 'optin' ];
			// your logic
		},
		10,
		2
	);
}