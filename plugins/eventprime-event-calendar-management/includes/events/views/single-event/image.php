<div class="ep-box-row">
    <div class="ep-box-col-12" id="ep_single_event_image">
        <div class="ep-single-event-slide-container ep-text-center">
            <?php $is_style = $image_style = $image_style_attr = '';
            // image width
            /* $event_detail_image_width = ep_get_global_settings( 'event_detail_image_width' );
            if( ! empty( $event_detail_image_width ) ) {
                $is_style = 1;
                $image_style_attr .= 'width:' . $event_detail_image_width . 'px;';
            }
            // image height
            $event_detail_image_height = ep_get_global_settings( 'event_detail_image_height' );
            if( ! empty( $event_detail_image_height ) && $event_detail_image_height == 'custom' ) {
                $event_detail_image_height_custom = ep_get_global_settings( 'event_detail_image_height_custom' );
                $is_style = 1;
                $image_style_attr .= 'height:' . $event_detail_image_height_custom . 'px;';
            }
            // image align
            $event_detail_image_align = ep_get_global_settings( 'event_detail_image_align' );
            if( ! empty( $event_detail_image_align ) ) {
                $is_style = 1;
                $image_style_attr .= 'text-align:' . $event_detail_image_align . ';';
            }
            // set style
            if( ! empty( $is_style ) ) {
                $image_style = 'style=' . $image_style_attr;
            } */
            $event_gallery = ( ! empty( $args->event->em_gallery_image_ids ) ? $args->event->em_gallery_image_ids : '' );
            if( empty( $event_gallery ) ) {?>
                <img src="<?php echo esc_url( $args->event->image_url );?>" alt="<?php echo esc_attr( $args->event->name ); ?>" class="ep-d-block" <?php echo esc_attr( $image_style );?> /><?php
            } else{?>
                <ul class="ep-rslides ep-m-0 ep-p-0" id="ep_single_event_image_gallery">
                    <?php
                    $event_gallery = explode( ',', $event_gallery );
                    if( ! empty( $args->event->image_url ) && ! empty( has_post_thumbnail( $args->event->em_id ) ) ) {?>
                        <li class="ep-m-0 ep-p-0">
                            <img src="<?php echo esc_url( $args->event->image_url );?>" alt="<?php echo esc_attr( $args->event->name );?>" class="ep-d-block" <?php echo esc_attr( $image_style );?>>
                        </li><?php
                    }
                    foreach( $event_gallery as $image ){
                        $gal_url = wp_get_attachment_image_url( $image, 'large' );
                        if( ! empty( $gal_url ) ) {?>
                            <li><img src="<?php echo esc_url( $gal_url );?>" alt="<?php echo esc_attr( $args->event->name );?>" <?php echo esc_attr( $image_style );?>></li><?php
                        }
                    }?>
                </ul><?php
            }?>      
            <div class="ep-single-event-nav"></div>
        </div>
    </div>
</div>

