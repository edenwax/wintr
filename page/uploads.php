<div id="uploads">

<!-- just for dev 
<div class="remove_msg">
	<div>
		<h1>Removed <span class="rm_file_name">dickslol.jpeg</span> as requested.</h1>
		<i class="fas fa-snowflake fa-spin"></i>
	</div>
</div> -->


<?php 
if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){

	$user_id = $_SESSION['user_id'];
	$username = $_SESSION['username'];
	$file_name = "";

	// delete script
	if (isset($_POST['delete'])) {
		$fname = $_REQUEST['filename'];
		$user = $_SESSION['user_id'];
		try {
			$stmt2 = $pdo->prepare("DELETE FROM upload_data WHERE upload_user = :user AND FILE_NAME = :fname");
			$stmt2->bindParam(':user', $user);
			$stmt2->bindParam(':fname', $fname);
			unlink('i/'.$fname);
			$stmt2->execute();
			echo '<div class="remove_msg"><div><h1>Removed <span class="rm_file_name">'.$fname.'</span> as requested.</h1>';
			echo '<i class="fas fa-snowflake fa-spin"></i>';
			echo '<script>
				  	setTimeout(function () {
       					window.location.href = "'.$installed_dir.'uploads";
    				}, 2000);
				  </script></div></div>';
		}catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	} else {
		// all is well
	}
	//end delete script

try {

	//////////////////////////////////
	//////////////////////////////////
	// PAGINATION ATTEMPT

    // Find out how many items are in the table per user
	$crows = $pdo->prepare('SELECT upload_user 
							FROM upload_data 
							WHERE upload_user = :user_id');
	$crows->execute(array($user_id));
	$total = $crows->rowCount(); // it will actually return all the rows
    // How many items to list per page
    $limit = 12;

    // How many pages will there be
    $pages = ceil($total / $limit);

    // What page are we currently on?
    $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
        'options' => array(
            'default'   => 1,
            'min_range' => 1,
        ),
    )));

    // Calculate the offset for the query
    $offset = ($page - 1)  * $limit;

    // Some information to display to the user
    $start = $offset + 1;
    $end = min(($offset + $limit), $total);

       // The "back" link
    $prevlink = ($page > 1) ? '<a href="?page=1" title="First page"><i class="fa fa-angle-double-left"></i> &nbsp; first</a> <a href="?page=' . ($page - 1) . '" title="Previous page"><i class="fa fa-angle-left"></i> &nbsp; prev</a>' : '<span class="disabled"><i class="fa fa-angle-double-left"></i> &nbsp; first</span> <span class="disabled"><i class="fa fa-angle-left"></i> &nbsp; prev</span>';

    // The "forward" link
    $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">next &nbsp; <i class="fa fa-angle-right"></i></a> <a href="?page=' . $pages . '" title="Last page">last &nbsp; <i class="fa fa-angle-double-right"></i></a>' : '<span class="disabled">next &nbsp; <i class="fa fa-angle-right"></i></span> <span class="disabled">last &nbsp; <i class="fa fa-angle-double-right"></i></span>';

/* Fetch all of the values of the first column */

    // PAGINATION ATTEMPT
    /////////////////////////////////
    /////////////////////////////////
    $stmt = $pdo->prepare("SELECT * 
    					   FROM upload_data 
    					   WHERE upload_user = :user_id 
    					   ORDER BY upload_dt DESC 
    					   LIMIT :limit 
    					   OFFSET :offset");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
	$stmt->execute();

	// $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


	if ($stmt->rowCount() > 0) {
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
        $iterator = new IteratorIterator($stmt);
		echo '<div class="uploads-header"><h2><span class="uh-underline">'.$username.'\'s</span> uploads</h2><p>Everything you\'ve uploaded should be here.</p></div>';
    // Display the paging information
    echo '<div class="paging"><div class="prev-link"><p>', $prevlink, '</p></div><div class="page-count"><p> Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results </p></div><div class="next-link"><p>', $nextlink, ' </p></div></div>';
		echo '<div class="uploads-wrap">';
		foreach($iterator as $row) {
			$fname = $row['file_name'];
			$ftype = $row['file_type'];
			$fsize = $row['file_size'];
			$date = $row['upload_dt'];
		    //print_r($results);
			echo '<form action="" method="post" class="uploads-main">';
			echo '<p><a href="'.$fname.'"><img src="'.$fname.'" alt="'.$fname.'" /></a></p>';
			echo '<input type="hidden" name="filename" value="'.$fname.'" />';
			echo '<div><input type="text" class="url" onClick="this.setSelectionRange(0, this.value.length)" value="'.$hosted_url. '' .$fname.'" readonly />';
			echo '<button type="submit" name="delete"><i class="fa fa-times-circle"></i></button></div>';
			echo '</form>';
		    // and others
		}
	echo '</div>';

		} else {
			echo '<style>.paging { display: none; }</style><div id="uploads-error"><h2>You have no uploads :(</h2>
			<p>Not to worry though, '.$username.'!</p>
			<a href="../wi">You can upload some files here.</a></div>';
		}
} catch(PDOException $e) {
   //echo 'ERROR: ' . $e->getMessage();
			echo '<style>.paging { display: none; }</style><div id="uploads-error"><h2>You have no uploads :(</h2>
			<p>Not to worry though, '.$username.'!</p>
			<a href="../wi">You can upload some files here.</a></div>';
			//this will hopefully remain temporary
}

    // Display the paging information
    echo '<div class="paging"><div class="prev-link"><p>', $prevlink, '</p></div><div class="page-count"><p> Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results </p></div><div class="next-link"><p>', $nextlink, ' </p></div></div>';			
	
} else {
	echo '<div id="uploads-error"><h2>what in the sweet heck! <span class="uh-underline">you\'re not logged in.</span></h2>
		<p>only logged in users can manage their uploads.</p></div>';
}

?>
</div>