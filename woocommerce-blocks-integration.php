<?php

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;
use Automattic\WooCommerce\Blocks\Package;
use Automattic\WooCommerce\Blocks\Domain\Services\ExtendRestApi;
use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CheckoutSchema;

defined( 'ABSPATH' ) || exit;

/**
 * Class Newsletter_Blocks_Integration
 *
 * Class for integrating marketing optin block with WooCommerce Checkout
 *
 */
class Newsletter_Blocks_Integration implements IntegrationInterface {

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'newsletter-test';
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 */
	public function initialize() {
		$this->register_frontend_scripts();
		$this->register_editor_scripts();
		$this->register_editor_blocks();
		$this->extend_store_api();
		add_filter( '__experimental_woocommerce_blocks_add_data_attributes_to_block', [ $this, 'add_attributes_to_frontend_blocks' ], 10, 1 );
	}

	public function register_frontend_scripts() {
		$script_path       = '/build/newsletter-block-frontend.js';
		$script_url        = plugins_url( $script_path, __FILE__ );
		$script_asset_path = dirname( __FILE__ ) . '/build/newsletter-block-frontend.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => $this->get_file_version( $script_asset_path ),
			);

		wp_register_script(
			'newsletter-test-frontend',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
		wp_set_script_translations(
			'newsletter-test-frontend', // script handle
			'newsletter-test', // text domain
			dirname( __FILE__ ) . '/languages'
		);
	}

	public function register_editor_scripts() {
		$script_path       = '/build/newsletter-block.js';
		$script_url        = plugins_url( $script_path, __FILE__ );
		$script_asset_path = dirname( __FILE__ ) . '/build/newsletter-block.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => $this->get_file_version( $script_asset_path ),
			);

		wp_register_script(
			'newsletter-test-editor',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations(
			'newsletter-test-editor', // script handle
			'newsletter-test', // text domain
			dirname( __FILE__ ) . '/languages'
		);
	}
	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		return array( 'newsletter-test-frontend' );
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return array( 'newsletter-test-editor' );
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @return array
	 */
	public function get_script_data() {
		$data = array(
			'optinDefaultText' => __( 'I want to receive updates about products and promotions.', 'newsletter-test' ),
		);

		return $data;
	}

	/**
	 * Register blocks.
	 */
	public function register_editor_blocks() {
		register_block_type( dirname( __FILE__ ) . '/assets/js/checkout-newsletter-subscription-block', array(
			'editor_script' => 'newsletter-test-editor',
		) );
	}

	/**
	 * This allows dynamic (JS) blocks to access attributes in the frontend.
	 *
	 * @param string[] $allowed_blocks
	 */
	public function add_attributes_to_frontend_blocks( $allowed_blocks ) {
		$allowed_blocks[] = 'woocommerce/checkout-newsletter-subscription';
		return $allowed_blocks;
	}

	/**
	 * Add schema Store API to support posted data.
	 */
	public function extend_store_api() {
		$extend = Package::container()->get(
			ExtendRestApi::class
		);

		$extend->register_endpoint_data(
			array(
				'endpoint'        => CheckoutSchema::IDENTIFIER,
				'namespace'       => $this->get_name(),
				'schema_callback' => function() {
					return array(
						'optin' => array(
							'description' => __( 'Subscribe to marketing opt-in.', 'newsletter-test' ),
							'type'        => 'boolean',
							'context'     => array(),
							'arg_options' => array(
								'validate_callback' => function( $value ) {
									if ( ! is_bool( $value ) ) {
										return new \WP_Error( 'api-error', 'value of type ' . gettype( $value ) . ' was posted to the newslteer optin callback' );
									}
									return true;
								},
							),
						),
					);
				},
			)
		);
	}

	/**
	 * Get the file modified time as a cache buster if we're in dev mode.
	 *
	 * @param string $file Local path to the file.
	 * @return string The cache buster value to use for the given file.
	 */
	protected function get_file_version( $file ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( $file ) ) {
			return filemtime( $file );
		}
		return NEWSLETTER_VERSION;
	}
}
