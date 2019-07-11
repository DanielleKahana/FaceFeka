<?php
include_once("php_includes/db_connect.php");
include_once("php_includes/check_login_status.php");
include_once ("playGame.php");

$isFriend = false;

if(isset($_POST['fid'])) {
    $friend_id = $_POST['fid'];

    $sql = "INSERT INTO friends(user_id, friend_id) VALUES ('$userid', '$friend_id')";
    $query = mysqli_query($db_connect, $sql);

    $sql = "INSERT INTO friends(user_id, friend_id) VALUES ('$friend_id', '$userid')";
    $query = mysqli_query($db_connect, $sql);

    echo "friend_added";
    exit();
}

if(isset($_POST["n"])) {
    $s1 = $_POST["n"];
    if($s1 == '*') {
        $sql = "select * from users where id != '$userid'";
    }
    else {
        $sql = "select * from users where concat(f_name,' ',l_name) like '$s1%' AND id != '$userid'";
    }

    $query = mysqli_query($db_connect, $sql);
    $rowCount = mysqli_num_rows($query);
    if($rowCount < 1) {
        echo "
        
		<div class='live-outer'>
                <div class='live-product-det'>
                	<div class='live-product-name'>
                    	<p>No Friends Found</p>
                    </div>
                </div>
            </div>

	";
    }
    $s = "";

    while ($row = mysqli_fetch_array($query)) {
        $friend_id = $row['id'];
        $avatar = $row['avatar'];
        //Check if user in list is my friend already
        $sql = "SELECT * FROM friends WHERE user_id='$userid' AND friend_id='$friend_id'";
        $friends_query = mysqli_query($db_connect, $sql);
        $rowCount = mysqli_num_rows($friends_query);
        if($rowCount > 0) {
            $isFriend = true;
        }
        else {
            $isFriend = false;
        }

        $f = mysqli_fetch_row($friends_query);

        $s = $s . "
		<div class='live-outer'>
		        <div class='profile'>
                	<img src='$avatar' class='avatar'>	
                </div>
                <div class='username'>
                <p><b>".$row['f_name']." ".$row['l_name']."</b></p>
                </div>
                
                <div id='friendBtn'>";
        if($isFriend) {
            $s .= "<button type='submit' id='friendbtn$friend_id' disabled='disabled' name='add' class='add-friend'>Added</button>
                    
                    <button type='submit' id='invite$friend_id' name='invite' class='invite-friend' onclick='httpGet()'>Invite</button>
";
        } else {
            $s .= "<button type='submit'  id='friendbtn$friend_id' name='add' onclick='add_friend($friend_id)' class='add-friend'>Add</button>";
        }
        $s .= "</div></div>";

//
//                <button type='submit' name='add' class='add-friend'>Add</button>
//            </div>

//	";
    }
    echo $s;
}





?>



