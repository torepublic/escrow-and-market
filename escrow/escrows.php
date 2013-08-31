<?php
error_reporting(E_ALL);
ini_set("display_errors", 1); 
if (!defined('FORUM_ROOT'))
	define('FORUM_ROOT', './');
require FORUM_ROOT.'include/common.php';

($hook = get_hook('ul_start')) ? eval($hook) : null;

if ($forum_user['g_read_board'] == '0')
	message($lang_common['No view']);
else if ($forum_user['g_view_users'] == '0')
	message($lang_common['No permission']);

// Load the userlist.php language file
require FORUM_ROOT.'lang/'.$forum_user['language'].'/userlist.php';
require FORUM_ROOT.'lang/'.$forum_user['language'].'/escrows.php';
define('FORUM_PAGE', 'escrows');
require FORUM_ROOT.'header.php';
//odswiezam dane

// START SUBST - <!-- forum_main -->

if ($forum_user['is_guest'])
{
	header('Location: login.php');
	exit;
}
ob_start();
$now = time();
if (!isset($_SESSION['addresses_updated']) or $now%3==1 )
{
	update_btcaddresses();
	escrow_update_old_active_escrows();
	$_SESSION['addresses_updated']=1;
}

if ($forum_user['is_admmod'] and !isset($_GET['action']))
{
	//wyswietl liste zlecen wyplaty TYLKO DLA ADMINA
	if ($forum_user['g_id'] == FORUM_ADMIN)
	{	
		$query = array(
			'SELECT'	=> '*',
			'FROM'		=> 'payouts AS p',
			'WHERE'		=> 'p.status=0'
			);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		?>
		<div class="main-head">
				<h2 class="hn"><span><?php echo $lang_escrows['Payout orders']; ?></span></h2>
		</div>
		<table border="1">
			<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?><?php if ($forum_page['item_count'] == 1) echo ' row1'; ?>">
				<td width="4%">Date</td>
				<td>Receiver name</td>
				<td width="4%">Amount</td>
				<td width="4%">Address</td>
				<td width ="4%">Escrow details</td>
				<td width ="14%">Action 
			<?php if (mysql_num_rows($result)>1)
					{ ?>
				<a href ="<?php echo FORUM_ROOT.'escrows.php?action=send_all_payouts'; ?>"> <b>(Pay all)</b></a>
			<?php	}?>
					</td>
			</tr>
		
			<?php
		
			//'time, receiverid, amount, btcaddress, status, escrowid,'
			while ($row = mysql_fetch_assoc($result))
			{
				$escrowinfo = find_escrow_by_id($row['escrowid']);
				if($escrowinfo['moderatorid'])
					$moderatorstring = "<a href=".FORUM_ROOT."profile.php?id=".$escrowinfo['moderatorid'].">".get_username($escrowinfo['moderatorid'])."</a>";
				else
					$moderatorstring = $lang_escrows['Moderation not requested'];
		
				$receivername =get_username($row['receiverid']);
				echo "<tr><td >".date('y/m/d',$row['time'])."</td>
				<td><a href=".FORUM_ROOT."profile.php?id=".$row['receiverid'].">".$receivername."</a></td>
				<td >".$row['amount']."</td>
				<td ><a href=http://blockchain.info/address/".$row['btcaddress'].">"."(Click)</a></td>
				<td><a href=".FORUM_ROOT."escrows.php?action=detail&id=".$row['escrowid']."&action=take_moderator_action>(Click)</a></td>";
				?>
				<td><a href ="<?php echo FORUM_ROOT.'escrows.php?id='.$row['index'].'&action=send_payout'; ?>"> <b>(Click to send)</b></a></td>
				<?php
				echo "</tr>";
			}
			?>
		</table>
		<?php
		}
	//wyswietl liste linkow do nowych problemow 
	
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'escrows AS e',
		'WHERE'		=> 'e.status='.PROBLEM_REPORTED
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	?>
	</br>
	<div class="main-head">
			<h2 class="hn"><span><?php echo $lang_escrows['New escrow problems']; ?></span></h2>
	</div>
	<table border="1">
		<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?><?php if ($forum_page['item_count'] == 1) echo ' row1'; ?>">
			<td width="4%">Date</td>
			<td>Buyer name</td>
			<td>Seller name</td>
			<td width="3%">Amount</td>
			<td width="4%">Recived</td>
			<td width="4%">Address</td>
			<td width ="12%">Moderator</td>
			<td width ="14%">Action</td>
		</tr>
		<?php
		//'time, receiverid, amount, btcaddress, status, escrowid,'
		while ($row = mysql_fetch_assoc($result))
		{
			$addressid = find_address_id($row['btcaddress']);
			$addressbalance = find_address_balance($addressid);
			//$receivername =get_username($row['buyerid']);
			echo "<tr><td width=\"4%\">".date('y/m/d',$row['time'])."</td>
			<td><a href=".FORUM_ROOT."profile.php?id=".$row['buyerid'].">".get_username($row['buyerid'])."</a></td>
			<td><a href=".FORUM_ROOT."profile.php?id=".$row['sellerid'].">".get_username($row['sellerid'])."</a></td>
			<td width=\"3%\">".$row['amount']."btc</td>
			<td >".$addressbalance."btc</td>
			<td width=\"4%\"><a href="."http://blockchain.info/address/".$row['btcaddress'].">".'(click)'."</a></td>
			<td><a href=".FORUM_ROOT."profile.php?id=".$row['moderatorid'].">".get_username($row['moderatorid'])."</a></td>"
			?>
			<td><a href="<?php echo FORUM_ROOT.'escrows.php?id='.$row['index'].'&action=take_moderator_action'; ?>"><b><?php echo $lang_escrows['Take moderator action'] ?></b></a></td>
			<?php
			echo "</tr>";
	}
		
		?>
	</table>
	<?php
	
	// wyswietl wszystkie biezace escrowy gdzie przyszly pieniadze
 	
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'escrows AS e',
		'WHERE'		=> 'e.status='.BITCOINS_RECEIVED.' OR e.status='.PROBLEM_REPORTED,
		'ORDER BY'	=>	'time DESC'
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	?>
	</br>
	<div class="main-head">
			<h2 class="hn"><span><?php echo $lang_escrows['Current escrows']; ?></span></h2>
	</div>
	<table border="1">
		<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?><?php if ($forum_page['item_count'] == 1) echo ' row1'; ?>">
			<td width="4%">Date</td>
			<td>Buyer name</td>
			<td>Seller name</td>
			<td width="4%">Amount</td>
			<td width="4%">Address</td>
			<td width ="12%">Status</td>
			<td width ="14%">Moderator</td>
		</tr>
		<?php
		//'time, receiverid, amount, btcaddress, status, escrowid,'
		while ($row = mysql_fetch_assoc($result))
		{
			//$escrowinfo = find_escrow_by_id($row['']);
			//$receivername =get_username($row['buyerid']);
			if($row['moderatorid'])
				$moderatorstring = "<a href=".FORUM_ROOT."profile.php?id=".$row['moderatorid'].">".get_username($row['moderatorid'])."</a>";
			else
				$moderatorstring = $lang_escrows['Moderation not requested'];
				
			echo "<tr><td>".date('y/m/d',$row['time'])."</td>
			<td><a href=".FORUM_ROOT."profile.php?id=".$row['buyerid'].">".get_username($row['buyerid'])."</a></td>
			<td><a href=".FORUM_ROOT."profile.php?id=".$row['sellerid'].">".get_username($row['sellerid'])."</a></td>
			<td >".$row['amount']."btc</td>
			<td ><a href="."http://blockchain.info/address/".$row['btcaddress'].">".'(click)'."</a></td>
			<td>".get_escrow_status($row['status'])."</td>
			<td>".$moderatorstring."</td></tr>";
	}	
		$_SESSION['currentpayout'] = escrow_moderator_get_currentpayout($forum_user['id']);
		?>
	</table>
	
	<!-- tabela z zarobkami moderatora -->
			<div class="main-head">
				<h2 class="hn"><span><?php echo $lang_escrows['Moderator earnings']; ?></span></h2>
		</div>
		<table border="1">
			<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?><?php if ($forum_page['item_count'] == 1) echo ' row1'; ?>">
				<td>Currently earned</td>
				<td>Total earned</td>
				<td>Minimum payout amount: <?php echo $forum_config['o_minimum_escrow_value'];?>BTC</td>
			</tr>
			<tr>
				<td><?php echo $_SESSION['currentpayout']; ?> BTC</td>
				<td><?php echo escrow_moderator_get_totalpayout($forum_user['id']); ?> BTC</td>
				<td>
				<?php if ($_SESSION['currentpayout']>$forum_config['o_minimum_escrow_value'])
					  { ?>
					<a href="<?php echo FORUM_ROOT.'escrows.php?action=moderator_payout';?>"><b>Request earning payout</b></a>
				<?php }else
					  {?>
					<b>To small amount to payout</b>
				<?php } ?>
				</td>
			</tr>
		</table>
	<?php
}
else if (isset($_GET['action']) and $_GET['action']=='moderator_payout' and $forum_user['is_admmod'] and
	$_SESSION['currentpayout'] > $forum_config['o_minimum_escrow_value'])
{
	if (isset($_GET['answer']) and $_GET['answer']=='yes')
	{
		$now =time();
		escrow_note_new_payout($now,$forum_user['id'],$_SESSION['currentpayout'],$forum_user['btcaddress'],-1);
		escrow_update_moderator_currentpayout($forum_user['id'], 0);
		echo "<p>Thanks, please wait for admins payout</p>";
		$_SESSION['currentpayout']=0;
	}
	else
	{
?>
	<div class="main-head">
		<h2 class="hn"><span><?php echo "<b>".$lang_escrows['Are you sure you want to payout your earnings?']."</b>";?></span></h2>
	</div>
	<div>
	<table>
		<tr>
			<td width="80%">
				<p><?php echo $lang_escrows['If you are sure']?></p>
			</td>
			<td>
				<span class="submit primary">
					<a href="<?php echo FORUM_ROOT.'escrows.php?action=moderator_payout&answer=yes'; ?>"><?php echo "<b><u>".$lang_escrows['Confirm']."</u></b>"; ?></a>
				</span>
			</td>
		</tr>
	</table>
	</div>	
<?php
	}
}
else if (isset($_GET['action']) and $_GET['action']=='send_all_payouts' and $forum_user['g_id'] == FORUM_ADMIN)
{
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'payouts AS p',
		'WHERE'		=> 'p.status=0'
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);	
	$payoutsum =0;
	while ($row = mysql_fetch_assoc($result))
	{
		$payoutsum = $payoutsum + $row['amount'];
	}
	?>	
	<div class="main-head">
		<h2 class="hn"><span><?php echo "<b>".$lang_escrows['Are you sure you want to payout']." ".$payoutsum." BTC</b>";?></span></h2>
	</div>
	<span class="submit primary">
	<FORM METHOD="POST" ACTION="<?php echo FORUM_ROOT.'escrows.php?action=confirm_send_all_payouts'; ?>">
	<div>
		<table>
			<tr>
			<td >
				<p><?php echo $lang_escrows['Secondary blockchain password'];?></p>
			</td>
			<td>
				<INPUT TYPE="text" NAME='req_blockchain_password' >
			</td>
			</tr>
			
			<tr>
				<td>				
				<INPUT TYPE="submit" VALUE="<?php echo $lang_escrows['Accept'] ;?>">
				</td>
				<td>
					<a href=<?php echo FORUM_ROOT.'escrows.php';?>><?php echo $lang_escrows['Go back'] ?></a>
				</td>
			</tr>
		</table>
	</div>
	</FORM></span>
	<?php
}

else if (isset($_GET['action']) and $_GET['action']=='confirm_send_all_payouts' and $forum_user['g_id'] == FORUM_ADMIN and
	isset($_POST['req_blockchain_password']))
{
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'payouts AS p',
		'WHERE'		=> 'p.status=0'
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$response_string = '<table>';	
	$response_string_end = '</table>';	
	
	while ($row = mysql_fetch_assoc($result))
	{
		$escrowinfo = find_escrow_by_id($row['escrowid']);
		$uri = $blockchainUserRoot."payment?password=".$blockchainpassword1."&second_password=".$_POST['req_blockchain_password']."&to=".
			$row['btcaddress']."&amount=".amount($row['amount'])."&from=".$escrowinfo['btcaddress']."&note=ToRepublic";
		$btc_data = get_bitcoin_data($uri);
		if ($btc_data->tx_hash)
		{
		$previousbalance = escrow_get_address_balance($escrowinfo['btcaddress']); //odczyt z bazy 
		$newbalance = $previousbalance -$row['amount']-$blockchainfee;
		escrow_update_address_balance($escrowinfo['btcaddress'],$newbalance);
		escrow_payout_insert_transaction_hash($row['index'],$btc_data->tx_hash);
		if ($escrowinfo['status']!=PARTIAL_BITCOIN_RETURN)  //nie moze zwalniac adresu przy pierwszej platnosci partial bo nie bedzie moglo wykonac drugiej
			escrow_free_escrow_address($escrowinfo['btcaddress'],$newbalance , $_POST['req_blockchain_password']);
		escrow_notify_payment_send($row['receiverid'],$row['amount'],$row['btcaddress'],$btc_data->tx_hash);
		escrow_change_payment_status($row['index'],PAYOUT_REALISED);
		}
		sleep(1);
		$response_string = $response_string.'<tr><td>'.$row['amount'].'</td><td>'.$btc_data->tx_hash.'</td></tr>';
	}
	echo $response_string.$response_string_end;
}

else if ( isset($_GET['action']) and $_GET['action']=='send_payout' and $forum_user['g_id'] == FORUM_ADMIN and
	isset($_GET['id']) and is_numeric($_GET['id']))
{
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'payouts AS p',
		'WHERE'		=> 'p.index='.$_GET['id']
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$row = mysql_fetch_assoc($result);	
	$payoutsum = $row['amount'];
	?>	
	<div class="main-head">
		<h2 class="hn"><span><?php echo "<b>".$lang_escrows['Are you sure you want to payout']." ".$payoutsum." BTC</b>";?></span></h2>
	</div>
	<span class="submit primary">
	<FORM METHOD="POST" ACTION="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=confirm_send_payout'; ?>">
	<div>
		<table>
			<tr>
			<td >
				<p><?php echo $lang_escrows['Secondary blockchain password'];?></p>
			</td>
			<td>
				<INPUT TYPE="text" NAME='req_blockchain_password' >
			</td>
			</tr>
			
			<tr>
				<td>				
				<INPUT TYPE="submit" VALUE="<?php echo $lang_escrows['Accept'] ;?>">
				</td>
				<td>
					<a href="<?php echo FORUM_ROOT.'escrows.php'; ?>"><?php echo $lang_escrows['Go back']; ?></a>
				</td>
			</tr>
		</table>
	</div>
	</FORM></span>
	<?php
}

else if (isset($_GET['action']) and $_GET['action']=='confirm_send_payout' and $forum_user['g_id'] == FORUM_ADMIN and
	isset($_POST['req_blockchain_password']) and isset($_GET['id']) and is_numeric($_GET['id']))
{
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'payouts AS p',
		'WHERE'		=> 'p.index='.$_GET['id']
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$response_string = '<table>';	
	$response_string_end = '</table>';
	
	$row = mysql_fetch_assoc($result);
	$escrowinfo = find_escrow_by_id($row['escrowid']);
	$uri = $blockchainUserRoot."payment?password=".$blockchainpassword1."&second_password=".$_POST['req_blockchain_password']."&to=".
			$row['btcaddress']."&amount=".amount($row['amount'])."&from=".$escrowinfo['btcaddress']."&note=ToRepublic";
	$btc_data = get_bitcoin_data($uri);  //place
	if ($btc_data->tx_hash)
	{
		$previousbalance = escrow_get_address_balance($escrowinfo['btcaddress']); //odczyt z bazy 
		$newbalance = $previousbalance -$row['amount']-$blockchainfee;
		escrow_update_address_balance($escrowinfo['btcaddress'],$newbalance);
		escrow_change_payment_status($row['index'],PAYOUT_REALISED);
		escrow_payout_insert_transaction_hash($row['index'],$btc_data->tx_hash);
		escrow_free_escrow_address($escrowinfo['btcaddress'],$newbalance , $_POST['req_blockchain_password']);
		escrow_notify_payment_send($row['receiverid'],$row['amount'],$row['btcaddress'],$btc_data->tx_hash);
	}
	$response_string = $response_string.'<tr><td>'.$row['amount'].' BTC</td><td>'.$btc_data->tx_hash.'</td></tr>';
	echo $response_string.$response_string_end;
}

else if ($forum_user['is_admmod'] and isset($_GET['id']) and is_numeric($_GET['id']) 
		and isset($_GET['action']) and ($_GET['action']=='take_moderator_action') and isset($_GET['answer']) 
		and $_GET['answer']=='yes' and isset($_SESSION['escrowinfo']) and $_SESSION['escrowinfo']['status']==PROBLEM_REPORTED
		and $escrowinfo['moderatorid']==0)
{
	$moderatorid = $forum_user['id'];
	set_escrow_moderatorid($_GET['id'], $forum_user['id']);
	redirect(FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&mod=1', $lang_post['Post redirect']);
}
//pytanie moderatora o potwierdzenie checi moderacji sporu
// oraz inne akcje dla modow
else if ($forum_user['is_admmod'] and isset($_GET['id']) and is_numeric($_GET['id']) and isset($_GET['action']) 
		and ($_GET['action']=='take_moderator_action') )
{
	$_SESSION['escrowinfo'] = find_escrow_by_id($_GET['id']);
	//jesli nie ma jeszcze moderatora tego problemu
	if ($_SESSION['escrowinfo']['moderatorid']==0 and $_SESSION['escrowinfo']['status']==PROBLEM_REPORTED)
	{
	?>
	</br>
	<div class="main-head">
			<h2 class="hn"><span><?php echo $lang_escrows['Do you really want to moderate?']; ?></span></h2>
	</div>
	<table border="1">
		<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?><?php if ($forum_page['item_count'] == 1) echo ' row1'; ?>">
			<td><a href="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=take_moderator_action&answer=yes';?>"><b>Yes</b></a></td>
			<td><a href="<?php echo FORUM_ROOT.'escrows.php';?>"><b>No</b></a></td>
		</tr>
	</table>
	<?php
	}//jesli  problem jest ogladany przez moderatora problemu
	if ($_SESSION['escrowinfo']['moderatorid']==$forum_user['id'] and $_SESSION['escrowinfo']['status']==PROBLEM_REPORTED or $forum_user['g_id'] == FORUM_ADMIN)
	{
	?>
		</br>
	<div class="main-head">
			<h2 class="hn"><span><?php echo $lang_escrows['Choose action']; ?></span></h2>
	</div>
	<table border="1">
		<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?><?php if ($forum_page['item_count'] == 1) echo ' row1'; ?>">
			
			<?php $problemlink = sprintf($lang_escrows['click to read details link'],$_SESSION['escrowinfo']['problemid']); ?>

			<td>See claim history<?php echo $problemlink;?></td>
			<!-- No refund powoduje ze kasa jest zwalniana i idzie do sprzedawcy, wymaga potwierdzenia-->
			<td><a href="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=no_refund';?>">No refund</a></td>
		</tr>
		<tr>
			<!-- Partial refund powoduje ,ze otwiera sie strona z pytaniem ile procent zrefundowac kupujacemu -->
			<td><a href="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=partial_refund';?>">Partial refund</a></td>
			<!-- Full refund powoduje, ze cala kasa wraca do kupujacego -->
			<td><a href="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=full_refund';?>">Full refund</a></td>
		</tr>
	</table>
	<?php
	}
	
	$sellerinfo = get_user_info($_SESSION['escrowinfo']['sellerid']);
	$buyerinfo = get_user_info($_SESSION['escrowinfo']['buyerid']);
	$addressbalance = find_address_balance_by_address($_SESSION['escrowinfo']['btcaddress']);
	?>
	
		
	<div class="main-head">
			<h2 class="hn"><span><?php echo $lang_escrows['Escrow details']; ?></span></h2>
	</div>
	<table border="1">
		<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?><?php if ($forum_page['item_count'] == 1) echo ' row1'; ?>">
			<td>ID</td>
			<td><?php echo $_SESSION['escrowinfo']['index'];?></td>
			
			<td>Date</td>
			<td><?php echo date('Y-m-d H:i', $_SESSION['escrowinfo']['time']);?></td>
		</tr>
		<tr>
			<td>Buyer</td>
			<td><?php echo "<a href=".FORUM_ROOT."profile.php?id=".$_SESSION['escrowinfo']['buyerid'].">".get_username($_SESSION['escrowinfo']['buyerid'])."</a>";?></td>

			<td>Seller</td>
			<td><?php echo "<a href=".FORUM_ROOT."profile.php?id=".$_SESSION['escrowinfo']['sellerid'].">".get_username($_SESSION['escrowinfo']['sellerid'])."</a>";?></td>

		</tr>
		<tr>
			<td>Subject</td>
			<td><?php echo $_SESSION['escrowinfo']['subject'];?></td>

			<td>Declared amount</td>
			<td><?php echo $_SESSION['escrowinfo']['amount'];?></td>
		</tr>
		<tr>
			<td>Received amount</td>
			<td><?php echo $addressbalance." btc";?></td>

			<td>Received time</td>
			<td><?php echo date('Y-m-d H:i',$_SESSION['escrowinfo']['recivedtime']);?></td>
		</tr>
		<tr>
			<td>Storage address</td>
			<td><?php echo "<a href=http://blockchain.info/address/".$_SESSION['escrowinfo']['btcaddress'].">"."(click)</a>"?></td>
			
			<td>Moderator</td>
			<td><?php echo get_username($_SESSION['escrowinfo']['moderatorid']);?></td>
		</tr>
		<tr>
			<td>Status</td>
			<td><?php echo get_escrow_status($_SESSION['escrowinfo']['status']);?></td>

			<td>Problem occured</td>
			<td><?php echo ($_SESSION['escrowinfo']['problemoccured']) ? 'Yes' : 'No';?></td>
		</tr>
		<tr>
			<td>Claim reason</td>
			<td><?php echo escrow_get_claim_reason($_SESSION['escrowinfo']['problemreason']);?></td>
			
			<td>Action claimed</td>
			<td><?php echo escrow_get_action_claimed($_SESSION['escrowinfo']['problemclaim']);?></td>
		</tr>
		<tr>
			<td>Payout amount</td>
			<td><?php echo escrow_get_payout_amount($_SESSION['escrowinfo']);?></td>
			
			<td>Payout address</td>
			<td><?php echo escrow_get_payout_address_link($_SESSION['escrowinfo']);?></td>
		</tr>
		<tr>
			<td>Seller address</td>
			<td><?php echo "<a href=http://blockchain.info/address/".$sellerinfo['btcaddress'].">"."(click)</a>"?></td>
			
			<td>Buyer address</td>
			<td><?php echo "<a href=http://blockchain.info/address/".$buyerinfo['btcaddress'].">"."(click)</a>"?></td>
		</tr>
	</table>
	<?php
}
//NO REFUND
else if (isset($_GET['action']) and isset($_GET['id']) and $forum_user['is_admmod'] and 
	$forum_user['id']==$_SESSION['escrowinfo']['moderatorid'] and $_GET['action']=='no_refund')
{ 
	?>
			<div class="main-head">
			<h2 class="hn"><span><?php echo "<b>".$lang_escrows['Are you sure you want to give no return?']."</b>";?></span></h2>
			</div>
			<div>
			<table>
				<tr>
					<td width="80%">
						<p><?php echo $lang_escrows['If you are sure']?></p>
					</td>
					<td>
						<span class="submit primary">
							<a href="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=confirm_no_refund'; ?>"><?php echo "<b><u>".$lang_escrows['Confirm']."</u></b>"; ?></a>
						</span>
					</td>
				</tr>
			</table>
			</div>	
	<?php
}
else if (isset($_GET['action']) and isset($_GET['id']) and $forum_user['is_admmod'] and
	$forum_user['id']==$_SESSION['escrowinfo']['moderatorid'] and $_GET['action']=='partial_refund')
{
	// TU PARTIAL REFUND ARE YOU SURE? 
	?>
			<div class="main-head">
			<h2 class="hn"><span><?php echo "<b>".$lang_escrows['Are you sure you want to give partial return?']."</b>";?></span></h2>
			</div>
			<div>
			<table>
				<tr>
					<td width="80%">
						<p><?php echo $lang_escrows['If you are sure']?></p>
					</td>
					<td>
						<span class="submit primary">
							<a href="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=confirm_partial_refund'; ?>"><?php echo "<b><u>".$lang_escrows['Confirm']."</u></b>"; ?></a>
						</span>
					</td>
				</tr>
			</table>
			</div>	
	<?php	

} 
else if (isset($_GET['action']) and isset($_GET['id']) and $forum_user['is_admmod'] and 
	$forum_user['id']==$_SESSION['escrowinfo']['moderatorid'] and $_GET['action']=='full_refund')
{
	//TU FULL REFUND ARE YOU SURE? 
	?>
			<div class="main-head">
			<h2 class="hn"><span><?php echo "<b>".$lang_escrows['Are you sure you want to give full return?']."</b>";?></span></h2>
			</div>
			<div>
			<table>
				<tr>
					<td width="80%">
						<p><?php echo $lang_escrows['If you are sure']?></p>
					</td>
					<td>
						<span class="submit primary">
							<a href="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=confirm_full_refund'; ?>"><?php echo "<b><u>".$lang_escrows['Confirm']."</u></b>"; ?></a>
						</span>
					</td>
				</tr>
			</table>
			</div>	
	<?php
}
//NO REFUND
else if (isset($_GET['action']) and isset($_GET['id']) and $forum_user['is_admmod'] and 
	$forum_user['id']==$_SESSION['escrowinfo']['moderatorid'] and 
	$_GET['action']=='confirm_no_refund' and $_SESSION['escrowinfo']['status']==PROBLEM_REPORTED)
{ 
	$selleraddress = get_user_info($_SESSION['escrowinfo']['sellerid'])['btcaddress'];
	change_escrow_status($_SESSION['escrowinfo']['index'] ,NO_BITCOIN_RETURN);
	$_SESSION['escrowinfo']['status']= NO_BITCOIN_RETURN;
	$amount=escrow_get_payout_amount($_SESSION['escrowinfo']);
	$now = time();
	escrow_note_new_payout($now, $_SESSION['escrowinfo']['sellerid'],$amount ,$selleraddress,$_SESSION['escrowinfo']['index']);
	escrow_notify_problem_resolved($_SESSION['escrowinfo']);
	$moderatorincome = escrow_get_moderator_earning($_SESSION['escrowinfo']);
	escrow_note_moderator_earnings($forum_user['id'], $moderatorincome);
	?>
	<h1>Confirmed</h1>
	<?php
}// FULL REFUND
else if (isset($_GET['action']) and isset($_GET['id']) and $forum_user['is_admmod'] and 
	$forum_user['id']==$_SESSION['escrowinfo']['moderatorid'] and 
	$_GET['action']=='confirm_full_refund' and $_SESSION['escrowinfo']['status']==PROBLEM_REPORTED)
{
	$buyeraddress = get_user_info($_SESSION['escrowinfo']['buyerid'])['btcaddress'];
	change_escrow_status($_SESSION['escrowinfo']['index'] ,FULL_BITCOIN_RETURN); 
	$_SESSION['escrowinfo']['status']= FULL_BITCOIN_RETURN;
	$amount=escrow_get_payout_amount($_SESSION['escrowinfo']);
	$now = time();
	escrow_note_new_payout($now, $_SESSION['escrowinfo']['buyerid'],$amount ,$buyeraddress,$_SESSION['escrowinfo']['index']);
	escrow_notify_problem_resolved($_SESSION['escrowinfo']);
	$moderatorincome = escrow_get_moderator_earning($_SESSION['escrowinfo']);
	escrow_note_moderator_earnings($forum_user['id'], $moderatorincome);
	?>
	<h1>Confirmed</h1>
	<?php
}  //PARTIAL REFUND
else if (isset($_GET['action']) and isset($_GET['id']) and isset($_GET['answer']) and $forum_user['is_admmod'] and 
	$forum_user['id']==$_SESSION['escrowinfo']['moderatorid'] and $_GET['action']=='confirm_partial_refund' and
	$_GET['answer']=='yes' and $_SESSION['escrowinfo']['status']==PROBLEM_REPORTED and isset($_POST['req_refund_percentage']) and
	intval($_POST['req_refund_percentage'])<100 and intval($_POST['req_refund_percentage'])>0)
{ 
	$now = time();
	change_escrow_status($_SESSION['escrowinfo']['index'] ,PARTIAL_BITCOIN_RETURN);
	$_SESSION['escrowinfo']['status']= PARTIAL_BITCOIN_RETURN;
	$totalpayoutamount = escrow_get_payout_amount($_SESSION['escrowinfo']);
	$buyerpayoutamount = $_POST['req_refund_percentage']/100*$totalpayoutamount;
	$sellerpayoutamount= (1-$_POST['req_refund_percentage']/100)*$totalpayoutamount;
	
	$buyeraddress= get_user_info($_SESSION['escrowinfo']['buyerid'])['btcaddress'];
	$selleraddress= get_user_info($_SESSION['escrowinfo']['sellerid'])['btcaddress'];
	
	//buyer payment
	escrow_note_new_payout($now, $_SESSION['escrowinfo']['buyerid'] ,
		$buyerpayoutamount ,$buyeraddress,$_SESSION['escrowinfo']['index']);
	
	//seller payment
	escrow_note_new_payout($now, $_SESSION['escrowinfo']['sellerid'] ,
		$sellerpayoutamount ,$selleraddress,$_SESSION['escrowinfo']['index']);
	escrow_notify_problem_resolved($_SESSION['escrowinfo']);
	$moderatorincome = escrow_get_moderator_earning($_SESSION['escrowinfo']);
	escrow_note_moderator_earnings($forum_user['id'], $moderatorincome);
	?>
	<h1>Confirmed <?php echo $buyerpayoutamount;?> BTC refund.</h1>
	<?php	
}
//  PARTIAL REFUND 
else if (isset($_GET['action']) and isset($_GET['id']) and $forum_user['is_admmod'] and 
	$forum_user['id']==$_SESSION['escrowinfo']['moderatorid'] and $_GET['action']=='confirm_partial_refund')
{ 

// formularz ile procent z wyplaty dla kupujacego 
?>
	<div class="main-head">
		<h2 class="hn"><span><?php echo "<b>".$lang_escrows['Insert refund percentage']."</b>";?></span></h2>
	</div>
	<span class="submit primary">
	<FORM METHOD="POST" ACTION="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=confirm_partial_refund&answer=yes'; ?>">

	<div>
		<table>
			<tr>
			<td >
				<p><?php echo $lang_escrows['Refund percentage']?></p>
			</td>
				<td>
				<INPUT TYPE="number" NAME='req_refund_percentage' VALUE="50">%
				</td>
			</tr>
			
			<tr>
				<td>				
				<INPUT TYPE="submit" VALUE="<?php echo $lang_escrows['Accept'] ?>">
				</td>
				<td>
				<a href="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=take_moderator_action'; ?>"><?php echo "<b><u>".$lang_escrows['Go back']."</u></b>"; ?></a>
				</td>
			</tr>
		</table>
	</div>
	</FORM></span>
<?php
//potem 
// new payment for seller
//new payment for buyer
// messages for seller and buyer
// close topic
}

//zlecono wyswietlenie szczegolow
else if (isset($_GET['action']) and isset($_GET['id']) and $_GET['action']=='detail' and 
((isset($_SESSION['buyer']) and isset($_SESSION['seller'])) 
    or     (isset($_GET['mod']) and $forum_user['is_admmod']=='is_admmod')))
{
	$id = $_GET['id'];
	if (is_numeric($id))
	{
		$query = array(
			'SELECT'	=>	'e.*',
			'FROM'		=>	'escrows AS e',
			'WHERE'		=>	'e.index='.$id 
			);
		($hook = get_hook('ul_qr_get_details')) ? eval($hook) : null;
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		//$details = mysql_fetch_assoc($result);
		$_SESSION['escrowinfo']=mysql_fetch_assoc($result);
		if($forum_user['id']== $_SESSION['escrowinfo']['sellerid'] or $forum_user['id']== $_SESSION['escrowinfo']['buyerid'] or $forum_user['is_admmod'])
		{
			$addressID = find_address_id($_SESSION['escrowinfo']['btcaddress']);
			$balance = find_address_balance($addressID);
			?>
			<div class="main-head">
			<h2 class="hn"><span><?php echo $lang_escrows['Escrow summary']; ?></span></h2>
			</div>
			<table border="1">
				<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ;?><?php if ($forum_page['item_count'] == 1) echo ' row1'; ?>">
					<td width="25%">Date</td><td><?php echo date('Y-m-d',$_SESSION['escrowinfo']['time']); ?></td>
				</tr>
				<tr>
					<td width="25%">Buyer name</td><td> <?php echo get_username($_SESSION['escrowinfo']['buyerid']);?></td>
				</tr>
				<tr>
					<td width="25%">Seller name</td><td> <?php echo get_username($_SESSION['escrowinfo']['sellerid']);?></td>
				</tr>
				<tr>
					<td width="25%">Ordered Amount</td><td> <?php echo $_SESSION['escrowinfo']['amount'];?></td>
				</tr>
				<tr>
					<td width="25%">Received</td><td> <?php echo $balance;?></td>
				</tr>
				<tr>
					<?php $problemlink = sprintf($lang_escrows['click to read details link'],$_SESSION['escrowinfo']['problemid']); ?>
					<td width="25%">Problem reported</td><td> <?php echo ($_SESSION['escrowinfo']['problemoccured'])? 'Yes'.$problemlink : 'Not' ;?></td>
				</tr>
				<?php 
				if ($forum_user['id']== $_SESSION['escrowinfo']['sellerid'] or $forum_user['id']== $_SESSION['escrowinfo']['buyerid'])
				{
				?>
				<tr>
					<td width="25%">Action</td>
					<td>
					<table border="0">
						<tr>
							<!--jesli wplacone ale jeszcze nie doszlo do konca czasu i user jest kupujacym -->
							<?php 
							if (($_SESSION['escrowinfo']['status']==BITCOINS_RECEIVED OR $_SESSION['escrowinfo']['status']==PROBLEM_REPORTED) and $_SESSION['escrowinfo']['buyerid']==$forum_user['id'])
							{ ?>
								<td width="48%"><span class="submit primary"><a href="<?php echo FORUM_ROOT.'escrows.php?action=accept&id='.$id; ?>"><?php echo "<b>".$lang_escrows['Transaction succedeed']."</b>";?></a></FORM></span></td>
							<?php
							} ?>
							<!--zawsze moze byc GO BACK-->
							<td><a href="<?php echo FORUM_ROOT.'escrows.php'; ?>"><?php echo "<b>".$lang_escrows['Go back']."</b>";?></a></td>
							<!-- jesli nie doszlo do konca aukcji i pieniadze zostaly wplacone kupujacy moze REPORT PROBLEM-->
							<?php
							if ($_SESSION['escrowinfo']['status']==BITCOINS_RECEIVED and $_SESSION['escrowinfo']['buyerid']==$forum_user['id'])
							{
							?>
							<td><a href="<?php echo FORUM_ROOT.'escrows.php?action=report_problem&id='.$id; ?>"><?php echo "<b>".$lang_escrows['Report problem']; ?></a></td>
							<?php
							}
							?>
							<!-- jesli doszlo do konca aukcji i jest sprzedawca lub kupujacym ze zwrotem to moze wyplacic pieniadze -->
							<!--  lub jesli przyznano kupujacemu zwrot pieniedzy -->
							<?php
							if ($_SESSION['escrowinfo']['status']==BITCOINS_RELEASED and $_SESSION['escrowinfo']['sellerid']==$forum_user['id'])
							{
							?>
							<td><a href="<?php echo FORUM_ROOT.'escrows.php?id='.$id.'&action=payout'; ?>"><?php echo "<b>".$lang_escrows['Payout']."</b>"; ?></a></td>
							<?php	
							}
							?>
						</tr>
					</table>
					</td>
				</tr>
			<?php } ?>
			</table>

			<?php
		}
	}
}
// zgloszono problem
else if (isset($_GET['action']) and isset($_GET['id']) and $_GET['action']=='report_problem')
{
	$id = $_GET['id'];
	if (is_numeric($id))
	{
		$query = array(
			'SELECT'	=>	'e.*',
			'FROM'		=>	'escrows AS e',
			'WHERE'		=>	'e.index='.$id 
			);
		//($hook = get_hook('ul_qr_get_details')) ? eval($hook) : null;
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$_SESSION['escrowinfo'] = mysql_fetch_assoc($result);
		if($forum_user['id']== $_SESSION['escrowinfo']['sellerid'] or $forum_user['id']== $_SESSION['escrowinfo']['buyerid'] and $_SESSION['escrowinfo']['status']!=PROBLEM_REPORTED)
		{
			
			//$_SESSION['escrowinfo']=$details;
			$_SESSION['problem_subject'] = sprintf($lang_escrows['New problem reported by buyer'], $_SESSION['escrowinfo']['index'] , get_username($_SESSION['escrowinfo']['buyerid']), $_SESSION['escrowinfo']['subject'], $_SESSION['escrowinfo']['amount']);
			if (count($_SESSION['problem_subject'] >70))
			{
				$_SESSION['problem_subject'] = substr($_SESSION['problem_subject'],0,70);
			}
			?>
			<div class="main-head">
			<h2 class="hn"><span><?php echo "<b>".$lang_escrows['Are you sure?']."</b>";?></span></h2>
			</div>
			<div>
			<table>
				<tr>
					<td width="80%">
						<p><?php echo $lang_escrows['If you are sure please click the link']?></p>
					</td>
					<td>
						<span class="submit primary">
							<a href="<?php echo FORUM_ROOT.'reportproblem.php?fid='.$forum_config['o_problem_forum_id'].'&subject=1'; ?>"><?php echo "<b><u>".$lang_escrows['Report problem']."</u></b>"; ?></a>
						</span>
					</td>
				</tr>
			</table>
			</div>
			<?php
		}
	}
}
// przed czasem zwolniono srodki
else if (isset($_GET['action']) and isset($_GET['id']) and $_GET['action']=='accept' and is_numeric($_GET['id']))
{
	
	$escrowinfo = find_escrow_by_id($_GET['id']);
	//sprawdzam czy uprawniony uzytkownik wszedl pod ten link
	if ($escrowinfo['buyerid']==$forum_user['id'] and $escrowinfo['status']==BITCOINS_RECEIVED OR $escrowinfo['status']==PROBLEM_REPORTED)
	{
		change_escrow_status($_GET['id'], BITCOINS_RELEASED);
		?>
		<div class="main-head">
			<h2 class="hn"><span></span></h2>
		</div>
		<table>
		<tr>
		<td>
		<?php
		echo "<h1>".$lang_escrows['Thank you for bitcoins release']."</h1>";
		?>
		</td>
		<td>
		<FORM METHOD="LINK" ACTION="<?php echo FORUM_ROOT.'escrows.php'; ?>">
			<INPUT TYPE="submit" VALUE="<?php echo $lang_escrows['Go back'] ?>"></FORM>
		</td>
		</table>
		<?php
		
	}
	else
	{
		?>
		<div class="main-head">
			<h2 class="hn"><span></span></h2>
		</div>
		<table>
		<tr>
		<td>
		<?php
		echo "<h1>".$lang_common['No permission']."</h1>";
		?>
		</td>
		<td>
		<FORM METHOD="LINK" ACTION="<?php echo FORUM_ROOT.'escrows.php'; ?>">
			<INPUT TYPE="submit" VALUE="<?php echo $lang_escrows['Go back'] ?>"></FORM>
		</td>
		</table>
		<?php
	}
}

//zlecono wyplate  i potwierdzono
else if (isset($_GET['action']) and isset($_GET['id']) and isset($_GET['answer']) and 
	$_GET['action']=='payout' and $_GET['answer'] == 'yes' and is_numeric($_GET['id']) and 
	isset($_SESSION['escrowinfo']) and $forum_user['id']==$_SESSION['escrowinfo']['sellerid'] and
	$_SESSION['escrowinfo']['status']==BITCOINS_RELEASED)
{  
		$now = time();
		escrow_note_new_payout($now, $forum_user['id'], $_SESSION['finalpayout'] , $_SESSION['receiveraddress'], $_GET['id']);
		change_escrow_status($_SESSION['escrowinfo']['index'],ESCROW_FINISHED);
		?>
		<div class="main-head">
		<h2 class="hn"><span><?php echo $lang_escrows['Payment summary']; ?></span></h2>
		</div>
		<table border="1">
		<tr>
			<td>
				<h2><?php echo $lang_escrows['Seller payment thanks'] ;?> </h2>
			</td>
			<td><span class="cancel">
				<FORM METHOD="LINK" ACTION="<?php echo FORUM_ROOT.'escrows.php'; ?>"><INPUT TYPE="submit" VALUE="<?php echo $lang_escrows['Go back'];?>"></FORM>
				</span>
			</td>
		</tr>
		</table>
		<?php
} 

// zlecono wyplate

else if ( isset($_GET['action']) and isset($_GET['id']) and $_GET['action']=='payout' and
	is_numeric($_GET['id']) and isset($_SESSION['escrowinfo']) and 
	$forum_user['id']==$_SESSION['escrowinfo']['sellerid'] and $_SESSION['escrowinfo']['status']==BITCOINS_RELEASED)
{
	$_SESSION['finalpayout'] = escrow_get_payout_amount($_SESSION['escrowinfo']);
	$_SESSION['receiveraddress']= escrow_get_seller_address($_SESSION['escrowinfo']);
	
	?>
	<div class="main-head">
		<h2 class="hn"><span><?php echo $lang_escrows['Please check your data'] ;?></span></h2>
	</div>
	<table>
	<tr>
		<td>Amount</td>
		<td>Address</td>
	</tr>
	<tr>
		<td>
		<?php
		echo "<h1>".$_SESSION['finalpayout']."</h1>";
		?>
		</td>
		<td>
		<?php
		echo "<h1>".$_SESSION['receiveraddress']."</h1>";
		?>		
		</td>
	</tr>
	<tr>
		<td>
		<FORM METHOD="POST" ACTION="<?php echo FORUM_ROOT.'escrows.php?id='.$_GET['id'].'&action=payout&answer=yes'; ?>">
			<INPUT TYPE="submit" VALUE="<?php echo $lang_escrows['Accept']; ?>"></FORM>
		</td>
		<td>
		<FORM METHOD="LINK" ACTION="<?php echo FORUM_ROOT.'escrows.php'; ?>">
			<INPUT TYPE="submit" VALUE="<?php echo $lang_escrows['Go back']; ?>"></FORM>
		</td>
	</tr>
	</table>
	<?php	
}
 // kupujacy zaakceptowal
else if (isset($_GET['action']) and isset($_GET['id']) and $_GET['action']=='accept')
{
	$id = $_GET['id'];
	if (is_numeric($id))
	{
		$query = array(
			'SELECT'	=>	'e.*',
			'FROM'		=>	'escrows AS e',
			'WHERE'		=>	'e.index='.$id 
			);
		($hook = get_hook('ul_qr_get_details')) ? eval($hook) : null;
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$_SESSION['escrowinfo'] = mysql_fetch_assoc($result);
		if($forum_user['id']== $_SESSION['escrowinfo']['buyerid'])
		{
			//uwolnij btc
		$query = array(
			'UPDATE'	=>	'escrows',
			'SET'		=> 'status = '.BITCOINS_RELEASED,
			'WHERE'		=> 'id = '.$id,
			);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		?>
		<div class="main-head">
		<h2 class="hn"><span><?php echo $lang_escrows['Escrow summary']; ?></span></h2>
		</div>
		<table border="1">
		<tr><td><h2>Thank you for accepting the transaction.</h2></td></tr>
		<tr><td><span class="cancel"><FORM METHOD="LINK" ACTION="<?php echo FORUM_ROOT.'escrows.php'; ?>"><INPUT TYPE="submit" VALUE="<?php echo $lang_escrows['Go back'];?>"></FORM></span></td></tr>
		</table>
		<?php
		}
	}
}
else  //wyswietla wszystkie transakcje usera
	{
	//moderatora wyrzuca gdzie indziej bo moderator nie robi transakcji
	if ($forum_user['is_admmod'])
		redirect(FORUM_ROOT.'escrows.php', 'redirect');
	//transakcje z ktorych mozna wyplacic kase
	$query = array(
		'SELECT'	=> 'e.*',
		'FROM'		=> 'escrows AS e',
		'WHERE'		=> 'e.sellerid='.intval($forum_user['id']).' AND e.status='.BITCOINS_RELEASED,
		'ORDER BY'	=>	'e.time DESC'
		);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	?>
	<div class="main-head">
	<h2 class="hn"><span><?php echo $lang_escrows['Bitcoin payouts avalable']; ?></span></h2>
	</div>
	<table border="1">
		<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?><?php if ($forum_page['item_count'] == 1) echo ' row1'; ?>">
			<td width="5%">Date</td>
			<td>Subject</td>
			<td>Buyer</td>
			<td>Seller</td>
			<td width="4%">Declared</td>
			<td>Transaction Status</td>
		</tr>
	<?php
	while ($row = mysql_fetch_assoc($result))
	{
		$_SESSION['seller'] =get_username($row['sellerid']);
		$_SESSION['buyer']	=get_username($row['buyerid']);
		echo "<tr><td>".date('y-m-d',$row['time'])."</td><td>".$row['subject'].
		"</td><td><a href=".FORUM_ROOT."profile.php?id=".$row['buyerid'].">".
		$_SESSION['buyer']."</a></td><td><a href=".
		FORUM_ROOT."profile.php?id=".$row['sellerid'].">".
		$_SESSION['seller']."</a></td><td>".
		$row['amount']."</td><td><a href=".FORUM_ROOT."escrows.php?action=detail&id=".$row['index'].">".
		get_escrow_status($row['status'])."</a></td></tr>";
	}

	?>

	</table>

	<?php
	
	
	//wszystkie transakcje usera	
	$query = array(
		'SELECT'	=> 'e.*',
		'FROM'		=> 'escrows AS e',
		'WHERE'		=> 'e.buyerid='.intval($forum_user['id']).' OR e.sellerid='.intval($forum_user['id']).'',
		'ORDER BY'	=>	'e.time DESC'
		);
	($hook = get_hook('ul_qr_get_users')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	?>
	<div class="main-head">
	<h2 class="hn"><span><?php echo $lang_escrows['Escrow summary']; ?></span></h2>
	</div>
	<table border="1">
		<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?><?php if ($forum_page['item_count'] == 1) echo ' row1'; ?>">
			<td width="5%">Date</td>
			<td>Subject</td>
			<td>Buyer</td>
			<td>Seller</td>
			<td width="4%">Amount</td>
			<td>Transaction Status</td>
		</tr>
	<?php
	while ($row = mysql_fetch_assoc($result))
	{
		$_SESSION['seller'] =get_username($row['sellerid']);
		$_SESSION['buyer']	=get_username($row['buyerid']);
		echo "<tr><td>".date('y-m-d',$row['time'])."</td><td>".$row['subject'].
		"</td><td><a href=".FORUM_ROOT."profile.php?id=".$row['buyerid'].">".
		$_SESSION['buyer']."</a></td><td><a href=".
		FORUM_ROOT."profile.php?id=".$row['sellerid'].">".
		$_SESSION['seller']."</a></td><td>".
		$row['amount']."</td><td><a href=".FORUM_ROOT."escrows.php?action=detail&id=".$row['index'].">".
		get_escrow_status($row['status'])."</a></td></tr>";
	}

	?>

	</table>

	<?php
}

($hook = get_hook('ul_end')) ? eval($hook) : null;

$tpl_temp = forum_trim(ob_get_contents());
$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
ob_end_clean();
// END SUBST - <!-- forum_main -->

require FORUM_ROOT.'footer.php';
?>

<?php

?>
