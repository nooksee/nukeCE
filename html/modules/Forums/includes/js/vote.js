function __off(n) { 
    if(n && n.style) { 
        if('none' != n.style.display) { 
            n.style.display = 'none'; 
        }
    }
}

function __on(n) { 
    if(n && n.style) { 
        if('none' == n.style.display) { 
            n.style.display = ''; 
        } 
    } 
} 

function __toggle(n) { 
    if(n && n.style) { 
        if('none' == n.style.display) { 
            n.style.display = ''; 
        } else { 
            n.style.display = 'none'; 
        } 
    } 
} 

function onoff(objName,bObjState) { 
    var sVar = ''+objName; 
    var sOn = ''+objName+'_on'; 
    var sOff = ''+objName+'_off'; 
    var sOnStyle = bObjState ? ' style="display:none;" ':''; 
    var sOffStyle = !bObjState ? ' style="display:none;" ':''; 
    var sSymStyle = ' style="text-align: center;width: 13;height: 13;font-family: Arial,Verdana;font-size: 7pt;border-style: solid;border-width: 1;cursor: hand;color: #003344;background-color: #CACACA;" '; 
    if( (navigator.userAgent.indexOf("MSIE") >= 0) && document && document.body && document.body.style) { 
        document.write( '<span '+sOnStyle+'onclick="__on('+sVar+');__off('+sOn+');__on('+sOff+');" id="'+sOn+'" title="Click here to show details"'+sSymStyle+'>+<\/span>' + 
        '<span '+sOffStyle+'onclick="__off('+sVar+');__off('+sOff+');__on('+sOn+');" id="'+sOff+'" title="Click here to hide details"'+sSymStyle+'>-<\/span>' ); 
    } else { 
        document.write('<span id="' + objName + '_on" onclick="__on(document.getElementById(\'' + objName + '\'));__off(document.getElementById(\'' + objName + '_on\'));__on(document.getElementById(\'' + objName + '_off\'));" title="Click here to show details" style="text-align: center;width: 13;height: 13;font-family: monospace;font-size: 7pt;border-style: solid;border-width: 1;cursor: pointer;color: #003344;background-color: #CACACA;' + (bObjState ? ' display:none;' : '') + '">&nbsp;+&nbsp;</span>'); 
        document.write('<span id="' + objName + '_off" onclick="__off(document.getElementById(\'' + objName + '\'));__on(document.getElementById(\'' + objName + '_on\'));__off(document.getElementById(\'' + objName + '_off\'));" title="Click here to show details" style="text-align: center;width: 13;height: 13;font-family: monospace;font-size: 7pt;border-style: solid;border-width: 1;cursor: pointer;color: #003344;background-color: #CACACA;' + (!bObjState ? ' display:none;' : '') + '">&nbsp;&minus;&nbsp;</span>'); 
    }
}