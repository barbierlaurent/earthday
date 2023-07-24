<?php

require get_stylesheet_directory() . '/customizer/options-services.php';
require get_stylesheet_directory() . '/customizer/options-event.php';
require get_stylesheet_directory() . '/sections/services.php';
require get_stylesheet_directory() . '/sections/event.php';

add_action( 'wp_enqueue_scripts', 'ngo_charity_connection_chld_thm_parent_css' );
function ngo_charity_connection_chld_thm_parent_css() {

    wp_enqueue_style( 
    	'ngo_charity_connection_chld_css', 
    	trailingslashit( get_template_directory_uri() ) . 'style.css', 
    	array( 
    		'bootstrap',
    		'font-awesome-5',
    		'bizberg-main',
    		'bizberg-component',
    		'bizberg-style2',
    		'bizberg-responsive' 
    	) 
    );

    if ( is_rtl() ) {
        wp_enqueue_style( 
            'ngo_charity_connection_parent_rtl', 
            trailingslashit( get_template_directory_uri() ) . 'rtl.css'
        );
    }
    
}

add_action( 'after_setup_theme', 'ngo_charity_connection_setup_theme' );
function ngo_charity_connection_setup_theme() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
}

add_filter( 'bizberg_sidebar_settings', 'ngo_charity_connection_sidebar_settings' );
function ngo_charity_connection_sidebar_settings(){
    return '4';
}

add_filter( 'bizberg_footer_social_links' , 'ngo_charity_connection_footer_social_links' );
function ngo_charity_connection_footer_social_links(){
    return [];
}

add_filter( 'bizberg_theme_color', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_header_menu_color_hover_sticky_menu', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_header_button_color_sticky_menu', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_header_button_color_hover_sticky_menu', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_header_menu_color_hover', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_header_button_color', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_header_button_color_hover', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_slider_title_box_highlight_color', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_slider_arrow_background_color', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_slider_dot_active_color', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_read_more_background_color', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_read_more_background_color_2', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_link_color', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_link_color_hover', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_blog_listing_pagination_active_hover_color', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_sidebar_widget_link_color_hover', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_sidebar_widget_title_color', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_footer_social_icon_background', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_background_color_1', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_background_color_2', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_transparent_header_menu_color_hover', 'ngo_charity_connection_change_theme_color' );
add_filter( 'bizberg_transparent_header_sticky_menu_color_hover', 'ngo_charity_connection_change_theme_color' );
function ngo_charity_connection_change_theme_color(){
    return '#e0be53';
}

add_filter( 'bizberg_three_col_listing_radius', 'ngo_charity_connection_three_col_listing_radius' );
function ngo_charity_connection_three_col_listing_radius(){
    return '0';
}

add_filter( 'bizberg_transparent_header_homepage', 'ngo_charity_connection_transparent_header_homepage' );
function ngo_charity_connection_transparent_header_homepage(){
    return true;
}

add_filter( 'bizberg_transparent_navbar_background', 'ngo_charity_connection_transparent_navbar_background' );
function ngo_charity_connection_transparent_navbar_background(){
    return 'rgba(10,10,10,0)';
}

add_filter( 'bizberg_header_blur', 'ngo_charity_connection_header_blur' );
function ngo_charity_connection_header_blur(){
    return 0;
}

add_filter( 'bizberg_transparent_header_menu_sticky_background', 'ngo_charity_connection_transparent_header_menu_sticky_background' );
add_filter( 'bizberg_transparent_header_menu_toggle_color_mobile', 'ngo_charity_connection_transparent_header_menu_sticky_background' );
function ngo_charity_connection_transparent_header_menu_sticky_background(){
    return '#fff';
}

add_filter( 'bizberg_transparent_header_menu_sticky_text_color', 'ngo_charity_connection_transparent_header_menu_sticky_text_color' );
function ngo_charity_connection_transparent_header_menu_sticky_text_color(){
    return '#64686d';
}

add_filter( 'bizberg_banner_spacing', 'ngo_charity_connection_banner_spacing' );
function ngo_charity_connection_banner_spacing(){
    return [
        'padding-top'    => '160px',
        'padding-bottom' => '110px',
        'padding-left'   => '0px',
        'padding-right'  => '400px',
    ];
}

add_filter( 'bizberg_banner_image', 'ngo_charity_connection_banner_image' );
function ngo_charity_connection_banner_image(){
    return [
        'background-color'      => 'rgba(20,20,20,.8)',
        'background-image'      => get_stylesheet_directory_uri() . '/img/work-man-person-people-street-old-560343-pxhere.com.jpg',
        'background-repeat'     => 'repeat',
        'background-position'   => 'center center',
        'background-size'       => 'cover',
        'background-attachment' => 'fixed'
    ];
}

add_filter( 'bizberg_banner_title', 'ngo_charity_connection_banner_title' );
function ngo_charity_connection_banner_title(){
    return current_user_can( 'edit_theme_options' ) ? esc_html__( "Let's Build the Better World Together", 'ngo-charity-connection' ) : '';
}

add_filter( 'bizberg_banner_subtitle', 'ngo_charity_connection_banner_subtitle' );
function ngo_charity_connection_banner_subtitle(){
    return current_user_can( 'edit_theme_options' ) ? esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form by injected humour.', 'ngo-charity-connection' ) : '';
}

add_filter( 'bizberg_banner_title_font_status' , 'ngo_charity_connection_banner_title_font_status' );
function ngo_charity_connection_banner_title_font_status(){
    return true;
}

add_filter( 'bizberg_banner_title_font_desktop' , 'ngo_charity_connection_banner_title_font_desktop' );
function ngo_charity_connection_banner_title_font_desktop(){
    return [
        'font-family'    => 'Poppins',
        'variant'        => '900',
        'font-size'      => '80px',
        'line-height'    => '1',
        'letter-spacing' => '0',
        'text-transform' => 'none'
    ];
}

add_filter( 'bizberg_banner_title_font_tablet' , 'ngo_charity_connection_banner_title_font_tablet' );
function ngo_charity_connection_banner_title_font_tablet(){
    return [
        'font-size'      => '70px',
        'line-height'    => '1',
        'letter-spacing' => '0'
    ];
}

add_filter( 'bizberg_banner_title_font_mobile' , 'ngo_charity_connection_banner_title_font_mobile' );
function ngo_charity_connection_banner_title_font_mobile(){
    return [
        'font-size'      => '55px',
        'line-height'    => '1',
        'letter-spacing' => '0'
    ];
}

add_filter( 'bizberg_banner_subtitle_font_status' , 'ngo_charity_connection_banner_subtitle_font_status' );
function ngo_charity_connection_banner_subtitle_font_status(){
    return true;
}

add_filter( 'bizberg_banner_subtitle_font_settings_desktop' , 'ngo_charity_connection_banner_subtitle_font_settings_desktop' );
function ngo_charity_connection_banner_subtitle_font_settings_desktop(){
    return [
        'font-family'    => 'Poppins',
        'variant'        => 'regular',
        'font-size'      => '20px',
        'line-height'    => '1.4',
        'letter-spacing' => '0',
        'text-transform' => 'none'
    ];
}

add_filter( 'bizberg_transparent_header_sticky_menu_toggle_color_mobile' , 'ngo_charity_connection_transparent_header_sticky_menu_toggle_color_mobile' );
function ngo_charity_connection_transparent_header_sticky_menu_toggle_color_mobile(){
    return '#434343';
}

add_filter( 'bizberg_site_title_font', 'ngo_charity_connection_site_title_font' );
function ngo_charity_connection_site_title_font(){
    return [
        'font-family'    => 'Montserrat',
        'variant'        => '600',
        'font-size'      => '23px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'uppercase',
        'text-align'     => 'left',
    ];
}

add_filter( 'bizberg_site_tagline_font', 'ngo_charity_connection_site_tagline_font' );
function ngo_charity_connection_site_tagline_font(){
    return [
        'font-family'    => 'Montserrat',
        'variant'        => '300',
        'font-size'      => '13px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
        'text-align'     => 'left',
    ];
}

add_filter( 'bizberg_sidebar_spacing_status', 'ngo_charity_connection_sidebar_spacing_status' );
function ngo_charity_connection_sidebar_spacing_status(){
    return '0px';
}

add_filter( 'bizberg_sidebar_widget_border_color', 'ngo_charity_connection_sidebar_widget_background_color' );
add_filter( 'bizberg_sidebar_widget_background_color', 'ngo_charity_connection_sidebar_widget_background_color' );
function ngo_charity_connection_sidebar_widget_background_color(){
    return 'rgba(251,251,251,0)';
}

add_filter( 'bizberg_getting_started_screenshot', 'ngo_charity_connection_getting_started_screenshot' );
function ngo_charity_connection_getting_started_screenshot(){
    return true;
}

add_action( 'after_switch_theme', 'ngo_charity_connection_switch_theme' );
function ngo_charity_connection_switch_theme() {

    $flag = get_theme_mod( 'ngo_charity_connection_copy_settings', false );

    if ( true === $flag ) {
        return;
    }

    foreach( Kirki::$fields as $field ) {
        set_theme_mod( $field["settings"],$field["default"] );
    }

    //Set flag
    set_theme_mod( 'ngo_charity_connection_copy_settings', true );
    
}