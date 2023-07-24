<?php

add_action( 'bizberg_before_homepage_blog', 'ngo_charity_connection_homepage_services' );
function ngo_charity_connection_homepage_services(){

	$status = bizberg_get_theme_mod( 'services_status' );
	 
	if( empty( $status ) ){
		return;
	}

	$services = bizberg_get_theme_mod( 'ngo_charity_connection_services' );
	$services = json_decode($services,true); ?>

	<div class="services_wrapper">
		
		<div class="container">
			
			<div class="services_outer">

				<?php 

				foreach( $services as $value ){

					$icon    = !empty( $value['icon'] ) ? $value['icon'] : '';
					$page_id = !empty( $value['page_id'] ) ? $value['page_id'] : '';

					$services_post = get_post( $page_id ); ?>
				
					<div class="item">
						
						<div class="icon">
							<i class="<?php echo esc_attr( $icon ); ?>"></i>
						</div>

						<div class="right">
							<h3><?php echo esc_html( $services_post->post_title ); ?></h3>
							<p><?php echo esc_html( wp_trim_words( sanitize_text_field( $services_post->post_content ), 15, null ) ); ?></p>
						</div>

					</div>

					<?php 

				} ?>

			</div>

		</div>

	</div>

	<?php
}