<?php
/**
 * Reading time calculation helpers.
 *
 * @package Mornrain_Reading_Time
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mornrain_reading_time_count_content' ) ) :
	/**
	 * Count Latin words and CJK characters in a piece of content.
	 *
	 * @since 1.0.0
	 * @param string $content Raw post content.
	 * @return array{words:int,cjk:int}
	 */
	function mornrain_reading_time_count_content( $content ) {
		$text = wp_strip_all_tags( (string) $content );
		$text = strip_shortcodes( $text );

		/*
		 * CJK blocks are matched first so that the Latin word counter does not
		 * treat every ideograph as a separate word.
		 */
		$cjk_pattern = '/[\x{2E80}-\x{2EFF}\x{3000}-\x{303F}\x{3040}-\x{30FF}\x{3400}-\x{4DBF}\x{4E00}-\x{9FFF}\x{AC00}-\x{D7AF}\x{F900}-\x{FAFF}]/u';
		$cjk_count   = 0;

		if ( preg_match_all( $cjk_pattern, $text, $cjk_matches ) ) {
			$cjk_count = count( $cjk_matches[0] );
			$text      = (string) preg_replace( $cjk_pattern, ' ', $text );
		}

		$word_count = 0;

		if ( preg_match_all( "/[A-Za-z0-9_'\-]+/", $text, $word_matches ) ) {
			$word_count = count( $word_matches[0] );
		}

		return array(
			'words' => (int) $word_count,
			'cjk'   => (int) $cjk_count,
		);
	}
endif;

if ( ! function_exists( 'mornrain_reading_time_minutes' ) ) :
	/**
	 * Estimate how many minutes a reader needs for the given content.
	 *
	 * @since 1.0.0
	 * @param string $content Raw post content.
	 * @return int Minutes, never below one.
	 */
	function mornrain_reading_time_minutes( $content ) {
		$counts = mornrain_reading_time_count_content( $content );

		/**
		 * Filter how many Latin words a reader covers per minute.
		 *
		 * @since 1.0.0
		 * @param int $words_per_minute Words per minute.
		 */
		$words_per_minute = (int) apply_filters( 'mornrain_reading_time_words_per_minute', 200 );
		$words_per_minute = max( 1, $words_per_minute );

		/**
		 * Filter how many CJK characters a reader covers per minute.
		 *
		 * @since 1.0.0
		 * @param int $characters_per_minute Characters per minute.
		 */
		$characters_per_minute = (int) apply_filters( 'mornrain_reading_time_characters_per_minute', 450 );
		$characters_per_minute = max( 1, $characters_per_minute );

		$minutes = ( $counts['words'] / $words_per_minute ) + ( $counts['cjk'] / $characters_per_minute );
		$minutes = (int) ceil( $minutes );

		/**
		 * Filter the final reading time estimate.
		 *
		 * @since 1.0.0
		 * @param int    $minutes Estimated minutes.
		 * @param array  $counts  Word and character counts.
		 * @param string $content The analysed content.
		 */
		$minutes = (int) apply_filters( 'mornrain_reading_time_minutes', $minutes, $counts, $content );

		return max( 1, $minutes );
	}
endif;

if ( ! function_exists( 'mornrain_reading_time_get_html' ) ) :
	/**
	 * Build the escaped reading time notice for a post.
	 *
	 * @since 1.0.0
	 * @param int|WP_Post|null $post Post to analyse. Defaults to the current post.
	 * @return string Empty string when there is nothing to measure.
	 */
	function mornrain_reading_time_get_html( $post = null ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return '';
		}

		$raw = (string) $post->post_content;

		if ( '' === trim( $raw ) ) {
			return '';
		}

		$minutes = mornrain_reading_time_minutes( $raw );

		$label = sprintf(
			/* translators: %d: number of minutes. */
			_n( '%d minute read', '%d minute read', $minutes, 'mornrain-reading-time' ),
			$minutes
		);

		/**
		 * Filter the visible reading time label.
		 *
		 * @since 1.0.0
		 * @param string  $label   Human readable label.
		 * @param int     $minutes Estimated minutes.
		 * @param WP_Post $post    Post being measured.
		 */
		$label = (string) apply_filters( 'mornrain_reading_time_label', $label, $minutes, $post );

		$html = sprintf(
			'<p class="mornrain-reading-time"><span class="mornrain-reading-time__icon" aria-hidden="true"></span> %s</p>',
			esc_html( $label )
		);

		/**
		 * Filter the fully built reading time markup.
		 *
		 * @since 1.0.0
		 * @param string  $html    Escaped HTML.
		 * @param int     $minutes Estimated minutes.
		 * @param WP_Post $post    Post being measured.
		 */
		return (string) apply_filters( 'mornrain_reading_time_html', $html, $minutes, $post );
	}
endif;
