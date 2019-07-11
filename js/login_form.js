function emptyElement(x){
    _(x).innerHTML = "";
}
function login(){
    var e = _("email").value;
    var p = _("password").value;
    if(e == "" || p == ""){
        _("login-status").innerHTML = "Please fill out all the fields";
    } else {
        _("loginbtn").style.display = "none";
        _("login-status").innerHTML = "<img class='loading' src='images/loading.gif'>";
        // _("login-status").innerHTML = 'please wait ...';
        var ajax = ajaxObj("POST", "Login.php");
        ajax.onreadystatechange = function() {
            if(ajaxReturn(ajax) == true) {
                var res = ajax.responseText;
                if(ajax.responseText == "login_failed"){
                    _("login-status").innerHTML = "Login unsuccessful, please try again.";
                    _("loginbtn").style.display = "block";
                } else {
                    window.location = "profile.php?e="+ajax.responseText;
                }
            }
        }
        ajax.send("e="+e+"&p="+p);
    }
}