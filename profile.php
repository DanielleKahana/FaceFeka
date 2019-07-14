<?php
include_once ("php_includes/db_connect.php");
include_once ("php_includes/check_login_status.php");
include_once ("php_includes/photo_system.php");

$postimage = "post_image";
$avatar_form = "";



$f_name = "";
$l_name = "";
$email = "";
$isFriend = false;
$friend_id = -1;
$friend_name = "";
$likes = 0;
$val = "";
$permission ="";
$avatar = '';

$private_posts="";
$posts="";
$comments="";

//Get all public posts from friends and private+public of mine
$sql = "SELECT * FROM posts WHERE (posts.userid = $userid OR (posts.userid IN (SELECT friend_id FROM friends WHERE user_id = $userid) AND posts.permission='Public')) ORDER BY posts.posted_at DESC";
$query = mysqli_query($db_connect, $sql);

foreach ($query as $p) {
    $posted_by = $p['userid'];
    $likes = $p['likes'];
    $postid = $p['id'];
    $permission = $p['permission'];
    $posted_at = $p['posted_at'];

//Query: check if the user already liked this post
$sql = "SELECT user_id FROM post_likes WHERE user_id='$userid' AND post_id='$postid'";
$queryy = mysqli_query($db_connect, $sql);
$numrows = mysqli_num_rows($queryy);
if($numrows > 0) {
    $val = "Unlike";
}
else { $val = "Like";}

    //Get full name of the user who posted
    $sql = "SELECT * FROM users WHERE id='$posted_by'";
    $sql_query = mysqli_query($db_connect, $sql);
    $row = mysqli_fetch_row($sql_query);
    $full_name = $row['1']." ".$row['2'];
    $user_email = $row['3'];

    //Get post's comments
    $sql = "SELECT * FROM comments WHERE post_id='$postid' ORDER BY posted_at DESC";
    $comment_query = mysqli_query($db_connect, $sql);

    //Get post's images (if exists)
    $sql = "SELECT filename FROM posts JOIN images ON posts.id = images.post_id WHERE posts.id='$postid'";
    $images_query = mysqli_query($db_connect, $sql);

    $path = "user/". $user_email . "/";

    $posts .=
     "<tr><td><div class='post-wrapper'><div id='post-body'>".
        $p['body']."</div>
    <div id='post_images$postid'>";
    foreach ($images_query as $img) {
    $posts .= "<a class='posted-image' href='" . $path ."pictures/". $img['filename'] . "'><img class='normal-img' src='" . $path ."thumb/". $img['filename'] . "'></a>";
    }
$posts .= "</div>";
    if($posted_by == $userid){
    $posts .= "<input class='permission-btn' type='image' src='images/$permission.png' id='permissionbtn$postid' onclick='change_status($postid)' value='$permission'>";
    }
    $posts .= "<div id='posted_by'>$full_name <img class='normal-img' src='images/calendar.png'> $posted_at </div>
    <form id='like' onsubmit='return false;'>
        <img class='normal-img' src='images/heart.png'>
        <div id='likesCount$postid' style='display: inline;'>$likes</div>
        <input id='likebtn$postid' class='like-btn' post-id='$postid' type = 'submit' name = 'like_btn' value = '$val' onclick='like($postid)' >
    </form>
    
    <form name='commentform' id='commentform' onsubmit='return false;'>
    <textarea class='comment-body' id='commentbody$postid' row='3' cols='50'></textarea>
    <input type='submit' id='comment-btn' name='comment' value='Comment' onclick='comment_it(\"$postid\",\"$current_username\")' >
    </form>
    <div id='comment-section'>
    <table id='comment_table'><tbody id='comment_tbody$postid'>";
    foreach ($comment_query as $c) {
        $comment_by = $c['user_id'];

        $sql = "SELECT * FROM users WHERE id='$comment_by'";
        $comment_by_query = mysqli_query($db_connect, $sql);
        $r = mysqli_fetch_row($comment_by_query);
        $user_name = $r['1']." ".$r['2'];
        $posted_at = $c['posted_at'];
        $comment_body = $c['comment'];

        $posts .= "

             <tr>
             <td><span id ='comment-title'>$user_name ~ $posted_at</span><br/> $comment_body</td>
            </tr>
            ";

        }
    $posts .= "</tbody></table></div></div></td></tr>";
}

if(isset($_POST['postid'])) {
    $post = $_POST['postid'];

    //Query: check if the user already liked this post
    $sql = "SELECT user_id FROM post_likes WHERE user_id='$userid' AND post_id='$post'";
    $query = mysqli_query($db_connect, $sql);
    $numrows = mysqli_num_rows($query);
    if($numrows > 0) {
        //Query: decrease likes by 1
        $sql = "UPDATE posts SET likes=likes-1 WHERE id='$post'";
        $query = mysqli_query($db_connect, $sql);

        //Query: delete into post_likes who liked this post
        $sql = "DELETE FROM post_likes WHERE post_id='$post' AND user_id='$userid'";
        $query = mysqli_query($db_connect, $sql);
    } else {

        //Query: increase likes by 1
        $sql = "UPDATE posts SET likes=likes+1 WHERE id='$post'";
        $query = mysqli_query($db_connect, $sql);

        //Query: save into post_likes who liked this post
        $sql = "INSERT INTO post_likes(post_id, user_id) VALUES ('$post', '$userid')";
        $query = mysqli_query($db_connect, $sql);
    }

    //Query: get new number of likes
    $sql = "SELECT likes FROM posts WHERE id='$post'";
    $query = mysqli_query($db_connect, $sql);
    $likesCount = mysqli_fetch_row($query)[0];

    if($query) {
        echo $likesCount;
        exit();
    }
}

if(isset($_POST['t'])) {
    $msg = "";
    $text = htmlspecialchars($_POST['t']);
    $per = $_POST['p'];

    if($per == 'true') {
        $permission = 'Public';
    } else {
        $permission = 'Private';
    }
    $sql = "INSERT INTO posts (body,userid,posted_at,permission) VALUES('$text', '$userid', NOW() , '$permission')";
    $query = mysqli_query($db_connect, $sql);
    if(!$query) {
        echo "post_failed";
        exit();
    } else {
        $last_id = mysqli_insert_id($db_connect);

        $v = $_FILES[$postimage]['name'];
        if (isset($_FILES['post_image']['name']) && $_FILES['post_image']['tmp_name'] != "") {
           $msg = uploadImages($last_id, $db_connect, $userid);
        }

        echo $v;
        exit();
    }
}

if(isset($_POST['c'])) {
    $text = htmlspecialchars($_POST['c']);
    $postid = $_POST['post'];

    $sql = "INSERT INTO comments (post_id, user_id, comment, posted_at) VALUES('$postid', '$userid','$text', NOW())";
    $query = mysqli_query($db_connect, $sql);
    $id = mysqli_insert_id($db_connect);


    if(!$query) {
        echo "comment_failed";
        exit();
    } else {
        $sql = "SELECT posted_at FROM comments WHERE id='$id'";
        $query = mysqli_query($db_connect, $sql);
        $row = mysqli_fetch_row($query);
        echo $row[0];
        exit();
    }
}

if(isset($_POST['changePermission'])) {
    $postid = $_POST['changePermission'];
    $state = $_POST['state'];
    $pn = "Public";
    if($state == 'Public') {
        $pn = 'Private';
    }
    $sql = "UPDATE posts SET permission='$pn' WHERE id='$postid'";
    $q = mysqli_query($db_connect, $sql);
    if($q) {
        echo $pn;
        exit();
    }
}


if(isset($_GET['e'])) {
    $email = $_GET['e'];
    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $query = mysqli_query($db_connect, $sql);
    $numrows = mysqli_num_rows($query);
    if ($numrows < 1) {
        echo('User not found!');
        exit();
    }
    else {
        $row = mysqli_fetch_row($query);
        $f_name = $row[1];
        $l_name = $row[2];
        $avatar = $row[5];
        $friend_id = $row[0];
        $friend_name = $f_name. ' '.$l_name;


        $sql = "SELECT id FROM friends WHERE user_id='$userid' AND friend_id='$friend_id'";
        $query = mysqli_query($db_connect, $sql);
        $numrows = mysqli_num_rows($query);

        if($numrows != 0) {
            $isFriend = true;
        }

        if(isset($_POST["add"])) {
            if ($numrows < 1) {
                $sql = "INSERT INTO friends(user_id, friend_id) VALUES ('$userid', '$friend_id')";
                $query = mysqli_query($db_connect, $sql);

                $sql = "INSERT INTO friends(user_id, friend_id) VALUES ('$friend_id', '$userid')";
                $query = mysqli_query($db_connect, $sql);

                $isFriend = true;
            }
        }

        if(isset($_POST["remove"])) {
            if($numrows != 0) {
                $sql = "DELETE FROM friends WHERE user_id='$userid' AND friend_id='$friend_id'";
                $query = mysqli_query($db_connect, $sql);
            }
            $isFriend = false;
        }


    }
}

// Check to see if the viewer is the account owner
$isOwner = false;
if($friend_id == $userid && $user_ok == true){
    $isOwner = true;
}

?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="style/stylesheet.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/ajaxModule.js"></script>
    <script src="js/post.js"></script>
    <script src="js/search_friends.js"></script>
    <script src="js/uploadScript.js"></script>
</head>
<body>
<?php include_once("php_includes/pageTop_template.php"); ?>
<div id="pageMiddle">
    <div id='user_det'>
    <?php if($isOwner) {
        $avatar_form  = '<form id="avatar_form" enctype="multipart/form-data" method="post" action="php_includes/photo_system.php">';
        $avatar_form .=   '<h4>Change your avatar</h4>';
        $avatar_form .=   '<input type="file" name="avatar" required>';
        $avatar_form .=   '<p><input type="submit" value="Upload"></p>';
        $avatar_form .= '</form>';

    }
        ?>
    <div class="hero-image">

        <div id='profile_pic_box'>
            <img src=<?php echo $avatar?> class='image'>
            <div class='middle'><?php echo $avatar_form?></div>
        </div>
            <h1 class="user-title"><?php echo $friend_name ?></h1>

    </div>
    </div>
    <div id="post-wrapper">
    <form id="form" action="ajaxupload.php" method="post" enctype="multipart/form-data">
        <?php
        if($isOwner) {
            echo "
         <textarea id = 'postbody' name='postbody' rows = '5' cols = '80' placeholder='What&apos;s on your mind, $f_name?'></textarea >
         <div id='post-attr'>
          <input id='uploadImage' type='file' name='image[]' accept=\"image/*\" multiple>
          <div id='radio-buttons'>
          
        <input type = 'radio' name = 'permission' id = 'public_per' value = 'Public' checked = 'checked' >
        <label for='public_per'>Public </label>
         
        <input type = 'radio' name = 'permission' id = 'private_per' value = 'Private' >
        <label for='private_per'>Private </label>
        </div>
        
         </div>
         <input id='postbtn' type = 'submit' name = 'post'  value = 'Post'  > ";
        }
        ?>
    </form>

    <p id="post-status"></p>
    </div>

    <div id="posts">
        <table id="poststb">
            <tbody id="tbody">

        <?php echo $posts; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once("php_includes/pageBottom_template.php"); ?>
</body>
</html>


