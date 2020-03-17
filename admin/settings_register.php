<?php
if (!defined('ABSPATH')) {
	exit;
}
function ucsc_cdp_register_settings() {
	register_setting(
		'ucsc_cdp_options',
		'ucsc_cdp_options',
		'ucsc_cdp_callback_validate_options'
	);

	add_settings_section(
		'ucsc_cdp_section_connection',
		'Connections',
		'ucsc_cdp_callback_section_connection',
		'ucsc_cdp'
	);

	add_settings_section(
		'ucsc_cdp_section_cache',
		'Cache',
		'ucsc_cdp_callback_section_cache',
		'ucsc_cdp' // must match menu slug - which page to display the section
	);

	add_settings_field(
		'proxy_uri',
		'Directory Proxy URI',
		'ucsc_cdp_callback_field_text',
		'ucsc_cdp',
		'ucsc_cdp_section_connection',
		['id' => 'proxy_uri', 'label' => 'URI of the LDAP proxy server to use']
	);

	add_settings_field(
		'proxy_api_key',
		'Proxy API Key',
		'ucsc_cdp_callback_field_text',
		'ucsc_cdp',
		'ucsc_cdp_section_connection',
		['id' => 'proxy_api_key', 'label' => 'Value to pass in the x-api-key header']
	);

	add_settings_field(
		'profile_server_url',
		'Full Profile Page URL',
		'ucsc_cdp_callback_field_text',
		'ucsc_cdp',
		'ucsc_cdp_section_connection',
		['id' => 'profile_server_url', 'label' => 'URL to the campus directory profile site']
	);

	add_settings_field(
		'cache_ttl_minutes',
		'Profile TTL',
		'ucsc_cdp_callback_field_minutes',
		'ucsc_cdp',
		'ucsc_cdp_section_cache',
		['id' => 'cache_ttl_minutes', 'label' => 'Amount of minutes to keep cached profile data']
	);
}
add_action('admin_init', 'ucsc_cdp_register_settings');

?>
