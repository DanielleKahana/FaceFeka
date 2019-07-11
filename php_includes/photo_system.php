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
        header("location: ../message.php?msg=ERROR: That image has no dimensions");
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

//function uploadImages($postid, $db_connect, $userid){
//
//    $sql = "SELECT email FROM users WHERE id='$userid' LIMIT 1";
//    $query = mysqli_query($db_connect, $sql);
//    $row = mysqli_fetch_row($query);
//    $email = $row[0];
//
//    if (!file_exists("user/$email/thumb")) {
//        mkdir("user/$email/thumb", 0755);
//    }
//    if(!file_exists("user/$email/pictures")) {
//        mkdir("user/$email/pictures", 0755);
//    }
//
//    $targetDir = "user/$email/pictures";
//    $targetThumbDir = "user/$email/thumb";
//    $allowTypes = array('jpg','png','jpeg','gif');
//
//    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
//    if(!empty(array_filter($_FILES['post_image']['name']))) {
//        foreach ($_FILES['post_image']['name'] as $key => $val) {
//            // File upload path
//            $fileName = basename($_FILES['post_image']['name'][$key]);
//            $targetFilePath = $targetDir . $fileName;
//            $targetThumbFilePath = $targetThumbDir . $fileName;
//
//            // Check whether file type is valid
//            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
//            if (in_array($fileType, $allowTypes)) {
//                // Upload file to server
//                if (move_uploaded_file($_FILES["post_image"]["tmp_name"][$key], $targetFilePath)) {
//                    // Image db insert sql
//                    createThumbs($fileName, $targetThumbDir, 100);
//                    $insertValuesSQL .= "('$userid','$postid', '" . $fileName . "'),";
//                } else {
//                    $errorUpload .= $_FILES['post_image']['name'][$key] . ', ';
//                }
//            } else {
//                $errorUploadType .= $_FILES['post_image']['name'][$key] . ', ';
//            }
//        }
//
//        if(!empty($insertValuesSQL)){
//            $insertValuesSQL = trim($insertValuesSQL,',');
//            // Insert image file name into database
//            $sql = "INSERT INTO images (user_id, post_id, filename) VALUES $insertValuesSQL";
//            $query = mysqli_query($db_connect, $sql);
//            if($query){
//                $errorUpload = !empty($errorUpload)?'Upload Error: '.$errorUpload:'';
//                $errorUploadType = !empty($errorUploadType)?'File Type Error: '.$errorUploadType:'';
//                $errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType;
//                $statusMsg = "Files are uploaded successfully.".$errorMsg;
//            }else{
//                $statusMsg = "Sorry, there was an error uploading your file.";
//            }
//        }
//    }else{
//        $statusMsg = 'Please select a file to upload.';
//    }
//
//
//return $statusMsg;
//
//}
//
//function createThumbs( $fileName, $pathToThumbs, $thumbWidth){
//    $kaboom = explode(".", $fileName);
//    $fileExt = end($kaboom);
//    $thumb_create = imagecreatetruecolor($thumbWidth,$thumbWidth);
//    switch($fileExt){
//        case 'jpg':
//            $source = imagecreatefromjpeg($pathToThumbs);
//            break;
//        case 'jpeg':
//            $source = imagecreatefromjpeg($pathToThumbs);
//            break;
//        case 'png':
//            $source = imagecreatefrompng($pathToThumbs);
//            break;
//        default:
//            $source = imagecreatefromjpeg($pathToThumbs);
//    }
//
//
//    // load image and get image size
//
//    $width = imagesx( $source );
//    $height = imagesy( $source );
//
//    // calculate thumbnail size
//    $new_width = $thumbWidth;
//    $new_height = floor( $height * ( $thumbWidth / $width ) );
//
//    // create a new temporary image
//    $tmp_img = imagecreatetruecolor( $new_width, $new_height );
//
//    // copy and resize old image into new image
//    imagecopyresized( $tmp_img, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
//
//    // save thumbnail into a file
//    $moveResult = move_uploaded_file($tmp_img, $pathToThumbs);
//    if ($moveResult != true) {
//        echo("File upload failed");
//        exit();
//    }
//}


?>