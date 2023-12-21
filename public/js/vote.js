document.addEventListener('DOMContentLoaded', () => {

    if (document.getElementById('voting-btn')) {
        document.getElementById('voting-btn').addEventListener('click', function (evt) {
            // Check for choices.
            if (!document.getElementById('yes').checked && !document.getElementById('no').checked) {
                alert('No choice has been made.');

                return;
            }

            if (confirm('Are you sure ?')) {
                document.getElementById('votingForm').submit();
            }
        }); 

        document.getElementById('back-btn').addEventListener('click', function (evt) {
            const url = document.getElementById('backUrl').value;
            window.location.replace(url);
        }); 
    }
});
