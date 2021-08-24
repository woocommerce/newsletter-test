<?php
/**
 * Plugin Name: Newsletter Test Plugin
 * Plugin URI: https://github.com/opr/newsletter-test
 * Description: Test the i2 integration
 * Author: opr
 * Author URI: https://github.com/opr
 * Version: 1.0.0
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

/**
 * Newsletter field integration
 */
add_action(
	'woocommerce_blocks_loaded',
	function() {
		if ( ! \Automattic\WooCommerce\Blocks\Package::feature()->is_experimental_build() ) {
			return;
		}
		$extend = \Automattic\WooCommerce\Blocks\Package::container()->get(
			\Automattic\WooCommerce\Blocks\Domain\Services\ExtendRestApi::class
		);
		$extend->register_endpoint_data(
			array(
				'endpoint'        => \Automattic\WooCommerce\Blocks\StoreApi\Schemas\CheckoutSchema::IDENTIFIER,
				'namespace'       => 'newsletter-extension',
				'schema_callback' => function() {
					return array(
						'newsletter' => array(
							'description' => __( 'Subscribe to newsletter opt-in.', 'woo-gutenberg-products-block' ),
							'type'        => 'boolean',
							'context'     => array(),
						),
					);
				},
			)
		);
	}
);