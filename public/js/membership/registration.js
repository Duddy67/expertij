document.addEventListener('DOMContentLoaded', () => {

    // Use event delegation to ckeck whenever an element in the form is clicked.
    document.body.addEventListener('click', function (evt) {
        // Handle the add, delete and submit buttons
        if (evt.target.classList.contains('form-action-btn')) {
            setAjaxRequest(evt.target);
        }

        if (evt.target.classList.contains('licence-type')) {
            switchJurisdiction(evt.target);
        }

        if (evt.target.classList.contains('language-skill')) {
            setCassationOption(evt.target);
        }

        if (evt.target.getAttribute('id') == 'associated-member') {
            setForm(evt.target);
        }
    }, false);

    // Show or hide the professional_status_info field according to the professional_status field value.
    if (document.getElementById('professional_status')) { 
        // The field is hidden by default.
        document.getElementById('professional_status_info_row').style.display = 'none';

        document.getElementById('professional_status').onchange= function() { 
          if (this.value == 'other') {
              document.getElementById('professional_status_info_row').style.display = 'block';
          }
          else {
              document.getElementById('professional_status_info_row').style.display = 'none';
          }
        }
    }

    function switchJurisdiction(element) {
        if (element.dataset.type == 'expert') {
            document.getElementById('licences.'+element.dataset.licenceIndex+'.court').disabled = true;
            document.getElementById('licences.'+element.dataset.licenceIndex+'.appeal_court').disabled = false;
        }
        // ceseda
        else {
            document.getElementById('licences.'+element.dataset.licenceIndex+'.appeal_court').disabled = true;
            document.getElementById('licences.'+element.dataset.licenceIndex+'.court').disabled = false;
        }
    }

    function setCassationOption(element) {
        if (element.checked) {
          document.getElementById('licences.'+element.dataset.licenceIndex+'.attestations.'+element.dataset.attestationIndex+'.skills.'+element.dataset.skillIndex+'.'+element.dataset.type+'_cassation').disabled = false;
        }
        else {
          document.getElementById('licences.'+element.dataset.licenceIndex+'.attestations.'+element.dataset.attestationIndex+'.skills.'+element.dataset.skillIndex+'.'+element.dataset.type+'_cassation').disabled = true;
          // In case the checkbox is checked.
          document.getElementById('licences.'+element.dataset.licenceIndex+'.attestations.'+element.dataset.attestationIndex+'.skills.'+element.dataset.skillIndex+'.'+element.dataset.type+'_cassation').checked = false;
        }
    }

    function setForm(element) {
        if (element.checked) {
            // Associated members have no licence and no professional status.
            document.getElementById('licence-tab').style.display = 'none';
            document.getElementById('professional-status-tab').style.display = 'none';
        }
        else {
            // Regular members have both licences and professional status.
            document.getElementById('licence-tab').style.display = 'block';
            document.getElementById('professional-status-tab').style.display = 'block';
        }
    }

    function setAjaxRequest(element) {
        const spinner = document.getElementById('ajax-progress');
        spinner.classList.remove('d-none');

        let route = element.dataset.route;
        let url = document.getElementById(route).value;

        // Check for items to add.
        if (route.startsWith('add')) {
            let containerIndex = '';
            url = url+'?_type='+element.dataset.type;

            // A new attestation is being added.
            if (element.dataset.licenceIndex !== undefined) {
                url = url+'&_licence_index='+element.dataset.licenceIndex;
                containerIndex = '-'+element.dataset.licenceIndex;
            }

            // A new skill is being added.
            if (element.dataset.attestationIndex !== undefined) {
                url = url+'&_attestation_index='+element.dataset.attestationIndex;
                containerIndex = containerIndex+'-'+element.dataset.attestationIndex;
            }

            // Count the number of (first) children into a item container to set the new item index.
            let newIndex = document.getElementById(element.dataset.type+'-container'+containerIndex).children.length;
            url = url+'&_new_index='+newIndex;
//console.log(element.dataset.type+containerIndex+'-'+newIndex);
        }

        // Check for items to delete.
        if (route.startsWith('delete')) {
            // Set the Laravel method field to "delete".
            document.getElementsByName('_method')[0].value = 'delete';
            // Set the item id to zero if no parameter is given (ie: the item is not in database).
            let id = (element.dataset.itemId !== undefined) ? element.dataset.itemId : 0;
            // Add the item id and the item type and the element index to the route url.
            url = url+'/'+id+'?_type='+element.dataset.type+'&_index='+element.dataset.index;
        }

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

        if (status === 200) {
            // An item has to be added.
            if (result.html !== undefined) {
                document.getElementById(result.destination).insertAdjacentHTML('beforeend', result.html);
                // Refresh the date inputs.
                $.fn.initDatePickers();
            }

            // An item has to be removed.
            if (result.target !== undefined) {
                document.getElementById(result.target).remove();
            }
        }
        else if (status === 422) {
            displayMessage('danger', 'Please check the form below for errors.');
            let latestElementId;
            // Loop through the returned errors and set the messages accordingly.
            for (const [name, message] of Object.entries(result.errors)) {
                document.getElementById(name+'Error').innerHTML = message;
                latestElementId = name;
            }
          
            showTab(latestElementId);
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

    function showTab(elementId) {
        // Get the tab div container that contains the given element.  
        let divContainer = document.getElementById(elementId).closest('.tab-pane');

        if (divContainer !== null) {
            // The div container id corresponds to the tab id.
            let tabId = divContainer.id;
            // Get all the tab div containers.
            let elements = document.getElementsByClassName('tab-pane');

            Array.prototype.forEach.call(elements, function(element) {
                // Look for the tab to show.
                if (element.id == tabId) {
                    // Make the tab div container active
                    element.classList.add('active');
                    // as well as its corresponding tab.
                    document.getElementById(element.id+'-tab').classList.add('active');
                }
                else {
                    element.classList.remove('active');
                    document.getElementById(element.id+'-tab').classList.remove('active');
                }
            });
        }
    }
});

