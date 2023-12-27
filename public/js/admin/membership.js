(function() {

    // Run a function when the page is fully loaded including graphics.
    document.addEventListener('DOMContentLoaded', () => {
        setStatuses();

        if (document.getElementById('_sendingEmails').value == 1) {
           document.getElementById('sendEmails').disabled = true
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
        if (currentStatus == 'member' || currentStatus == 'refused' || currentStatus == 'cancelled' || currentStatus == 'revoked' || currentStatus == 'cancellation') {
            document.getElementById('status').disabled = true;
        }
        // Disables some options according to the pending status.
        else {
            const disabled = {pending: ['cancelled', 'pending_renewal', 'member', 'revoked', 'cancellation'],
                            pending_subscription: ['pending', 'refused', 'member', 'pending_renewal', 'revoked', 'cancellation'],
                            pending_renewal: ['pending', 'refused', 'member', 'pending_subscription', 'cancelled', 'cancellation']};

            const enabled = {pending: ['refused', 'pending_subscription'],
                            pending_subscription: ['cancelled'],
                            pending_renewal: ['revoked']};

            disabled[currentStatus].forEach( function(stat) {
                document.querySelector('#status option[value=' + stat + ']').disabled = true;
            });

            enabled[currentStatus].forEach( function(stat) {
                document.querySelector('#status option[value=' + stat + ']').disabled = false;
            });

            /*if (currentStatus == 'pending_subscription') {
                document.querySelector('#status option[value=cancelled]').disabled = true;
            }*/
        }
    };

})();

