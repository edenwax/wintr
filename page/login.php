<?php
if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
   echo '<script>setTimeout(function(){ window.location = "../sinister"; }, 2000);</script>';
   echo '<div id="uploads-error"><h2>what in the sweet heck! <span class="uh-underline">you\'re already logged in.</span></h2>
        <p>redirecting you back to the home page.</p></div>';
} else {
    if(isset($_POST['login'])){
        $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
        $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;
        
        $sql = "SELECT userid, username, password FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindValue(':username', $username);
        
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user === false){
            echo '<div class="error"><h2>Try again</h2><p>Incorrect username.</p></div>';
        } else {

            //$validPassword = password_verify($passwordAttempt, $user['password']);
            
            if(password_verify($passwordAttempt, $user['password'])){
                
                $_SESSION['user_id'] = $user['userid'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['regdate'] = $user['regdate'];
                $_SESSION['logged_in'] = time();
                
                echo '<script>setTimeout(function(){ window.location = "'.$install_dir.'"; }, 50);</script>';
                exit;
                
            } else{
                echo '<div class="error"><h2>Try again</h2><p>Incorrect password.</p></div>';
            }
        }
        
    }
    echo '<div id="login"><h2>Login</h2>
            <form method="post" action="" />
                <input type="text" name="username" placeholder="Username" size=25 />
                <input type="password" name="password" placeholder="Password" size=25 />
                <input type="submit" name="login" value="Login" class="sub-button" />
            </form>
        </div>';
}
 
?>