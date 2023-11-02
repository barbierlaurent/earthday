<?php
/**
 * Plugin Name: Opportunity Custom Plugin
 * Description: As of 2023-08-14, allow to Register, Unregister and Display Opportunity Volunteer.
 * Version: 1.0
 * Author: Gravel
 */

// Activation Hook: Perform actions when the plugin is activated
register_activation_hook(__FILE__, 'opportunity_custom_plugin_activation');
function opportunity_custom_plugin_activation() {
    global $wpdb;

	$table_name = $wpdb->prefix . 'opportunity_registrations';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id INT(11) NOT NULL AUTO_INCREMENT,
		opportunity_id INT(11) NOT NULL,
		user_id INT(11) NOT NULL,
		waitinglist INT(1) NOT NULL default 0,
		registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

// Deactivation Hook: Perform actions when the plugin is deactivated
function uninstall_opportunity_custom_plugin(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'opportunity_registrations';
    $wpdb->query("DROP TABLE IF EXISTS $table_name"); 
}
register_uninstall_hook(__FILE__,'uninstall_opportunity_custom_plugin');


function enqueue_custom_scripts() {
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'gleaner-registration-script.js', array('jquery'), null, true);
	wp_localize_script('custom-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

	// Pass opportunity_id to JavaScript.
	wp_localize_script('custom-script', 'customData', array(
		'opportunity_id' => get_entry_ID(),
		'currentUserId' => get_current_user_id()
	));

    wp_enqueue_style('custom-script', plugin_dir_url(__FILE__) . 'style.css', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

function get_registered_food_bank_users($registered_users) {
	$food_bank = array();
    
	foreach ($registered_users as $user) {
		$user_info = get_userdata($user->user_id);
		$user_roles = get_user_roles($user->user_id);

		if (in_array('food_bank', $user_roles)) {
			array_push($food_bank, $user->user_id);
		} 
	}
	return $food_bank;
}

function new_registration_callback() {
	global $wpdb;

    $opportunity_id = intval($_POST['opportunity_id']); // Get the opportunity ID from the AJAX request.
	$user_id = get_current_user_id();

	if (!is_user_already_registered($user_id, $opportunity_id)) {  // waitinglist = 0
		
		$table_name = $wpdb->prefix . 'opportunity_registrations';
		$waitinglist = 0;
		
		if(!is_user_already_registered_in_waiting_list($user_id, $opportunity_id)) {

			$wpdb->insert(
				$table_name,
				array(
					'opportunity_id' => $opportunity_id,
					'user_id' => $user_id,
					'waitinglist' => $waitinglist,
					'registration_date' => current_time('mysql'),
				)
			);

			$response = array(
				'success' => true,
				'message' => 'Registration successful.'
			);

			update_registration($opportunity_id);

		} else {
			$response = array(
				'success' => false,
				'message' => 'You are already registered into waitinglist to this opportunity.'
			);
		}
	} else {
		$response = array(
			'success' => false,
			'message' => 'You are already registered to this opportunity.'
		);
	}

	wp_send_json($response);
	wp_die();
}

function remove_registration_callback() {
    global $wpdb;

    $opportunity_id = intval($_POST['opportunity_id']); // Get the opportunity ID from the AJAX request.
	$user_id = get_current_user_id();

	$user_registration_info = get_user_registration_info($opportunity_id, $user_id);
	
	if (!empty($user_registration_info)) { 

		$table_name = $wpdb->prefix . 'opportunity_registrations';
		$is_waiting_listed = $user_registration_info[0]->waitinglist;

		$wpdb->delete(
			$table_name,
			array(
				'user_id' => $user_id,
				'opportunity_id' => $opportunity_id,
			)
		);

		$response = array(
			'success' => true,
			'message' => 'You have successfully unregistered.'
		);

		update_registration($opportunity_id);
	
	} else {
		$response = array(
			'success' => false,
			'message' => 'You are NOT registered to this opportunity.'
		);
	}

	wp_send_json($response);
	wp_die();
}

function update_registration($opportunity_id) {
	$max_participants = get_max_participants($opportunity_id);
	global $wpdb;
	$table_name = $wpdb->prefix . 'opportunity_registrations';
	$query = $wpdb->prepare("SELECT * FROM $table_name WHERE opportunity_id = %d order by registration_date asc", $opportunity_id);
	$all_users_before_change = $wpdb->get_results($query);

	$query = $wpdb->prepare("UPDATE $table_name set waitinglist = 1 WHERE opportunity_id = %d", $opportunity_id);
	$wpdb->query($query); 

	$max_participant_counter = 1;
	foreach($all_users_before_change as $index=>$value) {
		if($max_participant_counter <= $max_participants) {
			$user_id = $all_users_before_change[$index]->user_id;	
			$user_roles = get_user_roles($user_id);
			if (!in_array('food_bank', $user_roles)) {
				$query = $wpdb->prepare("UPDATE $table_name set waitinglist = 0 WHERE opportunity_id = %d and user_id = %d", $opportunity_id, $user_id);
				$wpdb->query($query);
				$max_participant_counter = $max_participant_counter + 1;
			}
	    }
	}

	$max_foodbank_participant_counter = 1;
	foreach($all_users_before_change as $index=>$value) {
		if($max_foodbank_participant_counter <= $max_participants) {
			$user_id = $all_users_before_change[$index]->user_id;	
			$user_roles = get_user_roles($user_id);
			if (in_array('food_bank', $user_roles)) {
				$query = $wpdb->prepare("UPDATE $table_name set waitinglist = 0 WHERE opportunity_id = %d and user_id = %d", $opportunity_id, $user_id);
				$wpdb->query($query);
				break;
			}  
	    }
	}

	$query = $wpdb->prepare("SELECT * FROM $table_name WHERE opportunity_id = %d order by registration_date asc", $opportunity_id);
	$all_users_after_change = $wpdb->get_results($query);

	$farm_name  = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 6, 'entry' => $opportunity_id)); // key [8] is Max number of volunteers
	$event_name = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 7, 'entry' => $opportunity_id));

	$html = '<h3>';

	foreach($all_users_before_change as $index=>$value) {
		$user_id = $all_users_before_change[$index]->user_id;	
		if ($all_users_before_change[$index]->waitinglist != $all_users_after_change[$index]->waitinglist) {
			if ($all_users_after_change[$index]->waitinglist == '1') {
				$message = ' You have been added to the waiting list for the farm: '. $farm_name. ', event: '.$event_name;
				$html = $html. ' sending email to ' .$email. '-'.$message;
				sendEmail($user_id, 'Farm Gleaning Notification', $message);
			} else {
				$message = ' You have been added to the main list for the farm: '. $farm_name. ', event: '.$event_name;
				$html = $html. ' sending email to '. $email. '-'.$message;
				sendEmail($user_id, 'Farm Gleaning Notification', $message);
			}
		}	
	}
	
	return $html.'</h3>';
}

function sendEmail($user_id, $subject, $message) {
	$user = new WP_User($user_id);
	$email = $user->user_email;
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$sent = wp_mail($email, $subject, $message, $headers);
	if ($sent) {
		return 'Email sent successfully';
	} else {
		return 'Email not sent </br>'.$subject.'</br>'.$message.'</br>';
	}
}

function is_user_already_registered($user_id, $opportunity_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'opportunity_registrations';

    // Check if the user is registered for the opportunity.
    $query = $wpdb->prepare("SELECT user_id FROM $table_name WHERE user_id = %d AND opportunity_id = %d AND waitinglist = 0", $user_id, $opportunity_id);
    $registered_user_id = $wpdb->get_var($query);

	error_log('Debugging: ' . $registered_user_id);
    // Return true if the user is registered, otherwise return false.
    return !empty($registered_user_id);
}

function is_user_already_registered_in_waiting_list($user_id, $opportunity_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'opportunity_registrations';

    // Check if the user is registered for the opportunity via waiting list.
    $query = $wpdb->prepare("SELECT user_id FROM $table_name WHERE user_id = %d AND opportunity_id = %d AND waitinglist=1", $user_id, $opportunity_id);
    $registered_user_id = $wpdb->get_var($query);

	error_log('Debugging: ' . $registered_user_id);
    // Return true if the user is registered, otherwise return false.
    return !empty($registered_user_id);
}

function show_registration_list_shortcode() {
	
	$opportunity_id = get_entry_ID();
    $registered_users = get_registered_users($opportunity_id, 0);
	$registered_users_waiting_list = get_registered_users($opportunity_id, 1);
		
    $html_content = ''; 

	$foodbank_participant_counter = 0;
	foreach($registered_users as $index=>$value) {
		$user_id = $registered_users[$index]->user_id;	
		$user_roles = get_user_roles($user_id);
		if (in_array('food_bank', $user_roles)) {
			$foodbank_participant_counter = $foodbank_participant_counter + 1;
		}  
	}

    // Display registered users.
    if (!empty($registered_users)) {

		$cnt = count($registered_users);
		$max_participants = get_max_participants($opportunity_id);

		$html_content = '<ul><h3>List of volunteers ('. ($cnt - $foodbank_participant_counter).'/'.$max_participants.'):</h3></ul>';
		 
        foreach ($registered_users as $user) {
            $user_info = get_userdata($user->user_id);
			$user_roles = get_user_roles($user->user_id);
			$registration_date = date_i18n(
				get_option('date_format') . ' ' . get_option('time_format'),
				strtotime($user->registration_date ?? '')
			);
			$user_roles_string = implode(",", $user_roles);
			if (in_array('food_bank', $user_roles)) {
				$html_content = $html_content. '<li>' . $user_info->display_name . ' - ' . $registration_date. ' - <b>'. $user_roles_string . '</b>';
			} else {
				$html_content = $html_content. '<li>' . $user_info->display_name . ' - ' . $registration_date. ' - '. $user_roles_string;
			}
			
        }
        $html_content = $html_content. '</ul></br>';
    } else {
        $html_content = $html_content. 'No registered users for this event.</br>';
    }

	// Display registered users.
    if (!empty($registered_users_waiting_list)) {
		$html_content = $html_content. '</br><h5>waiting list:</h5>';
        foreach ($registered_users_waiting_list as $user) {
            $user_info = get_userdata($user->user_id);
			$user_roles = get_user_roles($user->user_id);
			$registration_date = date_i18n(
				get_option('date_format') . ' ' . get_option('time_format'),
				strtotime($user->registration_date ?? '')
			);
			$user_roles_string = implode(",", $user_roles);
			if (in_array('food_bank', $user_roles)) {
				$html_content = $html_content. '<li>' . $user_info->display_name . ' - ' . $registration_date. ' - <b>'. $user_roles_string. '</b></li>';
			} else {
				$html_content = $html_content. '<li>' . $user_info->display_name . ' - ' . $registration_date. ' - '. $user_roles_string.'</li>';
			}
			
        }
        $html_content = $html_content. '</ul>';
    } else {
        $html_content = $html_content. '</br>No users in waiting list for this event.';
    }

	return $html_content . '</br>';
}

function display_registration_button_shortcode($atts, $content = null) {

    if (is_user_logged_in()) {	

		$user_id = get_current_user_id();
        $opportunity_id = get_entry_ID();
	
		$roles = get_user_roles($user_id);
		if (in_array('farmer', $roles)) {
			return '';
		} 
	
		$registered_users = get_registered_users($opportunity_id, 0);

		$is_normal_registered = is_user_already_registered($user_id, $opportunity_id);
		$is_waitinglist_registered = is_user_already_registered_in_waiting_list($user_id, $opportunity_id);

        if ($is_normal_registered) {
            return '<h3 id=registered-label>You are Registered to this event. </h3><button id=gleanerRegistrationButton class="unregister-button">Unregister</button>';
        } else if ($is_waitinglist_registered) {
			return '<h3 id=registered-label>You are in the waiting list for this event. </h3><button id=gleanerRegistrationButton class="unregister-button">Unregister</button>';
		} else {

			$users_not_food_bank_in_main_list = 0;
			foreach($registered_users as $index=>$value) {
				$user_id = $registered_users[$index]->user_id;	
				$user_roles = get_user_roles($user_id);
				if (!in_array('food_bank', $user_roles)) {
					$users_not_food_bank_in_main_list = $users_not_food_bank_in_main_list + 1;
				}
			}

			if ($users_not_food_bank_in_main_list >= get_max_participants($opportunity_id)) {
				return '<button id=gleanerRegistrationButton class="add-waiting-list-button">Please Add me to waiting list</button>';
			} else {
				return '<button id=gleanerRegistrationButton class="register-button">Register</button>';
			}
            
        }
    }
    return ''; // User not logged in
}

function get_entry_ID() {
	global $wp;
	$url = home_url($wp->request);
	$pattern = '/\/entry\/([^\/]+)/'; // Regex pattern
	$entry_id = '';

	if (preg_match($pattern, $url, $matches)) {
		$entry_id = $matches[1];
	} 
	return $entry_id;
}

function get_max_participants($entry_id) {	
	$val = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 8, 'entry' => $entry_id)); // key [8] is Max number of volunteers
	return $val;
}

function get_registered_users($opportunity_id, $waitinglist) {
	global $wpdb;

    $table_name = $wpdb->prefix . 'opportunity_registrations';

    $query = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE opportunity_id = %d and waitinglist = %d", $opportunity_id, $waitinglist
    );

    $registered_users = $wpdb->get_results($query);
	return $registered_users;
}

function get_all_registered_users($opportunity_id) {
	global $wpdb;

    $table_name = $wpdb->prefix . 'opportunity_registrations';

    $query = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE opportunity_id = %d", $opportunity_id
    );

    $registered_users = $wpdb->get_results($query);
	return $registered_users;
}

function get_user_registration_info($opportunity_id, $user_id) {
	global $wpdb;

    $table_name = $wpdb->prefix . 'opportunity_registrations';

    $query = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE opportunity_id = %d and user_id = %d", $opportunity_id, $user_id
    );

    $user_info = $wpdb->get_results($query);
	return $user_info;
}

function get_user_roles($user_id) {
	if (!empty($user_id)) {
		$user = new WP_User($user_id);
		$roles = $user -> roles;
		return $roles;
	}
	return ''; 
}


function link_fields($entry_id, $form_id){
	if ($form_id == '2') {
		update_registration($entry_id);
	} 
}

add_action('frm_after_update_entry', 'link_fields', 10, 2);

add_shortcode('display_registration_button', 'display_registration_button_shortcode', 10, 2);
add_shortcode('show_registration_list_shortcode', 'show_registration_list_shortcode');
add_shortcode('is_user_already_registered', 'is_user_already_registered');
add_shortcode('get_user_roles', 'get_user_roles');
add_shortcode('get_registered_users', 'get_registered_users');

add_action('wp_ajax_new_registration_action', 'new_registration_callback');
add_action('wp_ajax_nopriv_new_registration_action', 'new_registration_callback'); // For non-logged-in users
add_action('wp_ajax_deregistration_action', 'remove_registration_callback');
add_action('wp_ajax_nopriv_deregistration_action', 'remove_registration_callback'); // For non-logged-in users

add_action('wp_ajax_add_waiting_list_action', 'new_registration_callback');
add_action('wp_ajax_nopriv_add_waiting_list_action', 'new_registration_callback'); // For non-logged-in users