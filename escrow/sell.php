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
?>
<div class="main-head">
		<h2 class="hn"><span><?php echo $lang_escrows['Choose a category']; ?></span></h2>
</div>
	<table border="1">
				
<?php
	echo market_get_category_sell_page($forum_config['o_trade_category_id']);
?>

	</table>
<?php
($hook = get_hook('ul_end')) ? eval($hook) : null;

$tpl_temp = forum_trim(ob_get_contents());
$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
ob_end_clean();
// END SUBST - <!-- forum_main -->

require FORUM_ROOT.'footer.php';
?>
