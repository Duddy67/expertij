(function() {
    // Run a function when the page is fully loaded including graphics.
    document.addEventListener('DOMContentLoaded', () => {

        const messages = (document.getElementById('JsMessages')) ? JSON.parse(document.getElementById('JsMessages').value) : {};

        document.getElementById('documentSharing').addEventListener('click', (event) => {
            if (event.target.tagName == 'BUTTON' && event.target.classList.contains('document-management')) {
                console.log(event.target.dataset.action);
                const action = event.target.dataset.action;

                if (action == 'delete') {
                    if (!window.confirm(messages.confirm_item_deletion)) {
                        return;
                    }
                }

                // The add-document form has no document id. 
                let form = action == 'add' ? document.getElementById(action + '-document') : document.getElementById(action + '-document-' + event.target.dataset.documentId);
                //const route = action == 'create' ? form.action : form.action.replace(/.$/, event.target.dataset.documentId);
                const route = form.action;
                let formData = new FormData(form);
                runAjax(route, formData);
            }
        });

        hideDeleteButton();

        // N.B: jQuery is required with the Select2 plugin.
        $('#licence_types').change( function() { setJuridictions($(this)); });
        setJuridictions($('#licence_types'));
    });

    function runAjax(route, formData) {
        let ajax = new C_Ajax.init({
            method: 'post',
            url: route,
            dataType: 'json',
            data: formData,
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json'}
        });

        ajax.run(getAjaxResult);
    } 

    function getAjaxResult(status, result) {
      if(status === 200) {
        if (result.action == 'add') {
            const table = document.getElementById('documentTable');
            let tbody = table.getElementsByTagName('tbody')[0];
            // Insert the new document row at the end of the table.
            tbody.insertAdjacentHTML('beforeend', result.row);
            // Clean the file input.
            document.getElementById('addDocument').value = null;
            displayMessage('success', result.success)
        }
        else if (result.action == 'replace') {
            const table = document.getElementById('documentTable');
            let oldRow = document.getElementById('document-row-' + result.oldId);
            // Replace the old document row with the new one.
            oldRow.insertAdjacentHTML('afterend', result.row);
            oldRow.remove();
            // In case it's the first document in the table that was replaced.
            hideDeleteButton();
            displayMessage('success', result.success)
        }
        else if (result.action == 'delete') {
            document.getElementById('document-row-' + result.id).remove();
            displayMessage('success', result.success);
        }
      }
      else {
        console.log(result);
	  alert(result.message);
      }
    }
        
    // Hide the delete button of the first document row of the table (as the first document can be replaced but cannot be deleted).
    function hideDeleteButton() {
        const table = document.getElementById('documentTable');

        if (table) {
            const documentRow = table.getElementsByTagName('tr')[1];
            let deleteButton = documentRow.getElementsByTagName('button')[1];
            deleteButton.style.display = 'none';
        }
    }

    function setJuridictions(elem) {
        if (elem.val() == '') {
	    $('#appeal_courts').prop('disabled', true);
	    $('#courts').prop('disabled', true);

	    return;
	}

        let types = elem.val();

        if (types.length == 1 && types[0] == 'expert') {
	    $('#appeal_courts').prop('disabled', false);
	    $('#courts').prop('disabled', true);
	}
        else if (types.length == 1 && types[0] == 'ceseda') {
	    $('#appeal_courts').prop('disabled', true);
	    $('#courts').prop('disabled', false);
	}
        else if (types.length == 2) {
	    $('#appeal_courts').prop('disabled', false);
	    $('#courts').prop('disabled', false);
	}
    }

    function displayMessage(type, message) {
        // Hide the possible displayed flash messages.
        document.querySelectorAll('.flash-message').forEach(elem => {
            if (!elem.classList.contains('d-none')) {
                elem.classList.add('d-none');
            }
        });

        const messageAlert = document.getElementById('ajax-message-alert');
        messageAlert.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning', 'alert-info');
        messageAlert.classList.add('alert-'+type);
        document.getElementById('ajax-message').innerHTML = message;
    }
})();
