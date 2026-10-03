<?php
/**
 * Uninstall routine.
 *
 * MornRain Reading Time keeps no options, no custom tables, no transients and
 * no user meta. Every value it prints is derived on the fly from post content.
 *
 * @package Mornrain_Reading_Time
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Nothing to clean up: this plugin keeps no persistent data.
