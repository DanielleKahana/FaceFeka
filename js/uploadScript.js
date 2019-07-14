$(document).ready(function (e) {
    $("#form").on('submit', (function (e) {
        e.preventDefault();
        var text = $("#postbody").val();
        var per = $('#public_per').is(':checked');
        var permission = "";
        if (per) {
            permission = "Public";
        } else {
            permission = "Private";
        }

        $.ajax({
            url: "php_includes/ajaxUpload.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'JSON',
            beforeSend: function () {
            },
            success: function (data) {
                msg = data.msg;

                if (msg == 'post_failed') {
                    $("#post-status").html("Your post didn't posted, please try again later.");
                } else {
                    var email = data.email;
                    var postid = data.postid;
                    var full_name = data.full_name;
                    var hasFiles = data.hasFiles;
                    var posted_at = data.posted_at;


                    var fileNames = data.filename;


                    $("#tbody").prepend("<tr><td><div class='post-wrapper'><div id='post-body'>" +
                        text + "</div><div id='post_images"+postid+"'></div><input class='permission-btn' type='image' src='images/"+permission+".png' id='permissionbtn"+postid+"' onclick='change_status("+postid+")' value='"+permission+"'>"+
                        "<div id='posted_by'>"+full_name+" <img class='normal-img' src='images/calendar.png'> "+posted_at+"</div>"+
                        "<form id='like' onsubmit='return false;'> <img class='normal-img' src='images/heart.png'> "+
                        "<div id='likesCount"+ postid +"' style='display: inline;'> 0 </div><input id='likebtn"+postid+"' class='like-btn' post-id='"+ postid+"' type = 'submit' name = 'like_btn' value = 'Like' onclick='like("+postid+")' ></form>"+
                        "<form name='commentform' id='commentform' onsubmit='return false;'>"+
                        "<textarea class='comment-body' id='commentbody"+postid+"' row='3' cols='50'></textarea> <input type='submit' id='comment-btn' name='comment' value='Comment' onclick='comment_it(\""+postid+"\",\""+full_name+"\")'>"+
                        "</form><div id='comment-section'><table id='comment_table'><tbody id='comment_tbody"+postid+"'></tbody></table></div></div></td></tr>");

                    if (hasFiles) {
                        //add images to post div
                        var html = "";
                        var path = "user/" + email + "/";

                        for (var i = 0; i < fileNames.length; i++) {
                            html += "<a class='posted-image' href='" + path +"pictures/"+ fileNames[i] + "'><img class='normal-img' src='" + path +"thumb/"+ fileNames[i] + "'></a>";
                        }
                        $("#post_images" + postid).append(html);
                    }


                }
                $("#form")[0].reset();
            },
            error: function (e) {
                $("#post-status").html("Your post didn't posted, please try again later.");
                $("#form")[0].reset();
            }
        });
    }));
});

