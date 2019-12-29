<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: WP Fetch Feedpress Podcast Hits
 * Plugin URI:  https://github.com/web-dev-media/wp-fetch-feedpress-podcast-hits
 * Description:
 * Version:     1.0.1
 * Author:      web dev media UG (haftungsbeschrängt)
 * Author URI:  http://web-dev-media.de
 * License:     GPLv2+
 * Text Domain: wpfetfeepodhits
 * Domain Path: /languages
 */

namespace WebDevMedia\WpFetchFeedpressPodcastHits;

use WebDevMedia\WpFetchFeedpressPodcastHits\Query;

add_action( 'plugins_loaded', __NAMESPACE__ . '\init', 8 );

/**
 * load the plugin
 */
function init() {
	$textDomain = get_file_data(__FILE__, [
		'TextDomain' => 'Text Domain'
	], 'plugin');

	/**
	 * adding text ddomain for L10N
	 **/
	#load_plugin_textdomain( $textDomain, false, __DIR__ . '/languages' );

	$autoloader = require_once __DIR__ . '/vendor/autoload.php';
	require_once __DIR__ . '/src/Functions.php';

	if (is_admin()) {
		new SettingsPage();
	}

	(new Query\Hits())->get_items();
}