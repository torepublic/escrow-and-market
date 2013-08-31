<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

define('ADDRESS_FREE',0);
define('ADDRESS_TAKEN', 1);

//jak wszystko idzie dobrze
define('ESCROW_STARTED',	0);
define('BITCOINS_RECEIVED', 1);
define('BITCOINS_RELEASED',	2);
define('ESCROW_FINISHED',	3);
//jak klient zglosi problem
define('PROBLEM_REPORTED',	4);
define('FULL_BITCOIN_RETURN',5);
define('PARTIAL_BITCOIN_RETURN',6);
define('NO_BITCOIN_RETURN',7);
//problemreason
define('NOT_RECEIVED',1);
define('FALSE_DESCRIPTION',2);
//problemclaim
define('FULL_REFUND',1);
define('PARTIAL_REFUND',2);

//payouts
define('PAYOUT_REQUESTED',0);
define('PAYOUT_REALISED',1);

function market_get_category_forums($categoryid)
{
	global $forum_db;
	$query = array(
		'SELECT'	=>	'f.id , f.forum_name',
		'FROM'		=>	'forums AS f',
		'WHERE'		=>	'f.cat_id='.intval($categoryid)
	);
	$result =$forum_db->query_build($query);
	$output_str = '';
	while ($row = mysql_fetch_assoc($result))
	{
		$link = '<td><a href='.FORUM_ROOT.'search.php?category='.$row['id'].'>'.$row['forum_name'].'</a></td>';
		$output_str=$output_str.$link;
	}	
	return $output_str;
}

function market_get_category_sell_page($categoryid)
{
	global $forum_db;
	$query = array(
		'SELECT'	=>	'f.id , f.forum_name',
		'FROM'		=>	'forums AS f',
		'WHERE'		=>	'f.cat_id='.intval($categoryid)
	);
	$result =$forum_db->query_build($query);
	$output_str = '';
	while ($row = mysql_fetch_assoc($result))
	{
		$link = '<tr><td><a href='.FORUM_ROOT.'post.php?fid='.$row['id'].'>'.$row['forum_name'].'</a></td></tr>';
		$output_str=$output_str.$link;
	}	
	return $output_str;
}

function market_get_forum_name($forum_id)
{
	global $forum_db;
	$query = array(
		'SELECT'	=>	'f.id , f.forum_name',
		'FROM'		=>	'forums AS f',
		'WHERE'		=>	'f.id='.intval($forum_id)
	);
	$result =$forum_db->query_build($query);
	$row = mysql_fetch_assoc($result);
	return $row['forum_name'];
}

//search through topics and return the newest 30
function market_get_newest_offers($forum_id, $forbidden_forum_index){
	global $forum_db;
	if ($forum_id!=0)
	{
		$query = array(
			'SELECT'	=>	't.id , t.subject, t.posted, t.forum_id',
			'FROM'		=>	'topics AS t',
			'WHERE'		=>	't.forum_id='.intval($forum_id),
			'ORDER BY'	=>	't.posted DESC',
			'LIMIT'		=>	'0, 20'
			);	
	}
	else
	{
		$query = array(
			'SELECT'	=>	't.id , t.subject, t.posted, t.forum_id',
			'FROM'		=>	'topics AS t',
			'WHERE'		=>	't.forum_id!='.intval($forbidden_forum_index),
			'ORDER BY'	=>	't.posted DESC',
			'LIMIT'		=>	'0, 20'
			);	
	}
	$result =$forum_db->query_build($query);
	$output_str = '';
	while ($row = mysql_fetch_assoc($result))
	{
		$output_str=$output_str.'<tr><td width="14%">'.date('y-m-d H:i',$row['posted']).'</td><td width="10%">'.market_get_forum_name($row['forum_id']).'</td><td><a href='.
			FORUM_ROOT.'viewtopic.php?id='.$row['id'].'>'.$row['subject'].'</a></td></tr>';
	}
	if (mysql_num_rows($result)==0)
		{
			$output_str='<tr><td>No offers found</td></tr>';
		}
	return $output_str;
}

function escrow_get_claim_reason($problemreason)
{
	if ($problemreason ==NOT_RECEIVED )
		return 'Not Received';
	else if ($problemreason ==FALSE_DESCRIPTION )
		return 'False Description';
	else
		return 'No Problem';
}
function escrow_get_action_claimed($problemclaim)
{
	if ($problemclaim ==FULL_REFUND)
		return 'Full Refund Claimed';
	else if ($problemclaim ==PARTIAL_REFUND)
		return 'Partial Refund Claimed';
	else
		return 'No Claim';
}

function get_escrow_status($int_status)
{
	if ($int_status==0)
		return 'Not paid yet';
	else if ($int_status==1)
		return 'Paid, Bitcoins pending';
	else if ($int_status==2)
		return 'Paid, Bitcoins released';
	else if ($int_status==3)
		return 'Finished';
	else if ($int_status==4)
		return 'Paid, but problem reported!';
	else if ($int_status==5)
		return 'Bitcoins returned to the buyer';
	else if ($int_status==6)
		return 'Bitcoins partialy returned to the buyer';
	else if ($int_status==7)
		return 'Problem occured, but no return to the buyer';
}

function note_problem_occured($id)
{
	global $forum_db;
	$query = array(
		'UPDATE' 	=> 'escrows AS e',
		'SET'		=>	'e.problemoccured=1',
		'WHERE'		=>	'e.index = '.intval($id)
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);	
}

function find_escrow_by_id($id)
{
	global $forum_db;
	$query = array(
		'SELECT'	=>	'e.*',
		'FROM'		=>	'escrows AS e',
		'WHERE'		=>	'e.index='.intval($id)
	);
	$result =$forum_db->query_build($query);
	$row = $forum_db->fetch_assoc($result);	
	return $row;
}

function find_escrow_status_by_id($id)
{
	global $forum_db;
	$query = array(
		'SELECT'	=>	'e.status',
		'FROM'		=>	'escrows AS e',
		'WHERE'		=>	'e.index='.intval($id)
	);
	$result =$forum_db->query_build($query);
	$row = $forum_db->fetch_assoc($result);	
	return $row['status'];
}

function set_escrow_moderatorid($id, $moderatorid)
{
	global $forum_db;
	$query = array(
		'UPDATE' 	=> 'escrows AS e',
		'SET'		=>	'e.moderatorid='.intval($moderatorid),
		'WHERE'		=>	'e.index = '.intval($id)
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);	
}

function get_forum_id_by_problemid($id)
{
	global $forum_db;
	$query = array(
		'SELECT'	=>	't.forum_id',
		'FROM'		=>	'topics AS t',
		'WHERE'		=>	't.id='.intval($id)
	);
	$result =$forum_db->query_build($query);
	$row = $forum_db->fetch_assoc($result);	
	return $row['forum_id'];
}

function find_escrow_info_by_problemid($id)
{
	global $forum_db;
	$query = array(
		'SELECT'	=>	'e.*',
		'FROM'		=>	'escrows AS e',
		'WHERE'		=>	'e.problemid='.intval($id)
	);
	$result =$forum_db->query_build($query);
	$row = $forum_db->fetch_assoc($result);	
	return $row;	
}
function get_user_info($id)
{
	global $forum_db;

	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'users',
		'WHERE'		=> 'id='.intval($id),
	);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$row = $forum_db->fetch_assoc($result);
	return $row;
}

function get_username($id)
{
	global $forum_db;

	$query = array(
		'SELECT'	=> 'username',
		'FROM'		=> 'users',
		'WHERE'		=> 'id='.intval($id),
	);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	$row = $forum_db->fetch_assoc($result);
	if ($row)
		$username = $row['username'];
	else
		$username = '';

	return $username;
}

function clear_pm_cache($id)
{
	global $forum_db, $forum_user;

	$query = array(
		'UPDATE'	=> 'users',
		'SET'		=> 'pun_pm_new_messages = NULL',
		'WHERE'		=> 'id = '.intval($id),
	);

	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	if ($forum_user['id'] == $id)
		unset($forum_user['pun_pm_new_messages']);
}

function set_escrow_problemid($id, $new_tid)
{
	global $forum_db;
	$query = array(
		'UPDATE' 	=> 'escrows AS e',
		'SET'		=>	'e.problemid='.intval($new_tid),
		'WHERE'		=>	'e.index = '.intval($id)
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);	
}

function escrow_note_problem_reason_and_claim($index,$claim_reason ,$claim_action)
{
	global $forum_db;
	$query = array(
		'UPDATE' 	=> 'escrows AS e',
		'SET'		=>	'e.problemreason='.intval($claim_reason).' , e.problemclaim='.intval($claim_action),
		'WHERE'		=>	'e.index = '.intval($index)
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
}

function notify_payment_received($btcaddress, $amount, $balance, $escrowinfo)
{
	global $forum_db, $forum_url, $forum_config, $forum_flash, $lang_pun_pm, $lang_escrows;
	$errors=array();
	
	$sellerid = intval($escrowinfo['sellerid']);
	$buyerid =  intval($escrowinfo['buyerid']);
	$escrowdeclaredamount = $escrowinfo['amount']; 
	$escrowsubject = $escrowinfo['subject'];
	
	$buyername = get_username($buyerid);
	$sellername= get_username($sellerid);

	$buyermessage = sprintf($lang_escrows['Buyer payment received message'],
							$buyername, $amount, $escrowsubject , $sellername);
	$buyersubject = sprintf($lang_escrows['Buyer payment received subject'],
							$amount, $sellername);
	
	$sellermessage= sprintf($lang_escrows['Seller payment received message'],
							$buyername, $amount, $escrowsubject , $sellername);
							
	$sellersubject= sprintf($lang_escrows['Seller payment received subject'],
							$amount, $buyername);
	escrows_send_messages($buyerid,$sellerid,$buyersubject,$sellersubject,$buyermessage,$sellermessage);
}						

function escrow_notify_payment_send($receiverid , $amount, $address, $txhash)
{
	global $forum_db, $forum_url, $forum_config, $forum_flash, $lang_pun_pm, $lang_escrows;
	$message = sprintf($lang_escrows['Payment send message'],get_username($receiverid),$amount,$address,$txhash);
	$subject = $lang_escrows['Payment send subject'];
	escrows_send_messages(0, $receiverid, '', $subject, '', $message);
}

function escrows_send_messages($buyerid,$sellerid,$buyersubject,$sellersubject,$buyermessage,$sellermessage)
{
	global $forum_db; 
	
	$now = time();
	if (count($buyermessage))
	{
		// ############ Send buyer message 
		$query = array(
			'INSERT'		=> 'sender_id, receiver_id, status, lastedited_at, read_at, subject, body',
			'INTO'			=> 'pun_pm_messages',
			'VALUES'		=> '0'.', '.intval($buyerid).', \'sent\', '.intval($now).', 0, \''.$forum_db->escape($buyersubject).'\', \''.$forum_db->escape($buyermessage).'\''
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	}
		
	// ############ Send seller message
	if (count($sellermessage))
	{
		$query = array(
			'INSERT'		=> 'sender_id, receiver_id, status, lastedited_at, read_at, subject, body',
			'INTO'			=> 'pun_pm_messages',
			'VALUES'		=> '0'.', '.intval($sellerid).', \'sent\', '.intval($now).', 0, \''.$forum_db->escape($sellersubject).'\', \''.$forum_db->escape($sellermessage).'\''
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	}
	clear_pm_cache($sellerid);
	clear_pm_cache($buyerid);
}

function escrow_notify_problem_occured($escrowinfo)
{
	global $forum_db, $forum_url, $forum_config, $forum_flash, $lang_pun_pm, $lang_escrows;
	$errors=array();
	
	$sellerid = $escrowinfo['sellerid'];
	$buyerid =  $escrowinfo['buyerid'];
	$escrowdeclaredamount = $escrowinfo['amount']; 
	$escrowsubject = $escrowinfo['subject'];
	
	$buyername = get_username($buyerid);
	$sellername= get_username($sellerid);

	$buyermessage = sprintf($lang_escrows['Buyer problem reported message'],
							$escrowinfo['subject'], $escrowinfo['amount']);
							
	$buyersubject = $lang_escrows['Buyer problem reported subject'];
	
	$sellermessage= sprintf($lang_escrows['Seller problem reported message'],
							$buyername, $escrowinfo['subject'], $escrowinfo['amount']);
							
	$sellersubject= $lang_escrows['Seller problem reported subject'];
							
	escrows_send_messages($buyerid,$sellerid,$buyersubject,$sellersubject,$buyermessage,$sellermessage);
}

function escrow_notify_problem_resolved($escrowinfo)
{
	global $forum_db, $forum_url, $forum_config, $forum_flash, $lang_pun_pm, $lang_escrows;
	$errors=array();
	
	$payoutamount = escrow_get_payout_amount($escrowinfo);
	
	$sellerid = $escrowinfo['sellerid'];
	$buyerid =  $escrowinfo['buyerid'];
	$escrowdeclaredamount = $escrowinfo['amount']; 
	$escrowsubject = $escrowinfo['subject'];
	
	$buyername = get_username($buyerid);
	$sellername= get_username($sellerid);

	$buyermessage = sprintf($lang_escrows['Buyer problem resolved message'],$escrowinfo['subject'],
					$payoutamount, get_username($escrowinfo['moderatorid']));
							
	$buyersubject = $lang_escrows['Buyer problem resolved subject'];
	
	$sellermessage= sprintf($lang_escrows['Seller problem resolved message'],$escrowinfo['subject'],
					$payoutamount, get_username($escrowinfo['moderatorid']));
							
	$sellersubject= $lang_escrows['Seller problem resolved subject'];
							
	escrows_send_messages($buyerid,$sellerid,$buyersubject,$sellersubject,$buyermessage,$sellermessage);	
}

function escrow_get_moderator_earning($escrowinfo)
{
	global $forum_config;
	$recivedamount = find_address_balance_by_address($escrowinfo['btcaddress']);
	if($escrowinfo['problemoccured']==1)
	{
		$moderatorearning = $forum_config['o_problem_commission_value']/100*$recivedamount;
		return $moderatorearning; 
	}
	else
	{
		return 0;	
	}
}

function escrow_get_payout_amount($escrowinfo)
{
	global $forum_db;
	$recivedamount = find_address_balance_by_address($escrowinfo['btcaddress']);
	
	$query = array(
		'SELECT' => 'c.conf_value',
		'FROM'	=>	'config AS c',
		'WHERE'=> 'c.conf_name=\'o_regular_commission_value\''
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$row = $forum_db->fetch_assoc($result);	
	$regular_commission = $row['conf_value'];
	
	
	$query = array(
		'SELECT' => 'c.conf_value',
		'FROM'	=>	'config AS c',
		'WHERE'=> 'c.conf_name=\'o_problem_commission_value\''
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$row = $forum_db->fetch_assoc($result);	
	$problem_commission	= $row['conf_value'];

	if ($escrowinfo['problemoccured']==1)  // jesli wystapil problem to wieksza prowizja
		$payoutamount = $recivedamount*(1.0-($regular_commission+$problem_commission)/100.0);
	else
		$payoutamount = $recivedamount*(1.0-($regular_commission)/100.0);
	
	return $payoutamount;
}
function escrow_get_seller_address($escrowinfo)
{
	global $forum_db;
	$query = array(
		'SELECT'	=>	'e.sellerid',
		'FROM'		=>	'escrows AS e',
		'WHERE'		=>	'e.index='.intval($escrowinfo['index'])
	);
	$result =$forum_db->query_build($query);
	$row = $forum_db->fetch_assoc($result);	
	
	$query = array(
		'SELECT'	=> 'u.btcaddress',
		'FROM'		=> 'users AS u',
		'WHERE'		=> 'u.id='.intval($row['sellerid']),
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$row = $forum_db->fetch_assoc($result);
	return $row['btcaddress'];
}

function escrow_get_payout_address_link($escrowinfo)
{
	
	if ($escrowinfo['status']==ESCROW_FINISHED or $escrowinfo['status']==NO_BITCOIN_RETURN or 
			$escrowinfo['status']==BITCOINS_RELEASED)
	{
		$return_string = "<a href=http://blockchain.info/address/".
			get_user_info($escrowinfo['sellerid'])['btcaddress'].">"."(click)</a>";
		
	}
	else if ($escrowinfo['status']==PARTIAL_BITCOIN_RETURN)
	{
		$return_string = "<a href=http://blockchain.info/address/".
			get_user_info($escrowinfo['sellerid'])['btcaddress'].">"."(click)</a>";
		$return_string=$return_string."  <a href=http://blockchain.info/address/".
			get_user_info($escrowinfo['buyerid'])['btcaddress'].">"."(click)</a>";
	}
	else if ($escrowinfo['status']==FULL_BITCOIN_RETURN)
	{
		$return_string ="<a href=http://blockchain.info/address/".
			get_user_info($escrowinfo['buyerid'])['btcaddress'].">"."(click)</a>";
	}
	else
	{
		return '---------------';
	}
	return $return_string;
}

function escrow_get_address_balance($address)
{
	global $forum_db;
	$query = array(
		'SELECT'	=>	'balance',
		'FROM'		=>	'btcaddresses',
		'WHERE'		=>	'btcaddress=\''.$address.'\''
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$row = $forum_db->fetch_assoc($result);	
	return $row['balance'];
}
function escrow_update_address_balance($address, $balance)
{
	global $forum_db;
	$query = array(
		'UPDATE'	=> 'btcaddresses',
		'SET'		=> 'balance = '.floatval($balance),
		'WHERE'		=> 'btcaddress = \''.$address.'\'',
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);	
}

function get_free_btcaddress(&$errors)
{
	global $forum_db;
	
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'btcaddresses',
		'WHERE'		=> 'status=0',
		'LIMIT'		=>'0, 1',
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$row = $forum_db->fetch_assoc($result);	
	if ($row)
	{
		//zajmij adres
		change_address_status($row['id'],ADDRESS_TAKEN);
		return $row['btcaddress'];
	}
	else
	{
		$errors[] = "Sorry,  no free addresses avalable, please try later";
		/*
		//wygeneruj nowy adres
		$btcescrowaddress= generate_new_bitcoin_address($label);
		//if (!is_valid_btcaddress($btcescrowaddress))
			$errors[] = 'Could not connect with bitcoin network, please try again in a few minutes';	

		//jesli sie udalo
		if ($btcescrowaddress)
		{
			//zapisz do bazy nowy adres i zajmij adres
			save_new_btcaddress($btcescrowaddress, ADDRESS_TAKEN);
			//zwroc adres
			return $btcescrowaddress;
		}
		else //jesli sie nie udalo
		{
			$errors[] = $lang_pun_pm['Sorry, service not avalable, please try again in one hour'];
		}
		*/
	}
}

function get_bitcoin_data($json_url)
{
    $json_data = file_get_contents($json_url);
    $json_feed = json_decode($json_data);
    return $json_feed;
}
// nie dziala bo potrzebne jest 2 haslo a 2 hasla nie mozna trzymac na serwerze
function generate_new_bitcoin_address($label)
{
	global $blockchainUserRoot , $blockchainpassword1;
    $json_url = $blockchainUserRoot.'new_address?password='.$blockchainpassword1.'&label='.$label;
	$response = get_bitcoin_data($json_url);
	return $response->address;
}

function escrow_free_escrow_address($address, $balance, $secondarypassword)
{
	global $outgoingaddress , $blockchainmovebetweenwalletsfee;
	//$balance = escrow_get_address_balance($address);
	if ($balance > 5*$blockchainmovebetweenwalletsfee)
	{
		$tx_hash = escrow_make_bitcoin_payment($outgoingaddress, 
			amount($balance-$blockchainmovebetweenwalletsfee) , $address, $secondarypassword);
		if($tx_hash)
		{
			change_address_status_by_address($address, ADDRESS_FREE);
			escrow_update_address_balance($address,0);
		}
	}
	else
	{
		change_address_status_by_address($address, ADDRESS_FREE);
	}
}

function save_new_btcaddress($address, $status)
{
	global $forum_db;
	$query = array(
		'INSERT'		=> 'status, btcaddress',
		'INTO'			=> 'btcaddresses',
		'VALUES'		=> intval($status).','.$address,
		);
	$forum_db->query_build($querry) or error(__FILE__, __LINE__);
}

function change_address_status($id, $status)
{
	global $forum_db;
	$query = array(
		'UPDATE'	=> 'btcaddresses',
		'SET'		=> 'status = '.intval($status),
		'WHERE'		=> 'id = '.intval($id),
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
}

function change_address_status_by_address($address, $status)
{
	global $forum_db;
	$query = array(
		'UPDATE'	=> 'btcaddresses',
		'SET'		=> 'status = '.intval($status),
		'WHERE'		=> 'btcaddress = \''.$address.'\'',
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
}

function escrow_change_payment_status($id, $status)
{
	global $forum_db;
	$query = array(
		'UPDATE'	=>'payouts AS p',
		'SET'		=>'p.status='.intval($status),
		'WHERE'		=>'p.index ='.intval($id)
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
}

function escrow_payout_insert_transaction_hash($id, $txhash)
{
	global $forum_db;
	$query = array(
		'UPDATE'	=>'payouts AS p',
		'SET'		=>'p.txhash=\''.$txhash.'\'',
		'WHERE'		=>'p.index ='.intval($id)
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
}

function change_escrow_status($id, $status)
{
	global $forum_db;
	$query = array(
		'UPDATE' 	=> 	'escrows AS e',
		'SET'		=>	'e.status = '.intval($status),
		'WHERE'		=>	'e.index = '.intval($id),
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
}

function escrow_set_received_bitcoins_time($id, $recivedtime)
{
	global $forum_db; 
	$query = array(
		'UPDATE' 	=> 	'escrows AS e',
		'SET'		=>	'e.recivedtime ='.intval($recivedtime),
		'WHERE'		=>	'e.index = '.intval($id)
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
}

function find_escrow_by_address($btcaddress)
{
	global $forum_db;
	$query = array(
		'SELECT'	=>	'*',
		'FROM'		=>	'escrows',
		'WHERE'		=>	'btcaddress=\''.$btcaddress.'\' AND status!='.ESCROW_FINISHED.' AND status!='.NO_BITCOIN_RETURN.' AND status!='.PARTIAL_BITCOIN_RETURN.' AND status!='.FULL_BITCOIN_RETURN
	);
	$result = $forum_db->query_build($query);
	$row = $forum_db->fetch_assoc($result);	
	return $row;
}

function update_btcaddresses()
{
	global $blockchainUserRoot, $blockchainpassword1;
    $json_url = $blockchainUserRoot.'list?password='.$blockchainpassword1;
    $json_data = file_get_contents($json_url);
    $addressesinfo = json_decode($json_data)->addresses;
    $number_of_addresses = count($addressesinfo);
    if($number_of_addresses>1)
    {
		for( $i=0 ; $i<$number_of_addresses; $i++)
		{
			$btcaddressinfo = $addressesinfo[$i];
			$balance = satoshi2bitcoin($btcaddressinfo->balance);
			$btcaddress= $btcaddressinfo->address;
			$id =find_address_id($btcaddress);
			$old_balance = find_address_balance($id);	
			
			if ($balance > $old_balance)    //otrzymano pewna kwote
			{
				$escrowinfo = find_escrow_by_address($btcaddress);
				if ($escrowinfo['status']==ESCROW_STARTED) //zaznacz, ze otrzymano pierwsza wplate
					{
					$now = time();
					change_escrow_status($escrowinfo['index'], BITCOINS_RECEIVED);
					escrow_set_received_bitcoins_time($escrowinfo['index'], $now);
					}
				// wysylam wiadomosc do sprzedawcy i odbiorcy ,ze wplata zostala zaksiegowana
				$amount = $balance - $old_balance;
				notify_payment_received($btcaddress, $amount ,$balance, $escrowinfo);
				update_address_balance($id, $balance);
			}
			else if ($balance != $old_balance)
			{
				update_address_balance($id, $balance);
			}
		}
	}
}

function escrow_update_old_active_escrows()
{
	//finsih active escrows
	global $forum_db, $forum_config;
	$now = time();
	$time_delta = $forum_config['o_escrow_duration']*60*60;
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'escrows AS e',
		'WHERE'		=> 'e.status='.BITCOINS_RECEIVED
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($result))
	{
		if ($row['time']+$time_delta < $now) // if its an old escrow
		{
			change_escrow_status($row['index'], BITCOINS_RELEASED);
		}
	}		
	//finish escrows that ware declared but not paid - free addresses
	
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'escrows AS e',
		'WHERE'		=> 'e.status='.ESCROW_STARTED
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($result))
	{
		if ($row['time']+$time_delta < $now) // if its an old escrow
		{
			change_escrow_status($row['index'], ESCROW_FINISHED);
			change_address_status_by_address($row['btcaddress'], ADDRESS_FREE);
		}
	}
}

function find_address_id($address)
{
	global $forum_db;
	
	$query = array(
		'SELECT'	=> 'id',
		'FROM'		=> 'btcaddresses',
		'WHERE'		=> 'btcaddress=\''.$address.'\'',
		'LIMIT'		=>'0, 1'
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$row = $forum_db->fetch_assoc($result);	
	return $row['id'];
}

function update_address_balance($id, $balance)
{
global $forum_db;

	$query = array(
		'UPDATE'	=> 'btcaddresses',
		'SET'		=> 'balance = '.floatval($balance),
		'WHERE'		=> 'id = '.intval($id),
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);	
}

function find_address_balance($id)
{
	global $forum_db;
	
	$query = array(
		'SELECT'	=> 'balance',
		'FROM'		=> 'btcaddresses',
		'WHERE'		=> 'id='.intval($id),
		'LIMIT'		=>'0, 1',
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$row = $forum_db->fetch_assoc($result);	
	return $row['balance'];
}
function find_address_balance_by_address($address)
{
	global $forum_db;
	
	$query = array(
		'SELECT'	=> 'balance',
		'FROM'		=> 'btcaddresses',
		'WHERE'		=> 'btcaddress=\''.$address.'\'',
		'LIMIT'		=>'0, 1',
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$row = $forum_db->fetch_assoc($result);	
	return $row['balance'];
}

function amount($amount)
{
    //changes form bitcoin to satoshi
    return 100000000*$amount;
}
function satoshi2bitcoin($amount)
{
	//changes from satoshi to bitcoin
	return $amount/100000000;
}

function escrow_make_bitcoin_payment($addressTo , $amount, $addressFrom=0, $secondarypassword)
{
	global $blockchainUserRoot , $blockchainpassword1;
    $json_url = $blockchainUserRoot.'payment?password='.$blockchainpassword1.'&second_password='.$secondarypassword.'&to='.$addressTo.'&amount='.$amount;
    if ($addressFrom!=0)
    {    $json_url = $json_url.'&from='.$addressFrom; }
    print_r($json_url);
    $json_feed = get_bitcoin_data($json_url);
    print_r($json_feed);
    return $json_feed->tx_hash; 
}

function escrow_note_new_payout($time, $receiverid , $amount , $btcaddress  , $escrowid)
{
	global $forum_db;
	$query = array(
		'INSERT' 	=> 'time , receiverid , amount , btcaddress , escrowid',
		'INTO'		=> 'payouts',
		'VALUES'	=> intval($time).', '.intval($receiverid).', '.floatval($amount).', \''.$btcaddress.'\', '.intval($escrowid)
		);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
}

function escrow_note_moderator_earnings($id , $amount)
{
	global $forum_db;
	$query = array(
		'SELECT' =>	'm.currentpayout, m.totalpayout',
		'FROM'	=>	'moderator_payouts AS m',
		'WHERE'	=>	'm.id ='.intval($id)
	);
	$result =$forum_db->query_build($query) or error(__FILE__, __LINE__);
	if (mysql_num_rows($result)==0)
	{
		echo "dodaje do tabeli moderator_payouts";
		$query = array(
			'INSERT'	=>	'id, currentpayout , totalpayout',
			'INTO'		=>	'moderator_payouts',
			'VALUES'	=>	intval($id).', '.floatval($amount).', '.floatval($amount)
		);		
		$forum_db->query_build($query) or error(__FILE__, __LINE__);
	}
	else
	{
		echo "aktualizuje moderator_payouts";
		$row = $forum_db->fetch_assoc($result);
		$newcurrent_payout = $row['currentpayout']+$amount;
		$totalpayout		=$row['totalpayout']+$amount;
		
		$query = array(
			'UPDATE' 	=> 'moderator_payouts AS m',
			'SET'		=>	'm.currentpayout='.floatval($newcurrent_payout).', m.totalpayout='.floatval($totalpayout),
			'WHERE'		=>	'm.id = '.intval($id)
		);
		$forum_db->query_build($query) or error(__FILE__, __LINE__);
	}
}

function escrow_moderator_get_currentpayout($id)
{
	global $forum_db;
	$query = array(
		'SELECT' =>	'm.currentpayout',
		'FROM'	=>	'moderator_payouts AS m',
		'WHERE'	=>	'm.id ='.intval($id)
	);
	$result =$forum_db->query_build($query) or error(__FILE__, __LINE__);
	if (mysql_num_rows($result)!=0)
	{
		$row = $forum_db->fetch_assoc($result);
		return $row['currentpayout'];
	}
	else
	{
		return 0;
	}
}
function escrow_update_moderator_currentpayout($id, $amount)
{
	global $forum_db;
	$query = array(
		'UPDATE' 	=> 'moderator_payouts AS m',
		'SET'		=>	'm.currentpayout='.floatval($amount),
		'WHERE'		=>	'm.id = '.intval($id)
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
}

function escrow_moderator_get_totalpayout($id)
{
	global $forum_db;
	$query = array(
		'SELECT' =>	'm.totalpayout',
		'FROM'	=>	'moderator_payouts AS m',
		'WHERE'	=>	'm.id ='.intval($id)
	);
	$result =$forum_db->query_build($query) or error(__FILE__, __LINE__);
	if (mysql_num_rows($result)!=0)
	{
		$row = $forum_db->fetch_assoc($result);
		return $row['totalpayout'];
	}
	else
	{
		return 0;
	}
}

function pun_pm_is_valid_pubkey($pubkey)
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

function is_valid_subject($subject)
{
	$maximal_len =255;

	$forbidden_chars = array(";","$","*","!","\'","\"");
	$minimal_lenght = 7;
	
	$errors_sum =0;
	//check pubkey lenght
	if (strlen($subject)<$minimal_lenght)
		$errors_sum=$errors_sum+1;	
		

	//check if forbidden chars occure
	foreach ($forbidden_chars as &$forbidden_character)
		{
		if (strpos($subject, $forbidden_character))
			$errors_sum=$errors_sum+1;	
		}
	//result of checking
	if ($errors_sum ==0)
		return true;
	else
		return false;
}

//
function encrypt_message($message , $pubkey, $email)
{
	$temp_file_url = '../cache/gpgtemp.php';
	if ($pubkey!='')
	{
		file_put_contents($pubkey, $temp_file_url);
		$result = shell_exec("gpg --trust-model always --yes --import $temp_file_url");
		file_put_contents($message,$temp_file_url);
		$result = shell_exec("gpg --trust-model always --yes -q -e -a -r $email  -o /dev/stdout $temp_file_url");
	}
	else
	{
		$result =$message;
	}
	return $result;
}
?>
