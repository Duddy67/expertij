document.addEventListener('DOMContentLoaded', () => {

    const messages = JSON.parse(document.getElementById('JsMessages').value);

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

        if (evt.target.getAttribute('id') == 'payment-btn') {
            const paymentMode = document.querySelector( 'input[name="payment_mode"]:checked').value;
            if (confirm(messages[paymentMode + '_payment_confirmation'])) {
                document.getElementById('paymentForm').submit();
            }
        }
    }, false);

    // Initialise the jurisdiction types.
    const licenceTypes = document.getElementsByClassName('licence-type');
    for (let licenceType of licenceTypes) {
        switchJurisdiction(licenceType);
    }

    // Show or hide the professional_status_info field according to the professional_status field value.
    if (document.getElementById('professional_status')) { 
        // The field is hidden by default.
        document.getElementById('professional_status_info_row').style.display = 'none';

        document.getElementById('professional_status').onchange = function() { 
          if (this.value == 'other') {
              document.getElementById('professional_status_info_row').style.display = 'block';
          }
          else {
              document.getElementById('professional_status_info_row').style.display = 'none';
          }
        }
    }

    // Hides the irrelevant tabs for associated members in the membership space.
    if (document.getElementById('_isAssociatedMember')) {
        // Associated members have no licence and no professional status.
        document.getElementById('licences-tab').style.display = 'none';
        document.getElementById('professional_information-tab').style.display = 'none';
    } 

    // Set the jurisdiction type (appeal_court / court) for each licence type (expert / ceseda).
    function switchJurisdiction(element) {
        if (element.checked) {
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
            document.getElementById('licences-tab').style.display = 'none';
            document.getElementById('professional_information-tab').style.display = 'none';
        }
        else {
            // Regular members have both licences and professional status.
            document.getElementById('licences-tab').style.display = 'block';
            document.getElementById('professional_information-tab').style.display = 'block';
        }
    }

    function setAjaxRequest(element) {
        // Show the progress bar.
        document.getElementById('progress-container').classList.remove('d-none');

        let route = element.dataset.route;
        let url = document.getElementById(route).value;

        // Check for items to add to the DOM.
        if (route == 'createItem') {
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

            // Compute the new item index.

            // Get the latest index then increment it by 1.
            let newIndex = Number(document.getElementById(element.dataset.type+'-container'+containerIndex).dataset.latestIndex) + 1;
            // Update the latest index value for this item type.
            document.getElementById(element.dataset.type+'-container'+containerIndex).dataset.latestIndex = newIndex;
            url = url+'&_new_index='+newIndex;
        }

        // Check for items to remove from the DOM.
        if (route == 'deleteItem') {
            if (confirm('You are about to delete an item. Are you sure ?')) {
                // Set the Laravel method field to "delete".
                document.getElementById('_item_form_method').value = 'delete';

                // The item exists in database and has to be deleted.
                if (element.dataset.itemId !== undefined) {
                    // Remove the default zero id at the end of the url.
                    url = url.slice(0, -1);
                    // Add the id of the item to delete.
                    url = url+element.dataset.itemId;
                }

                // Add the item type and the element index to the route url.
                url = url+'?_type='+element.dataset.type+'&_index='+element.dataset.index;
            }
            else {
                // Hide the progress bar.
                document.getElementById('progress-container').classList.add('d-none');

                return;
            }
        }

        let formData = new FormData(document.getElementById(element.dataset.form));

        let ajax = new C_Ajax.init({
            method: 'post',
            url: url,
            dataType: 'json',
            data: formData,
            progressBar: 'progress-bar',
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json'}
        });

        if (route == 'deleteItem') {
            // Set back the Laravel method field to "post".
            //document.getElementsByName('_method')[0].value = 'post';
            document.getElementById('_item_form_method').value = 'post';
        }

        ajax.run(getAjaxResult);
    }

    function getAjaxResult(status, result) {
        // Hide the progress bar.
        document.getElementById('progress-container').classList.add('d-none');

        if (status === 200) {
            // Loop through the returned result.
            for (const [key, value] of Object.entries(result)) {
                if (key == 'redirect') {
                    window.location.href = result.redirect;
                }
                // Update the value of the given elements.
                else if (key == 'updates') {
                    updateFieldValues(result.updates);
                }
                // Replace html code inside the given containers.
                else if (key == 'replacements') {
                    for (const [key, replacement] of Object.entries(result.replacements)) {
                        document.getElementById(replacement.containerId).innerHTML = replacement.html;
                    }
                }
                // messages
                else if (['success', 'warning', 'info'].includes(key)) {
                    displayMessage(key, value);
                }
                // Add html code inside the given containers.
                else if (key == 'additions') {
                    for (const [key, addition] of Object.entries(result.additions)) {
                        document.getElementById(addition.containerId).insertAdjacentHTML(addition.position, addition.html);
                    }

                    // Initialize possible new date fields.
                    initDatepickers();
                }
                // Delete the given html elements. 
                else if (key == 'deletions') {
                    for (const [key, deletion] of Object.entries(result.deletions)) {
                        document.getElementById(deletion.targetId).remove();
                    }
                }
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

            // Scroll to the element with error.
            location.href = '#';
            location.href = '#'+latestElementId;
        }
        else {
            displayMessage('danger', 'Error '+status+': '+result.message);
        }
    }

    function updateFieldValues(updates) {
        for (const [elementId, value] of Object.entries(updates)) {
            if (document.getElementById(elementId).tagName == 'IMG') {
                document.getElementById(elementId).setAttribute('src', value);
            }
            else {
                document.getElementById(elementId).value = value;
            }
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

