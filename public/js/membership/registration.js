document.addEventListener('DOMContentLoaded', () => {

    // Use event delegation to ckeck whenever a button in the form is clicked.
    document.body.addEventListener('click', function (evt) {
        // Handle only the elements with the form-action-btn class.
        if (evt.target.classList.contains('form-action-btn')) {
            setAjaxRequest(evt.target);
        }
    }, false);

    function setAjaxRequest(element) {
        const spinner = document.getElementById('ajax-progress');
        spinner.classList.remove('d-none');

        let route = element.dataset.route;
        let url = document.getElementById(route).value;

        // Check for items to delete.
        if (route.startsWith('delete')) {
            // Set the Laravel method field to "delete".
            document.getElementsByName('_method')[0].value = 'delete';
            // Set the item id to zero if no parameter is given (ie: the item is not in database).
            let id = (element.dataset.itemId !== undefined) ? element.dataset.itemId : 0;
            // Add the item id and the element index to the route url.
            url = url+id+'/?_index='+element.dataset.index;
        }

        if (route.startsWith('add')) {
            if (element.dataset.licenceIndex !== undefined) {
                url = url+'?_licence_index='+element.dataset.licenceIndex;
            }

            if (element.dataset.attestationIndex !== undefined) {
                url = url+'&_attestation_index='+element.dataset.attestationIndex;
            }
        }
console.log(url);

        let formData = new FormData(document.getElementById(element.dataset.form));

        let ajax = new C_Ajax.init({
            method: 'post',
            url: url,
            dataType: 'json',
            data: formData,
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json'}
        });

        if (route.startsWith('delete')) {
            // Set back the Laravel method field to "post".
            document.getElementsByName('_method')[0].value = 'post';
        }

        ajax.run(getAjaxResult);
    }

    function getAjaxResult(status, result) {
        const spinner = document.getElementById('ajax-progress');
        spinner.classList.add('d-none');
console.log(result);

        if (status === 200) {
            // An item has to be added.
            if (result.html !== undefined) {
                document.getElementById(result.destination).innerHTML += result.html;
            }

            // An item has to be removed.
            if (result.target !== undefined) {
                document.getElementById(result.target).remove();
            }
        }
        else if (status === 422) {
            displayMessage('danger', 'Please check the form below for errors.');
            // Loop through the returned errors and set the messages accordingly.
            for (const [name, message] of Object.entries(result.errors)) {
                document.getElementById(name+'Error').innerHTML = message;
            }
        }
        else {
            displayMessage('danger', 'Error '+status+': '+result.message);
        }
    }

    function displayMessage(type, message) {
        // Empty some possible error messages.
        document.querySelectorAll('div[id$="Error"]').forEach(elem => {
            elem.innerHTML = '';
        });

        // Hide the possible displayed flash messages.
        document.querySelectorAll('.flash-message').forEach(elem => {
            if (!elem.classList.contains('d-none')) {
                elem.classList.add('d-none');
            }
        });

        // Adapt to Bootstrap alert class names.
        type = (type == 'error') ? 'danger' : type;

        const messageAlert = document.getElementById('ajax-message-alert');
        messageAlert.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning', 'alert-info');
        messageAlert.classList.add('alert-'+type);
        document.getElementById('ajax-message').innerHTML = message;
    }
});

