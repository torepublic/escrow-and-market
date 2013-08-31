<?php

define('FORUM_HOOKS_LOADED', 1);

$forum_hooks = array (
  'aex_section_manage_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\'))
	include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\';
else
	include $ext_info[\'path\'].\'/lang/English/pun_repository.php\';

require_once $ext_info[\'path\'].\'/pun_repository.php\';

($hook = get_hook(\'pun_repository_pre_display_ext_list\')) ? eval($hook) : null;

?>
	<div class="main-subhead">
		<h2 class="hn"><span><?php echo $lang_pun_repository[\'PunBB Repository\'] ?></span></h2>
	</div>
	<div class="main-content main-extensions">
		<p class="content-options options"><a href="<?php echo $base_url ?>/admin/extensions.php?pun_repository_update&amp;csrf_token=<?php echo generate_form_token(\'pun_repository_update\') ?>"><?php echo $lang_pun_repository[\'Clear cache\'] ?></a></p>
<?php

if (!defined(\'PUN_REPOSITORY_EXTENSIONS_LOADED\') && file_exists(FORUM_CACHE_DIR.\'cache_pun_repository.php\'))
	include FORUM_CACHE_DIR.\'cache_pun_repository.php\';

if (!defined(\'FORUM_EXT_VERSIONS_LOADED\') && file_exists(FORUM_CACHE_DIR.\'cache_ext_version_notifications.php\'))
	include FORUM_CACHE_DIR.\'cache_ext_version_notifications.php\';

// Regenerate cache only if automatic updates are enabled and if the cache is more than 12 hours old
if (!defined(\'PUN_REPOSITORY_EXTENSIONS_LOADED\') || !defined(\'FORUM_EXT_VERSIONS_LOADED\') || ($pun_repository_extensions_timestamp < $forum_ext_versions_update_cache))
{
	$pun_repository_error = \'\';

	if (pun_repository_generate_cache($pun_repository_error))
	{
		require FORUM_CACHE_DIR.\'cache_pun_repository.php\';
	}
	else
	{

		?>
		<div class="ct-box warn-box">
			<p class="warn"><?php echo $pun_repository_error ?></p>
		</div>
		<?php

		// Stop processing hook
		return;
	}
}

$pun_repository_parsed = array();
$pun_repository_skipped = array();

// Display information about extensions in repository
foreach ($pun_repository_extensions as $pun_repository_ext)
{
	// Skip installed extensions
	if (isset($inst_exts[$pun_repository_ext[\'id\']]))
	{
		$pun_repository_skipped[\'installed\'][] = $pun_repository_ext[\'id\'];
		continue;
	}

	// Skip uploaded extensions (including incorrect ones)
	if (is_dir(FORUM_ROOT.\'extensions/\'.$pun_repository_ext[\'id\']))
	{
		$pun_repository_skipped[\'has_dir\'][] = $pun_repository_ext[\'id\'];
		continue;
	}

	// Check for unresolved dependencies
	if (isset($pun_repository_ext[\'dependencies\']))
		$pun_repository_ext[\'dependencies\'] = pun_repository_check_dependencies($inst_exts, $pun_repository_ext[\'dependencies\']);

	if (empty($pun_repository_ext[\'dependencies\'][\'unresolved\']))
	{
		// \'Download and install\' link
		$pun_repository_ext[\'options\'] = array(\'<a href="\'.$base_url.\'/admin/extensions.php?pun_repository_download_and_install=\'.$pun_repository_ext[\'id\'].\'&amp;csrf_token=\'.generate_form_token(\'pun_repository_download_and_install_\'.$pun_repository_ext[\'id\']).\'">\'.$lang_pun_repository[\'Download and install\'].\'</a>\');
	}
	else
		$pun_repository_ext[\'options\'] = array();

	$pun_repository_parsed[] = $pun_repository_ext[\'id\'];

	// Direct links to archives
	$pun_repository_ext[\'download_links\'] = array();
	foreach (array(\'zip\', \'tgz\', \'7z\') as $pun_repository_archive_type)
		$pun_repository_ext[\'download_links\'][] = \'<a href="\'.PUN_REPOSITORY_URL.\'/\'.$pun_repository_ext[\'id\'].\'/\'.$pun_repository_ext[\'id\'].\'.\'.$pun_repository_archive_type.\'">\'.$pun_repository_archive_type.\'</a>\';

	($hook = get_hook(\'pun_repository_pre_display_ext_info\')) ? eval($hook) : null;

	// Let\'s ptint it all out
?>
		<div class="ct-box info-box extension available" id="<?php echo $pun_repository_ext[\'id\'] ?>">
			<h3 class="ct-legend hn"><span><?php echo forum_htmlencode($pun_repository_ext[\'title\']).\' \'.$pun_repository_ext[\'version\'] ?></span></h3>
			<p><?php echo forum_htmlencode($pun_repository_ext[\'description\']) ?></p>
<?php

	// List extension dependencies
	if (!empty($pun_repository_ext[\'dependencies\'][\'dependency\']))
		echo \'
			<p>\', $lang_pun_repository[\'Dependencies:\'], \' \', implode(\', \', $pun_repository_ext[\'dependencies\'][\'dependency\']), \'</p>\';

?>
			<p><?php echo $lang_pun_repository[\'Direct download links:\'], \' \', implode(\' \', $pun_repository_ext[\'download_links\']) ?></p>
<?php

	// List unresolved dependencies
	if (!empty($pun_repository_ext[\'dependencies\'][\'unresolved\']))
		echo \'
			<div class="ct-box warn-box">
				<p class="warn">\', $lang_pun_repository[\'Resolve dependencies:\'], \' \', implode(\', \', array_map(create_function(\'$dep\', \'return \\\'<a href="#\\\'.$dep.\\\'">\\\'.$dep.\\\'</a>\\\';\'), $pun_repository_ext[\'dependencies\'][\'unresolved\'])), \'</p>
			</div>\';

	// Actions
	if (!empty($pun_repository_ext[\'options\']))
		echo \'
			<p class="options">\', implode(\' \', $pun_repository_ext[\'options\']), \'</p>\';

?>
		</div>
<?php

}

?>
		<div class="ct-box warn-box">
			<p class="warn"><?php echo $lang_pun_repository[\'Files mode and owner\'] ?></p>
		</div>
<?php

if (empty($pun_repository_parsed) && (count($pun_repository_skipped[\'installed\']) > 0 || count($pun_repository_skipped[\'has_dir\']) > 0))
{
	($hook = get_hook(\'pun_repository_no_extensions\')) ? eval($hook) : null;

	?>
		<div class="ct-box info-box">
			<p class="warn"><?php echo $lang_pun_repository[\'All installed or downloaded\'] ?></p>
		</div>
	<?php

}

($hook = get_hook(\'pun_repository_after_ext_list\')) ? eval($hook) : null;

?>
	</div>
<?php

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aex_new_action' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Clear pun_repository cache
if (isset($_GET[\'pun_repository_update\']))
{
	// Validate CSRF token
	if (!isset($_POST[\'csrf_token\']) && (!isset($_GET[\'csrf_token\']) || $_GET[\'csrf_token\'] !== generate_form_token(\'pun_repository_update\')))
		csrf_confirm_form();

	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\'))
		include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\';
	else
		include $ext_info[\'path\'].\'/lang/English/pun_repository.php\';

	@unlink(FORUM_CACHE_DIR.\'cache_pun_repository.php\');
	if (file_exists(FORUM_CACHE_DIR.\'cache_pun_repository.php\'))
		message($lang_pun_repository[\'Unable to remove cached file\'], \'\', $lang_pun_repository[\'PunBB Repository\']);

	redirect($base_url.\'/admin/extensions.php?section=manage\', $lang_pun_repository[\'Cache has been successfully cleared\']);
}

if (isset($_GET[\'pun_repository_download_and_install\']))
{
	$ext_id = preg_replace(\'/[^0-9a-z_]/\', \'\', $_GET[\'pun_repository_download_and_install\']);

	// Validate CSRF token
	if (!isset($_POST[\'csrf_token\']) && (!isset($_GET[\'csrf_token\']) || $_GET[\'csrf_token\'] !== generate_form_token(\'pun_repository_download_and_install_\'.$ext_id)))
		csrf_confirm_form();

	// TODO: Should we check again for unresolved dependencies here?

	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\'))
		include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\';
	else
		include $ext_info[\'path\'].\'/lang/English/pun_repository.php\';

	require_once $ext_info[\'path\'].\'/pun_repository.php\';

	($hook = get_hook(\'pun_repository_download_and_install_start\')) ? eval($hook) : null;

	// Download extension
	$pun_repository_error = pun_repository_download_extension($ext_id, $ext_data);

	if ($pun_repository_error == \'\')
	{
		if (empty($ext_data))
			redirect($base_url.\'/admin/extensions.php?section=manage\', $lang_pun_repository[\'Incorrect manifest.xml\']);

		// Validate manifest
		$errors = validate_manifest($ext_data, $ext_id);
		if (!empty($errors))
			redirect($base_url.\'/admin/extensions.php?section=manage\', $lang_pun_repository[\'Incorrect manifest.xml\']);

		// Everything is OK. Start installation.
		redirect($base_url.\'/admin/extensions.php?install=\'.urlencode($ext_id), $lang_pun_repository[\'Download successful\']);
	}

	($hook = get_hook(\'pun_repository_download_and_install_end\')) ? eval($hook) : null;
}

// Handling the download and update extension action
if (isset($_GET[\'pun_repository_download_and_update\']))
{
	$ext_id = preg_replace(\'/[^0-9a-z_]/\', \'\', $_GET[\'pun_repository_download_and_update\']);

	// Validate CSRF token
	if (!isset($_POST[\'csrf_token\']) && (!isset($_GET[\'csrf_token\']) || $_GET[\'csrf_token\'] !== generate_form_token(\'pun_repository_download_and_update_\'.$ext_id)))
		csrf_confirm_form();

	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\'))
		include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\';
	else
		include $ext_info[\'path\'].\'/lang/English/pun_repository.php\';

	require_once $ext_info[\'path\'].\'/pun_repository.php\';

	$pun_repository_error = \'\';

	($hook = get_hook(\'pun_repository_download_and_update_start\')) ? eval($hook) : null;

	pun_repository_rm_recursive(FORUM_ROOT.\'extensions/\'.$ext_id.\'.old\');

	// Check dependancies
	$query = array(
		\'SELECT\'	=> \'e.id\',
		\'FROM\'		=> \'extensions AS e\',
		\'WHERE\'		=> \'e.disabled=0 AND e.dependencies LIKE \\\'%|\'.$forum_db->escape($ext_id).\'|%\\\'\'
	);

	($hook = get_hook(\'aex_qr_get_disable_dependencies\')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	if ($forum_db->num_rows($result) != 0)
	{
		$dependency = $forum_db->fetch_assoc($result);
		$pun_repository_error = sprintf($lang_admin[\'Disable dependency\'], $dependency[\'id\']);
	}

	if ($pun_repository_error == \'\' && ($ext_id != $ext_info[\'id\']))
	{
		// Disable extension
		$query = array(
			\'UPDATE\'	=> \'extensions\',
			\'SET\'		=> \'disabled=1\',
			\'WHERE\'		=> \'id=\\\'\'.$forum_db->escape($ext_id).\'\\\'\'
		);

		($hook = get_hook(\'aex_qr_update_disabled_status\')) ? eval($hook) : null;
		$forum_db->query_build($query) or error(__FILE__, __LINE__);

		// Regenerate the hooks cache
		require_once FORUM_ROOT.\'include/cache.php\';
		generate_hooks_cache();
	}

	if ($pun_repository_error == \'\')
	{
		if ($ext_id == $ext_info[\'id\'])
		{
			// Hey! That\'s me!
			// All the necessary files should be included before renaming old directory
			// NOTE: Self-updating is to be tested more in real-life conditions
			if (!defined(\'PUN_REPOSITORY_TAR_EXTRACT_INCLUDED\'))
				require $ext_info[\'path\'].\'/pun_repository_tar_extract.php\';
		}

		$pun_repository_error = pun_repository_download_extension($ext_id, $ext_data, FORUM_ROOT.\'extensions/\'.$ext_id.\'.new\'); // Download the extension

		if ($pun_repository_error == \'\')
		{
			if (is_writable(FORUM_ROOT.\'extensions/\'.$ext_id))
				pun_repository_dir_copy(FORUM_ROOT.\'extensions/\'.$ext_id.\'.new/\'.$ext_id, FORUM_ROOT.\'extensions/\'.$ext_id);
			else
				$pun_repository_error = sprintf($lang_pun_repository[\'Copy fail\'], FORUM_ROOT.\'extensions/\'.$ext_id);
		}
	}

	if ($pun_repository_error == \'\')
	{
		// Do we have extension data at all? :-)
		if (empty($ext_data))
			$errors = array(true);

		// Validate manifest
		if (empty($errors))
			$errors = validate_manifest($ext_data, $ext_id);

		if (!empty($errors))
			$pun_repository_error = $lang_pun_repository[\'Incorrect manifest.xml\'];
	}

	if ($pun_repository_error == \'\')
	{
		($hook = get_hook(\'pun_repository_download_and_update_ok\')) ? eval($hook) : null;

		// Everything is OK. Start installation.
		pun_repository_rm_recursive(FORUM_ROOT.\'extensions/\'.$ext_id.\'.new\');
		redirect($base_url.\'/admin/extensions.php?install=\'.urlencode($ext_id), $lang_pun_repository[\'Download successful\']);
	}

	($hook = get_hook(\'pun_repository_download_and_update_error\')) ? eval($hook) : null;

	// Enable extension
	$query = array(
		\'UPDATE\'	=> \'extensions\',
		\'SET\'		=> \'disabled=0\',
		\'WHERE\'		=> \'id=\\\'\'.$forum_db->escape($ext_id).\'\\\'\'
	);

	($hook = get_hook(\'aex_qr_update_enabled_status\')) ? eval($hook) : null;
	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	// Regenerate the hooks cache
	require_once FORUM_ROOT.\'include/cache.php\';
	generate_hooks_cache();

	($hook = get_hook(\'pun_repository_download_and_update_end\')) ? eval($hook) : null;
}

// Do we have some error?
if (!empty($pun_repository_error))
{
	// Setup breadcrumbs
	$forum_page[\'crumbs\'] = array(
		array($forum_config[\'o_board_title\'], forum_link($forum_url[\'index\'])),
		array($lang_admin_common[\'Forum administration\'], forum_link($forum_url[\'admin_index\'])),
		array($lang_admin_common[\'Extensions\'], forum_link($forum_url[\'admin_extensions_manage\'])),
		array($lang_admin_common[\'Manage extensions\'], forum_link($forum_url[\'admin_extensions_manage\'])),
		$lang_pun_repository[\'PunBB Repository\']
	);

	($hook = get_hook(\'pun_repository__pre_header_load\')) ? eval($hook) : null;

	define(\'FORUM_PAGE_SECTION\', \'extensions\');
	define(\'FORUM_PAGE\', \'admin-extensions-pun-repository\');
	require FORUM_ROOT.\'header.php\';

	// START SUBST - <!-- forum_main -->
	ob_start();

	($hook = get_hook(\'pun_repository_display_error_output_start\')) ? eval($hook) : null;

?>
	<div class="main-subhead">
		<h2 class="hn"><span><?php echo $lang_pun_repository[\'PunBB Repository\'] ?></span></h2>
	</div>
	<div class="main-content">
		<div class="ct-box warn-box">
			<p class="warn"><?php echo $pun_repository_error ?></p>
		</div>
	</div>
<?php

	($hook = get_hook(\'pun_repository_display_error_pre_ob_end\')) ? eval($hook) : null;

	$tpl_temp = trim(ob_get_contents());
	$tpl_main = str_replace(\'<!-- forum_main -->\', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->

	require FORUM_ROOT.\'footer.php\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aex_section_manage_pre_header_load' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\'))
	include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\';
else
	include $ext_info[\'path\'].\'/lang/English/pun_repository.php\';

require_once $ext_info[\'path\'].\'/pun_repository.php\';

if (!defined(\'PUN_REPOSITORY_EXTENSIONS_LOADED\') && file_exists(FORUM_CACHE_DIR.\'cache_pun_repository.php\'))
	include FORUM_CACHE_DIR.\'cache_pun_repository.php\';

if (!defined(\'FORUM_EXT_VERSIONS_LOADED\') && file_exists(FORUM_CACHE_DIR.\'cache_ext_version_notifications.php\'))
	include FORUM_CACHE_DIR.\'cache_ext_version_notifications.php\';

// Regenerate cache only if automatic updates are enabled and if the cache is more than 12 hours old
if (!defined(\'PUN_REPOSITORY_EXTENSIONS_LOADED\') || !defined(\'FORUM_EXT_VERSIONS_LOADED\') || ($pun_repository_extensions_timestamp < $forum_ext_versions_update_cache))
{
	if (pun_repository_generate_cache($pun_repository_error))
		require FORUM_CACHE_DIR.\'cache_pun_repository.php\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aex_section_manage_pre_ext_actions' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (defined(\'PUN_REPOSITORY_EXTENSIONS_LOADED\') && isset($pun_repository_extensions[$id]) && version_compare($ext[\'version\'], $pun_repository_extensions[$id][\'version\'], \'<\') && is_writable(FORUM_ROOT.\'extensions/\'.$id))
{
	// Check for unresolved dependencies
	if (isset($pun_repository_extensions[$id][\'dependencies\']))
		$pun_repository_extensions[$id][\'dependencies\'] = pun_repository_check_dependencies($inst_exts, $pun_repository_extensions[$id][\'dependencies\']);

	if (empty($pun_repository_extensions[$id][\'dependencies\'][\'unresolved\']))
		$forum_page[\'ext_actions\'][] = \'<span><a href="\'.$base_url.\'/admin/extensions.php?pun_repository_download_and_update=\'.$id.\'&amp;csrf_token=\'.generate_form_token(\'pun_repository_download_and_update_\'.$id).\'">\'.$lang_pun_repository[\'Download and update\'].\'</a></span>\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'co_common' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[\'id\']));

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    1 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'logo\',
\'path\'			=> FORUM_ROOT.\'extensions/logo\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/logo\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!isset($logo)) {
	if ($forum_user[\'language\'] != \'English\' && file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/lang.php\')) {
		require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/lang.php\';
	} else {
		require $ext_info[\'path\'].\'/lang/English/lang.php\';
	}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'pf_change_details_settings_validation' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Validate option \'quote beginning of message\'
			if (!isset($_POST[\'form\'][\'pun_pm_long_subject\']) || $_POST[\'form\'][\'pun_pm_long_subject\'] != \'1\')
				$form[\'pun_pm_long_subject\'] = \'0\';
			else
				$form[\'pun_pm_long_subject\'] = \'1\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'pf_change_details_settings_email_fieldset_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Per-user option \'quote beginning of message\'
if ($forum_config[\'p_message_bbcode\'] == \'1\')
{
	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	$forum_page[\'item_count\'] = 0;

?>
			<fieldset class="frm-group group<?php echo ++$forum_page[\'group_count\'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_pun_pm[\'PM settings\'] ?></strong></legend>
				<fieldset class="mf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<legend><span><?php echo $lang_pun_pm[\'Private messages\'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_pm_long_subject]" value="1"<?php if ($user[\'pun_pm_long_subject\'] == \'1\') echo \' checked="checked"\' ?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_pun_pm[\'Begin message quote\'] ?></label>
						</div>
					</div>
				</fieldset>
<?php ($hook = get_hook(\'pun_pm_pf_change_details_settings_pre_pm_settings_fieldset_end\')) ? eval($hook) : null; ?>
			</fieldset>
<?php
}
else
	echo "\\t\\t\\t".\'<input type="hidden" name="form[pun_pm_long_subject]" value="\'.$user[\'pun_pm_long_subject\'].\'" />\'."\\n";

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'hd_head' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Incuding styles for pun_pm
			if (defined(\'FORUM_PAGE\') && \'pun_pm\' == substr(FORUM_PAGE, 0, 6))
			{
				if ($forum_user[\'style\'] != \'Oxygen\' && file_exists($ext_info[\'path\'].\'/css/\'.$forum_user[\'style\'].\'/pun_pm.min.css\'))
					$forum_loader->add_css($ext_info[\'url\'].\'/css/\'.$forum_user[\'style\'].\'/pun_pm.min.css\', array(\'type\' => \'url\', \'media\' => \'screen\'));
				else
					$forum_loader->add_css($ext_info[\'url\'].\'/css/Oxygen/pun_pm.min.css\', array(\'type\' => \'url\', \'media\' => \'screen\'));
			}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
    1 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'logo\',
\'path\'			=> FORUM_ROOT.\'extensions/logo\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/logo\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if($forum_config[\'o_logo_src\'] != \'0\')
		{
			$check_file=@fopen(FORUM_ROOT.$forum_config[\'o_logo_src\'], \'r\');
			if($check_file)
			fclose($check_file);
			else $forum_config[\'o_logo_enable\'] = \'0\';
		}
		if ($forum_config[\'o_logo_enable\'] == \'1\' )
			{
				$forum_loader->add_css($ext_info[\'url\'].\'/main.css\', array(\'type\' => \'url\'));
				$forum_loader->add_css(\'div.logo{background-image:url(\'.$base_url.$forum_config[\'o_logo_src\'].\'); width:\'.$forum_config[\'o_logo_width\'].\'px; height:\'.$forum_config[\'o_logo_height\'].\'px;}td.logo{width:\'.$forum_config[\'o_logo_width\'].\'px; height:\'.$forum_config[\'o_logo_height\'].\'px;}\', array(\'type\' => \'inline\'));
			}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'mi_new_action' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($action == \'pun_pm_send\' && !$forum_user[\'is_guest\'])
{
	if(!defined(\'PUN_PM_FUNCTIONS_LOADED\'))
		require $ext_info[\'path\'].\'/functions.php\';

	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	$pun_pm_body = isset($_POST[\'req_message\']) ? $_POST[\'req_message\'] : \'\';
	$pun_pm_subject = isset($_POST[\'pm_subject\']) ? $_POST[\'pm_subject\'] : \'\';
	$pun_pm_receiver_username = isset($_POST[\'pm_receiver\']) ? $_POST[\'pm_receiver\'] : \'\';
	$pun_pm_message_id = isset($_POST[\'message_id\']) ? (int) $_POST[\'message_id\'] : false;

	if (isset($_POST[\'send_action\']) && in_array($_POST[\'send_action\'], array(\'send\', \'draft\', \'delete\', \'preview\')))
		$pun_pm_send_action = $_POST[\'send_action\'];
	elseif (isset($_POST[\'pm_draft\']))
		$pun_pm_send_action = \'draft\';
	elseif (isset($_POST[\'pm_send\']))
		$pun_pm_send_action = \'send\';
	elseif (isset($_POST[\'pm_delete\']))
		$pun_pm_send_action = \'delete\';
	else
		$pun_pm_send_action = \'preview\';

	($hook = get_hook(\'pun_pm_after_send_action_set\')) ? eval($hook) : null;

	if ($pun_pm_send_action == \'draft\')
	{
		// Try to save the message as draft
		// Inside this function will be a redirect, if everything is ok
		$pun_pm_errors = pun_pm_save_message($pun_pm_body, $pun_pm_subject, $pun_pm_receiver_username, $pun_pm_message_id);
		// Remember $pun_pm_message_id = false; inside this function if $pun_pm_message_id is incorrect

		// Well... Go processing errors

		// We need no preview
		$pun_pm_msg_preview = false;
	}
	elseif ($pun_pm_send_action == \'send\')
	{
		// Try to send the message
		// Inside this function will be a redirect, if everything is ok
		$pun_pm_errors = pun_pm_send_message($pun_pm_body, $pun_pm_subject, $pun_pm_receiver_username, $pun_pm_message_id);
		// Remember $pun_pm_message_id = false; inside this function if $pun_pm_message_id is incorrect

		// Well... Go processing errors

		// We need no preview
		$pun_pm_msg_preview = false;
	}
	elseif ($pun_pm_send_action == \'delete\' && $pun_pm_message_id !== false)
	{
		pun_pm_delete_from_outbox(array($pun_pm_message_id));
		redirect(forum_link($forum_url[\'pun_pm_outbox\']), $lang_pun_pm[\'Message deleted\']);
	}
	elseif ($pun_pm_send_action == \'preview\')
	{
		// Preview message
		$pun_pm_errors = array();
		$pun_pm_msg_preview = pun_pm_preview($pun_pm_receiver_username, $pun_pm_subject, $pun_pm_body, $pun_pm_errors);
	}

	($hook = get_hook(\'pun_pm_new_send_action\')) ? eval($hook) : null;

	$pun_pm_page_text = pun_pm_send_form($pun_pm_receiver_username, $pun_pm_subject, $pun_pm_body, $pun_pm_message_id, false, false, $pun_pm_msg_preview);

	// Setup navigation menu
	$forum_page[\'main_menu\'] = array(
		\'inbox\'		=> \'<li class="first-item"><a href="\'.forum_link($forum_url[\'pun_pm_inbox\']).\'"><span>\'.$lang_pun_pm[\'Inbox\'].\'</span></a></li>\',
		\'outbox\'	=> \'<li><a href="\'.forum_link($forum_url[\'pun_pm_outbox\']).\'"><span>\'.$lang_pun_pm[\'Outbox\'].\'</span></a></li>\',
		\'write\'		=> \'<li class="active"><a href="\'.forum_link($forum_url[\'pun_pm_write\']).\'"><span>\'.$lang_pun_pm[\'Compose message\'].\'</span></a></li>\',
	);

	// Setup breadcrumbs
	$forum_page[\'crumbs\'] = array(
		array($forum_config[\'o_board_title\'], forum_link($forum_url[\'index\'])),
		array($lang_pun_pm[\'Private messages\'], forum_link($forum_url[\'pun_pm\'])),
		array($lang_pun_pm[\'Compose message\'], forum_link($forum_url[\'pun_pm_write\']))
	);

	($hook = get_hook(\'pun_pm_pre_send_output\')) ? eval($hook) : null;

	define(\'FORUM_PAGE\', \'pun_pm-write\');
	require FORUM_ROOT.\'header.php\';

	// START SUBST - <!-- forum_main -->
	ob_start();

	echo $pun_pm_page_text;

	$tpl_temp = trim(ob_get_contents());
	$tpl_main = str_replace(\'<!-- forum_main -->\', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->

	require FORUM_ROOT.\'footer.php\';
}

$section = isset($_GET[\'section\']) ? $_GET[\'section\'] : null;

if ($section == \'pun_pm\' && !$forum_user[\'is_guest\'])
{
	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	if (!defined(\'PUN_PM_FUNCTIONS_LOADED\'))
		require $ext_info[\'path\'].\'/functions.php\';

	$pun_pm_page = isset($_GET[\'pmpage\']) ? $_GET[\'pmpage\'] : \'\';

	($hook = get_hook(\'pun_pm_pre_page_building\')) ? eval($hook) : null;

	// pun_pm_get_page() performs everything :)
	// Remember $pun_pm_page correction inside pun_pm_get_page() if this variable is incorrect
	$pun_pm_page_text = pun_pm_get_page($pun_pm_page);

	// Setup navigation menu
	$forum_page[\'main_menu\'] = array(
		\'inbox\'		=> \'<li class="first-item\'.($pun_pm_page == \'inbox\' ? \' active\' : \'\').\'"><a href="\'.forum_link($forum_url[\'pun_pm_inbox\']).\'"><span>\'.$lang_pun_pm[\'Inbox\'].\'</span></a></li>\',
		\'outbox\'	=> \'<li\'.(($pun_pm_page == \'outbox\') ? \' class="active"\' : \'\').\'><a href="\'.forum_link($forum_url[\'pun_pm_outbox\']).\'"><span>\'.$lang_pun_pm[\'Outbox\'].\'</span></a></li>\',
		\'write\'		=> \'<li\'.(($pun_pm_page == \'write\' || $pun_pm_page == \'compose\') ? \' class="active"\' : \'\').\'><a href="\'.forum_link($forum_url[\'pun_pm_write\']).\'"><span>\'.$lang_pun_pm[\'Compose message\'].\'</span></a></li>\',
	);

	// Setup breadcrumbs
	$forum_page[\'crumbs\'] = array(
		array($forum_config[\'o_board_title\'], forum_link($forum_url[\'index\'])),
		array($lang_pun_pm[\'Private messages\'], forum_link($forum_url[\'pun_pm\']))
	);

	if ($pun_pm_page == \'inbox\')
		$forum_page[\'crumbs\'][] = array($lang_pun_pm[\'Inbox\'], forum_link($forum_url[\'pun_pm_inbox\']));
	else if ($pun_pm_page == \'outbox\')
		$forum_page[\'crumbs\'][] = array($lang_pun_pm[\'Outbox\'], forum_link($forum_url[\'pun_pm_outbox\']));
	else if ($pun_pm_page == \'write\' || $pun_pm_page == \'compose\')
		$forum_page[\'crumbs\'][] = array($lang_pun_pm[\'Compose message\'], forum_link($forum_url[\'pun_pm_write\']));

	($hook = get_hook(\'pun_pm_pre_page_output\')) ? eval($hook) : null;

	define(\'FORUM_PAGE\', \'pun_pm-\'.$pun_pm_page);
	require FORUM_ROOT.\'header.php\';

	// START SUBST - <!-- forum_main -->
	ob_start();

	echo $pun_pm_page_text;

	$tpl_temp = trim(ob_get_contents());
	$tpl_main = str_replace(\'<!-- forum_main -->\', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->

	require FORUM_ROOT.\'footer.php\';
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_features_avatars_fieldset_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Admin options
if (!isset($lang_pun_pm))
{
	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
		include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
	else
		include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
}

$forum_page[\'group_count\'] = $forum_page[\'item_count\'] = 0;

?>
			<div class="content-head">
				<h2 class="hn"><span><?php echo $lang_pun_pm[\'Features title\'] ?></span></h2>
			</div>
			<fieldset class="frm-group group<?php echo ++$forum_page[\'group_count\'] ?>">
				<legend class="group-legend"><span><?php echo $lang_pun_pm[\'PM settings\'] ?></span></legend>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_pm[\'Inbox limit\'] ?></span><small><?php echo $lang_pun_pm[\'Inbox limit info\'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[pun_pm_inbox_size]" size="6" maxlength="6" value="<?php echo $forum_config[\'o_pun_pm_inbox_size\'] ?>" /></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_pm[\'Outbox limit\'] ?></span><small><?php echo $lang_pun_pm[\'Outbox limit info\'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[pun_pm_outbox_size]" size="6" maxlength="6" value="<?php echo $forum_config[\'o_pun_pm_outbox_size\'] ?>" /></span>
					</div>
				</div>
				<fieldset class="mf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<legend><span><?php echo $lang_pun_pm[\'Navigation links\'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_pm_show_new_count]" value="1"<?php if ($forum_config[\'o_pun_pm_show_new_count\'] == \'1\') echo \' checked="checked"\' ?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_pun_pm[\'Snow new count\'] ?></label>
						</div>
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_pm_show_global_link]" value="1"<?php if ($forum_config[\'o_pun_pm_show_global_link\'] == \'1\') echo \' checked="checked"\' ?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_pun_pm[\'Show global link\'] ?></label>
						</div>
					</div>
				</fieldset>
<?php ($hook = get_hook(\'pun_pm_aop_features_pre_pm_settings_fieldset_end\')) ? eval($hook) : null; ?>
			</fieldset>
<?php

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_features_validation' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$form[\'pun_pm_inbox_size\'] = (!isset($form[\'pun_pm_inbox_size\']) || (int) $form[\'pun_pm_inbox_size\'] <= 0) ? \'0\' : (string)(int) $form[\'pun_pm_inbox_size\'];
			$form[\'pun_pm_outbox_size\'] = (!isset($form[\'pun_pm_outbox_size\']) || (int) $form[\'pun_pm_outbox_size\'] <= 0) ? \'0\' : (string)(int) $form[\'pun_pm_outbox_size\'];

			if (!isset($form[\'pun_pm_show_new_count\']) || $form[\'pun_pm_show_new_count\'] != \'1\')
				$form[\'pun_pm_show_new_count\'] = \'0\';

			if (!isset($form[\'pun_pm_show_global_link\']) || $form[\'pun_pm_show_global_link\'] != \'1\')
				$form[\'pun_pm_show_global_link\'] = \'0\';

			($hook = get_hook(\'pun_pm_aop_features_validation_end\')) ? eval($hook) : null;

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'fn_delete_user_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$query = array(
				\'DELETE\'	=> \'pun_pm_messages\',
				\'WHERE\'		=> \'receiver_id = \'.$user_id.\' AND deleted_by_sender = 1\'
			);
			$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

			$query = array(
				\'UPDATE\'	=> \'pun_pm_messages\',
				\'SET\'		=> \'deleted_by_receiver = 1\',
				\'WHERE\'		=> \'receiver_id = \'.$user_id
			);
			$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'hd_visit_elements' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// \'New messages (N)\' link
			if (!$forum_user[\'is_guest\'] && $forum_config[\'o_pun_pm_show_new_count\'])
			{
				global $lang_pun_pm;

				if (!isset($lang_pun_pm))
				{
					if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
						include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
					else
						include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
				}

				// TODO: Do not include all functions, divide them into 2 files
				if(!defined(\'PUN_PM_FUNCTIONS_LOADED\'))
					require $ext_info[\'path\'].\'/functions.php\';

				($hook = get_hook(\'pun_pm_hd_visit_elements_pre_change\')) ? eval($hook) : null;

				//$visit_elements[\'<!-- forum_visit -->\'] = preg_replace(\'#(<p id="visit-links" class="options">.*?)(</p>)#\', \'$1 <span><a href="\'.forum_link($forum_url[\'pun_pm_inbox\']).\'">\'.pun_pm_unread_messages().\'</a></span>$2\', $visit_elements[\'<!-- forum_visit -->\']);
				if ($forum_user[\'g_read_board\'] == \'1\' && $forum_user[\'g_search\'] == \'1\')
				{
					$visit_links[\'pun_pm\'] = \'<span id="visit-pun_pm"><a href="\'.forum_link($forum_url[\'pun_pm_inbox\']).\'">\'.pun_pm_unread_messages().\'</a></span>\';
				}

				($hook = get_hook(\'pun_pm_hd_visit_elements_after_change\')) ? eval($hook) : null;
			}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'vt_row_pre_post_contacts_merge' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

global $lang_pun_pm;

			if (!isset($lang_pun_pm))
			{
				if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
					include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
				else
					include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
			}

			($hook = get_hook(\'pun_pm_pre_post_contacts_add\')) ? eval($hook) : null;

			// Links \'Send PM\' near posts
			if (!$forum_user[\'is_guest\'] && $cur_post[\'poster_id\'] > 1 && $forum_user[\'id\'] != $cur_post[\'poster_id\'])
				$forum_page[\'post_contacts\'][\'PM\'] = \'<span class="contact"><a title="\'.$lang_pun_pm[\'Send PM\'].\'" href="\'.forum_link($forum_url[\'pun_pm_post_link\'], $cur_post[\'poster_id\']).\'">\'.$lang_pun_pm[\'PM\'].\'</a></span>\';

			($hook = get_hook(\'pun_pm_after_post_contacts_add\')) ? eval($hook) : null;

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'fn_generate_navlinks_end' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Link \'PM\' in the main nav menu
			if (isset($links[\'profile\']) && $forum_config[\'o_pun_pm_show_global_link\'])
			{
				global $lang_pun_pm;

				if (!isset($lang_pun_pm))
				{
					if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
						include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
					else
						include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
				}

				if (\'pun_pm\' == substr(FORUM_PAGE, 0, 6))
					$links[\'profile\'] = str_replace(\' class="isactive"\', \'\', $links[\'profile\']);

				($hook = get_hook(\'pun_pm_pre_main_navlinks_add\')) ? eval($hook) : null;

				$links[\'profile\'] .= "\\n\\t\\t".\'<li id="nav_pun_pm"\'.(\'pun_pm\' == substr(FORUM_PAGE, 0, 6) ? \' class="isactive"\' : \'\').\'><a href="\'.forum_link($forum_url[\'pun_pm\']).\'"><span>\'.$lang_pun_pm[\'Private messages\'].\'</span></a></li>\';

				($hook = get_hook(\'pun_pm_after_main_navlinks_add\')) ? eval($hook) : null;
			}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'pf_view_details_pre_header_load' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Link in the profile
			if (!$forum_user[\'is_guest\'] && $forum_user[\'id\'] != $user[\'id\'])
			{
				if (!isset($lang_pun_pm))
				{
					if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
						include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
					else
						include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
				}

				($hook = get_hook(\'pun_pm_pre_profile_user_contact_add\')) ? eval($hook) : null;

				$forum_page[\'user_contact\'][\'PM\'] = \'<li><span>\'.$lang_pun_pm[\'PM\'].\': <a href="\'.forum_link($forum_url[\'pun_pm_post_link\'], $id).\'">\'.$lang_pun_pm[\'Send PM\'].\'</a></span></li>\';

				($hook = get_hook(\'pun_pm_after_profile_user_contact_add\')) ? eval($hook) : null;
			}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'pf_change_details_about_pre_header_load' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

// Link in the profile
			if (!$forum_user[\'is_guest\'] && $forum_user[\'id\'] != $user[\'id\'])
			{
				if (!isset($lang_pun_pm))
				{
					if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
						include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
					else
						include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
				}

				($hook = get_hook(\'pun_pm_pre_profile_user_contact_add\')) ? eval($hook) : null;

				$forum_page[\'user_contact\'][\'PM\'] = \'<li><span>\'.$lang_pun_pm[\'PM\'].\': <a href="\'.forum_link($forum_url[\'pun_pm_post_link\'], $id).\'">\'.$lang_pun_pm[\'Send PM\'].\'</a></span></li>\';

				($hook = get_hook(\'pun_pm_after_profile_user_contact_add\')) ? eval($hook) : null;
			}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'co_modify_url_scheme' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if ($forum_config[\'o_sef\'] != \'Default\' && file_exists($ext_info[\'path\'].\'/url/\'.$forum_config[\'o_sef\'].\'.php\'))
				require $ext_info[\'path\'].\'/url/\'.$forum_config[\'o_sef\'].\'.php\';
			else
				require $ext_info[\'path\'].\'/url/Default.php\';

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  're_rewrite_rules' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

$forum_rewrite_rules[\'/^pun_pm[\\/_-]?send(\\.html?|\\/)?$/i\'] = \'misc.php?action=pun_pm_send\';
			$forum_rewrite_rules[\'/^pun_pm[\\/_-]?compose[\\/_-]?([0-9]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=compose&receiver_id=$1\';
			$forum_rewrite_rules[\'/^pun_pm(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm\';
			$forum_rewrite_rules[\'/^pun_pm[\\/_-]?([0-9a-z]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=$1\';
			$forum_rewrite_rules[\'/^pun_pm[\\/_-]?([0-9a-z]+)[\\/_-]?(p|page\\/)([0-9]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=$1&p=$3\';
			$forum_rewrite_rules[\'/^pun_pm[\\/_-]?([0-9a-z]+)[\\/_-]?([0-9]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=$1&message_id=$2\';

			($hook = get_hook(\'pun_pm_after_rewrite_rules_set\')) ? eval($hook) : null;

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'ca_fn_generate_admin_menu_new_sublink' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'logo\',
\'path\'			=> FORUM_ROOT.\'extensions/logo\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/logo\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if (!isset($logo))
{	
	if ($forum_user[\'language\'] != \'English\' && file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/lang.php\'))
	{
		require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/lang.php\';
	}
	else
	{
		require $ext_info[\'path\'].\'/lang/English/lang.php\';
	}
}

$forum_url[\'admin_settings_logo\'] = \'admin/settings.php?section=logo\';

if (FORUM_PAGE_SECTION == \'settings\')
	{
		$forum_page[\'admin_submenu\'][\'settings-logo\'] = \'<li class="\'.((FORUM_PAGE == \'admin-settings-logo\') ? \'active\' : \'normal\').((empty($forum_page[\'admin_submenu\'])) ? \' first-item\' : \'\').\'"><a href="\'.forum_link($forum_url[\'admin_settings_logo\']).\'">\'.$logo[\'logo\'].\'</a></li>\';
	}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_pre_update_configuration' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'logo\',
\'path\'			=> FORUM_ROOT.\'extensions/logo\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/logo\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

switch ($section)
	{
		case \'logo\':
		{
	
	if($_FILES[\'logo_src\'][\'tmp_name\'])
		{
			$file=$_FILES[\'logo_src\'];
			$allow = array(
				\'types\' => array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF),
				\'mime_types\' => array(\'image/gif\', \'image/jpeg\', \'image/pjpeg\', \'image/png\', \'image/x-png\'),
				\'max_width\' => \'1000\',
				\'max_height\' => \'200\');

		if(getimagesize($file[\'tmp_name\']) && in_array($file[\'type\'], $allow[\'mime_types\']))
			{
				list($file[\'width\'],$file[\'height\'],$file[\'image_type\'])=getimagesize($file[\'tmp_name\']);



				if($file[\'width\']<=$allow[\'max_width\'] && $file[\'height\']<=$allow[\'max_height\'] && in_array($file[\'image_type\'],$allow[\'types\']))
							{
								$pos = strrpos($file[\'name\'], \'.\'); 
								$basename = substr($file[\'name\'], 0, $pos); 
								$ext = strtolower(substr($file[\'name\'], $pos+1));
								if ($forum_config[\'o_logo_src\'] != \'0\')
									@unlink(\'..\'.$forum_config[\'o_logo_src\']);
								if(!@rename($file[\'tmp_name\'], \'../img/logo.\'.$ext))
									{
										message($logo[\'error_chmod\']);
									}
								
								else
									{
								$form[\'logo_src\']=\'/img/logo.\'.$ext;
								@chmod(\'..\'.$forum_config[\'o_logo_src\'], 0644);
								$form[\'logo_width\']=$file[\'width\'];
								$form[\'logo_height\']=$file[\'height\'];
									}
							}
							else message($logo[\'error_format\']);
					}
					else message($logo[\'error_mime_type\']);
		}
		$forum_url[\'admin_settings_logo\'] = \'admin/settings.php?section=logo\';
		
		if($forum_config[\'o_logo_src\'] == \'0\') $form[\'logo_enable\'] = \'0\';
		if (!isset($form[\'logo_enable\']) || $form[\'logo_enable\'] != \'1\') $form[\'logo_enable\'] = \'0\';
		if (!isset($form[\'logo_hide_forum_title\']) || $form[\'logo_hide_forum_title\'] != \'1\') $form[\'logo_hide_forum_title\'] = \'0\';
}

default:
{
	break;
}
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'aop_new_section' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'logo\',
\'path\'			=> FORUM_ROOT.\'extensions/logo\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/logo\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if($section == \'logo\'){
if (!isset($logo)) {
	if ($forum_user[\'language\'] != \'English\' && file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/lang.php\')) {
		require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/lang.php\';
	} else {
		require $ext_info[\'path\'].\'/lang/English/lang.php\';
	}
}

$forum_page[\'group_count\'] = $forum_page[\'item_count\'] = $forum_page[\'fld_count\'] = 0;
$forum_url[\'admin_settings_logo\'] = \'admin/settings.php?section=logo\';

	// Setup breadcrumbs
	$forum_page[\'crumbs\'] = array(
		array($forum_config[\'o_board_title\'], forum_link($forum_url[\'index\'])),
		array($lang_admin_common[\'Forum administration\'], forum_link($forum_url[\'admin_index\'])),
		array($lang_admin_common[\'Settings\'], forum_link($forum_url[\'admin_settings_setup\'])),
		array($logo[\'logo\'], forum_link($forum_url[\'admin_settings_logo\']))
	);

	define(\'FORUM_PAGE_SECTION\', \'settings\');
	define(\'FORUM_PAGE\', \'admin-settings-logo\');
	require FORUM_ROOT.\'header.php\';

	// START SUBST - <!-- forum_main -->
	ob_start();
	$check_file=@fopen(\'..\'.$forum_config[\'o_logo_src\'], \'r\');

				?>
					<div class="main-content frm parted">
						<form class="frm-form" enctype="multipart/form-data" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url[\'admin_settings_logo\']) ?>">
							<div class="hidden">
								<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url[\'admin_settings_logo\'])) ?>" />
								<input type="hidden" name="form_sent" value="1" />
								<input type="hidden" name="form[0]" value="0" />
							</div>


					<div class="content-head">
						<h2 class="hn"><span><?php echo $logo[\'settings\'] ?></span></h2>
					</div>
						<fieldset class="frm-group group1">

						<?
							
							
						if($forum_config[\'o_logo_src\'] != \'0\'){
							?>

					<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
						<div class="sf-box text required">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $logo[\'current\'] ?></span></label>
							<?if($check_file) echo \'<div style="border: solid 1px #999; margin-left: 10px; height:\'.$forum_config[\'o_logo_height\'].\'px; width:\'.$forum_config[\'o_logo_width\'].\'px;"><img src="\'.$base_url.$forum_config[\'o_logo_src\'].\'"></div>\';
							else echo \'<span style="margin-left: 10px; position: relative; top: 2px;">\'.$logo[\'file_not_found\'].\'</span>\'; ?>
						</div>
					</div>
					
					<?
				}
				if (!is_writable(FORUM_ROOT.\'img/\'))
					echo \'<div class="ct-box warn-box"><p class="important">\'.$logo[\'not_writable\'].\'</p></div>\';
				else{
					?>
					<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
						<div class="sf-box text required">
							<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $logo[\'upload_logo\'] ?></span><small><?php echo $logo[\'upload_logo_desc\'] ?></small></label><br />
							<span class="fld-input"><input id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="logo_src" type="file" size="40" /></span>	
						</div>
					</div>
					<?}
					if($forum_config[\'o_logo_src\'] != \'0\'){
						?>
					<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
						<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[logo_enable]" value="1" <?php if ($forum_config[\'o_logo_enable\'] == \'1\') echo \'checked\' ?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $logo[\'enable_logo\'] ?>
							</label>
						</div>
					</div>

					<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
						<div class="sf-box select">
							<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $logo[\'logo_align\'] ?></span></label><br />
							<span class="fld-input"><select id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[logo_align]">
							<option value="left"<?php if ($forum_config[\'o_logo_align\'] == \'left\') echo \' selected="selected"\' ?>><? echo $logo[\'left\'] ?></option>
							<option value="right"<?php if ($forum_config[\'o_logo_align\'] == \'right\') echo \' selected="selected"\' ?>><? echo $logo[\'right\'] ?></option>
							</select></span>
						</div>
					</div>

					<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
						<div class="sf-box select">
							<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $logo[\'title_align\'] ?></span><small><?php echo $logo[\'horizontal\'] ?></small></label><br />
							<span class="fld-input"><select id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[logo_title_align]" >
							<option value="left"<?php if ($forum_config[\'o_logo_title_align\'] == \'left\') echo \' selected="selected"\' ?>><? echo $logo[\'left\'] ?></option>
							<option value="center"<?php if ($forum_config[\'o_logo_title_align\'] == \'center\') echo \' selected="selected"\' ?>><? echo $logo[\'center\'] ?></option>
							<option value="right"<?php if ($forum_config[\'o_logo_title_align\'] == \'right\') echo \' selected="selected"\' ?>><? echo $logo[\'right\'] ?></option>
							</select></span>
						</div>
					</div>

					<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
						<div class="sf-box select">
							<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $logo[\'title_vertical\'] ?></span><small><?php echo $logo[\'vertical\'] ?></small></label><br />
							<span class="fld-input"><select id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[logo_title_vertical]">
							<option value="top"<?php if ($forum_config[\'o_logo_title_vertical\'] == \'top\') echo \' selected="selected"\' ?>><? echo $logo[\'top\'] ?></option>
							<option value="middle"<?php if ($forum_config[\'o_logo_title_vertical\'] == \'middle\') echo \' selected="selected"\' ?>><? echo $logo[\'middle\'] ?></option>
							<option value="bottom"<?php if ($forum_config[\'o_logo_title_vertical\'] == \'bottom\') echo \' selected="selected"\' ?>><? echo $logo[\'bottom\'] ?></option>
							</select></span>
						</div>
					</div>

					<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
						<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[logo_hide_forum_title]" value="1" <?php if ($forum_config[\'o_logo_hide_forum_title\'] == \'1\') echo \'checked\';?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $logo[\'hide_board_title\'] ?></label>
						</div>
					</div>

					<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
						<div class="sf-box text">
							<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>">
								<span><? echo $logo[\'logo_link_url\'] ?></span>
								<small><? echo $logo[\'logo_link_url_example\'] ?></small>
							</label><br />
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[logo_link]" size="50" maxlength="255" value="<?php echo forum_htmlencode($forum_config[\'o_logo_link\']) ?>" /></span>
						</div>
					</div>

					<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
						<div class="sf-box text">
							<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>">
								<span><? echo $logo[\'logo_link_title\'] ?></span>
							</label><br />
							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[logo_link_title]" size="50" maxlength="255" value="<?php echo forum_htmlencode($forum_config[\'o_logo_link_title\']) ?>" /></span>
						</div>
					</div>
					<?
				}
				?>
			</fieldset>
						<div class="frm-buttons">
							<span class="submit primary"><input type="submit" name="save" value="<?php echo $lang_admin_common[\'Save changes\'] ?>" /></span>
						</div>
					</form>
				</div><?
}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
  'hd_gen_elements' => 
  array (
    0 => '$GLOBALS[\'ext_info_stack\'][] = array(
\'id\'				=> \'logo\',
\'path\'			=> FORUM_ROOT.\'extensions/logo\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/logo\',
\'dependencies\'	=> array (
)
);
$ext_info = $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];

if( $forum_config[\'o_logo_enable\'] == \'1\' && $forum_config[\'o_logo_align\'] == \'left\' )
				{
					$gen_elements[\'<!-- forum_title -->\'] = \'<table class="logo">\';
					$gen_elements[\'<!-- forum_title -->\'] .= \'<td class="logo" id="left" ><div  id="left" class="logo">\';
					$gen_elements[\'<!-- forum_title -->\'] .= ($forum_config[\'o_logo_link\'] != \'\') ? \'<a class="logo" href="\'.$forum_config[\'o_logo_link\'].\'" title="\'.$forum_config[\'o_logo_link_title\'].\'"></a></div></td>\' : \'</div></td>\';
					$gen_elements[\'<!-- forum_title -->\'] .= \'<td class="title" style="vertical-align:\'.$forum_config[\'o_logo_title_vertical\'].\'">\';
					$gen_elements[\'<!-- forum_title -->\'] .= ($forum_config[\'o_logo_hide_forum_title\'] != \'1\') ? \'<p id="brd-title" style="text-align:\'.$forum_config[\'o_logo_title_align\'].\'"><a href="\'.forum_link($forum_url[\'index\']).\'">\'.forum_htmlencode($forum_config[\'o_board_title\']).\'</a></p>\' : \'\';
					$gen_elements[\'<!-- forum_desc -->\'] = ($forum_config[\'o_board_desc\'] != \'\') ? \'<p id="brd-desc" style="text-align:\'.$forum_config[\'o_logo_title_align\'].\'">\'.forum_htmlencode($forum_config[\'o_board_desc\']).\'</p></td>\' : \'</td>\';
					$gen_elements[\'<!-- forum_desc -->\'] .= (isset($ad)) ? "<td><div style=\'float:right;background:#999\'>{$ad}</div></td></table>" : \'</table>\';
				}
			if( $forum_config[\'o_logo_enable\'] == \'1\' && $forum_config[\'o_logo_align\'] == \'right\' )
				{
					$gen_elements[\'<!-- forum_title -->\'] = (isset($ad)) ? "<table class=\'logo\'><td><div style=\'background:#999\'>{$ad}</div></td>" : \'<table class="logo">\';
					$gen_elements[\'<!-- forum_title -->\'] .= \'<td class="title" style="vertical-align:\'.$forum_config[\'o_logo_title_vertical\'].\'">\';
					$gen_elements[\'<!-- forum_title -->\'] .= ($forum_config[\'o_logo_hide_forum_title\'] != \'1\') ? \'<p id="brd-title" style="text-align:\'.$forum_config[\'o_logo_title_align\'].\'"><a href="\'.forum_link($forum_url[\'index\']).\'">\'.forum_htmlencode($forum_config[\'o_board_title\']).\'</a></p>\' : \'\';
					$gen_elements[\'<!-- forum_desc -->\'] = ($forum_config[\'o_board_desc\'] != \'\') ? \'<p id="brd-desc" style="text-align:\'.$forum_config[\'o_logo_title_align\'].\'">\'.forum_htmlencode($forum_config[\'o_board_desc\']).\'</p></td>\' : \'</td>\';
					$gen_elements[\'<!-- forum_desc -->\'] .= \'<td class="logo" id="right"><div id="right" class="logo">\';
					$gen_elements[\'<!-- forum_desc -->\'] .= ($forum_config[\'o_logo_link\'] != \'\') ? \'<a class="logo" href="\'.$forum_config[\'o_logo_link\'].\'" title="\'.$forum_config[\'o_logo_link_title\'].\'"></a></div></td></table>\' : \'</div></td></table>\';
					
				}

array_pop($GLOBALS[\'ext_info_stack\']);
$ext_info = empty($GLOBALS[\'ext_info_stack\']) ? array() : $GLOBALS[\'ext_info_stack\'][count($GLOBALS[\'ext_info_stack\']) - 1];
',
  ),
);

?>