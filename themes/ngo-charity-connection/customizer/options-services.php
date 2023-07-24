<?php

add_action( 'init' , 'ngo_charity_connection_services' );
function ngo_charity_connection_services(){

	Kirki::add_section( 'ngo_charity_connection_services', array(
        'title'   => esc_html__( 'Services', 'ngo-charity-connection' ),
        'section' => 'homepage'
    ) );

    Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'services_status',
		'label'       => esc_html__( 'Enable / Disable', 'ngo-charity-connection' ),
		'section'     => 'ngo_charity_connection_services',
		'default'     => false,
	] );

	Kirki::add_field( 'bizberg', array(
    	'type'        => 'advanced-repeater',
    	'label'       => esc_html__( 'Services', 'ngo-charity-connection' ),
	    'section'     => 'ngo_charity_connection_services',
	    'settings'    => 'ngo_charity_connection_services',
	    'active_callback' => [
			[
				'setting'  => 'services_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	    'choices' => [
	        'button_label' => esc_html__( 'Add Services', 'ngo-charity-connection' ),
	        'row_label' => [
	            'value' => esc_html__( 'Services', 'ngo-charity-connection' ),
	        ],
	        'limit'  => 6,
	        'fields' => [
	        	'icon'  => [
	                'type'        => 'fontawesome',
	                'label'       => esc_html__( 'Icon', 'ngo-charity-connection' ),
	                'default'     => 'fab fa-accusoft',
	                'choices'     => bizberg_get_fontawesome_options(),
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