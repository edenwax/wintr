<?php echo '<h2>manage users</h2>
            <link rel="stylesheet" href="css/admin.css">
            <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet"> 
            <ul id="user-table">
                <li>
                    <div class="t_userid t_head">user</div>
                    <div class="t_usernm t_head">username</div>
                    <div class="t_emaily t_head">email</div>
                    <div class="t_uplods t_head">uploads</div>
                    <div class="t_reg_ip t_head">reg ip</div>
                    <div class="t_regdat t_head">register date</div>
                    <div class="t_action t_head">action</div>
                </li>';

            try {
                $stmt = $pdo->prepare("SELECT * FROM users ORDER BY regdat ASC LIMIT 100000");
                $stmt->execute();

                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(empty($results)){
                    echo '<li id="uploads-error"><h2>Unable to pull user list.</h2></li>';

                } else {
                    foreach($results as $row) {
                        $userid = $row['userid'];
                        $usernm = $row['username'];
                        $emaily = $row['emailaddy'];
                        $regdat = $row['regdat'];
                        $reg_ip = $row['reg_ip'];
                        //print_r($results);
                        $crows = $pdo->prepare('SELECT * 
                                            FROM upload_data 
                                            WHERE upload_user = :userid');
                        $crows->execute(array($userid));
                        $total = $crows->rowCount();

                        echo '<li>';
                        echo '<div class="t_userid">'.$userid.'</div>';
                        echo '<div class="t_usernm">'.$usernm.'</div>';
                        echo '<div class="t_emaily">'.$emaily.'</div>';
                        echo '<div class="t_uplods">'.$total.'</div>';
                        echo '<div class="t_reg_ip">'.$reg_ip.'</div>';
                        echo '<div class="t_regdat">'.date('y.m.d', strtotime($regdat)).'</div>';
                        echo '<div class="t_action"><form onSubmit="if(!confirm(\'Are you sure?\')){return false;}" action="" method="POST"><input type="hidden" name="userid" value="'.$userid.'" /><button name="delete" type="submit">delete</button></form></div>';
                        echo '</li>';
                        // and others
                    }
                    echo '</ul>';
                }
                    //delete user
                    if (isset($_POST['delete'])) {
                        $user = $_POST['userid'];
                        if($userid != 1) {
                            try {
                                $stmt2 = $pdo->prepare("DELETE FROM upload_data WHERE upload_user = :user");
                                $stmt2->bindParam(':user', $user);
                                $stmt2->execute();
                                $stmt3 = $pdo->prepare("DELETE FROM users WHERE userid = :user");
                                $stmt3->bindParam(':user', $user);
                                $stmt3->execute();
                                echo '<script>
                                        setTimeout(function () {
                                            window.location.href = "../admin/users";
                                        }, 5);
                                      </script>';
                            }catch(PDOException $e) {
                                echo 'ERROR: ' . $e->getMessage();
                            }
                        } else {
                            echo '<script>alert("The admin can\'t delete their account.");</script>';
                        }
                    } else {
                        // echo 'Cya nerd';
                    
                    }
                    //delete user
            } catch(PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
?>