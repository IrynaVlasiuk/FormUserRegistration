function handlerAjax(method, url, data, callback) {
    let xhr = new XMLHttpRequest();
    xhr.open(method, url,true);
    xhr.onload = function () {
        if(xhr.status !== 200){
            // Server does not return HTTP 200 (OK) response.
            return;
        }
        let response = this.response;
        if(IsJsonString(this.response)) {
            response = JSON.parse(response);
        }
        callback(response);
    };
    xhr.send(data);
}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}