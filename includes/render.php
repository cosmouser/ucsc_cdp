<?php // render.php
if (!defined('ABSPATH')) {
	exit;
}
function render_attr_cn($values, $val_key, $options, $attributes, $uid) {
	$result = '';
	if(!empty($values[$val_key])) {
		if($attributes['profLinks']) {
			$result .= '<a style="text-decoration: none" href="' . $options['profile_server_url'] . $uid . '">';
			$result .= $values[$val_key][0] . '</a>';
		} else {
			$values[$val_key][0];
		}
	}
	return $result;
}
function ucsc_cdp_read_more($data, $options, $uid) {
	$original = strip_tags($data);
	$original_length = strlen($original);
	if($original_length < 128) {
		return wp_kses_post($data);
	}
	$result = '<p>' . substr(strip_tags($data), 0, 128);
	$result .= ' <a href="' . $options['profile_server_url'] . $uid . '">...more</a></p>';
	return $result;
}
function render_attr_single_line($values, $val_key) {
	$result = '';
	if(!empty($values[$val_key])) {
		$result .= $values[$val_key][0];
	}
	return $result;
}
function render_attr_multi_line($values, $val_key) {
	$result = '';
	if(!empty($values[$val_key])) {
		$result .= '<div>' . join('<br />', $values[$val_key]) . '</div>';
	}
	return $result;
}
function render_attr_labeled_uri($values, $val_key) {
	$result = '';
	if(!empty($values[$val_key])) {
		$result .= '<div>' . join('<br />', array_map('render_attr_labeled_uri_map', $values[$val_key])) . '</div>';
	}
	return $result;
}
function render_attr_mail($values, $val_key) {
	$result = '';
	if(!empty($values[$val_key])) {
		$result .= '<div>' . join('<br />', array_map('render_attr_mail_map', $values[$val_key])) . '</div>';
	}
	return $result;
}
function render_attr_photo($values, $val_key) {
	$result = '';
	if(!empty($values[$val_key])) {
		$result .= '<div class="square-img" style="background-image: url(\'data:image/jpeg;base64, ' . $values[$val_key][0] . '\')"></div>';
	} else {
		$result .= '<div class="square-img" style="background-image: url(\'' . plugins_url('ucsc_cdp/public/icon-slug.jpg') . '\')"></div>';
	}
	return $result;
}
function marshal_or_filter_from_uids($uids) {
	$result = '(|';
	foreach($uids as $entry) {
		$result .= '(uid=' . trim($entry) . ')';
	}
	$result .= ')';
	return $result;
}
function render_attr_mail_map($email) {
	return '<a style="text-decoration:none" href="mailto:' . $email . '">' . $email . '</a>';
}
function render_attr_labeled_uri_map($labeled_uri) {
	$split = explode(' ', $labeled_uri, 2);
	if(sizeof($split) < 2) {
		return join('<br/>', $labeled_uri);
	}
	return '<a href="' . $split[0] . '">' . $split[1] . '</a>';
}
function render_list_attr($title, $content) {
	$result = '<li><span class="cdp-li-header">' . $title . '</span><ul class="cdp-inline-list">' . $content . '</ul></li>';
	return $result;
}
function render_grid_attr($content) {
	$result .= '<li>' . $content . '</li>';
	return $result;
}

function marshal_query_signature($filter, $attrs) {
	return "ucsc_cdp_query_" . md5($filter . json_encode($attrs), false);
}
function send_cdp_request($filter, $attribute_names) {
	$uri = '';
	$api_key = '';
	$options = get_option('ucsc_cdp_options', ucsc_cdp_options_default());
	if(isset($options['proxy_uri']) && !empty($options['proxy_uri'])) {
		$uri = $options['proxy_uri'];
	}
	if(isset($options['proxy_api_key']) && !empty($options['proxy_api_key'])) {
		$api_key = $options['proxy_api_key'];
	}
	if(!in_array('uid', $attribute_names)) {
		array_push($attribute_names, 'uid');
	}
	$payload = (object) [
		'filter' => $filter,
		'attributeNames' => $attribute_names,
	];
	$args = array(
		'body' => json_encode($payload),
		'timeout' => 20,
		'headers' => array(
			'x-api-key' => $api_key,
		),
	);
	return wp_safe_remote_post($uri, $args);
}

function ucsc_cdp_block_classes($attributes) {
        $classes = null;
	if(isset($attributes['align'])) {
		$classes = 'align' . $attributes['align'] . ' ';
	}
	if(isset($attributes['className'])) {
		$classes .= $attributes['className'];
	}
	if($classes === null) {
		return '';
	}
	return $classes;
}
function ucsc_cdp_profile_render_shortcode($attributes) {
	$sa = shortcode_atts(array(
		'cruzids' => 'cosmo',
		'photo' => true,
		'name' => true,
		'title' => false,
		'phone' => false,
		'email' => false,
		'websites' => false,
		'officelocation' => false,
		'officehours' => false,
		'expertise' => false,
		'profilelinks' => true,
		'biography' => false,
		'areas_of_expertise' => false,
		'research_interests' => false,
		'teaching_interests' => false,
		'awards' => false,
		'publications' => false,
		'displaystyle' => 'grid',
	), $attributes);
	foreach($sa as $key => $value) {
		if($key === 'cruzids' || $key === 'displaystyle') {
			continue;
		}
		if($value === 'true') {
			$sa[$key] = true;
		}
		if($value === 'false') {
			$sa[$key] = false;
		}
	}
	$attrs = array(
		'uids' => $sa['cruzids'],
		'jpegPhoto' => $sa['photo'],
		'cn' => $sa['name'],
		'title' => $sa['title'],
		'telephoneNumber' => $sa['phone'],
		'mail' => $sa['email'],
		'labeledURI' => $sa['websites'],
		'ucscPersonPubOfficeLocationDetail' => $sa['officelocation'],
		'ucscPersonPubOfficeHours' => $sa['officehours'],
		'ucscPersonPubAreaOfExpertise' => $sa['expertise'],
		'profLinks' => $sa['profilelinks'],
		'ucscPersonPubDescription' => $sa['biography'],
		'ucscPersonPubExpertiseReference' => $sa['areas_of_expertise'],
		'ucscPersonPubResearchInterest' => $sa['research_interests'],
		'ucscPersonPubTeachingInterest' => $sa['teaching_interests'],
		'ucscPersonPubAwardsHonorsGrants' => $sa['awards'],
		'ucscPersonPubSelectedPublication' => $sa['publications'],
		'displayStyle' => $sa['displaystyle'],
	);
	return ucsc_cdp_profile_render($attrs, null);
}
		
function ucsc_cdp_profile_render($attributes, $content) {
	// Load settings and set attribute defaults
	$uids = array('sammy');
	$attrs_for_query = array();
	$uids = preg_split('/[\s,]+/', $attributes['uids']);
	if($attributes['jpegPhoto']) {
		array_push($attrs_for_query, 'jpegPhoto');
	}
	if($attributes['cn']) {
		array_push($attrs_for_query, 'cn');
	}
	if($attributes['title']) {
		array_push($attrs_for_query, 'title');
	}
	if($attributes['telephoneNumber']) {
		array_push($attrs_for_query, 'telephoneNumber');
	}
	if($attributes['mail']) {
		array_push($attrs_for_query, 'mail');
	}
	if($attributes['labeledURI']) {
		array_push($attrs_for_query, 'labeledURI');
	}
	if($attributes['ucscPersonPubOfficeLocationDetail']) {
		array_push($attrs_for_query, 'ucscPrimaryLocationPubOfficialName');
		array_push($attrs_for_query, 'ucscPersonPubOfficeLocationDetail');
	}
	if($attributes['ucscPersonPubOfficeHours']) {
		array_push($attrs_for_query, 'ucscPersonPubOfficeHours');
	}
	if($attributes['ucscPersonPubAreaOfExpertise']) {
		array_push($attrs_for_query, 'ucscPersonPubAreaOfExpertise');
	}
	if($attributes['ucscPersonPubDescription']) {
		array_push($attrs_for_query, 'ucscPersonPubDescription');
	}
	if($attributes['ucscPersonPubExpertiseReference']) {
		array_push($attrs_for_query, 'ucscPersonPubExpertiseReference');
	}
	if($attributes['ucscPersonPubResearchInterest']) {
		array_push($attrs_for_query, 'ucscPersonPubResearchInterest');
	}
	if($attributes['ucscPersonPubTeachingInterest']) {
		array_push($attrs_for_query, 'ucscPersonPubTeachingInterest');
	}
	if($attributes['ucscPersonPubAwardsHonorsGrants']) {
		array_push($attrs_for_query, 'ucscPersonPubAwardsHonorsGrants');
	}
	if($attributes['ucscPersonPubSelectedPublication']) {
		array_push($attrs_for_query, 'ucscPersonPubSelectedPublication');
	}
	$options = get_option('ucsc_cdp_options', ucsc_cdp_options_default());
	$profile_server_url = $options['profile_server_url'];
	$filter = marshal_or_filter_from_uids($uids);
	$query_signature = marshal_query_signature($filter, $attrs_for_query);

	// Load profiles data from cache or request new cache data
	$profiles = null;
	if(false === ($profiles = get_transient($query_signature))) {
		$response = send_cdp_request($filter, $attrs_for_query);
		$response_code = wp_remote_retrieve_response_code($response);
		if($response_code != 200) {
			$response_message = wp_remote_retrieve_response_message($response);
			error_log('ucsc_cdp: proxy server returned ' . $response_message, 0);
			return;
		}
		$response_data = json_decode(wp_remote_retrieve_body($response), true);
		$exp_duration = $options['cache_ttl_minutes'];
		set_transient($query_signature, $response_data, $exp_duration * 60);
		// $profiles is assigned the return value of get_transient($query_signature)
		if(false === ($profiles = get_transient($query_signature))) {
			error_log('ucsc_cdp: unable to get transient ' . $query_signature, 0);

			// Set $profiles to data from response as a fallback.
			$profiles = $response_data;
		}
	}
	$result = '';
	if($attributes['displayStyle'] === 'list') {
		$result .= render_profiles_list($uids, $profiles, $attributes, $options);
	} else {
		$result .= render_profiles_grid($uids, $profiles, $attributes, $options);
	}
	return $result;
}
function gen_response_index_map($profiles) {
	$result = array();
	foreach($profiles as $num => $entry) {
		$result[$entry['uid'][0]] = $num;
	}
	return $result;
}
function render_profiles_grid($uids, $profiles, $attributes, $options) {
	$index_map = gen_response_index_map($profiles);
	$result = '<div class="cdp-profiles cdp-display-' . $attributes['displayStyle'] . ' ' . ucsc_cdp_block_classes($attributes) . '">';
	foreach($uids as $uid_value) {
		$entry = null;
		if(isset($profiles[$index_map[$uid_value]])) {
			$entry = $profiles[$index_map[$uid_value]];
		} else {
			continue;
		}
		$profile_uid = $entry['uid'][0];
		$result .= '<div class="cdp-profile grid" id="cdp-profile-';
		$result .= $entry['uid'][0] . '"><ul class="cdp-profile-ul">';
		if($attributes['jpegPhoto']) {
			$result .= render_attr_photo($entry, 'jpegPhoto', $options);
		}
		if($attributes['cn'] && !empty($entry['cn'])) {
			$result .= render_grid_attr('<strong>' . render_attr_cn($entry, 'cn', $options, $attributes, $entry['uid'][0]) . '</strong>');
		}
		if($attributes['title'] && !empty($entry['title'])) {
			$result .= render_grid_attr(render_attr_single_line($entry, 'title', $options));
		}
		if($attributes['telephoneNumber'] && !empty($entry['telephoneNumber'])) {
			$result .= render_grid_attr(render_attr_multi_line($entry, 'telephoneNumber', $options));
		}
		if($attributes['mail'] && !empty($entry['mail'])) {
			$result .= render_grid_attr(render_attr_mail($entry, 'mail', $options));
		}
		if($attributes['labeledURI'] && !empty($entry['labeledURI'])) {
			$result .= render_grid_attr(render_attr_labeled_uri($entry, 'labeledURI', $options));
		}
		if($attributes['ucscPersonPubOfficeLocationDetail'] && !empty($entry['ucscPersonPubOfficeLocationDetail'])) {
			$office_info = render_attr_multi_line($entry, 'ucscPrimaryLocationPubOfficialName', $options);
			$office_info .= render_attr_multi_line($entry, 'ucscPersonPubOfficeLocationDetail', $options);
			$result .= render_grid_attr($office_info);
		}
		if($attributes['ucscPersonPubOfficeHours'] && !empty($entry['ucscPersonPubOfficeHours'])) {
			$result .= render_grid_attr(render_attr_multi_line($entry, 'ucscPersonPubOfficeHours', $options));
		}
		if($attributes['ucscPersonPubAreaOfExpertise'] && !empty($entry['ucscPersonPubAreaOfExpertise'])) {
			if($attributes['ucscPersonPubAreaOfExpertise'] === 'short') {
				$result .= render_grid_attr(ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubAreaOfExpertise', $options, $attributes), $options, $profile_uid));
			} else {
				$result .= render_grid_attr(render_attr_single_line($entry, 'ucscPersonPubAreaOfExpertise', $options, $attributes));
			}
		}
		if($attributes['ucscPersonPubDescription'] && !empty($entry['ucscPersonPubDescription'])) {
			if($attributes['ucscPersonPubDescription'] === 'short') {
			        $result .= render_grid_attr(ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubDescription', $options, $attributes), $options, $profile_uid));
			} else {
			        $result .= render_grid_attr(render_attr_single_line($entry, 'ucscPersonPubDescription', $options, $attributes));
			}
		}
		if($attributes['ucscPersonPubExpertiseReference'] && !empty($entry['ucscPersonPubExpertiseReference'])) {
			$result .= render_grid_attr(render_attr_multi_line($entry, 'ucscPersonPubExpertiseReference', $options, $attributes));
		}
		if($attributes['ucscPersonPubResearchInterest'] && !empty($entry['ucscPersonPubResearchInterest'])) {
			if($attributes['ucscPersonPubResearchInterest'] === 'short') {
				$result .= render_grid_attr(ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubResearchInterest', $options, $attributes), $options, $profile_uid));
			} else {
				$result .= render_grid_attr(render_attr_single_line($entry, 'ucscPersonPubResearchInterest', $options, $attributes));
			}
		}
		if($attributes['ucscPersonPubTeachingInterest'] && !empty($entry['ucscPersonPubTeachingInterest'])) {
			if($attributes['ucscPersonPubTeachingInterest'] === 'short') {
				$result .= render_grid_attr(ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubTeachingInterest', $options, $attributes), $options, $profile_uid));
			} else {
				$result .= render_grid_attr(render_attr_single_line($entry, 'ucscPersonPubTeachingInterest', $options, $attributes));
			}
		}
		if($attributes['ucscPersonPubAwardsHonorsGrants'] && !empty($entry['ucscPersonPubAwardsHonorsGrants'])) {
			if($attributes['ucscPersonPubAwardsHonorsGrants'] === 'short') {
				$result .= render_grid_attr(ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubAwardsHonorsGrants', $options, $attributes), $options, $profile_uid));
			} else {
				$result .= render_grid_attr(render_attr_single_line($entry, 'ucscPersonPubAwardsHonorsGrants', $options, $attributes));
			}
		}
		if($attributes['ucscPersonPubSelectedPublication'] && !empty($entry['ucscPersonPubSelectedPublication'])) {
			if($attributes['ucscPersonPubSelectedPublication'] === 'short') {
				$result .= render_grid_attr(ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubSelectedPublication', $options, $attributes), $options, $profile_uid));
			} else {
				$result .= render_grid_attr(render_attr_single_line($entry, 'ucscPersonPubSelectedPublication', $options, $attributes));
			}
		}
		$result .= '</ul></div>';
	}
	$result .= '</div>';
	return $result;
}
function render_profiles_list($uids, $profiles, $attributes, $options) {
	$index_map = gen_response_index_map($profiles);
	$result = '<div class="cdp-profiles-list ' . ucsc_cdp_block_classes($attributes) . '">';
	foreach($uids as $uid_value) {
		$entry = null;
		if(isset($profiles[$index_map[$uid_value]])) {
			$entry = $profiles[$index_map[$uid_value]];
		} else {
			continue;
		}
		$profile_uid = $entry['uid'][0];
		$result .= '<div class="cdp-list-profile" id="cdp-profile-';
		$result .= $profile_uid . '">';
		if($attributes['cn'] && !empty($entry['cn'])) {
			$result .= '<h4>' . render_attr_cn($entry, 'cn', $options, $attributes, $profile_uid) . '</h4>';
		}
		$result .= '<div class="cdp-list-box"><div class="cdp-list-body"><ul class="cdp-list-render">';
		if($attributes['title'] && !empty($entry['title'])) {
			$result .= render_list_attr('Title', '<li>' . render_attr_single_line($entry, 'title', $options, $attributes) . '</li>');
		}
		if($attributes['telephoneNumber'] && !empty($entry['telephoneNumber'])) {
			$result .= render_list_attr('Phone', '<li>' . render_attr_multi_line($entry, 'telephoneNumber', $options, $attributes) . '</li>');
		}
		if($attributes['mail'] && !empty($entry['mail'])) {
			$result .= render_list_attr('Email', '<li>' . render_attr_mail($entry, 'mail', $options, $attributes) . '</li>');
		}
		if($attributes['labeledURI'] && !empty($entry['labeledURI'])) {
			$result .= render_list_attr('Website', '<li>' . render_attr_labeled_uri($entry, 'labeledURI', $options, $attributes). '</li>');
		}
		if($attributes['ucscPersonPubOfficeLocationDetail'] && !empty($entry['ucscPersonPubOfficeLocationDetail'])) {
			$result .= render_list_attr('Office Location', '<li>' . render_attr_multi_line($entry, 'ucscPrimaryLocationPubOfficialName', $options) . '</li><li>' . render_attr_multi_line($entry, 'ucscPersonPubOfficeLocationDetail', $options, $attributes) . '</li>');
		}
		if($attributes['ucscPersonPubOfficeHours'] && !empty($entry['ucscPersonPubOfficeHours'])) {
			$result .= render_list_attr('Office Hours', '<li>' . render_attr_multi_line($entry, 'ucscPersonPubOfficeHours', $options, $attributes) . '</li>');
		}
		if($attributes['ucscPersonPubAreaOfExpertise'] && !empty($entry['ucscPersonPubAreaOfExpertise'])) {
			if($attributes['ucscPersonPubAreaOfExpertise'] === 'short') {
				$result .= render_list_attr('Summary of Expertise', '<li>' . ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubAreaOfExpertise', $options, $attributes), $options, $profile_uid) . '</li>');
			} else {
				$result .= render_list_attr('Summary of Expertise', '<li>' . render_attr_single_line($entry, 'ucscPersonPubAreaOfExpertise', $options, $attributes) . '</li>');
			}
		}
		if($attributes['ucscPersonPubDescription'] && !empty($entry['ucscPersonPubDescription'])) {
			if($attributes['ucscPersonPubDescription'] === 'short') {
				$result .= render_list_attr('Biography, Education, and Training', '<li>' . ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubDescription', $options, $attributes), $options, $profile_uid) . '</li>');
			} else {
				$result .= render_list_attr('Biography, Education, and Training', '<li>' . render_attr_single_line($entry, 'ucscPersonPubDescription', $options, $attributes) . '</li>');
			}
		}
		if($attributes['ucscPersonPubExpertiseReference'] && !empty($entry['ucscPersonPubExpertiseReference'])) {
			$result .= render_list_attr('Areas of Expertise', '<li>' . render_attr_multi_line($entry, 'ucscPersonPubExpertiseReference', $options, $attributes) . '</li>');
		}
		if($attributes['ucscPersonPubResearchInterest'] && !empty($entry['ucscPersonPubResearchInterest'])) {
			if($attributes['ucscPersonPubResearchInterest'] === 'short') {
				$result .= render_list_attr('Research Interests', '<li>' . ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubResearchInterest', $options, $attributes), $options, $profile_uid) . '</li>');
			} else {
				$result .= render_list_attr('Research Interests', '<li>' . render_attr_single_line($entry, 'ucscPersonPubResearchInterest', $options, $attributes) . '</li>');
			}
		}
		if($attributes['ucscPersonPubTeachingInterest'] && !empty($entry['ucscPersonPubTeachingInterest'])) {
			if($attributes['ucscPersonPubTeachingInterest'] === 'short') {
				$result .= render_list_attr('Teaching Interests', '<li>' . ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubTeachingInterest', $options, $attributes), $options, $profile_uid) . '</li>');
			} else {
				$result .= render_list_attr('Teaching Interests', '<li>' . render_attr_single_line($entry, 'ucscPersonPubTeachingInterest', $options, $attributes) . '</li>');
			}
		}
		if($attributes['ucscPersonPubAwardsHonorsGrants'] && !empty($entry['ucscPersonPubAwardsHonorsGrants'])) {
			if($attributes['ucscPersonPubAwardsHonorsGrants'] === 'short') {
				$result .= render_list_attr('Awards, Honors, and Grants', '<li>' . ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubAwardsHonorsGrants', $options, $attributes), $options, $profile_uid) . '</li>');
			} else {
				$result .= render_list_attr('Awards, Honors, and Grants', '<li>' . render_attr_single_line($entry, 'ucscPersonPubAwardsHonorsGrants', $options, $attributes) . '</li>');
			}
		}
		if($attributes['ucscPersonPubSelectedPublication'] && !empty($entry['ucscPersonPubSelectedPublication'])) {
			if($attributes['ucscPersonPubSelectedPublication'] === 'short') {
				$result .= render_list_attr('Selected Publications', '<li>' . ucsc_cdp_read_more(render_attr_single_line($entry, 'ucscPersonPubSelectedPublication', $options, $attributes), $options, $profile_uid) . '</li>');
			} else {
				$result .= render_list_attr('Selected Publications', '<li>' . render_attr_single_line($entry, 'ucscPersonPubSelectedPublication', $options, $attributes) . '</li>');
			}
		}
		$result .= '</ul></div>';
		if($attributes['jpegPhoto']) {
			$result .= render_attr_photo($entry, 'jpegPhoto', $options, $attributes);
		}
		$result .= '</div></div>';
	}
	$result .= '</div>';
	return $result;
}
?>
