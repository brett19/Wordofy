<?php
	function die_error( $code, $message )
	{
		$err = array('error' => array(
			'code' => $code,
			'message' => $message
		));
		die( json_encode($err) );
	}
	
	mysql_connect( 'localhost', 'root', '' );
	mysql_select_db( 'quadrionary' );
	
	session_start();
	
	if( !$_SESSION['user_id'] ) {
		die_error( 3, 'Not Logged In' );
	}

	$user_id = $_SESSION['user_id'];
	$qs = "SELECT * FROM users WHERE user_id=$user_id";
	$q = mysql_query($qs);
	$user = mysql_fetch_assoc($q);

	echo json_encode(array('result'=>array(
		'id' => intval($user['user_id']),
		'first_name' => $user['fname'],
		'last_name' => $user['lname']
	)));
?>