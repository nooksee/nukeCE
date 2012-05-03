function handleClick(id) {
    var obj = "";	
    // Check browser compatibility
    if(document.getElementById)
    obj = document.getElementById(id);
    else if(document.all)
    obj = document.all[id];
    else if(document.layers)
    obj = document.layers[id];
    else
    return 1;

    if (!obj) {
        return 1;
    } else if (obj.style) {			
        obj.style.display = ( obj.style.display != "none" ) ? "none" : "";
    } else { 
        obj.visibility = "show";
    }
}