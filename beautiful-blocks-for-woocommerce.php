<?php

/**
 * Plugin Name: Beautiful Blocks for WooCommerce
 * Plugin URI:  https://github.com/younes-dro/beautiful-blocks-for-woocommerce
 * Description: A collection of Gutenberg blocks to display WooCommerce products beautifully.
 * Version:     1.0.0
 * Author:      Younes DRO
 * Author URI:  https://github.com/younes-dro/
 * Text Domain: beautiful-blocks-for-woocommerce
 * Domain Path: /languages
 * Requires at least: 6.7
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * License:     GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package BeautifulBlocksForWooCommerce
 */
declare(strict_types=1);
namespace Dro\BBWC;

use Dro\BBWC\includes\Dro_BBWC_Main as Main;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


define( 'DRO_BBWC_VERSION', get_file_data( __FILE__, array( 'Version' ), 'plugin' )[0] ?? '1.0.1' );
define( 'DRO_BBWC_FILE', __FILE__ );
define( 'DRO_BBWC_DIR', plugin_dir_path( __FILE__ ) );
define( 'DRO_BBWC_URL', plugin_dir_url( __FILE__ ) );
/**
 * Registers the block using a `blocks-manifest.php` file, which improves the performance of block type registration.
 * Behind the scenes, it also registers all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
 */

/**
 * Activation hook
 * TO DO: Add maybe some activation logic here in the future.
 *
 * @since 1.0.0
 *
 * @return void
 */
function dro_bbwc_activation() {
	// empty function for now, but can be used in the future for activation logic.
}

register_activation_hook( DRO_BBWC_FILE, __NAMESPACE__ . '\\dro_bbwc_activation' );

/**
 * Registers a custom autoloader.
 *
 * @return void
 */
function dro_bbwc_register_autoload() {
	spl_autoload_register(
		function ( $current_class ) {
			if ( strncmp( __NAMESPACE__ . '\\', $current_class, strlen( __NAMESPACE__ ) + 1 ) !== 0 ) {
				return;
			}

			$class_portions    = explode( '\\', $current_class );
			$class_portions    = array_map( 'strtolower', $class_portions );
			$class_file_name   = str_replace( '_', '-', strtolower( array_pop( $class_portions ) ) );
			$class_path        = __DIR__ . '/' . implode( DIRECTORY_SEPARATOR, array_slice( $class_portions, 2 ) );
			$class_file_prefix = ( stripos( $current_class, 'abstracts' ) !== false ? 'abstract-' : 'class-' );
			$class_full_path   = $class_path . DIRECTORY_SEPARATOR . $class_file_prefix . $class_file_name . '.php';

			if ( file_exists( $class_full_path ) ) {
				require_once $class_full_path;
			}
		}
	);
}
function dro_bbwc_variations_slider_block_init() {
	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
	 * based on the registered block metadata.
	 * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
	 *
	 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
	 */
	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
		wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
		return;
	}

	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` file.
	 * Added to WordPress 6.7 to improve the performance of block type registration.
	 *
	 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
	 */
	if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		wp_register_block_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
	}
	/**
	 * Registers the block type(s) in the `blocks-manifest.php` file.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	$manifest_data = include __DIR__ . '/build/blocks-manifest.php';
	foreach ( array_keys( $manifest_data ) as $block_type ) {
		register_block_type( __DIR__ . "/build/{$block_type}" );
	}
}
add_action( 'init', __NAMESPACE__ . '\\dro_bbwc_variations_slider_block_init' );

function dro_bbwc() {
	dro_bbwc_register_autoload();
	Main::get_instance();
}
dro_bbwc();
