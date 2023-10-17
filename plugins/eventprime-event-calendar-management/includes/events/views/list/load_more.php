<?php
/**
 * View: Event List - Load More
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/performers/list/load_more.php
 *
 */
?>
<?php
if( isset( $args->events->max_num_pages ) && $args->events->max_num_pages > 1 && $args->load_more == 1 ) {
    $show_no_of_events_card = ep_get_global_settings( 'show_no_of_events_card' );
    if( 'custom' == $show_no_of_events_card ) {
        $show_no_of_events_card = ep_get_global_settings( 'card_view_custom_value' );
    }
    if( ! empty( $args->events->posts ) && count( $args->events->posts ) >= $show_no_of_events_card ) {?>
        <div class="ep-events-load-more ep-frontend-loadmore ep-box-w-100 ep-my-4 ep-text-center">
            <input type="hidden" id="ep-events-limit" value="<?php echo esc_attr( $args->limit );?>"/>
            <input type="hidden" id="ep-events-order" value="<?php echo esc_attr( $args->order );?>"/>
            <input type="hidden" id="ep-events-paged" value="<?php echo esc_attr( $args->paged );?>"/>
            <input type="hidden" id="ep-events-types-ids" value="<?php echo esc_attr( isset( $args->types_ids ) ? implode( ',', $args->types_ids ) : '' ); ?>"/>
            <input type="hidden" id="ep-events-venues-ids" value="<?php echo esc_attr( isset( $args->venue_ids ) ? implode( ',', $args->venue_ids ) : '' ); ?>"/>
            <button data-max="<?php echo esc_attr( $args->events->max_num_pages );?>" id="ep-loadmore-events" class="ep-btn ep-btn-outline-primary">
                <span class="ep-spinner ep-spinner-border-sm ep-mr-1"></span>
                <?php echo esc_attr( $args->load_more_text );   ?>
            </button>
        </div><?php
    }
}?>

