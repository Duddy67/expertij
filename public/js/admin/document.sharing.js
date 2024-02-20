(function() {
    // Run a function when the page is fully loaded including graphics.
    document.addEventListener('DOMContentLoaded', () => {
        let getUrl = window.location;
        let pathParts = getUrl.pathname.split('/');
        // Removes the file name from the path.
        let path = getUrl.pathname.replace(pathParts[pathParts.length-1],'');
        const baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + path;

        /*let repeater = new C_Repeater.init({
                    item:'document',
                    ordering: false,
                    rootLocation: baseUrl,
                    rowsCells: [2,1],
                    Select2: false,
                    nbItemsPerPage: 8
        });*/

        document.getElementById('documentSharing').addEventListener('click', (event) => {
            if (event.target.tagName == 'BUTTON' && event.target.classList.contains('document-management')) {
                console.log(event.target.dataset.action);
                const action = event.target.dataset.action;
                let form = action == 'add' ? document.getElementById(action + '-document') : document.getElementById(action + '-document-' + event.target.dataset.documentId);
                //const route = action == 'create' ? form.action : form.action.replace(/.$/, event.target.dataset.documentId);
                const route = form.action;
                let formData = new FormData(form);
                runAjax(route, formData);
            }
        });

        /*populateDocumentItem = function(idNb, data) {
            // Defines the default field values.

            let attribs = {'type':'file', 'name':'document_'+idNb, 'id':'document-'+idNb, 'class':'form-control w-50 float-start me-4'};
            document.getElementById('document-row-1-cell-1-'+idNb).append(repeater.createElement('input', attribs));

            if (document.getElementById('document-0') === null) {
                attribs = {'type':'button', 'data-action': 'create', 'class':'btn btn-primary document-management'};
                let button = repeater.createElement('button', attribs);
                button.textContent = 'Create';
                document.getElementById('document-row-1-cell-1-'+idNb).append(button);
            }
        }*/
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
        console.log(result);
        if (result.action == 'add') {
            let table = document.getElementById('documentTable');
            let tbody = table.getElementsByTagName('tbody')[0];
            tbody.insertAdjacentHTML('beforeend', result.row);
            displayMessage('success', result.success)
        }
        else if (result.action == 'delete') {
            document.getElementById('document-row-' + result.id).remove();
        }
      }
      else {
	  alert('Error: '+result.response);
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
