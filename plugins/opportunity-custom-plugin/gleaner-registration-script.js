 // Global Variables
var registrationButtonHTML = {
    register: '<button id=gleanerRegistrationButton class="register-button">Register</button>',
    unregister: '<h3 id=registered-label>You are Registered to this event. </h3><button id=gleanerRegistrationButton class="unregister-button">Unregister</button>'
};

// Initialization
jQuery(document).ready(function($) {
    $('#gleanerRegistrationButton').on('click', attachRegisterButtonClickHandler($));
});


// Functions
function attachRegisterButtonClickHandler($) {
	return function () {
		var button = $(this)
		var opportunityId = customData.opportunity_id; // Opportunity ID from customData

		if (button.hasClass('register-button')) {
			registerOrUnregisterToOpportunity($, opportunityId, button, 'new_registration_action');
		} else if (button.hasClass('unregister-button')) {
			registerOrUnregisterToOpportunity($, opportunityId, button, 'deregistration_action');
		}
	}
}

function registerOrUnregisterToOpportunity($, opportunityId, button, action) {
    $.ajax({
        url: myAjax.ajaxurl, 
        type: 'POST',
        dataType: 'json',
        data: {
            action: action,
            opportunity_id: opportunityId
        },
        success: function(response) {
            if (response.success) {
                swapRegistrationButton($, button); // Replace the button
            }
            alert(response.message);
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function swapRegistrationButton($, button) {
    var replacementHtml = '';
    if (button.hasClass('register-button')) {
        replacementHtml = registrationButtonHTML.unregister;
    } else {
        var h3Element = document.getElementById('registered-label');
        if (h3Element) {
            h3Element.parentNode.removeChild(h3Element);
        }
        replacementHtml = registrationButtonHTML.register;
    }

    button.replaceWith(replacementHtml);
    $('#gleanerRegistrationButton').on('click', attachRegisterButtonClickHandler($));
}