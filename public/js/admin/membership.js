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
    });

    /*
     * Initializes the status dropdown list according to the current status.
     */
    function setStatuses() {
        let currentStatus = document.getElementById('status').value;

        // Disables the dropdown list.
        if (currentStatus == 'member' || currentStatus == 'refused' || currentStatus == 'cancelled' || currentStatus == 'revoked' || currentStatus == 'cancellation') {
            document.getElementById('status').disabled = true;
        }
        // Disables some options according to the pending status.
        else {
            let disabled = {pending: ['cancelled', 'pending_renewal', 'member', 'revoked', 'cancellation'],
                            pending_subscription: ['pending', 'refused', 'member', 'pending_renewal', 'revoked', 'cancellation'],
                            pending_renewal: ['pending', 'refused', 'member', 'pending_subscription', 'cancelled', 'cancellation']};

            disabled[currentStatus].forEach( function(stat) {
                document.querySelector('#status option[value='+stat+']').disabled = true;
            });

            if (currentStatus == 'pending_subscription') {
                document.querySelector('#status option[value=cancelled]').disabled = true;
            }
        }
    }

})();
