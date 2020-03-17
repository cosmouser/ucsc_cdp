<?php // admin_page.php
if (!defined('ABSPATH')) {
	exit;
}
// add sub-level administrative menu
function ucsc_cdp_add_sublevel_menu() {
	add_submenu_page(
		'options-general.php',
		'Campusdirectory Profiles Plugin Settings',
		'Campusdirectory Profiles',
		'manage_options',
		'ucsc_cdp',
		'ucsc_cdp_display_settings_page'
	);
}
add_action('admin_menu', 'ucsc_cdp_add_sublevel_menu');
?>
