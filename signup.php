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
