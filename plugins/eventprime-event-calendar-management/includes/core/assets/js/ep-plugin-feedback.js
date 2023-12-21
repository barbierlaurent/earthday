jQuery( function( $ ) {
    var ep_plugin_deactivate_location = '';
    // show feedback modal on click on the deactivate link
    $( '#the-list' ).find('[data-slug="eventprime-event-calendar-management"] span.deactivate a').click( function(event) {
        $( '#ep_plugin_feedback_form_modal' ).openPopup({
            anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
        });
        ep_plugin_deactivate_location = $(this).attr('href');
        event.preventDefault();
    });

    $( document ).on( 'change', 'input[name="ep_feedback_key"]', function() {
        var ep_selectedVal = $(this).val();
        var ep_reasonElement = $( '#ep_reason_' + ep_selectedVal );
        $( '.ep-deactivate-feedback-dialog-input-wrapper .epinput' ).hide();
        if( ep_reasonElement !== undefined ) {
            ep_reasonElement.show();
        }
    });

    // submit
    $( document ).on( 'click', '#ep_save_plugin_feedback_on_deactivation', function() {
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).html('');
        let selectedVal = $( 'input[name="ep_feedback_key"]:checked' ).val();
        if( !selectedVal ) {
            $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).text( ep_feedback.option_error );
            $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).show();
            return false;
        }

        let ep_feedbackInput = $( "input[name='ep_reason_"+ selectedVal + "']" );
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).hide();
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-loader' ).show();

        let data = { 
            action: 'ep_send_plugin_deactivation_feedback', 
            security: ep_feedback.feedback_nonce, 
            feedback: selectedVal,
            message: ep_feedbackInput.val()
        };
        $.ajax({
            type: 'POST', 
            url : ep_feedback.ajaxurl,
            data: data,
            success: function( data, textStatus, XMLHttpRequest ) {
                location.href = ep_plugin_deactivate_location;
            }
        });
    });

    // skip and deactivation
    $( document ).on( 'click', '#ep_save_plugin_feedback_direct_deactivation', function() {
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).html('');
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).hide();
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-loader' ).show();
        setTimeout( function() {
            location.href = ep_plugin_deactivate_location;
        }, 1000 );
    });
});