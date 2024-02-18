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

        /*document.getElementsByClassName('delete-document').addEventListener('click', (event) => {
          //event.target.style.background = "pink";
            alert('click'+event.target.dataset.documentId);
            console.log(event);
        });*/
        let form = document.getElementById('deleteDocument');
        let deleteRoute = form.action;

        for(const el of document.querySelectorAll(".delete-document")){
            el.addEventListener('click', function(e){
                const route = deleteRoute.replace(/.$/, e.target.dataset.documentId);
                console.log(route);
                let formData = new FormData(form);
                let ajax = new C_Ajax.init({
                    method: 'post',
                    url: route,
                    dataType: 'json',
                    data: formData,
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json'}
                });
            });
        }

        populateDocumentItem = function(idNb, data) {
            // Defines the default field values.
            if (data === undefined) {
                data = {id: '', title: '', path: '', filename: '', type: '', size: 0};
            }

            if (data.path == '') {
                let attribs = {'type':'file', 'name':'document_'+idNb, 'id':'document-'+idNb, 'class':'form-control'};
                document.getElementById('document-row-1-cell-1-'+idNb).append(repeater.createElement('input', attribs));
            }
            else {
                let attribs = {'title': 'fileName', 'name':'document_'+idNb, 'data-id': 41, 'id':'document-'+idNb, 'class':'btn btn-success', 'href': 'https://path/to/the/file'};
                elementType = 'a';
                let link = repeater.createElement('a', attribs)
                var linkText = document.createTextNode("my title text");
                link.appendChild(linkText);
                document.getElementById('document-row-1-cell-1-'+idNb).append(link);
            }

        }
    });



        
})();
