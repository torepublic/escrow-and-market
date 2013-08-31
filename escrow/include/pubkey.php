<?php
/**
 * Loads functions used in dealing with email addresses and email sending.
 *
 * @copyright (C) 2008-2012 PunBB, partially based on code (C) 2008-2009 FluxBB.org
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package PunBB
 */


// Make sure no one attempts to run this script "directly"
if (!defined('FORUM'))
	exit;


//
// Validate an public key
//

function is_valid_pubkey($pubkey)
	{
	$required_string0 = "BEGIN PGP PUBLIC KEY BLOCK";
	$required_string1 = "END PGP PUBLIC KEY BLOCK";
	$forbidden_chars = array(";","$","*","!");
	$minimal_lenght = 512;
	
	$errors_sum =0;
	//check pubkey lenght
	if (strlen($pubkey)<$minimal_lenght)
		$errors_sum=$errors_sum+1;	

	//check if required string occure
	if (!strpos($pubkey,$required_string0) or !strpos($pubkey,$required_string1) )
		$errors_sum=$errors_sum+1;		

	//check if forbidden chars occure
	foreach ($forbidden_chars as &$forbidden_character)
		{
		if (strpos($pubkey, $forbidden_character))
			$errors_sum=$errors_sum+1;	
		}
	//result of checking
	if ($errors_sum ==0)
		return true;
	else
		return false;

	}
	
function validate_pubkey($pubkey)
	{
	if (is_valid_pubkey($pubkey))
		return $pubkey;
	else
		return 'WRONG PUBLIC KEY';
	}
