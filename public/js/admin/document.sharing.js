(function() {
    // Run a function when the page is fully loaded including graphics.
    document.addEventListener('DOMContentLoaded', () => {
        let getUrl = window.location;
        let pathParts = getUrl.pathname.split('/');
        // Removes the file name from the path.
        let path = getUrl.pathname.replace(pathParts[pathParts.length-1],'');
        const baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + path;

        let repeater = new C_Repeater.init({
                    item:'document',
                    ordering: false,
                    rootLocation: baseUrl,
                    rowsCells: [2,1],
                    Select2: false,
                    nbItemsPerPage: 8
        });


        populateDocumentItem = function(idNb, data) {
            // Defines the default field values.
            if (data === undefined) {
                data = {id: '', title: '', path: '', filename: '', type: '', size: 0};
            }

            // Element label
            let attribs = {'title': 'File', 'class':'item-label', 'id':'document-label-'+idNb};
            document.getElementById('document-row-1-cell-1-'+idNb).append(repeater.createElement('span', attribs));
            document.getElementById('document-label-'+idNb).textContent = 'File';

            elementType = 'input';

            if (data.path == '') {
                attribs = {'type':'file', 'name':'document_'+idNb, 'id':'document-'+idNb, 'class':'form-control'};
                document.getElementById('document-row-1-cell-1-'+idNb).append(repeater.createElement('input', attribs));
            }
            else {
                attribs = {'title': 'fileName', 'name':'document_'+idNb, 'data-id': 41, 'id':'document-'+idNb, 'class':'btn btn-success', 'href': 'https://path/to/the/file'};
                elementType = 'a';
                let link = repeater.createElement('a', attribs)
                var linkText = document.createTextNode("my title text");
                link.appendChild(linkText);
                document.getElementById('document-row-1-cell-1-'+idNb).append(link);
            }

        }
    });



        
})();
