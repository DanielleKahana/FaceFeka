<?php
include_once ("db_connect.php");
class Post
{
    public static function createPost($postid, $val) {
        $posts="";
        global $db_connect;
        $sql = "SELECT * FROM posts WHERE post_id='$postid'";
        $query = mysqli_query($db_connect, $sql);
        $p = mysqli_fetch_array($query);
        //Get post's comments
        $sql = "SELECT * FROM comments WHERE post_id='$postid'";
        $comment_query = mysqli_query($db_connect, $sql);

        $posts .=
            "<tr><td><div class='post-wrapper'><div id='post-body'>".
            $p['body']."</div>
    <form id='like' onsubmit='return false;'>
        <input id='likebtn$postid' post-id='$postid' type = 'submit' name = 'like_btn' value = '$val' onclick='like($postid)' >
    </form>
    <div id='posted_by'>Dani</div>
    <div id='likesCount$postid'>0 Likes</div>
    <form name='commentform' id='commentform' onsubmit='return false;'>
    <textarea id='commentbody$postid' row='3' cols='50'></textarea>
    <input type='submit' name='comment' value='Comment' onclick='comment_it($postid)'> 
    </form>";
        foreach ($comment_query as $c) {
            $posts .= $c['comment']."
        <div id='comment-section'>
            <hr/>
        </div> </br> ";
        }
        $posts .= "<hr/></br></div></td></tr>";

    }
}