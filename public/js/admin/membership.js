(function() {

    // Run a function when the page is fully loaded including graphics.
    document.addEventListener('DOMContentLoaded', () => {
        setStatuses();
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

        // Refreshes the dropdown list.
        $('#status').select2().trigger('change');
    }

})();

/* Used for the Select2 jQuery plugin. */
(function($) {

    if (jQuery.fn.select2) {
        $('.select2').select2();
    }

    // Fixes Select2 bug with Bootstrap tabs: https://github.com/select2/select2/issues/4220
    $('.select2-container--default').attr('style', 'width: 100%');

})(jQuery);
