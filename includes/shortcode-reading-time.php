<?php
/**
 * The [mornrain_reading_time] shortcode.
 *
 * @package Mornrain_Reading_Time
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mornrain_reading_time_shortcode' ) ) :
	/**
	 * Render the [mornrain_reading_time] shortcode.
	 *
	 * @since 1.0.0
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string Escaped HTML.
	 */
	function mornrain_reading_time_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'post_id' => 0,
			),
			$atts,
			'mornrain_reading_time'
		);

		$post_id = absint( $atts['post_id'] );

		if ( 0 === $post_id ) {
			$post_id = (int) get_the_ID();
		}

		if ( $post_id <= 0 ) {
			return '';
		}

		return mornrain_reading_time_get_html( $post_id );
	}
endif;
