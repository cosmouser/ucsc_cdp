<?php
if (!defined('ABSPATH')) {
	exit;
}


function ucsc_cdp_callback_section_connection() {
	echo '<p>This section configures the connection to the LDAP proxy server.</p>';
}
function ucsc_cdp_callback_section_cache() {
	echo '<p>This section sets how long profile data will be cached in Wordpress.</p>';
}
function ucsc_cdp_callback_field_text($args) {
	$options = get_option('ucsc_cdp_options', ucsc_cdp_options_default());
	$id = isset($args['id']) ? $args['id'] : '';
	$label = isset($args['label']) ? $args['label'] : '';
	$value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';
	echo '<input id="ucsc_cdp_options_' . $id . '" name="ucsc_cdp_options[' . $id . ']" type="text" size="50" value="' . $value .'"><br />';
	echo '<label for="ucsc_cdp_options_' . $id . '">' . $label . '</label>';
}
function ucsc_cdp_callback_field_minutes($args) {
	$options = get_option('ucsc_cdp_options', ucsc_cdp_options_default());
	$id = isset($args['id']) ? $args['id'] : '';
	$label = isset($args['label']) ? $args['label'] : '';
	$value = isset($options[$id]) && $options[$id] != 0 ? absint($options[$id]) : 60; // Default to 60 minutes. Prevent zero or negative values.
	echo '<input id="ucsc_cdp_options_' . $id . '" name="ucsc_cdp_options[' . $id . ']" type="number" size="20" value="' . $value .'"><br />';
	echo '<label for="ucsc_cdp_options_' . $id . '">' . $label . '</label>';
}
function ucsc_cdp_callback_field_textarea($args) {
	$options = get_option('ucsc_cdp_options', ucsc_cdp_options_default());
	$id = isset($args['id']) ? $args['id'] : '';
	$label = isset($args['label']) ? $args['label'] : '';
	$allowed_tags = wp_kses_allowed_html('post');
	$value = isset($options[$id]) ? wp_kses(stripslashes_deep($options[$id]), $allowed_tags) : '';
	echo '<textarea id="ucsc_cdp_options_' . $id . '" name="ucsc_cdp_options[' . $id . ']" rows="5" cols="50">' . $value . '</textarea><br />';
	echo '<label for="ucsc_cdp_options_' . $id . '">' . $label . '</label>';
}
function ucsc_cdp_callback_field_checkbox($args) {
	$options = get_option('ucsc_cdp_options', ucsc_cdp_options_default());
	$id = isset($args['id']) ? $args['id'] : '';
	$label = isset($args['label']) ? $args['label'] : '';
	$checked = isset($options[$id]) ? checked($options[$id], 1, false) : '';
	echo '<input id="ucsc_cdp_options_' . $id . '" name="ucsc_cdp_options[' . $id . ']" type="checkbox" value="1"' . $checked . '> ';
	echo '<label for="ucsc_cdp_options_' . $id . '">' . $label . '</label>';
}
// validate plugin settings
function ucsc_cdp_validate_options($input) {
	// Proxy server uri field 
	if(isset($input['proxy_uri'])) {
		$input['proxy_uri'] = esc_url($input['proxy_uri']);
	}
	// Api key field 
	if(isset($input['proxy_api_key'])) {
		$input['proxy_api_key'] = sanitize_text_field($input['proxy_api_key']);
	}
	// Image server uri field 
	if(isset($input['profile_server_url'])) {
		$input['profile_server_url'] = esc_url($input['profile_server_url']);
	}
	return $input;
}

?>
