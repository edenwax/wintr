<?php
if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
   echo '<script>setTimeout(function(){ window.location = "'.$installed_url.'"; }, 2000);</script>';
   echo '<div id="uploads-error"><h2>what in the sweet heck! <span class="uh-underline">you\'re already logged in.</span></h2>
        <p>redirecting you back to the home page.</p></div>';
} else {
    if(isset($_POST['register'])){

        //Retrieve the field values and sanitize
        $pre_user = !empty($_POST['username']) ? trim($_POST['username']) : null;
        $username = preg_replace("/[^a-zA-Z0-9]+/", "", $pre_user);

        $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;

        $pass_conf =!empty($_POST['password_conf']) ? trim($_POST['password_conf']) : null;

        $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $reg_ip = $_SERVER['REMOTE_ADDR'];

        //make sure values are filled in and correct
            if(empty($username)){ $errormessage .= '<p>Oops! We seem to be missing <span class="highlight">a username</span>.</p>'; $errors = true;}
            if(strlen($username) >= 17){ $errormessage .= '<p>We can\'t accept usernames longer than 17 characters. <br/>C\'mon, make it short\'n\' sweet!</p>'; $errors = true;}
            if(empty($email)){ $errormessage .= '<p>Mmm looks like we didnt get the <span class="highlight">email address</span>.</p>'; $errors = true;}
            if(strlen($email) >= 60){ $errormessage .= '<p>You really have an email address over 60 characters long? <div class="hmm"><img src="img/hmm.png" alt="hmm" /></div></p>'; $errors = true;}
            if(empty($pass)){ $errormessage .= '<p>Uh oh! You didnt <span class="highlight">enter a password</span>, silly.</p>'; $errors = true;}
            if(strlen($pass) < 8){ $errormessage .= '<p>Keep it secure! Password requires a minimum of <span class="highlight">8 characters</span> please!</p>'; $errors = true;}
            if($pass != $pass_conf) { $errormessage .= '<p>Hmmm it looks like the passwords didnt match up.</p>'; $errors = true;}


                $sel = "SELECT COUNT(username) AS num FROM users WHERE username = :username";
                $salt = $pdo->prepare($sel);
                $salt->bindValue(':username', $username);
                $salt->execute();
                $row = $salt->fetch(PDO::FETCH_ASSOC);
                    
                //handle error
                if($row['num'] > 0){ $errormessage .='<p>Dang, looks like that <span class="highlight">username has been taken</span>. Try another!</p>'; $errors = true;}

                if($errors == false){
                    $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
                    //insert
                    $sql = "INSERT INTO users (username, password, emailaddy, reg_ip) VALUES (:username, :password, :email, :reg_ip)";
                    $stmt = $pdo->prepare($sql);
                    
                    //bind var
                    $stmt->bindValue(':username', $username);
                    $stmt->bindValue(':password', $passwordHash);
                    $stmt->bindValue(':email', $email);
                    $stmt->bindValue(':reg_ip', $reg_ip);

                 
                    //execute & insert
                    $result = $stmt->execute();
                    
                    //success
                    if($result){
                        //What you do here is up to you!
                        echo '<div class="reg_done"><h2>registration complete</h2><a href="login">Click here to log in</a></div>
                                <style>#register { display: none; }</style>';
                        
                    }
            } else {
                echo '<div class="error"><h2>Try again</h2>';
                echo ''.$errormessage.'</div>';
            }
    }
    echo '          <div id="register">
                        <h2>Register</h2>
                        <form method="post" action="register" onsubmit="return validate(this);">
                            <input type="text" name="username" placeholder="Username" />
                            <input type="password" name="password" placeholder="Password" />
                            <input type="password" name="password_conf" placeholder="Confirm" />
                            <input type="text" name="email" placeholder="Email" />
                            <input type="submit" name="register" value="Register" class="sub-button" />
                        </form>
                    </div>';

    echo '  <script>

            function validate(form) {
              var re = /^[a-z,A-Z]+$/i;

              if (!re.test(form.foo.value)) {
                alert(\'Please enter only letters from a to z\');
                return false;
              }
            }
            </script>';
}
 
?>