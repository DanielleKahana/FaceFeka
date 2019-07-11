<?php
include_once("db_connect.php");
// Files that inculde this file at the very top would NOT require
// connection to database or session_start(), be careful.
// Initialize some vars
$user_ok = false;
$userid = "";
$email = "";
$full_name = "";


// User Verify function
function isLoggedIn($conx) {
    if(isset($_COOKIE['SNID'])) {
        $snid = md5($_COOKIE['SNID']);
        $sql = "SELECT userid FROM login_tokens WHERE token='$snid' LIMIT 1";
        $query = mysqli_query($conx, $sql);
        global $userid, $email, $current_username;
        $userid = mysqli_fetch_row($query)[0];
        if($query) {
            $sql = "SELECT * FROM users WHERE id='$userid'";
            $user_query = mysqli_query($conx, $sql);
            $row = mysqli_fetch_row($user_query);
            $email = $row[3];
            $current_username = $row[1]. " " . $row[2];
            return true;
        }
    }
    return false;
}

if(isLoggedIn($db_connect)) {
    $user_ok = true;
}