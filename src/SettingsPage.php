<?php

namespace WebDevMedia\WpFetchFeedpressPodcastHits;

class SettingsPage {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action('admin_menu', array($this, 'add_plugin_page'));
		add_action('admin_init', array($this, 'page_init'));
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {

		// This page will be under "Settings"
		add_options_page(
			'Settings Admin',
			'FeedPress Settings',
			'manage_options',
			'feedpress-setting-admin',
			array($this, 'create_admin_page')
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {

		// Set class property
		$this->options = get_option('feedpress_option');
		?>
		<div class="wrap">
			<h1>FeedPress API Settings</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields('feedpress_option_group');
				do_settings_sections('feedpress-setting-admin');
				submit_button();
				?>
			</form>
			<h2>Podcast Hits</h2>
			<?php echo '<pre>';
			print_r([
				        'DEBUG_LOCATION' => ['PATH' => dirname(__FILE__), 'FILE' => basename(__FILE__), 'FUNCTION' => __FUNCTION__ . ':' . __LINE__],
				        'DEBUG'          => [
					        '' => get_option('feedpress_hits'),
				        ]
			        ]);
			die();?>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {

		register_setting(
			'feedpress_option_group', // Option group
			'feedpress_option', // Option name
			array($this, 'sanitize') // Sanitize
		);

		add_settings_section(
			'feedpress_api_credentials', // ID
			'FeedPress API Key', // Title
			array($this, 'print_section_info'), // Callback
			'feedpress-setting-admin' // Page
		);

		add_settings_field(
			'api_key', // ID
			'API Key', // Title
			array($this, 'api_key_callback'), // Callback
			'feedpress-setting-admin', // Page
			'feedpress_api_credentials' // Section
		);

		add_settings_field(
			'api_token',
			'API Token',
			array($this, 'api_token_callback'),
			'feedpress-setting-admin',
			'feedpress_api_credentials'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {

		$new_input = array();
		if (isset($input['api_key'])) {
			$new_input['api_key'] = sanitize_text_field($input['api_key']);
		}

		if (isset($input['api_token'])) {
			$new_input['api_token'] = sanitize_text_field($input['api_token']);
		}

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info() {

		print 'Enter your settings below:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function api_key_callback() {

		printf(
			'<input type="text" id="api_key" name="feedpress_option[api_key]" value="%s" />',
			isset($this->options['api_key']) ? esc_attr($this->options['api_key']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function api_token_callback() {

		printf(
			'<input type="text" id="api_token" name="feedpress_option[api_token]" value="%s" />',
			isset($this->options['api_token']) ? esc_attr($this->options['api_token']) : ''
		);
	}
}