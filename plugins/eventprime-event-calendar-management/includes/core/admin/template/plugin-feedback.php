<?php defined( 'ABSPATH' ) || exit;?>

<div id="ep_plugin_feedback_form_modal" class="ep-modal-view" style="display: none;">
    <div class="ep-modal-overlay ep-modal-overlay-fade-in close-popup" data-id="ep_plugin_feedback_form_modal"></div>
    <div class="popup-content ep-modal-wrap ep-modal-sm ep-modal-out"> 
        <div class="ep-modal-body">
            <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                <h3 class="ep-modal-title ep-px-3">
                    <?php esc_html_e( 'EventPrime Feedback', 'eventprime-event-calendar-management' ); ?>
                </h3>
                <a href="#" class="ep-modal-close ep-plugin-deactivation-modal-close close-popup" data-id="ep_plugin_feedback_form_modal">&times;</a>
            </div> 
            <div class="ep-modal-content-wrap">
                <form id="ep-deactivate-feedback-dialog-form" method="post"> 
                    <div class="ep-box-wrap">
                        <div class="ep-box-row ep-p-3 ep-settings-checkout-field-manager">
                            <input type="hidden" name="action" value="ep_deactivate_feedback" />
                            <div class="ep-uimrow">
                                <div id="ep-deactivate-feedback-dialog-form-caption" class="ep-mb-2"><?php esc_html_e('If you have a moment, please share why you are deactivating EventPrime:','eventprime-event-calendar-management'); ?></div>
                                <div id="ep-deactivate-feedback-dialog-form-body">
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-2">
                                        <input id="ep-deactivate-feedback-feature_not_available" class="ep-deactivate-feedback-dialog-input" type="radio" name="ep_feedback_key" value="feature_not_available">
                                        <label for="ep-deactivate-feedback-feature_not_available" class="ep-deactivate-feedback-dialog-label"><span class="ep-feedback-emoji">&#x1f61e;</span><?php esc_html_e("Doesn't have the feature I need","eventprime-event-calendar-management");?></label>
                                        <div class="epinput" id="ep_reason_feature_not_available" style="display:none"><input class="ep-feedback-text ep-form-control ep-box-w-50 ep-mt-2" type="text" name="ep_reason_feature_not_available" placeholder="<?php esc_html_e("Please let us know the missing feature...","eventprime-event-calendar-management");?>"></div>
                                    </div>
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-2">
                                        <input id="ep-deactivate-feedback-feature_not_working" class="ep-deactivate-feedback-dialog-input" type="radio" name="ep_feedback_key" value="feature_not_working" >
                                        <label for="ep-deactivate-feedback-feature_not_working" class="ep-deactivate-feedback-dialog-label"><span class="ep-feedback-emoji">&#x1f615;</span><?php esc_html_e("One of the features didn't work","eventprime-event-calendar-management");?></label>
                                        <div class="epinput" id="ep_reason_feature_not_working" style="display:none"><input class="ep-feedback-text ep-form-control ep-box-w-50 ep-mt-2" type="text" name="ep_reason_feature_not_working" placeholder="<?php esc_html_e("Please let us know the feature, like 'email notifications'","eventprime-event-calendar-management");?>"></div>
                                    </div>
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-2">
                                        <input id="ep-deactivate-feedback-found_a_better_plugin" class="ep-deactivate-feedback-dialog-input" type="radio" name="ep_feedback_key" value="found_a_better_plugin" >
                                        <label for="ep-deactivate-feedback-found_a_better_plugin" class="ep-deactivate-feedback-dialog-label"><span class="ep-feedback-emoji">&#x1f60a;</span><?php esc_html_e("Moved to a different plugin","eventprime-event-calendar-management");?></label>
                                        <div class="epinput" id="ep_reason_found_a_better_plugin" style="display:none"><input class="ep-feedback-text ep-form-control ep-box-w-50 ep-mt-2" type="text" name="ep_reason_found_a_better_plugin" placeholder="<?php esc_html_e("Could you please share the plugin's name","eventprime-event-calendar-management");?>"></div>
                                    </div>
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-2">
                                        <input id="ep-deactivate-feedback-plugin_broke_site" class="ep-deactivate-feedback-dialog-input" type="radio" name="ep_feedback_key" value="plugin_broke_site">
                                        <label for="ep-deactivate-feedback-plugin_broke_site" class="ep-deactivate-feedback-dialog-label"><span class="ep-feedback-emoji">&#x1f621;</span><?php esc_html_e("The plugin broke my site","eventprime-event-calendar-management");?></label>
                                    </div>
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-2">
                                        <input id="ep-deactivate-feedback-plugin_stopped_working" class="ep-deactivate-feedback-dialog-input" type="radio" name="ep_feedback_key" value="plugin_stopped_working">
                                        <label for="ep-deactivate-feedback-plugin_stopped_working" class="ep-deactivate-feedback-dialog-label"><span class="ep-feedback-emoji">&#x1f620;</span><?php esc_html_e("The plugin suddenly stopped working","eventprime-event-calendar-management");?></label>
                                    </div>
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-2">
                                        <input id="ep-deactivate-feedback-temporary_deactivation" class="ep-deactivate-feedback-dialog-input" type="radio" name="ep_feedback_key" value="temporary_deactivation">
                                        <label for="ep-deactivate-feedback-temporary_deactivation" class="ep-deactivate-feedback-dialog-label"><span class="ep-feedback-emoji">&#x1f60a;</span><?php esc_html_e("It's a temporary deactivation","eventprime-event-calendar-management");?></label>
                                    </div>
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-2">
                                        <input id="ep-deactivate-feedback-other" class="ep-deactivate-feedback-dialog-input" type="radio" name="ep_feedback_key" value="other">
                                        <label for="ep-deactivate-feedback-other" class="ep-deactivate-feedback-dialog-label"><span class="ep-feedback-emoji">&#x1f610;</span><?php esc_html_e("Other","eventprime-event-calendar-management");?></label>
                                        <div class="epinput" id="ep_reason_other"  style="display:none"><input class="ep-feedback-text ep-form-control ep-box-w-50 ep-mt-2" type="text" name="ep_reason_other" placeholder="<?php esc_html_e("Please share the reason","eventprime-event-calendar-management");?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Wrap Ends: -->
                    <div class="ep-modal-footer ep-d-flex ep-items-end ep-justify-content-between" id="ep_modal_buttonset">
                        <a href="javascript:void(0);" class="ep-mr-3" id="ep_save_plugin_feedback_direct_deactivation" title="<?php echo esc_attr( 'Skip & Deactivate', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e('Skip & Deactivate', 'eventprime-event-calendar-management'); ?></a>
                        <div class="ep-plugin-deactivation-message ep-mr-3 ep-text-danger" style="display:none"></div>
                        <div class="ep-plugin-deactivation-loader ep-mr-3 ep-text-danger" style="display:none">
                            <span class="spinner is-active"></span>
                            <span class=""><?php esc_html_e( 'Deactivating EventPrime...', 'eventprime-event-calendar-management' ); ?></span>
                        </div>
                        <button type="button" class="button button-primary button-large" id="ep_save_plugin_feedback_on_deactivation" title="<?php echo esc_attr( 'Submit & Deactivate', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e('Submit & Deactivate', 'eventprime-event-calendar-management'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>