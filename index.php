<?php
	require 'lib/password.php';
	require 'lib/connect.php';
    session_start(); 
	error_reporting (E_ALL ^ E_NOTICE);
    // testing purposes
    // echo '<strong>user id</strong> '.$_SESSION['user_id'].'<br />';
    // echo '<strong>username</strong> '.$_SESSION['username'];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>wintr</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script defer src="lib/fontawesome-all.js"></script>

        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/normalize.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat+Alternates:400,500,600,700" rel="stylesheet"> 

        <link rel="apple-touch-icon" href="img/icon.png">
        <link rel="icon" href="img/icon.png" type="image/png">
        <link rel="shortcut icon" href="img/icon.png" type="image/png"> 
    </head>
    <body>
        <div id="warning">
            <h1>This is only for development purposes, all use is restricted and hosted images are very temporary</h1>
        </div>
        <div id="page-wrap">
            <header>
                <div id="logo">
                    <a href="<?=$install_dir?>"><img src="img/wintr.png" alt="wintr" /></a>
                    <p><?=$winver?></p>
                </div>
                <div id="nav">
                    <ul>
                        <li><a href="<?=$install_dir?>">home</a></li>
                        <li><a href="">about</a></li>
                        <li><a href="">contact</a></li>
                        <li><a href="tos">legal</a></li>
                        <?php 
                            if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
                                if($_SESSION['user_id'] == '4' && $_SESSION['username'] == 'admin'){
                                    echo '<li><a href="admin">admin</a></li>';
                                }
                            }
                        ?>
                    </ul>
                </div>
                <div id="user">
                    <?php
                        if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
                            echo '<div id="greeting">'.$_SESSION['username'].' &nbsp; <span class="fa fa-angle-down"></span></div>';
                            include 'inc/user_in.php';
                        } else {
                            include 'inc/user_out.php';
                        }
                        ?>
                </div>
            </header>

            <div id="content">
                <?php
                    if($_GET['p'] == ""){
                        include "page/upload.php";
                    }
                    if($_GET['p'] == "register"){
                        include 'page/register.php';
                    }
                    if($_GET['p'] == "login"){
                        include 'page/login.php';
                    }
                    if($_GET['p'] == "logout"){
                        include 'page/logout.php';
                    }
                    if($_GET['p'] == "upload"){
                        include 'page/upload.php';
                    }
                    if($_GET['p'] == "uploads"){
                        include 'page/uploads.php';
                    }
                    if($_GET['p'] == "about"){
                        include 'page/about.html';
                    }
                    if($_GET['p'] == "tos"){
                        include 'page/tos.html';
                    }
                    if($_GET['p'] == "account"){
                        include 'page/account.php';
                    }
                ?>
            </div>
        </div>
            <div id="footer">
                <p>Copyright © 2018 <a href="<?=$hosted_url?>">Wintr</a></p>
            </div>
        <script>
            $("#greeting").click(function() {
              $("#user-drop").slideToggle("fast", function() {
                // Animation complete.
              });
            });
        </script>
    </body>
</html>