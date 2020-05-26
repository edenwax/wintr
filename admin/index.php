<?php
	require '../lib/password.php';
	require '../lib/connect.php';
    session_start(); 
	//error_reporting (E_ALL ^ E_NOTICE);
    // testing purposes
    // echo '<strong>user id</strong> '.$_SESSION['user_id'].'<br />';
    // echo '<strong>username</strong> '.$_SESSION['username'];
    if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
        if($_SESSION['user_id'] == '4' && $_SESSION['username'] == 'admin'){
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>wintr ACP</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script defer src="../lib/fontawesome-all.js"></script>

        <link rel="stylesheet" href="../css/main.css">
        <link rel="stylesheet" href="../css/admin.css">
        <link rel="stylesheet" href="../css/normalize.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet"> 

        <link rel="apple-touch-icon" href="../img/icon.png">
        <link rel="icon" href="../img/icon.png" type="image/png">
        <link rel="shortcut icon" href="../img/icon.png" type="image/png"> 
    </head>
    <body>
        <div id="page-wrap">
            <header>
                <div id="logo">
                    <a href="../"><!--wintr--><img src="../img/wintr.png" alt="wintr" /></a>
                    <p><?=$winver?></p>
                </div>
                <div id="nav">
                    <ul>
                        <li>admin control panel</li>
                        <li><a href="users">users</a></li>
                        <li><a href="uploads">uploads</a></li>
                        <li class="home"><a href="../<?=$install_dir?>">main site &nbsp; <i class="fa fa-angle-double-right"></i></a></li>
                    </ul>
                </div>
            </header>
            <div id="content">
                <div id="admin">
                        <?php
                        // include this into another file
                        if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
                           if($_SESSION['user_id'] == '4' && $_SESSION['username'] == 'admin'){

                            echo '<div id="stats">';
                            // get stats
                            try {
                                $count = $pdo->query("SELECT count(*) FROM users")->fetchColumn();
                                echo '<div class="usercount"><h1>'.$count.'</h1><h2>REGISTERED USERS</h2></div>';
                            } catch(PDOException $e) {
                                echo 'ERROR: ' . $e->getMessage();
                            }

                            try {
                                $count = $pdo->query("SELECT count(*) FROM upload_data")->fetchColumn();
                                echo '<div class="imagecount"><h1>'.$count.'</h1><h2>UPLOADED IMAGES</h2></div>';
                            } catch(PDOException $e) {
                                echo 'ERROR: ' . $e->getMessage();
                            }

                            try {
                                $count = $pdo->query("SELECT count(*) FROM upload_data WHERE upload_user = 0")->fetchColumn();
                                echo '<div class="imagecount"><h1>'.$count.'</h1><h2>UNREGISTERED IMAGES</h2></div>';
                            } catch(PDOException $e) {
                                echo 'ERROR: ' . $e->getMessage();
                            }

                            echo '</div>';

                            if($_GET['p'] == "users"){
                                include '../page/acpusers.php';
                            }
                            if($_GET['p'] == "uploads"){
                                include '../page/acpuploads.php';
                            }

                            // end stats
                            
                        } else {
                            // 404 code here
                            die();
                        }
                        } else {
                            echo 'There was an error.';
                        }
                        ?>
                </div>
            </div>
        </div>
            <div id="footer">
                <p>Copyright © 2018 <a href="//ed3n.me/">Colin Berry | ed3n.me</a></p>
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

<?php
    } else {
        // 404
    }
} else {
    // 404
}

?>