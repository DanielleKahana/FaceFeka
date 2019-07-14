function emptyElement(x){
    _(x).innerHTML = "";
}

function post_it(full_name){
    var text = _("postbody").value;
    var public = _("public_per").checked;
    var private = _("private_per").checked;
    if(text == ""){
        return;
    } else {
        var ajax = ajaxObj("POST", "profile.php");
        ajax.onreadystatechange = function() {
            if(ajaxReturn(ajax) == true) {
                if(ajax.responseText == "post_failed"){
                    _("status").innerHTML = "Your post didn't posted, please try again later.";
                }
                else {
                    var postid = ajax.responseText;
                    _("tbody").insertAdjacentHTML('afterbegin', "<tr><td><div class='post-wrapper'><div id='post-body'>"+
                        text+"</div> <form id='like' onsubmit='return false;'> <input id='likebtn"+postid+"' 'post-id='"+postid+"' type = 'submit' name = 'like_btn' value = 'Like' onclick='like("+postid+")' > </form> <div id='posted_by'>"+ full_name +"</div> <div id='likesCount"+postid+"'>0 Likes</div> <form name='commentform' id='commentform' onsubmit='return false;'> <textarea id='commentbody"+postid+"' row='3' cols='50'></textarea> <input type='submit' name='comment' value='Comment' onclick='comment_it("+postid+")'> </form> <hr/></br></div></td></tr>");
                    _("postbody").value="";
                }
            }
        }

        ajax.send("t="+text+"&p="+public);
    }
}

function like(id) {
    var postid = id;
    var button = _("likebtn" + postid).getAttribute("value");
    var ajax = ajaxObj("POST", "profile.php");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
            var count = ajax.responseText;
            _("likesCount" + postid).innerText = count;
            if(button == "Like") {
                _("likebtn" + postid).setAttribute("value", "Unlike");
            }
            else {
                _("likebtn" + postid).setAttribute("value", "Like");
            }
        }
    }
    ajax.send("postid="+postid);
}

function comment_it(id, user_name) {
    var postid = id
    var text = _("commentbody"+postid).value;
    if(text == ""){
        return;
    }
        var ajax = ajaxObj("POST", "profile.php");
        ajax.onreadystatechange = function() {
            if (ajaxReturn(ajax) == true) {
                var response = ajax.responseText;
                if (response == "comment_failed") {
                    _("commentbody" + postid).innerHTML = "Your comment didn't posted, please try again later.";
                } else {
                    _("commentbody" + postid).value = "";

                    //insert new row
                    _("comment_tbody"+postid).insertAdjacentHTML('afterbegin', "<tr> <td><span id ='comment-title'>"+user_name+" ~ "+response+"</span><br/>"+text+"</td></tr>");
                }
            }
        }


        ajax.send("c="+text+"&post="+postid);

}

function change_status(postid) {
    var button = _("permissionbtn" + postid).getAttribute("value");
    var ajax = ajaxObj("POST", "profile.php");
    ajax.onreadystatechange = function() {
        if (ajaxReturn(ajax) == true) {
            var response = ajax.responseText;
            _('permissionbtn'+postid).setAttribute("src", "images/"+ajax.responseText+".png");
            _('permissionbtn'+postid).setAttribute("value", response);


        }
    }
    ajax.send("changePermission="+postid+"&state="+button);
}



