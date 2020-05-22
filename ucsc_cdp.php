<?php
/*
Plugin Name: UCSC Profiles
Plugin URI: https://github.com/cosmouser/ucsc_cdp
Description: Provides a block for adding UCSC campus directory profiles to pages.
Author: Cosmo Martinez 
Author URI: https://github.com/cosmouser
License: BSD3
Version: 1.0.1
*/
defined( 'ABSPATH' ) || exit;

// if admin area
if (is_admin()) {
	// include dependencies
	require_once plugin_dir_path(__FILE__) . 'admin/admin_menu.php';
	require_once plugin_dir_path(__FILE__) . 'admin/settings_page.php';
	require_once plugin_dir_path(__FILE__) . 'admin/settings_register.php';
	require_once plugin_dir_path(__FILE__) . 'admin/settings_callbacks.php';
}

require_once plugin_dir_path(__FILE__) . 'includes/render.php';

// default plugin options
function ucsc_cdp_options_default() {
	return array(
		'proxy_uri' => 'https://proxy.appserver.edu',
		'proxy_api_key' => '',
		'profile_server_url' => '',
		'cache_ttl_minutes' => 30,
	);
}

// add the block
function ucsc_cdp_add_block() {
	wp_register_script(
		'ucsc_cdp-js',
		plugins_url('build/index.js', __FILE__),
		array(
			'wp-blocks',
			'wp-editor',
			'wp-components'
		),
		filemtime(plugin_dir_path(__FILE__) . 'build/index.js')
	);
	wp_register_style(
		'profile-block', 
		plugins_url('public/css/style.css', __FILE__),
		array(),
		filemtime(plugin_dir_path(__FILE__) . 'public/css/style.css')
	);
	if(function_exists('register_block_type')) {
		register_block_type(
			'ucsc-cdp/profile',
			array(
				'editor_script' => 'ucsc_cdp-js',
				'style' => 'profile-block',
				'render_callback' => 'ucsc_cdp_profile_render',
				'attributes' => array(
					'uids' => array(
						'type' => 'string',
						'default' => '',
					),
					'jpegPhoto' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'cn' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'title' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'telephoneNumber' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'mail' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'labeledURI' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'ucscPersonPubOfficeLocationDetail' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'ucscPersonPubOfficeHours' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'ucscPersonPubAreaOfExpertise' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'profLinks' => array(
						'type' => 'boolean',
						'default' => true,
					),
					'displayStyle' => array(
						'type' => 'string',
						'default' => 'grid',
					),
				),
			)
		);
	}
}
function enqueue_style() {
	wp_enqueue_style('profile-block');
}
add_shortcode('ucsc_profiles', 'ucsc_cdp_profile_render_shortcode');
add_action('init', 'ucsc_cdp_add_block');

add_action('wp_enqueue_scripts', 'enqueue_style');
?>
