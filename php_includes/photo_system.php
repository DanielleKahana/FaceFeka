<?php
include_once("check_login_status.php");
include_once("image_resize.php");
if(!$user_ok) {
    exit();
}
?><?php
if (isset($_FILES["avatar"]["name"]) && $_FILES["avatar"]["tmp_name"] != ""){
    $fileName = $_FILES["avatar"]["name"];
    $fileTmpLoc = $_FILES["avatar"]["tmp_name"];
    $fileType = $_FILES["avatar"]["type"];
    $fileSize = $_FILES["avatar"]["size"];
    $fileErrorMsg = $_FILES["avatar"]["error"];
    $kaboom = explode(".", $fileName);
    $fileExt = end($kaboom);
    list($width, $height) = getimagesize($fileTmpLoc);
    if($width < 10 || $height < 10){
        exit();
    }
    $db_file_name = "profile.".$fileExt;
    if($fileSize > 2048576) { // 2MB
        echo(" Your image file was larger than 2mb");
        exit();
    } else if (!preg_match("/\.(jpg|png|jpeg)$/i", $fileName) ) {
        echo("Your image file was not jpg, gif or png type");
        exit();
    } else if ($fileErrorMsg == 1) {
        echo(" An unknown error occurred");
        exit();
    }
    $sql = "SELECT email,avatar FROM users WHERE id='$userid' LIMIT 1";
    $query = mysqli_query($db_connect, $sql);
    $row = mysqli_fetch_row($query);
    $email = $row[0];
    $avatar = $row[1];
    if($avatar != "images/default.jpg"){
        $picurl = "$avatar";
        if (file_exists($picurl)) { unlink($picurl); }
    }
    $moveResult = move_uploaded_file($fileTmpLoc, "../user/$email/$db_file_name");
    if ($moveResult != true) {
        echo("File upload failed");
        exit();
    }

    $target_file = "../user/$email/$db_file_name";
    $resized_file = "../user/$email/$db_file_name";
    $wmax = 200;
    $hmax = 300;
    img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
    $target_file = "user/$email/$db_file_name";
    $sql = "UPDATE users SET avatar='$target_file' WHERE id='$userid'";
    $query = mysqli_query($db_connect, $sql);
    mysqli_close($db_connect);
    header("location: ../profile.php?e=$email");
    exit();
}
?>