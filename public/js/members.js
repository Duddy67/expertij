document.addEventListener('DOMContentLoaded', () => {

    const cselect = new C_Select.init();

    setup();

    const cselects = document.querySelectorAll('.cselect');
    for (let i = 0; i < cselects.length; i++) {
        cselects[i].addEventListener('change', function(e) {
            if (e.target.id == 'languages') {
                toggleSkill();
            }

            if (e.target.id == 'licence') {
                toggleJurisdiction(e.target.value);
            }
        });
    }

    // Check first the filters are available.
    if (document.getElementById('search-btn')) {

        document.getElementById('search-btn').addEventListener('click', function (evt) {
            document.getElementById('member-filters').submit();
        }, false);

        document.getElementById('clear-btn').addEventListener('click', function (evt) {
            const languages = document.getElementById('languages');
            languages.options[languages.selectedIndex].removeAttribute('selected');
            cselect.rebuildCSelect(languages);
            const licence = document.getElementById('licence');
            licence.options[licence.selectedIndex].removeAttribute('selected');
            cselect.rebuildCSelect(licence);

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
        const select = document.getElementById(selectId);
        if (select.selectedIndex != -1) {
            select.options[select.selectedIndex].removeAttribute('selected');
            cselect.rebuildCSelect(select);
        } 
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
            const skill = document.getElementById('skill');
            skill.options[skill.selectedIndex].removeAttribute('selected');
            cselect.rebuildCSelect(skill);
            document.getElementById('skill-col').style.display = 'none';
            const licence = document.getElementById('licence');
            licence.options[licence.selectedIndex].removeAttribute('selected');
            cselect.rebuildCSelect(licence);
            document.getElementById('licence-col').style.display = 'none';
        }
    }
});
