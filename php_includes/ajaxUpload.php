<?php
include_once("check_login_status.php");

$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'JPG'); // valid extensions
if (!empty($_POST['postbody']) && !empty($_POST['permission'])) {
    $msg = "success";
    $hasFiles = false;
    $result = array();
    $text = htmlspecialchars($_POST['postbody']);
    $per = $_POST['permission'];

    $sql = "INSERT INTO posts (body,userid,posted_at,permission) VALUES('$text', '$userid', NOW() , '$per')";
    $query = mysqli_query($db_connect, $sql);
    if (!$query) {
        $msg = "post_failed";
    } else {
        $last_id = mysqli_insert_id($db_connect);
        $sql = "SELECT email, f_name, l_name FROM users WHERE id='$userid' LIMIT 1";
        $query = mysqli_query($db_connect, $sql);
        $row = mysqli_fetch_row($query);
        $email = $row[0];
        $full_name = $row[1] . ' ' . $row[2];

        $sql = "SELECT posted_at,permission FROM posts WHERE id='$last_id'";
        $query = mysqli_query($db_connect, $sql);
        $posted_at = mysqli_fetch_row($query)[0];



        $img = array();
        $file_name = array();

        foreach ($_FILES['image']["name"] as $file => $key) {
            if (!empty($_FILES['image']["name"][$file])) {
                $hasFiles = true;

                if (!file_exists("../user/$email/thumb")) {
                    mkdir("../user/$email/thumb/", 0755);
                }
                if (!file_exists("../user/$email/pictures")) {
                    mkdir("../user/$email/pictures/", 0755);
                }

                $targetDir = "../user/$email/pictures/";
                $targetThumbDir = "../user/$email/thumb/";

                $img["name"] = $_FILES['image']["name"][$file];
                $img["tmp_name"] = $_FILES['image']["tmp_name"][$file];

                // File upload path
                $fileName = basename($img["name"]);
                $targetFilePath = $targetDir . $fileName;
                $targetThumbFilePath = $targetThumbDir . $fileName;


                array_push($file_name, $fileName);


                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                if (in_array($fileType, $valid_extensions)) {
                    if (move_uploaded_file($img["tmp_name"], $targetFilePath)) {
                        $insert = "INSERT INTO images (user_id, post_id, filename) VALUES ('$userid', '$last_id', '$fileName')";
                        $q = mysqli_query($db_connect, $insert);
                        createThumbs($targetFilePath, $targetThumbFilePath, 100);
                    } else {
                        $msg = "post_failed";

                    }
                } else {
                    $msg = "post_failed";

                }
            }
        }
    }
    $result = array("msg" => $msg,
        "postid" => $last_id,
        "email" => $email,
        "full_name" => $full_name,
        "hasFiles" => $hasFiles,
        "filename" => $file_name,
        "posted_at" => $posted_at);
    echo json_encode($result);
//    echo json_encode($msg);

}


function createThumbs($fileName, $pathToThumbs, $thumbWidth)
{
    $kaboom = explode(".", $fileName);
    $fileExt = end($kaboom);
    switch ($fileExt) {
        case 'jpg':
            $source = imagecreatefromjpeg($fileName);
            break;
        case 'jpeg':
            $source = imagecreatefromjpeg($fileName);
            break;
        case 'png':
            $source = imagecreatefrompng($fileName);
            break;
        default:
            $source = imagecreatefromjpeg($fileName);
    }

    // load image and get image size
    $width = imagesx($source);
    $height = imagesy($source);

    // calculate thumbnail size
    $new_width = $thumbWidth;
    $new_height = floor($height * ($thumbWidth / $width));

    // create a new temporary image
    $tmp_img = imagecreatetruecolor($new_width, $new_height);

    // copy and resize old image into new image
    imagecopyresized($tmp_img, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    switch ($fileExt) {
        case 'jpg':
            $res = imagejpeg($tmp_img, $pathToThumbs);
            break;
        case 'jpeg':
            $res = imagejpeg($tmp_img, $pathToThumbs);
            break;
        case 'png':
            $res = imagepng($tmp_img, $pathToThumbs);
            break;
        default:
            $res = imagejpeg($tmp_img, $pathToThumbs);
    }

    if (!$res) {
        echo "post_failed";
        exit();
    }
}



