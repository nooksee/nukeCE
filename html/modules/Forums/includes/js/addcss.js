function addCSS () {
    // Connect to Child's DOM
    var ifrm = document.getElementById('review');
    ifrm = (ifrm.contentWindow) ? ifrm.contentWindow : (ifrm.contentDocument.document) ? ifrm.contentDocument.document : ifrm.contentDocument;//alert(ifrm.document.getElementsByTagName("head")[0].innerHTML);
    // Get Child's head
    var docHead = ifrm.document.getElementsByTagName("head")[0].innerHTML;
    // Find all Parent's CSS files
    var cssFiles = document.getElementsByTagName("head")[0].getElementsByTagName("link");
    for (i=0; i<cssFiles.length; i++) {
        if (cssFiles[i].getAttribute("type").toLowerCase() == 'text/css') {
            ifrm.document.getElementsByTagName("head")[0].innerHTML = docHead+'\n<link rel="stylesheet" type="text/css" href="'+cssFiles[i].getAttribute("href")+'" />';

        }

    }

}