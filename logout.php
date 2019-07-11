<?php
include_once ("php_includes/db_connect.php");
include_once ("php_includes/check_login_status.php");

if(!$user_ok) {
    header("location: index.php");
}
if (isset($_POST['confirm'])) {
    if(isset($_COOKIE['SNID'])) {
        $token = md5($_COOKIE['SNID']);
        $sql= "DELETE FROM login_tokens WHERE userid='$userid'";
        $query = mysqli_query($db_connect, $sql);
    }
    //expire the cookie
    setcookie("SNID", "1", time() - 3600, '/', "", "", TRUE);
    header("location: index.php");

}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Log Out</title>
    <link rel="stylesheet" href="style/stylesheet.css">
    <script src="js/main.js"></script>
    <script src="js/ajaxModule.js"></script>
</head>
<body>
<?php include_once("php_includes/pageTop_template.php"); ?>
<div id="pageMiddle">
    <div id="logout-wrapper">
    <h1 id="logout-title">Logout of your account</h1>
        <p><strong><?php echo $current_username?></strong> are you sure you'd like to logout?</p>
    <form action="logout.php" method="post">
        <input type="submit" name ="confirm" value="confirm">
    </form>
    </div>

</div>
<?php include_once("php_includes/pageBottom_template.php"); ?>
</body>
</html>
