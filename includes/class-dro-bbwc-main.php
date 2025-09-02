<?php
/**
 * Beautiful Blocks for WooCommerce
 *
 * @package   Beautiful Blocks for WooCommerce
 * @author    Younes DRO <younesdro@gmail.com>
 * @since 1.0.0
 * @license  GPL-3.0-or-later https://www.gnu.org/licenses/gpl-3.0.html
 */

declare(strict_types=1);

namespace Dro\BBWC\includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class for Beautiful Blocks for WooCommerce.
 */
class Dro_BBWC_Main {

	/**
	 * The singleton instance of the class.
	 *
	 * @var self|null
	 */
	protected static ?self $instance = null;

	/**
	 * Get the instance of the class.
	 * The constructor is private to enforce the singleton pattern.
	 */
	private function __construct() {

		add_action( 'plugins_loaded', array( $this, 'plugin_loaded' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'block_categories_all', array( $this, 'register_bbwc_block_category' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_common_assets' ) );
	}
	/**
	 * Trigger the plugin_loaded action.
	 *
	 * @return void
	 */
	public function plugin_loaded() {
	}
	/**
	 * Get the instance of the class.
	 *
	 * @return self|null
	 */
	public static function get_instance(): ?self {
		return self::$instance ??= new self();
	}

	/**
	 * Prevent cloning of the instance.
	 *
	 * @return void
	 */
	public function __clone() {

		$cloning_message = sprintf(
			/* translators: %s is the class name that cannot be cloned */
			esc_html__( 'You cannot clone instance of %s', 'beautiful-blocks-for-woocommerce' ),
			get_class( $this )
		);
		_doing_it_wrong( __FUNCTION__, esc_html( $cloning_message ), esc_html( DRO_BBWC_VERSION ) );
	}
	/**
	 * Prevent unserializing of the instance.
	 *
	 * @throws \Exception If an attempt to unserialize the instance is made.
	 */
	public function __wakeup() {
		// Prevent unserializing of the instance.
		throw new \Exception( 'Cannot unserialize a singleton.' );
	}

	/**
	 * Initialize the plugin.
	 *
	 * This method is called on the 'init' action hook to set up the plugin.
	 */
	public function init() {
		// Load the block registration.
	}

	/**
	 * Register a custom block category for Beautiful Blocks.
	 *
	 * @param array                   $block_categories The existing block categories.
	 * @param WP_Block_Editor_Context $block_editor_context The block editor context.
	 * @return array The modified array of block categories.
	 */
	public function register_bbwc_block_category( $block_categories, $block_editor_context ): array {
		return array_merge(
			$block_categories,
			array(
				array(
					'slug'  => 'beautiful-blocks',
					'title' => esc_html__( 'Beautiful Blocks', 'beautiful-blocks-for-woocommerce' ),
					'icon'  => 'layout',
				),
			)
		);
	}

	/**
	 * Enqueue common assets for the block editor.
	 *
	 * @return void
	 */
	public function enqueue_common_assets() {
		$asset_file_path = DRO_BBWC_DIR . 'build/common/index.asset.php';
		error_log( print_r( DRO_BBWC_URL . '/build/common/index.js', true ) );
		if ( file_exists( $asset_file_path ) ) {
			$asset_file = include $asset_file_path;
		} else {

			$asset_file = array(
				'dependencies' => array( 'wp-blocks', 'wp-primitives', 'wp-element' ),
				'version'      => '1.0.0',
			);
		}

		wp_enqueue_script(
			'beautiful-blocks-common',
			DRO_BBWC_URL . 'build/common/index.js',
			$asset_file['dependencies'],
			$asset_file['version']
		);
	}
}
