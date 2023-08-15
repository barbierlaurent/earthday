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
		'opportunity_id' => get_the_ID(),
		'currentUserId' => get_current_user_id()
	));

    wp_enqueue_style('custom-script', plugin_dir_url(__FILE__) . 'style.css', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


function new_registration_callback() {
	global $wpdb;

    $opportunity_id = intval($_POST['opportunity_id']); // Get the opportunity ID from the AJAX request.
	$user_id = get_current_user_id();

	if (!is_user_already_registered($user_id, $opportunity_id)) { 
		$table_name = $wpdb->prefix . 'opportunity_registrations';
		$wpdb->insert(
			$table_name,
			array(
				'opportunity_id' => $opportunity_id,
				'user_id' => $user_id,
				'registration_date' => current_time('mysql'),
			)
		);
		$response = array(
			'success' => true,
			'message' => 'Registration successful.'
		);
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

	if (is_user_already_registered($user_id, $opportunity_id)) { 
		$table_name = $wpdb->prefix . 'opportunity_registrations';
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
	} else {
		$response = array(
			'success' => false,
			'message' => 'You are NOT registered to this opportunity.'
		);
	}

	wp_send_json($response);
	wp_die();
}


function is_user_already_registered($user_id, $opportunity_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'opportunity_registrations';

    // Check if the user is registered for the opportunity.
    $query = $wpdb->prepare("SELECT user_id FROM $table_name WHERE user_id = %d AND opportunity_id = %d", $user_id, $opportunity_id);
    $registered_user_id = $wpdb->get_var($query);

	error_log('Debugging: ' . $registered_user_id);
    // Return true if the user is registered, otherwise return false.
    return !empty($registered_user_id);
}

function show_registration_list_shortcode() {
	global $wpdb;

    $opportunity_id = get_the_ID();

    $table_name = $wpdb->prefix . 'opportunity_registrations';

    $query = $wpdb->prepare(
        "SELECT user_id FROM $table_name WHERE opportunity_id = %d",
        $opportunity_id
    );

    $registered_users = $wpdb->get_results($query);

    // Display registered users.
    if (!empty($registered_users)) {
        echo '<ul>';
        foreach ($registered_users as $user) {
            $user_info = get_userdata($user->user_id);
			$registration_date = date_i18n(
				get_option('date_format') . ' ' . get_option('time_format'),
				strtotime($user->registration_date ?? '')
			);
			echo '<li>' . $user_info->display_name . ' - ' . $registration_date . '</li>';
        }
        echo '</ul>';
    } else {
        echo 'No registered users for this event.';
    }
}


function display_registration_button_shortcode($atts, $content = null) {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $opportunity_id = get_the_ID();
        
        if (is_user_already_registered($user_id, $opportunity_id)) {
            return '<h3 id=registered-label>You are Registered to this event. </h3><button id=gleanerRegistrationButton class="unregister-button">Unregister</button>';
        } else {
            return '<button id=gleanerRegistrationButton class="register-button">Register</button>';
        }
    }
    return ''; // User not logged in
}


add_shortcode('display_registration_button', 'display_registration_button_shortcode');
add_shortcode('show_registration_list_shortcode', 'show_registration_list_shortcode');
add_shortcode('is_user_already_registered', 'is_user_already_registered');


add_action('wp_ajax_new_registration_action', 'new_registration_callback');
add_action('wp_ajax_nopriv_new_registration_action', 'new_registration_callback'); // For non-logged-in users
add_action('wp_ajax_deregistration_action', 'remove_registration_callback');
add_action('wp_ajax_nopriv_deregistration_action', 'remove_registration_callback'); // For non-logged-in users
