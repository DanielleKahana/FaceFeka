<?php
include_once ("php_includes/check_login_status.php");


$current_username = "";

if($user_ok) {
    $sql = "SELECT * FROM users WHERE id='$userid'";
    $query = mysqli_query($db_connect, $sql);
    $row = mysqli_fetch_row($query);
    $current_username = $row['1']." ".$row['2'];
    $email = $row['3'];
}
?>


<div id="pageTop">
    <div id="pageTopWrapper">
        <div id="pageTopLogo"><img class='normal-img' src="images/logo.png" alt="FaceFeka"></div>
        <div id="pageTopRest">
            <!-- contains the search bar, link to homepage and user settings  -->
            <div id="links">
                <?php if($user_ok) { echo "
                
                <div class='srbox'>
                            <input type='text' onKeyUp='search(this.value)' autocomplete='off' name='qu' id='qu' class='textbox' placeholder='Search Friends...' tabindex='1'>
                            <button type='submit' class='query-submit' tabindex='2'><img class='normal-img' src='images/magnifier.png'></button>
                            <div id='livesearch'></div>
                </div>
                
                <div class='console-btn' onmouseenter='showNotifications()' onmouseleave='toggle_element(\"dropdown-content\")'> 
                    <input type='image' src='images/console.png' id='show_notification' class='dropbtn' >
                    <div id='dropdown-content' class='dropdown-content'></div>
                </div>
                
                <div class='dropdown'>
                        <a href='profile.php?e=$email' id='username'>$current_username</a>
                </div>
                <a href='logout.php' >Log Out</a>";
            } ?>
            </div>
        </div>
    </div>
</div>
