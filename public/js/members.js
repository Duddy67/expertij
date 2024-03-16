document.addEventListener('DOMContentLoaded', () => {

    // N.B: jQuery is required with the Select2 plugin.
    $('.select2').select2();

    setup();

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
            $('#languages option').prop('selected', false);
            $('#languages').select2();
            $('#licence option').prop('selected', false);
            $('#licence').select2();

            toggleJurisdiction('');
            toggleSkill();

            document.getElementById('member-filters').submit();
        }, false);
    }

    function setup() {

        // One or more languages are selected.
        if (document.getElementById('languages').value) {
            // A licence is selected.
            if (document.getElementById('licence').value) {
                if (document.getElementById('licence').value == 'expert') {
                    document.getElementById('appeal_courts-col').style.display = 'block';
                    document.getElementById('courts-col').style.display = 'none';
                }
                // ceseda
                else {
                    document.getElementById('appeal_courts-col').style.display = 'none';
                    document.getElementById('courts-col').style.display = 'block';
                }
            }
            else {
                document.getElementById('appeal_courts-col').style.display = 'none';
                document.getElementById('courts-col').style.display = 'none';
            }

        }
        // No language selected.
        else {
            // Unselect everything just in case.
            unselectOptions('skill');
            unselectOptions('licence');
            unselectOptions('courts');
            unselectOptions('appeal_courts');

            // Hide drop down lists.
            document.getElementById('skill-col').style.display = 'none';
            document.getElementById('licence-col').style.display = 'none';
            document.getElementById('courts-col').style.display = 'none';
            document.getElementById('appeal_courts-col').style.display = 'none';
        }
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
        // One or more languages are selected.
        if (document.getElementById('languages').value) {
            document.getElementById('skill-col').style.display = 'block';
            document.getElementById('licence-col').style.display = 'block';
        }
        else {
            $('#skill option').prop('selected', false);
            $('#skill').select2();
            document.getElementById('skill-col').style.display = 'none';
            $('#licence option').prop('selected', false);
            $('#licence').select2();
            document.getElementById('licence-col').style.display = 'none';
        }
    }
});
