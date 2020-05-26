<?php echo '<h2>manage uploads</h2>
            <link rel="stylesheet" href="css/admin.css">
            <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet"> 
            <ul id="user-table">
                <li>
                    <div class="t_userid t_head">user</div>
                    <div class="t_finame t_head">filename</div>
                    <div class="t_fisize t_head">filesize</div>
                    <div class="t_uplodt t_head">upload date</div>
                    <div class="t_uploip t_head">upload ip</div>
                    <div class="t_action t_head">action</div>
                </li>';

            try {
                $stmt = $pdo->prepare("SELECT * FROM upload_data ORDER BY upload_dt DESC LIMIT 100000");
                $stmt->execute();

                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(empty($results)){
                    echo '<li id="uploads-error"><h2>Unable to pull uploads list.</h2></li>';

                } else {
                    foreach($results as $row) {
                        $userid = $row['upload_user'];
                        $finame = $row['file_name'];
                        $bytes = $row['file_size'];
                        $uplodt = $row['upload_dt'];
                        $uploip = $row['user_ip'];
                        //print_r($results);
                        
                        try {
                            if ($bytes >= 1073741824) {
                                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
                            }
                            elseif ($bytes >= 1048576) {
                                $bytes = number_format($bytes / 1048576, 2) . ' MB';
                            }
                            elseif ($bytes >= 1024) {
                                $bytes = number_format($bytes / 1024, 2) . ' KB';
                            }
                            elseif ($bytes > 1) {
                                $bytes = $bytes . ' bytes';
                            }
                            elseif ($bytes == 1) {
                                $bytes = $bytes . ' byte';
                            }
                            else {
                                $bytes = '0 bytes';
                            }
                        } catch(PDOException $e) {
                            echo 'ERROR: ' . $e->getMessage();
                        }
                        echo '<li>';
                        echo '<div class="t_userid">'.$userid.'</div>';
                        echo '<div class="t_finame"><a href="../'.$finame.'">'.$finame.'</a></div>';
                        echo '<div class="t_fisize">'.$bytes.'</div>';
                        echo '<div class="t_uplodt">'.$uplodt.'</div>';
                        echo '<div class="t_uploip">'.$uploip.'</div>';
                        //echo '<div class="t_regdat">'.date('y.m.d', strtotime($regdat)).'</div>';
                        echo '<div class="t_action"><form onSubmit="if(!confirm(\'Are you sure?\')){return false;}" action="" method="POST"><input type="hidden" name="finame" value="'.$finame.'" /><button name="delete" type="submit">delete</button></form></div>';
                        echo '</li>';
                        // and others
                    }
                    echo '</ul>';
                }
                    //delete user
                    if (isset($_POST['delete'])) {
                        $finame = $_POST['finame'];
                        try {
                            $stmt2 = $pdo->prepare("DELETE FROM upload_data WHERE file_name = :finame");
                            $stmt2->bindParam(':finame', $finame);
                            $stmt2->execute();
                            unlink('../i/'.$finame);
                            echo '<script>
                                    setTimeout(function () {
                                        window.location.href = "../admin/uploads";
                                    }, 5);
                                  </script>';
                        }catch(PDOException $e) {
                            echo 'ERROR: ' . $e->getMessage();
                        }
                    } else {
                        // echo 'Cya nerd';
                    
                    }
                    //delete user
            } catch(PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
?>