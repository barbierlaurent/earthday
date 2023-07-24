<?php

add_action( 'init' , 'ngo_charity_connection_event' );
function ngo_charity_connection_event(){

	Kirki::add_section( 'ngo_charity_connection_event_sections', array(
        'title'   => esc_html__( 'Event', 'ngo-charity-connection' ),
        'section' => 'homepage'
    ) );

    Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'event_subtitle',
		'label'    => esc_html__( 'Subtitle', 'ngo-charity-connection' ),
		'section'  => 'ngo_charity_connection_event_sections'
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'event_title',
		'label'    => esc_html__( 'Title', 'ngo-charity-connection' ),
		'section'  => 'ngo_charity_connection_event_sections'
	] );

	Kirki::add_field( 'bizberg', array(
    	'type'        => 'advanced-repeater',
    	'label'       => esc_html__( 'Events', 'ngo-charity-connection' ),
	    'section'     => 'ngo_charity_connection_event_sections',
	    'settings'    => 'ngo_charity_connection_event_sections',
	    'choices' => [
	        'button_label' => esc_html__( 'Add Events', 'ngo-charity-connection' ),
	        'row_label' => [
	            'value' => esc_html__( 'Events', 'ngo-charity-connection' ),
	        ],
	        'limit'  => 2,
	        'fields' => [
	        	'date'  => [
	                'type'        => 'date',
	                'label'       => esc_html__( 'Date', 'ngo-charity-connection' ),
	                'default'     => '2023-05-17',
	            ],
	            'location'  => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Location', 'ngo-charity-connection' ),
	                'default'     => 'Vancover, Canada',
	            ],
	            'page_id' => [
	                'type'        => 'select',
	                'label'       => esc_html__( 'Page', 'ngo-charity-connection' ),
	                'choices'     => bizberg_get_all_pages()
	            ],
	        ],
	    ]
    ));

}