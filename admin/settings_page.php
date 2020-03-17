<?php // settings_page.php
if (!defined('ABSPATH')) {
	exit;
}
// display the plugin settings page
function ucsc_cdp_display_settings_page() {
	// check if the user is allowed access
	if (!current_user_can('manage_options')) return;
	?>
	<div class="wrap">
	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
	<form action="options.php" method="post">
	<?php
		// output security fields
		settings_fields('ucsc_cdp_options');
		// output settings sections
		do_settings_sections('ucsc_cdp');
		// submit button
		submit_button();
	?>
	</form>
	</div>
	<?php
}
?>
