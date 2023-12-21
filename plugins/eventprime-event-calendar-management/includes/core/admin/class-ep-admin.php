<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for core admin use
 */
class EventM_Admin {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'plugin_redirect' ) );
        add_action( 'init', array( $this, 'includes' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'ep_admin_enqueues' ) );
        add_filter( 'display_post_states',array($this,'ep_display_post_states'),10, 2);
        //add_action( 'admin_notices', array($this,'ep_check_required_pages') );
        add_action( 'admin_footer', array( $this, 'ep_deactivation_feedback_form' ) );

        // admin notice for elementor
        add_action( 'admin_notices', array( $this, 'ep_check_for_elementor_plugin' ) );
    }

    /**
     * Redirect plugin after activate
     */
    public function plugin_redirect() {
        if ( get_option( 'event_magic_do_activation_redirect', false ) ) {
            delete_option( 'event_magic_do_activation_redirect' );
            $check_for_migration = get_option( 'ep_db_need_to_run_migration' );
            $update_migration = get_option( 'ep_update_revamp_version' );
            if( ! empty( $check_for_migration ) && empty( $update_migration ) ) {
                wp_safe_redirect( admin_url( 'edit.php?post_type=em_event&page=ep-revamp-migration' ) );
            } else{
                wp_safe_redirect( admin_url( 'edit.php?post_type=em_event' ) );
            }
            exit;
        }
    }

    /**
     * Include classes for admin use
     */
    public function includes() {
        // admin menu class
        include_once __DIR__ . '/class-ep-admin-menus.php';
        include_once __DIR__ . '/class-ep-admin-notices.php';
    }

    /**
     * Load common scripts and styles for admin
     */
    public function ep_admin_enqueues() {
        wp_enqueue_script(
            'ep-common-script',
            EP_BASE_URL . '/includes/assets/js/ep-common-script.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        // localized global settings
        $global_settings = ep_get_global_settings();
        $currency_symbol = ep_currency_symbol();
        wp_localize_script(
            'ep-common-script', 
            'eventprime', 
            array(
                'global_settings' => $global_settings,
                'currency_symbol' => $currency_symbol,
                'ajaxurl'         => admin_url( 'admin-ajax.php' ),
                'trans_obj'       => EventM_Factory_Service::ep_define_common_field_errors(),
            )
        );

        wp_enqueue_script(
			'ep-admin-utility-script',
			EP_BASE_URL . 'includes/assets/js/ep-admin-common-utility.js',
			array( 'jquery', 'jquery-ui-tooltip', 'jquery-ui-dialog' ), EVENTPRIME_VERSION
        );

        wp_localize_script(
            'ep-admin-utility-script', 
            'ep_admin_utility_script', 
            array(
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );

        wp_enqueue_style(
			'ep-admin-utility-style',
			EP_BASE_URL . 'includes/assets/css/ep-admin-common-utility.css',
			false, EVENTPRIME_VERSION
        );

        //wp_enqueue_style( 'ep-material-fonts', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), EVENTPRIME_VERSION );
        wp_enqueue_style( 'ep-material-fonts', EP_BASE_URL . '/includes/assets/css/ep-material-fonts-icon.css', array(), EVENTPRIME_VERSION );
        
        // register common scripts
        wp_register_script(
			'em-admin-jscolor',
			EP_BASE_URL . '/includes/assets/js/jscolor.min.js',
			false, EVENTPRIME_VERSION
		);

        wp_register_style(
			'em-admin-select2-css',
			EP_BASE_URL . '/includes/assets/css/select2.min.css',
			false, EVENTPRIME_VERSION
		);
		wp_register_script(
			'em-admin-select2-js',
			EP_BASE_URL . '/includes/assets/js/select2.full.min.js',
			false, EVENTPRIME_VERSION
		);

        wp_register_style(
		    'em-admin-jquery-ui',
		    EP_BASE_URL . '/includes/assets/css/jquery-ui.min.css',
		    false, EVENTPRIME_VERSION
        );
		// Ui Timepicker css
		wp_register_style(
		    'em-admin-jquery-timepicker',
		    EP_BASE_URL . '/includes/assets/css/jquery.timepicker.min.css',
		    false, EVENTPRIME_VERSION
        );

        // timepicker js
		wp_register_script(
		    'em-admin-timepicker-js',
		    EP_BASE_URL . '/includes/assets/js/jquery.timepicker.min.js',
		    false, EVENTPRIME_VERSION
        );

        // register toast
        wp_register_style(
            'ep-toast-css',
            EP_BASE_URL . '/includes/assets/css/jquery.toast.min.css',
            false, EVENTPRIME_VERSION
        );
        wp_register_script(
            'ep-toast-js',
            EP_BASE_URL . '/includes/assets/js/jquery.toast.min.js',
            array('jquery'), EVENTPRIME_VERSION
        );
        wp_register_script(
            'ep-toast-message-js',
            EP_BASE_URL . '/includes/assets/js/toast-message.js',
            array('jquery'), EVENTPRIME_VERSION
        );

        // Blocks style for admin
        wp_register_script(
            'eventprime-admin-blocks-js',
            EP_BASE_URL . '/includes/assets/js/blocks/index.js',
            array( 'wp-blocks', 'wp-editor', 'wp-i18n', 'wp-element', 'wp-components' ),
            EVENTPRIME_VERSION
        );

		wp_register_style(
		    'ep-admin-blocks-style',
		    EP_BASE_URL . '/includes/assets/css/ep-admin-blocks-style.css',
		    false, EVENTPRIME_VERSION
        );
    }
    
    public function ep_display_post_states($post_states, $post){
        if ( intval( ep_get_global_settings( 'performers_page' ) ) === $post->ID ) {
            $post_states['ep_performers_page'] = __( 'EventPrime Performer Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'venues_page' ) ) === $post->ID ) {
            $post_states['ep_venues_page'] = __( 'EventPrime Venues Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'events_page' ) ) === $post->ID ) {
            $post_states['ep_events_page'] = __( 'EventPrime Events Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'booking_page' ) ) === $post->ID ) {
            $post_states['ep_booking_page'] = __( 'EventPrime Checkout Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'profile_page' ) ) === $post->ID ) {
            $post_states['ep_profile_page'] = __( 'EventPrime Profile Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'event_types' ) ) === $post->ID ) {
            $post_states['ep_event_types'] = __( 'EventPrime Event Types Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'event_submit_form' ) ) === $post->ID ) {
            $post_states['ep_event_submit_form'] = __( 'EventPrime Submit Event Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'booking_details_page' ) ) === $post->ID ) {
            $post_states['ep_booking_details_page'] = __( 'EventPrime Booking Details Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'event_organizers' ) ) === $post->ID ) {
            $post_states['ep_event_organizers'] = __( 'EventPrime Organizers Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'login_page' ) ) === $post->ID ) {
            $post_states['ep_login_page'] = __( 'EventPrime Login Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'register_page' ) ) === $post->ID ) {
            $post_states['ep_register_page'] = __( 'EventPrime Registeration Page', 'eventprime-event-calendar-management' );
	}
        
	return $post_states;
    }
    
    public function ep_check_required_pages() {
        $notices = '';
        $pages = array(
            "events_page" => array("Event List", "[em_events"),
            "venues_page" => array("Site & Location", "[em_sites"),
            "booking_page" => array("Booking", "[em_booking"),
            "profile_page" => array("User Profile", "[em_profile"),
            "performers_page" => array("Performer List", "[em_performers"),
            "booking_details_page" => array("Booking Details", "[em_booking_details")
        );
        foreach ( $pages as $key => $value ) {
            $page_id = ep_get_global_settings( $key );
            $post = get_post( $page_id );
            if( empty( $post ) ) {
                $notices .= '<p> For ' . $value[0] . ' use ' . $value[1] . '] shortcode</p>';
                continue;
            }
            $short_code_exists = strpos( $post->post_content, $value[1] );
            if (empty($post) || $post->post_status == "trash" || $short_code_exists === false) {
                $notices .= '<p> For ' . $value[0] . ' use ' . $value[1] . '] shortcode</p>';
            }
        }

        if ( ! empty( $notices ) ) {
            echo '<div class="notice notice-error is-dismissible">EventPrime: It seems all the required pages are not configured.' . $notices .
            '<b>Note*: Once you have pasted all the shortcodes inside corresponding pages, you can configure the default pages in EventPrime Settings -> Pages. </b>' .
            '</div>';
        }
    }

    public function ep_deactivation_feedback_form() {
        // Enqueue feedback form scripts and render HTML on the Plugins backend page
        if ( get_current_screen()->parent_base == 'plugins' ) {
            wp_enqueue_script(
                'ep-plugin-feedback-js',
                EP_BASE_URL . '/includes/core/assets/js/ep-plugin-feedback.js',
                array('jquery'), EVENTPRIME_VERSION
            );
            wp_localize_script(
                'ep-plugin-feedback-js', 
                'ep_feedback', 
                array(
                    'ajaxurl'        => admin_url( 'admin-ajax.php' ),
                    'option_error'   => esc_html__( 'Please select one option', 'eventprime-event-calendar-management' ),
                    'feedback_nonce' => wp_create_nonce( 'ep-plugin-deactivation-nonce' ),
                )
            );
            include_once __DIR__ . '/template/plugin-feedback.php';
        }
    }

    public function ep_check_for_elementor_plugin() {
        if ( get_current_screen()->parent_base == 'plugins' ) {
            if ( ! function_exists( 'get_plugins' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $installed_plugins = get_plugins();
            if( isset( $installed_plugins['elementor/elementor.php'] ) && ! empty( $installed_plugins['elementor/elementor.php'] ) && ! class_exists( 'EP_Elementor_Integration' ) ) {?>
                <div class="notice notice-error is-dismissible ep-p-2">
                    EventPrime widgets for Elementor with Elementor Integration Extension.
                    <a target="_blank" href="<?php echo esc_url( 'https://theeventprime.com/all-extensions/elementor-integration-extension/' );?>"><?php echo esc_html( 'Download Now', 'eventprime-event-calendar-management' );?></a>
                </div><?php
            }
        }
    }
}

return new EventM_Admin();