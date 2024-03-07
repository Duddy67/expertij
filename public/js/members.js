document.addEventListener('DOMContentLoaded', () => {

    // N.B: jQuery is required with the Select2 plugin.
    $('.select2').select2();

    //initialize();

    $('.select2').change( function(e) {
        //console.log(e.target.id);
        if (e.target.id == 'languages') {
            toggleSkill();
        }

        if (e.target.id == 'licence') {
            toggleJurisdiction(e.target.value);
        }
    });

    // Check first the filters are available.
    if (document.getElementById('search-btn')) {
        document.getElementById('search-btn').addEventListener('click', function (evt) {
            document.getElementById('member-filters').submit();
        }, false);

        document.getElementById('clear-btn').addEventListener('click', function (evt) {
            initialize();
        }, false);
    }

    /*
     *  
     */
    function getSelectedOptions(selectId) {
        const select = document.getElementById(selectId);
        let selectedOptions = [];

        Array.from(select.options).forEach(option => {
            if (option.selected) {
                selectedOptions.push(option.value);
            }
        });

        return selectedOptions;
    }     
      
    function initialize() {
        $('#languages option').prop('selected', false);
        $('#languages').select2();
        $('#licence option').prop('selected', false);
        $('#licence').select2();

        toggleJurisdiction('');
        toggleSkill();
    }

    function unselectOptions(selectId) {
        $('#' + selectId + ' option').prop('selected', false);
        $('#' + selectId).select2();
    }

    function toggleJurisdiction(licence) {
        if (licence == 'expert') {
            unselectOptions('courts');
            document.getElementById('courts-col').style.display = 'none';
            document.getElementById('appeal_courts-col').style.display = 'block';
        }
        else if (licence == 'ceseda') {
            unselectOptions('appeal_courts');
            document.getElementById('appeal_courts-col').style.display = 'none';
            document.getElementById('courts-col').style.display = 'block';
        }   
        // No licence selected.
        else {
            unselectOptions('courts');
            unselectOptions('appeal_courts');
            document.getElementById('appeal_courts-col').style.display = 'none';
            document.getElementById('courts-col').style.display = 'none';
        }
    }

    function toggleSkill() {
        const languages = getSelectedOptions('languages');
        if (languages.length) {
            document.getElementById('skill-col').style.display = 'block';
        }
        else {
            $('#skill option').prop('selected', false);
            $('#skill').select2();
            document.getElementById('skill-col').style.display = 'none';
        }
    }
});
