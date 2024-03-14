const request = function(uri){
    return new Promise(function(resolve,reject) {
        let request = new XMLHttpRequest();
        let url = "http://" + window.location.hostname + ":8082/" + uri

        request.open('GET', url);
        request.responseType = "json";

        request.onload = function () {
            if(request.status === 200)
                resolve(request.response);
            else
                reject(request.response);
        };

        request.onerror = function () {
            let err = {
                "code": NETWORK_ERR,
                "args": []
            };
            reject(err);
        };

        request.send();
    });
};