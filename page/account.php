<div id="account">
<?php
if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
    $user_id = $_SESSION['user_id'];
    $seshuser = $_SESSION['username'];

    if(isset($_POST['account'])) {
        //Retrieve the field values from our registration form.
        $pre_user = !empty($_POST['username']) ? trim($_POST['username']) : null;
        $username = preg_replace("/[^a-zA-Z0-9]+/", "", $pre_user);

        $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
        $pass_conf = !empty($_POST['password_conf']) ? trim($_POST['password_conf']) : null;
        
        $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if(!empty($username)) {
            $salt = $pdo->prepare("SELECT username FROM users WHERE username = :username");
            $salt->bindParam(':username', $username);
            $salt->execute();
            $row = $salt->fetch(PDO::FETCH_ASSOC);
            
            //handle error
            if($salt->rowCount() > 0 ){ $errormessage .=  '<p>Hmm, it looks like this username is taken. Try another.</p>'; $errors = TRUE; } 

            if($errors == FALSE){
                try {
                    $stmt = $pdo->prepare("UPDATE users SET username=:username WHERE userid=:user_id");
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->bindParam(':username', $username, PDO::PARAM_STR); 
                    $stmt->execute();
                } catch(PDOException $e) {
                    echo '<br \>ERR0R: ' . $e->getMessage();
                }
                echo '<p>Your username has been updated to <span class="uh-underline">'.$username.'</span></p>';
                $_SESSION['username'] = $username;
            } else {
                // nope
                echo '<div class="error"><h2>Try again</h2>';
                echo ''.$errormessage.'</div>';
                echo '<script>$(document).ready(function() {$(".user_name").addClass("missing_field");});</script>';
            }
        } else {
            // esc
        }
        //check if text boxes have new value
        if(!empty($pass) || !empty($pass_conf)) {
            //check if password min 8 char
            if(strlen($pass) < 8){ 
                echo '<h4><i class="fa fa-exclamation-circle"></i> Password is too short. Minimum 8 characters is required.</h4>';
                echo '<script>$(document).ready(function() {$(".pass_word").addClass("missing_field");});</script>';
                $errors = TRUE;
            }
            //check if passwords match
            if($pass === $pass_conf && $errors == FALSE) {
                $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
                try {
                    $stmt = $pdo->prepare("UPDATE users SET password=:passwordHash WHERE userid=:user_id");
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->bindParam(':passwordHash', $passwordHash, PDO::PARAM_STR); 
                    $stmt->execute();
                } catch(PDOException $e) {
                    echo '<br \>ERR0R: ' . $e->getMessage();
                }
                echo '<h3>Your password has been changed.</h3><h5>You will be logged out to renew your session.</h5>';
                echo '<script>
                    setTimeout(function () {
                        window.location.href = "'.$install_url.'logout";
                    }, 2000);</script>';
            } else {
                echo '<h4><i class="fa fa-exclamation-circle"></i> Passwords do not match.</h4>';
                echo '<script>$(document).ready(function() {$(".pass_word").addClass("missing_field");});</script>';
            }
        } else {
            // esc
        }
        if(!empty($email)) {
            try {
                $stmt = $pdo->prepare("UPDATE users SET emailaddy=:email WHERE userid=:user_id");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR); 
                $stmt->execute();
            } catch(PDOException $e) {
                echo '<br \>ERR0R: ' . $e->getMessage();
            }
            echo 'Your email address has been updated to '.$email.'';
        } else {
            // esc
        }
        if(empty($username) && empty($pass) && empty($pass_conf) && empty($email)) {
            echo '<h4 id="msg">Nothing has changed.</h4>';
        } else {
            // esc
        }     
    } else {
        echo '<h4>greetings, '.$seshuser.'</h4>';
    }
    echo '                    <div id="account-info">
                        <h2>Account Information</h2>
                        <form method="post" action="" />
                            <div class="ai_icon"><i class="fa fa-id-card"></i></div>
                            <div class="ai_label">Username:</div>
                            <input type="text" name="username" class="user_name" placeholder="'.$seshuser.'"/>
                            
                            <div class="ai_icon"><i class="fa fa-key"></i></div>
                            <div class="ai_label">Password:</div>
                            <input type="password" class="pass_word" name="password" />
                            
                            <div class="ai_icon"><i class="fa fa-check-circle"></i></div>
                            <div class="ai_label">Confirm Password:</div>
                            <input type="password" class="pass_word" name="password_conf" />

                            <div class="ai_icon"><i class="fa fa-envelope-open"></i></div>
                            <div class="ai_label">Email Address:</div>
                            <input type="email" name="email" class="e_mail" placeholder="your@email.com" />
                            <input type="submit" name="account" value="Update" class="sub-button" />
                        </form>
';
    if($user_id == 4) {
        echo '<p><br />Admins cant delete their account through the accounts page.</p>';
    } else {
                        echo '<form onSubmit="if(!confirm(\'Are you sure you want to delete your Wintr account? This will delete all of your hosted images within 24 hours of the account being deleted. You will be logged out on deletion.\')){return false;}" method="post" action="?delete">
                            <button type="submit" name="del_account" class="del-button">Delete Account &nbsp; <i class="fa fa-frown"></i></button>
                        </form>';
    }
echo '
                    </div>

                    <div id="account-desc">
                        <p>Here you can change your username, password and email that Wintr has on file. Note, if you change your password the server will kick you offline. You will need to log back in using your new password afterwords.</p>
                        <p>You can also delete your account if you choose to do so. Note, any uploaded images will be removed from the server within 24 hours. It is not an instant process yet.</p>
                        <p>Also, if you try updating your account information without inputting any text the server will give you some sassy replies.</p>
                    </div>'; 
} else {
    echo "<div id='uploads-error'><h2>What in the sweet heck! You're not logged in.</h2>
        <p>Only logged in users can manage their account.<br/>Consider logging in or signing up, its free!</p></div>";
}
?>
                    <script>
                        function delete_alert() {
                            alert("Are you sure you want to delete your account?");
                        }
                    </script>
<?php

    if (isset($_POST['del_account'])) {
        $fname = $_REQUEST['filename'];
        $user = $_SESSION['user_id'];
        try {
            $stmt2 = $pdo->prepare("DELETE FROM upload_data WHERE upload_user = :user_id");
            $stmt2->bindParam(':user_id', $_SESSION['user_id']);
            $stmt2->execute();
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        try {
            $stmt3 = $pdo->prepare("DELETE FROM users WHERE userid = :user");
            $stmt3->bindParam(':user', $user);
            $stmt3->execute();

            echo '<script>
                    setTimeout(function () {
                        window.location.href = "'.$install_url.'logout";
                    }, 1000);
                  </script>';
        }catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    } else {
        // echo 'Cya nerd';
    }


?>
</div>

<script>
    
var myArray = [
  "Nothing has changed.",
  "What are you trying to do?",
  "Nothing has changed.",
  "You didnt do anything.",
  "Nothing has changed.",
  "Who do you think you are, huh?",
  "Cut the crap.",
  "Nothing has changed.",
  "Nothing has changed.",
  "I DONT UNDERSTAND.",
];

var randomItem = myArray[Math.floor(Math.random()*myArray.length)];
document.getElementById("msg").innerHTML = randomItem;

</script>

<!-- gotta have 'em easter eggs yo -->
