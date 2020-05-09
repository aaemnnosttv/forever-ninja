<?php
/**
 * Plugin Name: Forever Ninja
 * Description: A plugin for ninja immortality.
 * Author: Evan Mattson (@aaemnnosttv)
 * Author URI: https://aaemnnost.tv
 * Version: 1.0
 */

// Schedule a job to run once a day on plugin activation.
register_activation_hook(
	__FILE__,
	function () {
		if ( ! wp_next_scheduled( 'foreverninja_extend' ) ) {
			wp_schedule_event( time(), 'daily', 'foreverninja_extend' );
		}
	}
);

// Clear the scheduled job on deactivation.
register_deactivation_hook(
	__FILE__,
	function () {
		if ( wp_next_scheduled( 'foreverninja_extend' ) ) {
			wp_clear_scheduled_hook( 'foreverninja_extend' );
		}
	}
);

// Extend job: trigger the companion plugin's post-login function to reset its expiration.
add_action(
	'foreverninja_extend',
	function () {
		global $companion_api_base_url;
		if ( ! $companion_api_base_url ) {
			$companion_api_base_url = get_option( 'companion_api_base_url' );
		}

		if ( function_exists( 'companion_wp_login' ) ) {
			add_filter( 'pre_option_auto_login', '__return_zero' );
			companion_wp_login();
		}
	}
);
