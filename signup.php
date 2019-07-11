<?php
function encryptPassword($pass) {
    $pass=$pass[0].$pass.$pass[0];
    $pass=md5($pass);
    return $pass;
}
?>
<?php
include_once("php_includes/db_connect.php");
// Ajax calls this NAME CHECK code to execute
if(isset($_POST["emailCheck"])){
    $emailAdd = $_POST['emailCheck'];

if (!filter_var($emailAdd, FILTER_VALIDATE_EMAIL)) {
    echo '<strong style="color:#F00;"> Not a valid email address </strong>';
    exit();
}
    $sql = "SELECT id FROM users WHERE email='$emailAdd' LIMIT 1";
    $query = mysqli_query($db_connect, $sql);
    $email_check = mysqli_num_rows($query);

    if ($email_check < 1) {
        echo '<strong style="color:#009900;">' . $emailAdd . ' is OK</strong>';
        exit();
    } else {
        echo '<strong style="color:#F00;">' . $emailAdd . ' already signed up</strong>';
        exit();
    }
}

// Ajax calls this REGISTRATION code to execute
if(isset($_POST["f"])){
    // CONNECT TO THE DATABASE
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $first_name = $_POST['f'];
    $last_name = $_POST['l'];
    $email = mysqli_real_escape_string($db_connect, $_POST['e']);
    $password = $_POST['p'];

    // DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
    $sql = "SELECT id FROM users WHERE email='$email' LIMIT 1";
    $query = mysqli_query($db_connect, $sql);
    $e_check = mysqli_num_rows($query);
    // FORM DATA ERROR HANDLING
    if($first_name == "" || $last_name == "" || $email == "" || $password == ""){
        echo "The form submission is missing values.";
        exit();
    } else if ($e_check > 0) {
        echo "That email address is already in use in the system";
        exit();
    }     else {
        $default_avatar = 'images/default.png';
        // END FORM DATA ERROR HANDLING
        // Begin Insertion of data into the database
        // Hash the password and apply your own mysterious unique salt
        $cryptpass = encryptPassword($password);
        // Add user info into the database table for the main site table
        $sql = "INSERT INTO users (f_name, l_name, email, password, avatar) VALUES('$first_name', '$last_name','$email','$cryptpass', '$default_avatar')";
        $query = mysqli_query($db_connect, $sql);
        $uid = mysqli_insert_id($db_connect);


         //Create directory(folder) to hold each user's files(pics, MP3s, etc.)
        if (!file_exists("user/$email")) {
            mkdir("user/$email", 0755);
        }
        echo "signup_success";
        exit();
    }

}
?>

<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <title>SignUp</title>-->
<!--    <link rel="stylesheet" href="style/stylesheet.css">-->
<!--    <script src="js/main.js"></script>-->
<!--    <script src="js/ajaxModule.js"></script>-->
<!--    <script src="js/signup_form.js"></script>-->
<!--</head>-->
<!--<body>-->
<?php //include_once ("php_includes/pageTop_template.php"); ?>
<!--<div id="pageMiddle">-->
<!--    <h3>Create a New Account</h3>-->
<!--    <form name="signupform" id="signupform" onsubmit="return false;">-->
<!--        <div>First Name:</div>-->
<!--        <input id="f_name" type="text" onfocus="emptyElement('status')" onkeyup="restrict('f_name')" maxlength="88">-->
<!--        <div>Last Name:</div>-->
<!--        <input id="l_name" type="text" onfocus="emptyElement('status')" onkeyup="restrict('l_name')" maxlength="88">-->
<!--        <div>Email Address:</div>-->
<!--        <input id="email" type="text" onfocus="emptyElement('status')" onblur="check_email()" onkeyup="restrict('email')" maxlength="88">-->
<!--        <span id="unamestatus"></span>-->
<!--        <div>Create Password:</div>-->
<!--        <input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="100">-->
<!--        <div>Confirm Password:</div>-->
<!--        <input id="pass2" type="password" onfocus="emptyElement('status')" maxlength="100">-->
<!--        <br /><br />-->
<!--        <button id="signupbtn" onclick="signup()">Sign Up</button>-->
<!--        <span id="status"></span>-->
<!--    </form>-->
<!---->
<!--</div>-->
<?php //include_once ("php_includes/pageBottom_template.php"); ?>
<!--</body>-->
<!--</html>-->