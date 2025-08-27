<?php

/**
 * Plugin Name: Beautiful Blocks for WooCommerce
 * Plugin URI:  https://github.com/younes-dro/beautiful-blocks-for-woocommerce
 * Description: A collection of Gutenberg blocks to display WooCommerce products beautifully. Supports single and variable products in carousels, tabs, and more.
 * Version:     1.0.0
 * Author:      Younes DRO
 * Author URI:  https://github.com/younes-dro/
 * Text Domain: beautiful-blocks-for-woocommerce
 * Domain Path: /languages
 * License:     GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package BeautifulBlocksForWooCommerce
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Registers the block using a `blocks-manifest.php` file, which improves the performance of block type registration.
 * Behind the scenes, it also registers all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
 */
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
	$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
	foreach ( array_keys( $manifest_data ) as $block_type ) {
		register_block_type( __DIR__ . "/build/{$block_type}" );
	}
}
add_action( 'init', 'dro_bbwc_variations_slider_block_init' );


/**
 * To move to the plugin main  class
 */
add_filter( 'block_categories_all', function( $block_categories, $block_editor_context ) {
    return array_merge(
        $block_categories,
        [
            [
                'slug'  => 'beautiful-blocks',
                'title' => __( 'Beautiful Blocks', 'beautiful-blocks-for-woocommerce' ),
                'icon'  => 'smiley', // optional, Dashicon name or SVG
            ],
        ]
    );
}, 10, 2 );
