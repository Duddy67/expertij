(function() {

    // Run a function when the page is fully loaded including graphics.
    document.addEventListener('DOMContentLoaded', () => {
        setStatuses();

        // Hide the sendEmails button once the pending status is over.
        if (document.getElementById('status').value != 'pending') {
           document.getElementById('sendEmails').parentElement.style.display = 'none';
        }
        // The emails have been sent.
        else if (document.getElementById('_sendingEmails').value == 1) {
           document.getElementById('sendEmails').disabled = true;
        }

        if (document.getElementById('_isAssociatedMember').value == 1) {
            // Associated members have no licence and no professional status.
            document.getElementById('licences-tab').style.display = 'none';
            document.getElementById('professional_information-tab').style.display = 'none';
        }

        if (document.getElementById('professional_status').value != 'other') {
            document.getElementById('professional_status_info').style.display = 'none'; 
            document.querySelector('label[for="professional_status_info"]').style.display = 'none'; 
        }

        // Get the buttons related to saving. 
        const buttons = document.querySelectorAll('[id^="save"]');
        const messages = JSON.parse(document.getElementById('JsMessages').value);

        buttons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                let currentStatus = document.getElementById('_currentStatus').value;

                // The membership status has changed.
                if (document.getElementById('status').value != currentStatus) {
                    // Inform the user about the consequences.
                    if (confirm(messages.status_change_confirmation) == false) {
                        // Abort saving.
                        e.information = ['abort'];
                    }
                    // Save membership.
                    else {
                        // Update the current status value.
                        document.getElementById('_currentStatus').value = document.getElementById('status').value;
                    }
                }
            });
        });
    });

    /*
     * Initializes the status dropdown list according to the current status.
     * N.B: Make the function global as it is called through AJAX after saving. 
     */
    window.setStatuses = function() {
        const currentStatus = document.getElementById('status').value;

        // Disables the dropdown list.
        if (currentStatus == 'member' || currentStatus == 'refused' || currentStatus == 'cancelled' || currentStatus == 'revoked') {
            document.getElementById('status').disabled = true;

            // Cancel the possible pending payment in case the membership is cancelled or revoked.
            if ((currentStatus == 'cancelled' || currentStatus == 'revoked') && document.getElementById('payment-status')) {
                document.getElementById('payment-status').value = 'cancelled';
                document.getElementById('payment-status').disabled = true;
                document.getElementById('save-payment-status').disabled = true;
            }
        }
        // Disables some options according to the pending status.
        else {
            const disabled = {pending: ['cancelled', 'pending_renewal', 'member', 'revoked', 'cancellation'],
                            pending_subscription: ['pending', 'refused', 'member', 'pending_renewal', 'revoked', 'cancellation'],
                            pending_renewal: ['pending', 'refused', 'member', 'pending_subscription', 'cancelled', 'cancellation'],
                            cancellation: ['pending', 'refused', 'member', 'pending_subscription', 'cancelled', 'revoked']};

            const enabled = {pending: ['refused', 'pending_subscription'],
                            pending_subscription: ['cancelled'],
                            pending_renewal: ['revoked'], 
                            cancellation: ['pending_renewal']};

            disabled[currentStatus].forEach( function(stat) {
                document.querySelector('#status option[value=' + stat + ']').disabled = true;
            });

            enabled[currentStatus].forEach( function(stat) {
                document.querySelector('#status option[value=' + stat + ']').disabled = false;
            });
        }

        // Refresh the cselect status item.
        const cselect = new C_Select.init();
        cselect.rebuildCSelect(document.getElementById('status'));
    };

    /*
     * Set some elements according to the payment status change. 
     * N.B: Make the function global as it is called through AJAX after saving. 
     */
    window.afterPayment = function() {
        const paymentItem = document.getElementById('_paymentItem').value;

        if (document.getElementById('payment-status').value == 'completed' && paymentItem.startsWith('subscription')) {
            // Set the membership status to "member" then disable the select element.
            document.getElementById('status').value = 'member';
            document.getElementById('status').disabled = true;
            // Set the current membership status to "member" to prevent the 
            // warning javascript message regarding the status change. 
            document.getElementById('_currentStatus').value = 'member';
            // Refresh the cselect status item.
            const cselect = new C_Select.init();
            cselect.rebuildCSelect(document.getElementById('status'));
        }

        // Disable the elements of the payment row.
        document.getElementById('payment-status').disabled = true;
        document.getElementById('save-payment-status').disabled = true;
    };
})();

