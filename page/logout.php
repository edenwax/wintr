<div id="logout">
	<?php
		$_SESSION['logged_in'] = NULL;
		$_SESSION['id']   = NULL;
		$_SESSION['username']   = NULL;

		session_unset(); 
		session_destroy();  
	 
	 	echo '<h1>you have successfully logged out.</h1> <h2>redirecting you back to the home page.</h2>';
	 	echo '<script>setTimeout(function(){
	  window.location = "'.$install_dir.'";
	}, 3000);</script>';
	?>
</div>