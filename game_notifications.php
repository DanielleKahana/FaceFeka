<?php
include_once("php_includes/db_connect.php");
include_once("php_includes/check_login_status.php");

if(isset($_POST['response'])) {
    $request_id = $_POST['response'];
    $sql = "DELETE FROM invites WHERE id='$request_id'";
    $query = mysqli_query($db_connect, $sql);
    if($query){
        echo "done";
        exit();
    }
}

$sql = "SELECT * FROM invites WHERE friend_id='$userid' AND sent_at >= now() - INTERVAL 10 minute";
$query = mysqli_query($db_connect, $sql);
$rowCount = mysqli_num_rows($query);

if($rowCount < 1) {
    echo "
        <div class='notification-inner'>
        <div class='notification-det'>
                <p>No Notification</p>
              </div>
                 </div>
        ";
    exit();
} else {
    while ($row = mysqli_fetch_array($query)) {
        $friend_id = $row['sender_id'];
        $request_id = $row['id'];
        $sql = "SELECT f_name, l_name FROM users WHERE id='$friend_id'";
        $friend_query = mysqli_query($db_connect, $sql);


        $res = mysqli_fetch_row($friend_query);
        $full_name = $res[0] . ' ' . $res[1];

        echo "
        <div class='notification-inner'>
        <div id='notifi-det' class='notification-det'>
                <p id='invite-text'>$full_name sent you game invitation</p>
                <button id='accept-btn' onclick='response_handler($request_id, true)'>Accept</button>
                <button id='reject-btn' onclick='response_handler($request_id, false)'>Reject</button>
                </div>
        </div>
        ";
    }

}




