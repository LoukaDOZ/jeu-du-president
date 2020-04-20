const request = function(url){
    return new Promise(function(resolve,reject) {
        let request = new XMLHttpRequest();

        request.open('GET', url);
        request.setRequestHeader("Access-Control-Allow-Origin","*");
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