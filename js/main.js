function _(x) {
    return document.getElementById(x);
}

function toggle_element(x){
    var x = _(x);
    if(x.style.display == 'block'){
        x.style.display = 'none';
    }else{
        x.style.display = 'block';
    }
}

function emptyElement(x){
    _(x).innerHTML = "";
}