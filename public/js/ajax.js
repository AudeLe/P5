// Executing and AjaxGet Call

function ajaxGet(url, callback){
    var req = new XMLHttpRequest();
    req.open("GET", url);
    req.addEventListener("load", function(){
        if(req.status >= 200 && req.status < 400){
            // Call the callback function with the request response as a parameter
            callback(req.responseText);
        } else {
            console.error(req.status + " " + req.statusText + " " + url);
        }
    });
    req.addEventListener("error", function(){
        // The request has not successfully reach the server
        console.error("Erreur réseau avec l'URL " + url);
    });
    req.send(null);
}