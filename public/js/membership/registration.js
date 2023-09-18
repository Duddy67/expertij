document.addEventListener('DOMContentLoaded', () => {

    /*document.getElementById('submit').onclick = function(e) { 
        const spinner = document.getElementById('ajax-progress');
        spinner.classList.remove('d-none');

        let formData = new FormData(document.getElementById('registration'));
//console.log(formData);
        let ajax = new C_Ajax.init({
            method: 'post',
            url: document.getElementById('registration').action,
            dataType: 'json',
            data: formData,
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json'}
        });

        ajax.run(getAjaxResult);
    }

    document.getElementById('add-licence').onclick = function(e) { 
        const spinner = document.getElementById('ajax-progress');
        spinner.classList.remove('d-none');

//const parent = document.querySelector('#licence-container');
//const count = parent.children.length;
        let formData = new FormData(document.getElementById('registration'));

        let ajax = new C_Ajax.init({
            method: 'post',
            url: document.getElementById('addLicence').value,
            dataType: 'json',
            data: formData,
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json'}
        });

        ajax.run(getAjaxResult);
    }*/

    let buttons = document.getElementsByClassName('form-action-btn');

    Array.prototype.forEach.call(buttons, function(button) {
        button.onclick = setAjaxRequest;
    });

    function setAjaxRequest() {
        const spinner = document.getElementById('ajax-progress');
        spinner.classList.remove('d-none');

        let formData = new FormData(document.getElementById(this.dataset.form));

        let ajax = new C_Ajax.init({
            method: 'post',
            url: document.getElementById(this.dataset.route).value,
            dataType: 'json',
            data: formData,
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json'}
        });

        ajax.run(getAjaxResult);
    }

    function getAjaxResult(status, result) {
        const spinner = document.getElementById('ajax-progress');
        spinner.classList.add('d-none');
console.log(result);

        if (status === 200) {
            if (result.html !== undefined) {
                document.getElementById(result.destination).innerHTML += result.html;
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

