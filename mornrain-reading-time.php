<?php
/**
 * Plugin Name: MornRain Reading Time
 * Plugin URI: https://github.com/mornrain-lin/mornrain-reading-time
 * Description: Automatically inserts an estimated reading time notice before the content of a single post.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: MornRain
 * Author URI: https://github.com/mornrain-lin
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mornrain-reading-time
 * Domain Path: /languages
 *
 * @package Mornrain_Reading_Time
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'MORNRAIN_READING_TIME_VERSION', '1.0.0' );
define( 'MORNRAIN_READING_TIME_FILE', __FILE__ );
define( 'MORNRAIN_READING_TIME_PATH', plugin_dir_path( __FILE__ ) );
define( 'MORNRAIN_READING_TIME_URL', plugin_dir_url( __FILE__ ) );

require_once MORNRAIN_READING_TIME_PATH . 'includes/functions-reading-time.php';
require_once MORNRAIN_READING_TIME_PATH . 'includes/class-mornrain-reading-time.php';

if ( ! function_exists( 'mornrain_reading_time' ) ) :
	/**
	 * Return the shared plugin instance.
	 *
	 * @since 1.0.0
	 * @return Mornrain_Reading_Time
	 */
	function mornrain_reading_time() {
		return Mornrain_Reading_Time::instance();
	}
endif;

mornrain_reading_time();
