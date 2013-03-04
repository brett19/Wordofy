<?php
	session_start();
	$_SESSION['user_id'] = NULL;
	
	echo json_encode(array(
		'error' => NULL
	));
?>