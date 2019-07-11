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


function httpGet() {
    // var xmlHttp = new XMLHttpRequest();
    // xmlHttp.open("GET", "http://localhost:5000", true);
    setTimeout(explode, 5000);

}

function explode(){
    window.open("http://localhost:5000");

}



