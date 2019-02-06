<?php
/**
 *
 * @since 1.0
 */
function github_plugin_updater_init() {
	define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin
		$config = array(
			'slug' => 'om-assistant',
			'proper_folder_name' => 'om-assistant',
			'api_url' => 'https://api.github.com/repos/Codestag/om-assistant',
			'raw_url' => 'https://raw.github.com/Codestag/om-assistant/master',
			'github_url' => 'https://github.com/Codestag/om-assistant',
			'zip_url' => 'https://github.com/Codestag/om-assistant/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '5.0.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}
add_action( 'init', 'github_plugin_updater_init' );

// Loads WP Github Updater Class.
include_once 'class-github-plugin-updater.php';
