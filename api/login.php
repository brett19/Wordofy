<?php
	require 'facebook-php-sdk/src/facebook.php';
	
	function die_error( $code, $message )
	{
		$err = array('error'=>array(
			'code' => $code,
			'message' => $message
		));
		die( json_encode($err) );
	}
	
	mysql_connect( 'localhost', 'root', '' );
	mysql_select_db( 'quadrionary' );
	
	session_start();
	
	$fbid = isset($_REQUEST['fbid']) ? $_REQUEST['fbid'] : false;
	$fbtkn = isset($_REQUEST['fbtkn']) ? $_REQUEST['fbtkn'] : false;
	
	if( $fbid ) {
		$facebook = new Facebook(array(
		  'appId'  => '545129182175702',
		  'secret' => '806cb98259cb9941ae27f7422dba3306',
		));
		$facebook->setAccessToken( $fbtkn );
		
		$user_profile = false;
		try {
			$prof = $facebook->api('/me');
			if( $prof['id'] == $fbid ) {
				$user_profile = $prof;
			}
		} catch( Exception $e ) {
		}
		
		if( !$user_profile ) {
			die_error( 1, 'Invalid Facebook Credentials' );
		}
		
		$qs = "SELECT * FROM users WHERE fbid='$fbid' AND status!=0 LIMIT 1";
		$q = mysql_query( $qs );
		$user = false;
		if( $q ) {
			$qr = mysql_fetch_assoc($q);
			if( $qr ) {
				$user = $qr;	
			}
		}
		
	
		if( !$user ) {
			$fname = $user_profile['first_name'];
			$lname = $user_profile['last_name'];
			$username = $user_profile['username'];
	
			$qs = "INSERT INTO users(fname,lname,username,fbid,status) VALUES('$fname','$lname','$username','$fbid',1)";
			$q = mysql_query($qs);
			if( !$q ) {
				die_error( 2, 'Failed to create account' );
			}
			
			$user_id = mysql_insert_id($q);
			$qs = "SELECT * FROM users WHERE user_id=$user_id";
			$q = mysql_query($qs);
			
			$user = mysql_fetch_assoc($q);
		}
		
		$_SESSION['user_id'] = $user['user_id'];
		
		header('Location: user.php');
	}
	

	
	
	
	
?>