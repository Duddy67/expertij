(function() {

    // Run a function when the page is fully loaded including graphics.
    document.addEventListener('DOMContentLoaded', () => {

        document.getElementById('myModal').addEventListener('focus', function(e) {
            var iframe = document.getElementById('documentIframe');
            // Get the total height of the document
            const totalHeight = document.documentElement.scrollHeight;
            // and the height of the iframe.
            const iframeHeight = iframe.contentWindow.document.documentElement.scrollHeight
            // Make sure the iframe is not higher than the document.
            const height = iframeHeight < totalHeight ? iframeHeight : totalHeight; 
            // Set the height of the iframe.
            iframe.style.height = height + 'px';
        });
    });

})();
