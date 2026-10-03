<?php
/**
 * Main plugin class.
 *
 * @package Mornrain_Reading_Time
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mornrain_Reading_Time' ) ) :
	/**
	 * Injects the reading time notice into singular content.
	 *
	 * @since 1.0.0
	 */
	final class Mornrain_Reading_Time {

		/**
		 * Shared instance.
		 *
		 * @since 1.0.0
		 * @var Mornrain_Reading_Time|null
		 */
		private static $instance = null;

		/**
		 * Posts already annotated in this request, to avoid double output.
		 *
		 * @since 1.0.0
		 * @var array<int, bool>
		 */
		private $rendered = array();

		/**
		 * Retrieve the shared instance, creating it on first call.
		 *
		 * @since 1.0.0
		 * @return Mornrain_Reading_Time
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Wire up the plugin.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {
			$this->includes();
			$this->hooks();
		}

		/**
		 * Load module files.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function includes() {
			require_once MORNRAIN_READING_TIME_PATH . 'includes/shortcode-reading-time.php';
		}

		/**
		 * Register WordPress hooks.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function hooks() {
			add_action( 'init', array( $this, 'register_shortcodes' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_filter( 'the_content', array( $this, 'add_notice' ), 12 );
		}

		/**
		 * Register the [mornrain_reading_time] shortcode.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function register_shortcodes() {
			add_shortcode( 'mornrain_reading_time', 'mornrain_reading_time_shortcode' );
		}

		/**
		 * Load the small front-end stylesheet.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function enqueue_styles() {
			if ( ! is_singular() ) {
				return;
			}

			wp_enqueue_style(
				'mornrain-reading-time',
				MORNRAIN_READING_TIME_URL . 'assets/css/reading-time.css',
				array(),
				MORNRAIN_READING_TIME_VERSION
			);
		}

		/**
		 * Add the reading time notice to singular content.
		 *
		 * @since 1.0.0
		 * @param string $content Post content.
		 * @return string
		 */
		public function add_notice( $content ) {
			if ( is_admin() || is_feed() || ! is_singular() || ! in_the_loop() ) {
				return $content;
			}

			$post_id = (int) get_the_ID();

			if ( $post_id <= 0 || isset( $this->rendered[ $post_id ] ) ) {
				return $content;
			}

			/**
			 * Filter whether the reading time notice is printed for this post.
			 *
			 * @since 1.0.0
			 * @param bool $enabled Whether to print the notice.
			 * @param int  $post_id Current post ID.
			 */
			if ( ! apply_filters( 'mornrain_reading_time_enabled', true, $post_id ) ) {
				return $content;
			}

			$notice = mornrain_reading_time_get_html( $post_id );

			if ( '' === $notice ) {
				return $content;
			}

			$this->rendered[ $post_id ] = true;

			/**
			 * Filter where the notice is placed relative to the content.
			 *
			 * @since 1.0.0
			 * @param string $position Either 'before' or 'after'.
			 * @param int    $post_id  Current post ID.
			 */
			$position = (string) apply_filters( 'mornrain_reading_time_position', 'before', $post_id );

			if ( 'after' === $position ) {
				return $content . $notice;
			}

			return $notice . $content;
		}
	}
endif;
