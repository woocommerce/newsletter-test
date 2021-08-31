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

$version = '1.0.0';
function get_file_version( $file ) {
	global $version;
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( $file ) ) {
		return filemtime( $file );
	}
	return $version;
}
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
							'arg_options' => array(
								'validate_callback' => function( $value ) {
									return new \WP_Error( 'api-error', ( $value ? 'true' : 'false' ) . ' was posted to the newsletter validation callback' );
								},
							),
						),
					);
				},
			)
		);
		$asset_api            = \Automattic\WooCommerce\Blocks\Package::container()->get( \Automattic\WooCommerce\Blocks\Assets\Api::class );
		$assets_data_registry = \Automattic\WooCommerce\Blocks\Package::container()->get( \Automattic\WooCommerce\Blocks\Assets\AssetDataRegistry::class );
		new \Automattic\WooCommerce\Blocks\BlockTypes\AtomicBlock(
			$asset_api,
			$assets_data_registry,
			new \Automattic\WooCommerce\Blocks\Integrations\IntegrationRegistry(),
			'checkout-newsletter-subscription-block'
		);

		if ( is_callable( [ $extend, 'register_update_callback' ] ) ) {
			$extend->register_update_callback(
				array(
					'namespace' => 'newsletter-test',
					'callback'  => 'mark_newsletter_signup_checked',
				)
			);
		}
	}
);

function mark_newsletter_signup_checked( $data ) {
}
function register_scripts() {
	$script_path = '/build/index.js';
	//$style_path  = '/build/style-index.css';

	$script_url = plugins_url( $script_path, __FILE__ );
	//$style_url  = plugins_url( $style_path, __FILE__ );

	$script_asset_path = dirname( __FILE__ ) . '/build/index.asset.php';
	$script_asset      = file_exists( $script_asset_path )
		? require $script_asset_path
		: array(
			'dependencies' => array(),
			'version'      => get_file_version( $script_asset_path ),
		);

	wp_enqueue_script(
		'newsletter-test',
		$script_url,
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);
}

add_action( 'wp_enqueue_scripts', 'register_scripts' );
add_action( 'admin_enqueue_scripts', 'register_scripts' );