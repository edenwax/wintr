<div id="uploader">
<?php
if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
	$user = $_SESSION['user_id'];
	$username = $_SESSION['username'];
} else {
	$user = "anon";
}
//echo '<h1>USER ID '.$user.'</h1>';
//echo '<h1>USERNAME '.$username.'</h1>';
if(isset($_FILES['files'])){
   	$errors     = array();
    $maxsize    = 5242880;
    $acceptable = array(
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png');
	foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
		$file_name=$key.$_FILES['files']['name'][$key];
		$file_size=$_FILES['files']['size'][$key];
		$file_tmp =$_FILES['files']['tmp_name'][$key];
		$file_type=$_FILES['files']['type'][$key];
       
        //check file size
	    if($file_size >= $maxsize) {
	        $errors[] = 'Incorrect file size. Must be less than 5 megabytes.';
	    }
	    //check if file even exists
	    if($file_size  == 0) {
	    	$errors[] = 'You didnt upload anything.';
	    }
        //check file type
	    if((!in_array($file_type, $acceptable)) && (!empty($file_type))) {
	        $errors[] = 'Invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
	    }

					//no errors, continue with upload
	    			//too lazy to clean up these indents
					if(count($errors) === 0) {
				        $filename = stripslashes($_FILES['image']['name']);
						$extension = pathinfo($file_name, PATHINFO_EXTENSION);
						$extension = strtolower($extension);
						$user_ip = $_SERVER['REMOTE_ADDR'];
						$newfilename = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,5) . '.' . $extension;

				        $sql = "INSERT INTO upload_data(upload_user,file_name,file_size,file_type,user_ip) VALUES(:user, :newfilename, :file_size, :file_type, :user_ip)";
				        $stmt = $pdo->prepare($sql);

						$stmt->bindParam(':user', $user, PDO::PARAM_STR);
				        $stmt->bindParam(':newfilename', $newfilename, PDO::PARAM_STR);
				        $stmt->bindParam(':file_size', $file_size, PDO::PARAM_STR);
				        $stmt->bindParam(':file_type', $file_type, PDO::PARAM_STR);
				        $stmt->bindParam(':user_ip', $user_ip, PDO::PARAM_STR);


				        $desired_dir="i";
				        if(isset($errors)){
				            if(is_dir($desired_dir)==false){
				                mkdir("$image_dir", 0700);		//create dir if /i/ non-existant
				            }
				            if(is_dir("$image_dir".$file_name)==false){
				                move_uploaded_file($file_tmp,"$image_dir".$newfilename);
				            } else {									//rename with time if filename already exists
				                $new_dir="$image_dir".$newfilename.time();
				                 rename($file_tmp,$new_dir);				
				            }
				            //execute dat insert
				            $stmt->execute();
				        } else {
				        		$errors[]='An error occurred';
				        		die();
				        }
				        echo '<div class="uploaded">';
				        echo '<div class="up_img"><a href="'.$desired_dir.'/'.$newfilename.'""><img src="'.$desired_dir.'/'.$newfilename.'" alt="'.$newfilename.'"" /></a></div>';
				        echo '<p><a href="'.$desired_dir.'/'.$newfilename.'"> '.$newfilename.' </a> was uploaded successfully.</p>';
				        echo '<div class="hotlink"><input type="text" class="url" onClick="this.setSelectionRange(0, this.value.length)" value="'.$hosted_url.''.$newfilename.'" readonly /><div class="tool-tip">COPY ME!</div></div>';
				        echo '<style>.error { display: none; !important }</style>';
						echo '</div>';
			    	}
	}
	echo '<div class="error">';
	echo implode('<br />', $errors);
	echo '</div>';

}
?>
</div>

<form name="uploader" method="POST" enctype="multipart/form-data"  action="" id="upload-form">
    <?php 

    if(empty($newfilename)){
    	echo '<h1>just upload somethin\', silly</h1>';
    } else {
    	echo '<h1 id="moremsg">is that all you got?</h1>';
    }


    ?>
	<div class="upload-btn-wrapper">
		<button class="btn">Upload file(s)</button>
		<input type="file" id="file" name="files[]" accept="image/*" onchange="openFile(event)" multiple>
	</div>

	<div class="upload-guidelines">
		<ul><h3><div><i class="fa fa-bullhorn"></i></div>heads up</h3>
			<li><div><i class="fa fa-database"></i></div><div>no files larger than 5mb</div></li>
			<li><div><i class="fa fa-image"></i></div><div>wintr only accepts image files: jpg, png, and gif</div></li>
			<li><div><i class="fa fa-book"></i></div><div>by uploading a file/files you agree to the <a href="tos">terms of use</div></a></li>
			<li><div><i class="fa fa-gavel"></i></div><div>that being said copyrighted and/or illegal material will be removed upon discovery, accounts caught uploading this information will be shut down and banned</div></li>
		</ul>
	</div>
	<script>
		document.getElementById("file").onchange = function() {
    		document.getElementById("upload-form").submit();
		}
	</script>
	<script>
	   //this is straight up just a gimmick-ey bit that can be removed 
	var myArray = [
	  "is that all you got?",
	  "is that it?",
	  "that's pretty cool.. I guess..",
	  "feed me more images",
	  "upload some more! come on!",
	  "gimme gimme more"
	];

	var randomItem = myArray[Math.floor(Math.random()*myArray.length)];
	document.getElementById("moremsg").innerHTML = randomItem;

	</script>

	</div>
</form>