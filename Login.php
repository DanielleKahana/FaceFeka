<?php
include_once("php_includes/db_connect.php");
include_once("php_includes/check_login_status.php");
?>
<?php
function encryptPassword($pass) {
    $pass=$pass[0].$pass.$pass[0];
    $pass=md5($pass);
    return $pass;
}
?>
<?php
// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if(isset($_POST["e"])){
    // CONNECT TO THE DATABASE
//    include_once("php_includes/db_connect.php");
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
    $email = mysqli_real_escape_string($db_connect, $_POST['e']);
    $password = encryptPassword($_POST['p']);
    // FORM DATA ERROR HANDLING
    if($email == "" || $password == ""){
        echo "login_failed";
        exit();
    } else {
        // END FORM DATA ERROR HANDLING
        $sql = "SELECT id, f_name, l_name , email, password FROM users WHERE email='$email' AND password='$password' LIMIT 1";
        $query = mysqli_query($db_connect, $sql);
        $row = mysqli_fetch_row($query);
        $res = mysqli_num_rows($query);
        $db_id = $row[0];
        $db_first_name = $row[1];
        $db_last_name = $row[2];
        $db_email = $row[3];
        $db_pass_str = $row[4];
        if($res < 1) {
            echo "login_failed";
            exit();
        } else {
            // CREATE COOKIES
            $cstrong = true;
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            $hash_token = md5($token);
            $sql = "INSERT INTO login_tokens (token, userid) VALUES ('$hash_token', '$db_id')";
            $query = mysqli_query($db_connect, $sql);

            setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', "", "", TRUE);
            echo $db_email;
            exit();
        }
    }
}
?>