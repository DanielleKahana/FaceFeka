<?php
$db_connect = mysqli_connect("localhost", "admin", "admin", "facefeka");
if(mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
}
?>
