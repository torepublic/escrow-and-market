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
// Validate an e-mail address
//

function is_valid_btcaddress($pubkey)
	{

	$forbidden_chars = array(";","$","*","!","[","]","@","#","%","^","&");
	$minimal_lenght = 25;
	
	$errors_sum =0;
	//check pubkey lenght
	if (strlen($pubkey)<$minimal_lenght)
		$errors_sum=$errors_sum+1;	
	// check if address begins from 1		
	if ($pubkey[0]!='1')
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

function validate_btcaddress($pubkey)
	{
	if (is_valid_btcaddress($pubkey))
		return $pubkey;
	else
		return 'WRONG BITCOIN ADDRESS';
	}
