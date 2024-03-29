<?php
include_once("db_connect.php");

//Users Table
$tbl_users = "CREATE TABLE IF NOT EXISTS users (
                id INT(11) NOT NULL AUTO_INCREMENT,
                f_name VARCHAR (255) NOT NULL,
                l_name VARCHAR (255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                avatar VARCHAR(100),
                PRIMARY KEY (id),
                UNIQUE KEY (email)
                            )";

$query = mysqli_query($db_connect, $tbl_users);

//Friends Table
$tbl_friends = "CREATE TABLE IF NOT EXISTS friends ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                user_id INT(11) NOT NULL,
                friend_id INT(11) NOT NULL,
                PRIMARY KEY (id),
				FOREIGN KEY (user_id) REFERENCES users(id)
                )";

$query = mysqli_query($db_connect, $tbl_friends);

//Posts Table
$tbl_posts = "CREATE TABLE IF NOT EXISTS posts ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                body VARCHAR(255) NOT NULL, 
                userid INT(11) NOT NULL ,
                posted_at DATETIME NOT NULL,
                likes INT(11) NOT NULL DEFAULT 0,
                permission ENUM ('Public', 'Private') NOT NULL ,
                PRIMARY KEY (id),
				FOREIGN KEY (userid) REFERENCES users(id)
                )";
$query = mysqli_query($db_connect, $tbl_posts);

//Images Table
$tbl_images = "CREATE TABLE IF NOT EXISTS images ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                user_id INT(11) NOT NULL,
                post_id INT(11) NOT NULL,
				filename VARCHAR(255) NOT NULL,
                PRIMARY KEY (id),
				FOREIGN KEY (user_id) REFERENCES users(id),
				FOREIGN KEY (post_id) REFERENCES posts(id)
                )";
$query = mysqli_query($db_connect, $tbl_images);

//Token Table(Cookies)
$tbl_tokens = "CREATE TABLE IF NOT EXISTS login_tokens ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                token CHAR(64) NOT NULL, 
                userid INT(11) NOT NULL ,
                PRIMARY KEY (id),
                UNIQUE KEY (token),
				FOREIGN KEY (userid) REFERENCES users(id)
                )";
$query = mysqli_query($db_connect, $tbl_tokens);

//Post's Likes Table
$tbl_post_likes = "CREATE TABLE IF NOT EXISTS post_likes ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                post_id INT(64) NOT NULL, 
                user_id INT(11) NOT NULL ,
                PRIMARY KEY (id),
				FOREIGN KEY (user_id) REFERENCES users(id),
				FOREIGN KEY (post_id) REFERENCES posts(id)
                )";
$query = mysqli_query($db_connect, $tbl_post_likes);

//Post's Comments Table
$tbl_comments = "CREATE TABLE IF NOT EXISTS comments ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                post_id INT(64) NOT NULL, 
                user_id INT(11) NOT NULL,
                comment TEXT NOT NULL,
                posted_at DATETIME NOT NULL,
                PRIMARY KEY (id),
				FOREIGN KEY (user_id) REFERENCES users(id),
				FOREIGN KEY (post_id) REFERENCES posts(id)
                )";
$query = mysqli_query($db_connect, $tbl_comments);

//Game Invitation Table
$tbl_invites = "CREATE TABLE IF NOT EXISTS invites ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                sender_id INT(11) NOT NULL,
                friend_id INT(11) NOT NULL,
                sent_at DATETIME NOT NULL,
                PRIMARY KEY (id),
				FOREIGN KEY (sender_id) REFERENCES users(id),
				FOREIGN KEY (friend_id) REFERENCES users(id)
                )";
$query = mysqli_query($db_connect, $tbl_invites);
?>
