<?php

add_action( 'bizberg_before_homepage_blog', 'ngo_charity_connection_eventss' );
function ngo_charity_connection_eventss(){

	$subtitle = bizberg_get_theme_mod( 'event_subtitle' );
	$title    = bizberg_get_theme_mod( 'event_title' );

	$events   = bizberg_get_theme_mod( 'ngo_charity_connection_event_sections' ); 
	$events   = json_decode( $events,true ); 

	if( empty( $events ) ){
		return;
	} ?>

	<div class="events_wrapper">
		
		<div class="container">

			<div class="title">
				<h4><?php echo esc_html( $subtitle ); ?></h4>
				<h3><?php echo esc_html( $title ); ?></h3>
			</div>

			<div class="events_outer">

				<?php 
				foreach ( $events as $value ) {

					$date       =  !empty( $value['date'] ) ? $value['date'] : '';
					$location   =  !empty( $value['location'] ) ? $value['location'] : '';
					$page_id    =  !empty( $value['page_id'] ) ? $value['page_id'] : '';
					$events_obj = get_post( $page_id );

					$featured_img_url = get_the_post_thumbnail_url( $page_id ,'full');  ?>

					<div class="item">
						
						<div class="image" style="background-image:url( <?php echo esc_url( $featured_img_url ); ?> )"></div>

						<div class="content">
							
							<div class="date"><?php echo esc_html( date( 'M j,Y' , strtotime($date) ) ); ?></div>
							<h3><?php echo esc_html( $events_obj->post_title ); ?></h3>
							<p><?php echo esc_html( wp_trim_words( sanitize_text_field( $events_obj->post_content ), 15, null ) ); ?></p>
							<span class="map"><i class="fas fa-map-pin"></i> <?php echo esc_html( $location ); ?></span>

						</div>

					</div>

					<?php
				} ?>

			</div>

		</div>

	</div>

	<?php
}