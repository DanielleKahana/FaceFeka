function ajaxObj(method, url) {
    let x = new XMLHttpRequest();
    x.open(method, url, true);
    x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    return x;
}

function ajaxReturn(x) {
    if(x.readyState == 4 && x.status == 200) {
        return true;
    }
}