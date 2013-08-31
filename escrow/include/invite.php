<?php
//if (!defined('FORUM_ROOT'))
//	define('FORUM_ROOT', './');

//... and the pub-key
//if (!defined('FORUM_PUBKEY_FUNCTIONS_LOADED'))
//	require FORUM_ROOT.'include/invite.php';

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM'))
	exit;

function is_valid_checksum($invite)
	{

	$forbidden_chars = array(";","$","*","!","[","]","@","#","%","^","&");
	$minimal_lenght = 41;
	
	$errors_sum =0;
	//check pubkey lenght
	if (strlen($invite)<$minimal_lenght)
		$errors_sum=$errors_sum+1;	
	//check if forbidden chars occure
	foreach ($forbidden_chars as &$forbidden_character)
		{
		if (strpos($invite, $forbidden_character))
			$errors_sum=$errors_sum+1;	
		}
	//check checksum value
	$proper_checksum = get_checksum($invite);
	$recived_checksum = substr($invite,-1);
	if ($proper_checksum!=$recived_checksum)
		{
		$errors_sum++;	
		}
	//result of checking
	if ($errors_sum ==0)
		return true;
	else
		return false;

	}

function get_checksum($invite)
	{
	$invite_lenght = strlen($invite);
	$invite = substr($invite,0,-1);
	$checksum = dechex(abs(crc32($invite.'3')%16));
	return $checksum;
	}

function get_username($invite)
	{
	return substr($invite,40,-1);	
	}

function get_salt($username)
	{
	$con=mysqli_connect("127.0.0.1","other","kurwaalenuda!","forum");
	// Check connection
	if (!$con)
		{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	else
		{
		$result = mysqli_query($con,"SELECT * FROM users WHERE username='$username'");
		$temp = mysqli_fetch_array($result);
		$salt = $temp[4];
		mysqli_close($con);
		return $salt;
		}
	}
	
function get_proper_invitation($username)
	{
	$salt = get_salt($username);
	$time=date('W');
	$hashedTxt = sha1($salt.$username.$time.$salt);
	$invite = $hashedTxt.$username;
	$checksum = get_checksum($invite);
	$invite = $invite.$checksum;
	return $invite;
	}

?>
