document.addEventListener('DOMContentLoaded', () => {

    const messages = JSON.parse(document.getElementById('JsMessages').value);

    if (document.getElementById('voting-btn')) {
        document.getElementById('voting-btn').addEventListener('click', function (evt) {
            // Check for choices.
            if (!document.getElementById('yes').checked && !document.getElementById('no').checked) {
                alert(messages.no_item_selected);

                return;
            }

            if (confirm(messages.applicant_vote_confirmation)) {
                document.getElementById('votingForm').submit();
            }
        }); 

        document.getElementById('back-btn').addEventListener('click', function (evt) {
            const url = document.getElementById('backUrl').value;
            window.location.replace(url);
        }); 
    }

    // Hides the irrelevant tabs for associated members.
    if (document.getElementById('_isAssociatedMember').value == 1) {
        // Associated members have no licence and no professional status.
        document.getElementById('licences-tab').style.display = 'none';
        document.getElementById('professional_information-tab').style.display = 'none';
    } 
});
