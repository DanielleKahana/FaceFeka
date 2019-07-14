function search(str) {
    if (str.length==0) {
        _("livesearch").innerHTML="";
        _("livesearch").style.border="0px";
        _("livesearch").style.display="block";
        return;
    }

    if (!str.replace(/\s/g, '').length) {
        return;
    }

    var s1=_("qu").value;
    var ajax = ajaxObj("POST", "live_search.php");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
                _("livesearch").innerHTML=ajax.responseText;
                _("livesearch").style.display="block";
        }
    }
    ajax.send("n="+s1);
}

function add_friend(friendid) {
    var ajax = ajaxObj("POST", "live_search.php");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
            if(ajax.responseText == 'friend_added') {
                _("friendbtn" + friendid).innerText = "Added";
                _("friendbtn" + friendid).setAttribute("disabled", "disabled");
            }
        }
    }
    ajax.send("fid="+friendid);
}


function invite(friendid) {
    var ajax = ajaxObj("POST", "live_search.php");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
            if(ajax.responseText == 'invited') {
                _("invite" + friendid).innerText = "Invited";
                _("invite" + friendid).setAttribute("disabled", "disabled");

                window.open("http://localhost:5000");
            }
        }
    }
    ajax.send("invite="+friendid);

}

function showNotifications() {
    var ajax = ajaxObj("GET", "game_notifications.php");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
            _("dropdown-content").innerHTML=ajax.responseText;
            _("dropdown-content").style.display="block";
        }
    }
    ajax.send();
}

function response_handler(request_id, isAccepted) {
    var ajax = ajaxObj("POST", "game_notifications.php");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
            var res = ajax.responseText;
            if (ajax.responseText == 'done') {

            if (isAccepted) {
                window.open("http://localhost:5000");
            } else {
                //TODO inform the user the invitation rejected ot timed out
            }
        }
        }
    }
    ajax.send("response="+request_id);
}







