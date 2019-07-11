function restrict(elem){
    var tf = _(elem);
    var rx = new RegExp;
    if(elem == "email"){
        rx = /[' "]/g;
    } else if(elem == "f_name" || elem == "l_name") {
         rx = /[^a-zA-Z]/g;
    }
    tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
    _(x).innerHTML = "";
}

function signup(){
    var first_name = _("f_name").value;
    var last_name = _("l_name").value;
    var email = _("user-email").value;
    var password = _("pass1").value;
    var verified_password = _("pass2").value;
    var status = _("signup-status");
    if(first_name == "" || last_name == "" || email == "" || password == "" || verified_password == "" ){
        status.innerHTML = "Please fill out all the fields";
    } else if(password != verified_password){
        status.innerHTML = "Your password fields do not match";
    } else {
        _("signupbtn").style.display = "none";
        status.innerHTML = '<img class=\'loading\' src=\'images/loading.gif\'>';
        var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
            if(ajaxReturn(ajax) == true) {
                if(ajax.responseText != "signup_success"){
                    status.innerHTML = ajax.responseText;
                    _("signupbtn").style.display = "block";
                } else {
                    window.scrollTo(0,0);
                    _("signupform").innerHTML = "Thank you, "+first_name + " " + last_name+" for signing up! You can start by adding some friends or posting whatever you like. But first you need to log in";
                }
            }
        }
        ajax.send("f="+first_name+"&l="+last_name+"&e="+email+"&p="+password);
    }
}

function check_email(){
    var email = _("user-email").value;
    if(email != ""){
        _("unamestatus").innerHTML = '<img class=\'loading\' src=\'images/loading.gif\'>';
        var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
            if(ajaxReturn(ajax) == true) {
                _("unamestatus").innerHTML = ajax.responseText;
            }
        }
        ajax.send("emailCheck="+ email);
    }
}
