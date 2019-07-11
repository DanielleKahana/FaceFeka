<?php
include_once ("php_includes/db_connect.php");
include_once ("php_includes/check_login_status.php");

if($user_ok) {
    header("location: profile.php?e=" . $email);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FaceFeka</title>
    <link rel="stylesheet" href="style/stylesheet.css">
    <script src="js/main.js"></script>
    <script src="js/search_friends.js"></script>
    <script src="js/ajaxModule.js"></script>
    <script src="js/login_form.js"></script>
    <script src="js/signup_form.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
<?php include_once ("php_includes/pageTop_template.php"); ?>

<div id="pageMiddle">
    <div id ='login-section'>
        <h3>Log In</h3>
        <!-- LOGIN FORM -->
        <form id="loginform" onsubmit="return false;">
            <div>Email Address:</div>
            <input type="text" id="email" onfocus="emptyElement('login-status')" maxlength="88">
            <div>Password:</div>
            <input type="password" id="password" onfocus="emptyElement('login-status')" maxlength="100">
            <br /><br />
            <button id="loginbtn" onclick="login()">Log In</button>
            <p id="login-status"></p>
        </form>
        <!-- LOGIN FORM -->
    </div>
    <div class="vl"></div>
    <div id ='signup-section'>
        <h3>Create a New Account</h3>
        <form name="signupform" id="signupform" onsubmit="return false;">
            <div>First Name:</div>
            <input id="f_name" type="text" onfocus="emptyElement('signup-status')" onkeyup="restrict('f_name')" maxlength="88">
            <div>Last Name:</div>
            <input id="l_name" type="text" onfocus="emptyElement('signup-status')" onkeyup="restrict('l_name')" maxlength="88">
            <div>Email Address:</div>
            <input id="user-email" type="text" onfocus="emptyElement('signup-status')" onblur="check_email()" onkeyup="restrict('user-email')" maxlength="88">
            <span id="unamestatus"></span>
            <div>Create Password:</div>
            <input id="pass1" type="password" onfocus="emptyElement('signup-status')" maxlength="100">
            <div>Confirm Password:</div>
            <input id="pass2" type="password" onfocus="emptyElement('signup-status')" maxlength="100">
            <br /><br />
            <button id="signupbtn" onclick="signup()">Sign Up</button>
            <span id="signup-status"></span>
        </form>
    </div>

</div>

<?php include_once ("php_includes/pageBottom_template.php"); ?>
</body>
</html>