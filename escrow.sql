-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 25, 2013 at 11:47 AM
-- Server version: 5.5.31
-- PHP Version: 5.4.4-14+deb7u3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `escrow`
--

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE IF NOT EXISTS `bans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(200) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `expire` int(10) unsigned DEFAULT NULL,
  `ban_creator` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `btcaddresses`
--

CREATE TABLE IF NOT EXISTS `btcaddresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL,
  `btcaddress` varchar(34) NOT NULL,
  `balance` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `btcaddresses`
--

INSERT INTO `btcaddresses` (`id`, `status`, `btcaddress`, `balance`) VALUES
(1, 1, 'your_main_bitcoin_blockchain_address', 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(80) NOT NULL DEFAULT 'New Category',
  `disp_position` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `cat_name`, `disp_position`) VALUES
(5, 'Reported problems', 3),
(2, 'Tablica.TOR.pl', 0),
(3, 'Sprawy zwiazane z systemem Escrow', 1),
(4, 'Kosz', 2);

-- --------------------------------------------------------

--
-- Table structure for table `censoring`
--

CREATE TABLE IF NOT EXISTS `censoring` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `search_for` varchar(60) NOT NULL DEFAULT '',
  `replace_with` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `conf_name` varchar(255) NOT NULL DEFAULT '',
  `conf_value` text,
  PRIMARY KEY (`conf_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`conf_name`, `conf_value`) VALUES
('o_cur_version', '1.4.2'),
('o_database_revision', '5'),
('o_board_title', 'Escrow ToRepublic'),
('o_board_desc', NULL),
('o_pun_pm_inbox_size', '100'),
('o_default_timezone', '0'),
('o_time_format', 'H'),
('o_date_format', 'Y-m-d'),
('o_check_for_updates', '1'),
('o_check_for_versions', '1'),
('o_timeout_visit', '5400'),
('o_timeout_online', '300'),
('o_redirect_delay', '0'),
('o_show_version', '0'),
('o_show_user_info', '1'),
('o_show_post_count', '1'),
('o_signatures', '1'),
('o_smilies', '1'),
('o_smilies_sig', '1'),
('o_make_links', '1'),
('o_default_lang', 'English'),
('o_default_style', 'Oxygen'),
('o_default_user_group', '3'),
('o_topic_review', '15'),
('o_disp_topics_default', '60'),
('o_disp_posts_default', '50'),
('o_indent_num_spaces', '4'),
('o_quote_depth', '3'),
('o_quickpost', '1'),
('o_users_online', '0'),
('o_censoring', '0'),
('o_ranks', '0'),
('o_show_dot', '0'),
('o_topic_views', '1'),
('o_quickjump', '1'),
('o_gzip', '1'),
('o_additional_navlinks', '0 = <a href="sell.php">Sell</a>\n1 = <a href="escrows.php">Escrow</a>'),
('o_report_method', '0'),
('o_regs_report', '0'),
('o_default_email_setting', '1'),
('o_mailing_list', 'bitcoinpower@tormail.org'),
('o_avatars', '1'),
('o_avatars_dir', 'img/avatars'),
('o_avatars_width', '60'),
('o_avatars_height', '60'),
('o_avatars_size', '15360'),
('o_search_all_forums', '0'),
('o_sef', 'Default'),
('o_admin_email', 'bitcoinpower@tormail.org'),
('o_webmaster_email', 'bitcoinpower@tormail.org'),
('o_subscriptions', '0'),
('o_smtp_host', NULL),
('o_smtp_user', NULL),
('o_smtp_pass', NULL),
('o_smtp_ssl', '0'),
('o_regs_allow', '1'),
('o_regs_verify', '0'),
('o_announcement', '0'),
('o_announcement_heading', 'Sample announcement'),
('o_announcement_message', '<p>Enter your announcement here.</p>'),
('o_rules', '0'),
('o_rules_message', 'Enter your rules here.'),
('o_maintenance', '0'),
('o_maintenance_message', 'The forums are temporarily down for maintenance. Please try again in a few minutes.<br /><br />Administrator'),
('o_default_dst', '0'),
('p_message_bbcode', '1'),
('p_message_img_tag', '1'),
('p_message_all_caps', '1'),
('p_subject_all_caps', '1'),
('p_sig_all_caps', '1'),
('p_sig_bbcode', '1'),
('p_sig_img_tag', '0'),
('p_sig_length', '400'),
('p_sig_lines', '4'),
('p_allow_banned_email', '1'),
('p_allow_dupe_email', '0'),
('p_force_guest_email', '1'),
('o_show_moderators', '0'),
('o_mask_passwords', '1'),
('o_pun_pm_outbox_size', '100'),
('o_pun_pm_show_new_count', '1'),
('o_pun_pm_show_global_link', '0'),
('o_problem_forum_id', '6'),
('o_problem_archive_id', '7'),
('o_regular_commission_value', '3'),
('o_problem_commission_value', '10'),
('o_logo_src', '/img/logo.png'),
('o_logo_enable', '1'),
('o_logo_hide_forum_title', '1'),
('o_logo_width', '562'),
('o_logo_height', '100'),
('o_logo_align', 'left'),
('o_logo_title_align', 'right'),
('o_logo_title_vertical', 'top'),
('o_logo_link', 'http://nco5ranerted3nkt.onion/escrow'),
('o_logo_link_title', 'Escrow ToRepublic'),
('o_minimum_escrow_value', '0.1'),
('o_escrow_duration', '1'),
('o_trade_category_id', '2');

-- --------------------------------------------------------

--
-- Table structure for table `escrows`
--

CREATE TABLE IF NOT EXISTS `escrows` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `buyerid` int(11) NOT NULL,
  `sellerid` int(11) NOT NULL,
  `amount` float NOT NULL,
  `subject` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` int(11) NOT NULL,
  `recivedtime` int(11) NOT NULL,
  `btcaddress` varchar(34) NOT NULL,
  `problemoccured` tinyint(4) NOT NULL,
  `moderatorid` int(11) NOT NULL,
  `problemid` int(11) NOT NULL,
  `problemreason` tinyint(4) NOT NULL,
  `problemclaim` tinyint(4) NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `escrows`
--

INSERT INTO `escrows` (`index`, `time`, `buyerid`, `sellerid`, `amount`, `subject`, `status`, `recivedtime`, `btcaddress`, `problemoccured`, `moderatorid`, `problemid`, `problemreason`, `problemclaim`) VALUES
(4, 1376909065, 8, 10, 1, 'test_escrow', 3, 1377254680, '17gCUV38JDVRnPULVfzWavDZ6jDEgwp1s3', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

CREATE TABLE IF NOT EXISTS `extensions` (
  `id` varchar(150) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(25) NOT NULL DEFAULT '',
  `description` text,
  `author` varchar(50) NOT NULL DEFAULT '',
  `uninstall` text,
  `uninstall_note` text,
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `dependencies` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `title`, `version`, `description`, `author`, `uninstall`, `uninstall_note`, `disabled`, `dependencies`) VALUES
('pun_repository', 'PunBB Repository', '1.3.1', 'Feel free to download and install extensions from PunBB repository.', 'PunBB Development Team', NULL, NULL, 0, '||'),
('pun_pm', 'Private Messaging', '2.4.2', 'Allows users to send and receive private messages.', 'PunBB Development Team', '// Delete extension options from the config\n		forum_config_remove(array(\n			''o_pun_pm_outbox_size'',\n			''o_pun_pm_inbox_size'',\n			''o_pun_pm_show_new_count'',\n			''o_pun_pm_show_global_link''));\n\n		$forum_db->drop_table(''pun_pm_messages'');\n\n		$forum_db->drop_field(''users'', ''pun_pm_new_messages'');\n		$forum_db->drop_field(''users'', ''pun_pm_long_subject'');', 'WARNING! All users messages will be removed during the uninstall process. It is strongly recommended you to disable "Private Messages" extension instead or to upgrade it without uninstalling.', 0, '||'),
('logo', 'Logo', '0.5.2', 'Add logo in header', 'floop', '//unlink(''..''.$forum_config[''o_logo_src'']);\r\n		forum_config_remove(''o_logo_src'');\r\n		forum_config_remove(''o_logo_enable'');\r\n		forum_config_remove(''o_logo_hide_forum_title'');\r\n		forum_config_remove(''o_logo_width'');\r\n		forum_config_remove(''o_logo_height'');\r\n		forum_config_remove(''o_logo_align'');\r\n		forum_config_remove(''o_logo_title_align'');\r\n		forum_config_remove(''o_logo_title_vertical'');\r\n		forum_config_remove(''o_logo_link'');\r\n		forum_config_remove(''o_logo_link_title'');', NULL, 0, '||');

-- --------------------------------------------------------

--
-- Table structure for table `extension_hooks`
--

CREATE TABLE IF NOT EXISTS `extension_hooks` (
  `id` varchar(150) NOT NULL DEFAULT '',
  `extension_id` varchar(50) NOT NULL DEFAULT '',
  `code` text,
  `installed` int(10) unsigned NOT NULL DEFAULT '0',
  `priority` tinyint(1) unsigned NOT NULL DEFAULT '5',
  PRIMARY KEY (`id`,`extension_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `extension_hooks`
--

INSERT INTO `extension_hooks` (`id`, `extension_id`, `code`, `installed`, `priority`) VALUES
('aex_section_manage_end', 'pun_repository', 'if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php''))\n	include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php'';\nelse\n	include $ext_info[''path''].''/lang/English/pun_repository.php'';\n\nrequire_once $ext_info[''path''].''/pun_repository.php'';\n\n($hook = get_hook(''pun_repository_pre_display_ext_list'')) ? eval($hook) : null;\n\n?>\n	<div class="main-subhead">\n		<h2 class="hn"><span><?php echo $lang_pun_repository[''PunBB Repository''] ?></span></h2>\n	</div>\n	<div class="main-content main-extensions">\n		<p class="content-options options"><a href="<?php echo $base_url ?>/admin/extensions.php?pun_repository_update&amp;csrf_token=<?php echo generate_form_token(''pun_repository_update'') ?>"><?php echo $lang_pun_repository[''Clear cache''] ?></a></p>\n<?php\n\nif (!defined(''PUN_REPOSITORY_EXTENSIONS_LOADED'') && file_exists(FORUM_CACHE_DIR.''cache_pun_repository.php''))\n	include FORUM_CACHE_DIR.''cache_pun_repository.php'';\n\nif (!defined(''FORUM_EXT_VERSIONS_LOADED'') && file_exists(FORUM_CACHE_DIR.''cache_ext_version_notifications.php''))\n	include FORUM_CACHE_DIR.''cache_ext_version_notifications.php'';\n\n// Regenerate cache only if automatic updates are enabled and if the cache is more than 12 hours old\nif (!defined(''PUN_REPOSITORY_EXTENSIONS_LOADED'') || !defined(''FORUM_EXT_VERSIONS_LOADED'') || ($pun_repository_extensions_timestamp < $forum_ext_versions_update_cache))\n{\n	$pun_repository_error = '''';\n\n	if (pun_repository_generate_cache($pun_repository_error))\n	{\n		require FORUM_CACHE_DIR.''cache_pun_repository.php'';\n	}\n	else\n	{\n\n		?>\n		<div class="ct-box warn-box">\n			<p class="warn"><?php echo $pun_repository_error ?></p>\n		</div>\n		<?php\n\n		// Stop processing hook\n		return;\n	}\n}\n\n$pun_repository_parsed = array();\n$pun_repository_skipped = array();\n\n// Display information about extensions in repository\nforeach ($pun_repository_extensions as $pun_repository_ext)\n{\n	// Skip installed extensions\n	if (isset($inst_exts[$pun_repository_ext[''id'']]))\n	{\n		$pun_repository_skipped[''installed''][] = $pun_repository_ext[''id''];\n		continue;\n	}\n\n	// Skip uploaded extensions (including incorrect ones)\n	if (is_dir(FORUM_ROOT.''extensions/''.$pun_repository_ext[''id'']))\n	{\n		$pun_repository_skipped[''has_dir''][] = $pun_repository_ext[''id''];\n		continue;\n	}\n\n	// Check for unresolved dependencies\n	if (isset($pun_repository_ext[''dependencies'']))\n		$pun_repository_ext[''dependencies''] = pun_repository_check_dependencies($inst_exts, $pun_repository_ext[''dependencies'']);\n\n	if (empty($pun_repository_ext[''dependencies''][''unresolved'']))\n	{\n		// ''Download and install'' link\n		$pun_repository_ext[''options''] = array(''<a href="''.$base_url.''/admin/extensions.php?pun_repository_download_and_install=''.$pun_repository_ext[''id''].''&amp;csrf_token=''.generate_form_token(''pun_repository_download_and_install_''.$pun_repository_ext[''id'']).''">''.$lang_pun_repository[''Download and install''].''</a>'');\n	}\n	else\n		$pun_repository_ext[''options''] = array();\n\n	$pun_repository_parsed[] = $pun_repository_ext[''id''];\n\n	// Direct links to archives\n	$pun_repository_ext[''download_links''] = array();\n	foreach (array(''zip'', ''tgz'', ''7z'') as $pun_repository_archive_type)\n		$pun_repository_ext[''download_links''][] = ''<a href="''.PUN_REPOSITORY_URL.''/''.$pun_repository_ext[''id''].''/''.$pun_repository_ext[''id''].''.''.$pun_repository_archive_type.''">''.$pun_repository_archive_type.''</a>'';\n\n	($hook = get_hook(''pun_repository_pre_display_ext_info'')) ? eval($hook) : null;\n\n	// Let''s ptint it all out\n?>\n		<div class="ct-box info-box extension available" id="<?php echo $pun_repository_ext[''id''] ?>">\n			<h3 class="ct-legend hn"><span><?php echo forum_htmlencode($pun_repository_ext[''title'']).'' ''.$pun_repository_ext[''version''] ?></span></h3>\n			<p><?php echo forum_htmlencode($pun_repository_ext[''description'']) ?></p>\n<?php\n\n	// List extension dependencies\n	if (!empty($pun_repository_ext[''dependencies''][''dependency'']))\n		echo ''\n			<p>'', $lang_pun_repository[''Dependencies:''], '' '', implode('', '', $pun_repository_ext[''dependencies''][''dependency'']), ''</p>'';\n\n?>\n			<p><?php echo $lang_pun_repository[''Direct download links:''], '' '', implode('' '', $pun_repository_ext[''download_links'']) ?></p>\n<?php\n\n	// List unresolved dependencies\n	if (!empty($pun_repository_ext[''dependencies''][''unresolved'']))\n		echo ''\n			<div class="ct-box warn-box">\n				<p class="warn">'', $lang_pun_repository[''Resolve dependencies:''], '' '', implode('', '', array_map(create_function(''$dep'', ''return \\''<a href="#\\''.$dep.\\''">\\''.$dep.\\''</a>\\'';''), $pun_repository_ext[''dependencies''][''unresolved''])), ''</p>\n			</div>'';\n\n	// Actions\n	if (!empty($pun_repository_ext[''options'']))\n		echo ''\n			<p class="options">'', implode('' '', $pun_repository_ext[''options'']), ''</p>'';\n\n?>\n		</div>\n<?php\n\n}\n\n?>\n		<div class="ct-box warn-box">\n			<p class="warn"><?php echo $lang_pun_repository[''Files mode and owner''] ?></p>\n		</div>\n<?php\n\nif (empty($pun_repository_parsed) && (count($pun_repository_skipped[''installed'']) > 0 || count($pun_repository_skipped[''has_dir'']) > 0))\n{\n	($hook = get_hook(''pun_repository_no_extensions'')) ? eval($hook) : null;\n\n	?>\n		<div class="ct-box info-box">\n			<p class="warn"><?php echo $lang_pun_repository[''All installed or downloaded''] ?></p>\n		</div>\n	<?php\n\n}\n\n($hook = get_hook(''pun_repository_after_ext_list'')) ? eval($hook) : null;\n\n?>\n	</div>\n<?php', 1375269731, 5),
('aex_new_action', 'pun_repository', '// Clear pun_repository cache\nif (isset($_GET[''pun_repository_update'']))\n{\n	// Validate CSRF token\n	if (!isset($_POST[''csrf_token'']) && (!isset($_GET[''csrf_token'']) || $_GET[''csrf_token''] !== generate_form_token(''pun_repository_update'')))\n		csrf_confirm_form();\n\n	if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php''))\n		include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php'';\n	else\n		include $ext_info[''path''].''/lang/English/pun_repository.php'';\n\n	@unlink(FORUM_CACHE_DIR.''cache_pun_repository.php'');\n	if (file_exists(FORUM_CACHE_DIR.''cache_pun_repository.php''))\n		message($lang_pun_repository[''Unable to remove cached file''], '''', $lang_pun_repository[''PunBB Repository'']);\n\n	redirect($base_url.''/admin/extensions.php?section=manage'', $lang_pun_repository[''Cache has been successfully cleared'']);\n}\n\nif (isset($_GET[''pun_repository_download_and_install'']))\n{\n	$ext_id = preg_replace(''/[^0-9a-z_]/'', '''', $_GET[''pun_repository_download_and_install'']);\n\n	// Validate CSRF token\n	if (!isset($_POST[''csrf_token'']) && (!isset($_GET[''csrf_token'']) || $_GET[''csrf_token''] !== generate_form_token(''pun_repository_download_and_install_''.$ext_id)))\n		csrf_confirm_form();\n\n	// TODO: Should we check again for unresolved dependencies here?\n\n	if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php''))\n		include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php'';\n	else\n		include $ext_info[''path''].''/lang/English/pun_repository.php'';\n\n	require_once $ext_info[''path''].''/pun_repository.php'';\n\n	($hook = get_hook(''pun_repository_download_and_install_start'')) ? eval($hook) : null;\n\n	// Download extension\n	$pun_repository_error = pun_repository_download_extension($ext_id, $ext_data);\n\n	if ($pun_repository_error == '''')\n	{\n		if (empty($ext_data))\n			redirect($base_url.''/admin/extensions.php?section=manage'', $lang_pun_repository[''Incorrect manifest.xml'']);\n\n		// Validate manifest\n		$errors = validate_manifest($ext_data, $ext_id);\n		if (!empty($errors))\n			redirect($base_url.''/admin/extensions.php?section=manage'', $lang_pun_repository[''Incorrect manifest.xml'']);\n\n		// Everything is OK. Start installation.\n		redirect($base_url.''/admin/extensions.php?install=''.urlencode($ext_id), $lang_pun_repository[''Download successful'']);\n	}\n\n	($hook = get_hook(''pun_repository_download_and_install_end'')) ? eval($hook) : null;\n}\n\n// Handling the download and update extension action\nif (isset($_GET[''pun_repository_download_and_update'']))\n{\n	$ext_id = preg_replace(''/[^0-9a-z_]/'', '''', $_GET[''pun_repository_download_and_update'']);\n\n	// Validate CSRF token\n	if (!isset($_POST[''csrf_token'']) && (!isset($_GET[''csrf_token'']) || $_GET[''csrf_token''] !== generate_form_token(''pun_repository_download_and_update_''.$ext_id)))\n		csrf_confirm_form();\n\n	if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php''))\n		include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php'';\n	else\n		include $ext_info[''path''].''/lang/English/pun_repository.php'';\n\n	require_once $ext_info[''path''].''/pun_repository.php'';\n\n	$pun_repository_error = '''';\n\n	($hook = get_hook(''pun_repository_download_and_update_start'')) ? eval($hook) : null;\n\n	pun_repository_rm_recursive(FORUM_ROOT.''extensions/''.$ext_id.''.old'');\n\n	// Check dependancies\n	$query = array(\n		''SELECT''	=> ''e.id'',\n		''FROM''		=> ''extensions AS e'',\n		''WHERE''		=> ''e.disabled=0 AND e.dependencies LIKE \\''%|''.$forum_db->escape($ext_id).''|%\\''''\n	);\n\n	($hook = get_hook(''aex_qr_get_disable_dependencies'')) ? eval($hook) : null;\n	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);\n\n	if ($forum_db->num_rows($result) != 0)\n	{\n		$dependency = $forum_db->fetch_assoc($result);\n		$pun_repository_error = sprintf($lang_admin[''Disable dependency''], $dependency[''id'']);\n	}\n\n	if ($pun_repository_error == '''' && ($ext_id != $ext_info[''id'']))\n	{\n		// Disable extension\n		$query = array(\n			''UPDATE''	=> ''extensions'',\n			''SET''		=> ''disabled=1'',\n			''WHERE''		=> ''id=\\''''.$forum_db->escape($ext_id).''\\''''\n		);\n\n		($hook = get_hook(''aex_qr_update_disabled_status'')) ? eval($hook) : null;\n		$forum_db->query_build($query) or error(__FILE__, __LINE__);\n\n		// Regenerate the hooks cache\n		require_once FORUM_ROOT.''include/cache.php'';\n		generate_hooks_cache();\n	}\n\n	if ($pun_repository_error == '''')\n	{\n		if ($ext_id == $ext_info[''id''])\n		{\n			// Hey! That''s me!\n			// All the necessary files should be included before renaming old directory\n			// NOTE: Self-updating is to be tested more in real-life conditions\n			if (!defined(''PUN_REPOSITORY_TAR_EXTRACT_INCLUDED''))\n				require $ext_info[''path''].''/pun_repository_tar_extract.php'';\n		}\n\n		$pun_repository_error = pun_repository_download_extension($ext_id, $ext_data, FORUM_ROOT.''extensions/''.$ext_id.''.new''); // Download the extension\n\n		if ($pun_repository_error == '''')\n		{\n			if (is_writable(FORUM_ROOT.''extensions/''.$ext_id))\n				pun_repository_dir_copy(FORUM_ROOT.''extensions/''.$ext_id.''.new/''.$ext_id, FORUM_ROOT.''extensions/''.$ext_id);\n			else\n				$pun_repository_error = sprintf($lang_pun_repository[''Copy fail''], FORUM_ROOT.''extensions/''.$ext_id);\n		}\n	}\n\n	if ($pun_repository_error == '''')\n	{\n		// Do we have extension data at all? :-)\n		if (empty($ext_data))\n			$errors = array(true);\n\n		// Validate manifest\n		if (empty($errors))\n			$errors = validate_manifest($ext_data, $ext_id);\n\n		if (!empty($errors))\n			$pun_repository_error = $lang_pun_repository[''Incorrect manifest.xml''];\n	}\n\n	if ($pun_repository_error == '''')\n	{\n		($hook = get_hook(''pun_repository_download_and_update_ok'')) ? eval($hook) : null;\n\n		// Everything is OK. Start installation.\n		pun_repository_rm_recursive(FORUM_ROOT.''extensions/''.$ext_id.''.new'');\n		redirect($base_url.''/admin/extensions.php?install=''.urlencode($ext_id), $lang_pun_repository[''Download successful'']);\n	}\n\n	($hook = get_hook(''pun_repository_download_and_update_error'')) ? eval($hook) : null;\n\n	// Enable extension\n	$query = array(\n		''UPDATE''	=> ''extensions'',\n		''SET''		=> ''disabled=0'',\n		''WHERE''		=> ''id=\\''''.$forum_db->escape($ext_id).''\\''''\n	);\n\n	($hook = get_hook(''aex_qr_update_enabled_status'')) ? eval($hook) : null;\n	$forum_db->query_build($query) or error(__FILE__, __LINE__);\n\n	// Regenerate the hooks cache\n	require_once FORUM_ROOT.''include/cache.php'';\n	generate_hooks_cache();\n\n	($hook = get_hook(''pun_repository_download_and_update_end'')) ? eval($hook) : null;\n}\n\n// Do we have some error?\nif (!empty($pun_repository_error))\n{\n	// Setup breadcrumbs\n	$forum_page[''crumbs''] = array(\n		array($forum_config[''o_board_title''], forum_link($forum_url[''index''])),\n		array($lang_admin_common[''Forum administration''], forum_link($forum_url[''admin_index''])),\n		array($lang_admin_common[''Extensions''], forum_link($forum_url[''admin_extensions_manage''])),\n		array($lang_admin_common[''Manage extensions''], forum_link($forum_url[''admin_extensions_manage''])),\n		$lang_pun_repository[''PunBB Repository'']\n	);\n\n	($hook = get_hook(''pun_repository__pre_header_load'')) ? eval($hook) : null;\n\n	define(''FORUM_PAGE_SECTION'', ''extensions'');\n	define(''FORUM_PAGE'', ''admin-extensions-pun-repository'');\n	require FORUM_ROOT.''header.php'';\n\n	// START SUBST - <!-- forum_main -->\n	ob_start();\n\n	($hook = get_hook(''pun_repository_display_error_output_start'')) ? eval($hook) : null;\n\n?>\n	<div class="main-subhead">\n		<h2 class="hn"><span><?php echo $lang_pun_repository[''PunBB Repository''] ?></span></h2>\n	</div>\n	<div class="main-content">\n		<div class="ct-box warn-box">\n			<p class="warn"><?php echo $pun_repository_error ?></p>\n		</div>\n	</div>\n<?php\n\n	($hook = get_hook(''pun_repository_display_error_pre_ob_end'')) ? eval($hook) : null;\n\n	$tpl_temp = trim(ob_get_contents());\n	$tpl_main = str_replace(''<!-- forum_main -->'', $tpl_temp, $tpl_main);\n	ob_end_clean();\n	// END SUBST - <!-- forum_main -->\n\n	require FORUM_ROOT.''footer.php'';\n}', 1375269731, 5),
('aex_section_manage_pre_header_load', 'pun_repository', 'if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php''))\n	include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/pun_repository.php'';\nelse\n	include $ext_info[''path''].''/lang/English/pun_repository.php'';\n\nrequire_once $ext_info[''path''].''/pun_repository.php'';\n\nif (!defined(''PUN_REPOSITORY_EXTENSIONS_LOADED'') && file_exists(FORUM_CACHE_DIR.''cache_pun_repository.php''))\n	include FORUM_CACHE_DIR.''cache_pun_repository.php'';\n\nif (!defined(''FORUM_EXT_VERSIONS_LOADED'') && file_exists(FORUM_CACHE_DIR.''cache_ext_version_notifications.php''))\n	include FORUM_CACHE_DIR.''cache_ext_version_notifications.php'';\n\n// Regenerate cache only if automatic updates are enabled and if the cache is more than 12 hours old\nif (!defined(''PUN_REPOSITORY_EXTENSIONS_LOADED'') || !defined(''FORUM_EXT_VERSIONS_LOADED'') || ($pun_repository_extensions_timestamp < $forum_ext_versions_update_cache))\n{\n	if (pun_repository_generate_cache($pun_repository_error))\n		require FORUM_CACHE_DIR.''cache_pun_repository.php'';\n}', 1375269731, 5),
('aex_section_manage_pre_ext_actions', 'pun_repository', 'if (defined(''PUN_REPOSITORY_EXTENSIONS_LOADED'') && isset($pun_repository_extensions[$id]) && version_compare($ext[''version''], $pun_repository_extensions[$id][''version''], ''<'') && is_writable(FORUM_ROOT.''extensions/''.$id))\n{\n	// Check for unresolved dependencies\n	if (isset($pun_repository_extensions[$id][''dependencies'']))\n		$pun_repository_extensions[$id][''dependencies''] = pun_repository_check_dependencies($inst_exts, $pun_repository_extensions[$id][''dependencies'']);\n\n	if (empty($pun_repository_extensions[$id][''dependencies''][''unresolved'']))\n		$forum_page[''ext_actions''][] = ''<span><a href="''.$base_url.''/admin/extensions.php?pun_repository_download_and_update=''.$id.''&amp;csrf_token=''.generate_form_token(''pun_repository_download_and_update_''.$id).''">''.$lang_pun_repository[''Download and update''].''</a></span>'';\n}', 1375269731, 5),
('co_common', 'pun_repository', '$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[''id'']));', 1375269731, 5),
('pf_change_details_settings_validation', 'pun_pm', '// Validate option ''quote beginning of message''\n			if (!isset($_POST[''form''][''pun_pm_long_subject'']) || $_POST[''form''][''pun_pm_long_subject''] != ''1'')\n				$form[''pun_pm_long_subject''] = ''0'';\n			else\n				$form[''pun_pm_long_subject''] = ''1'';', 1375442767, 5),
('pf_change_details_settings_email_fieldset_end', 'pun_pm', '// Per-user option ''quote beginning of message''\nif ($forum_config[''p_message_bbcode''] == ''1'')\n{\n	if (!isset($lang_pun_pm))\n	{\n		if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php''))\n			include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php'';\n		else\n			include $ext_info[''path''].''/lang/English/''.$ext_info[''id''].''.php'';\n	}\n\n	$forum_page[''item_count''] = 0;\n\n?>\n			<fieldset class="frm-group group<?php echo ++$forum_page[''group_count''] ?>">\n				<legend class="group-legend"><strong><?php echo $lang_pun_pm[''PM settings''] ?></strong></legend>\n				<fieldset class="mf-set set<?php echo ++$forum_page[''item_count''] ?>">\n					<legend><span><?php echo $lang_pun_pm[''Private messages''] ?></span></legend>\n					<div class="mf-box">\n						<div class="mf-item">\n							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[''fld_count''] ?>" name="form[pun_pm_long_subject]" value="1"<?php if ($user[''pun_pm_long_subject''] == ''1'') echo '' checked="checked"'' ?> /></span>\n							<label for="fld<?php echo $forum_page[''fld_count''] ?>"><?php echo $lang_pun_pm[''Begin message quote''] ?></label>\n						</div>\n					</div>\n				</fieldset>\n<?php ($hook = get_hook(''pun_pm_pf_change_details_settings_pre_pm_settings_fieldset_end'')) ? eval($hook) : null; ?>\n			</fieldset>\n<?php\n}\nelse\n	echo "\\t\\t\\t".''<input type="hidden" name="form[pun_pm_long_subject]" value="''.$user[''pun_pm_long_subject''].''" />''."\\n";', 1375442767, 5),
('hd_head', 'pun_pm', '// Incuding styles for pun_pm\n			if (defined(''FORUM_PAGE'') && ''pun_pm'' == substr(FORUM_PAGE, 0, 6))\n			{\n				if ($forum_user[''style''] != ''Oxygen'' && file_exists($ext_info[''path''].''/css/''.$forum_user[''style''].''/pun_pm.min.css''))\n					$forum_loader->add_css($ext_info[''url''].''/css/''.$forum_user[''style''].''/pun_pm.min.css'', array(''type'' => ''url'', ''media'' => ''screen''));\n				else\n					$forum_loader->add_css($ext_info[''url''].''/css/Oxygen/pun_pm.min.css'', array(''type'' => ''url'', ''media'' => ''screen''));\n			}', 1375442767, 5),
('mi_new_action', 'pun_pm', 'if ($action == ''pun_pm_send'' && !$forum_user[''is_guest''])\n{\n	if(!defined(''PUN_PM_FUNCTIONS_LOADED''))\n		require $ext_info[''path''].''/functions.php'';\n\n	if (!isset($lang_pun_pm))\n	{\n		if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php''))\n			include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php'';\n		else\n			include $ext_info[''path''].''/lang/English/''.$ext_info[''id''].''.php'';\n	}\n\n	$pun_pm_body = isset($_POST[''req_message'']) ? $_POST[''req_message''] : '''';\n	$pun_pm_subject = isset($_POST[''pm_subject'']) ? $_POST[''pm_subject''] : '''';\n	$pun_pm_receiver_username = isset($_POST[''pm_receiver'']) ? $_POST[''pm_receiver''] : '''';\n	$pun_pm_message_id = isset($_POST[''message_id'']) ? (int) $_POST[''message_id''] : false;\n\n	if (isset($_POST[''send_action'']) && in_array($_POST[''send_action''], array(''send'', ''draft'', ''delete'', ''preview'')))\n		$pun_pm_send_action = $_POST[''send_action''];\n	elseif (isset($_POST[''pm_draft'']))\n		$pun_pm_send_action = ''draft'';\n	elseif (isset($_POST[''pm_send'']))\n		$pun_pm_send_action = ''send'';\n	elseif (isset($_POST[''pm_delete'']))\n		$pun_pm_send_action = ''delete'';\n	else\n		$pun_pm_send_action = ''preview'';\n\n	($hook = get_hook(''pun_pm_after_send_action_set'')) ? eval($hook) : null;\n\n	if ($pun_pm_send_action == ''draft'')\n	{\n		// Try to save the message as draft\n		// Inside this function will be a redirect, if everything is ok\n		$pun_pm_errors = pun_pm_save_message($pun_pm_body, $pun_pm_subject, $pun_pm_receiver_username, $pun_pm_message_id);\n		// Remember $pun_pm_message_id = false; inside this function if $pun_pm_message_id is incorrect\n\n		// Well... Go processing errors\n\n		// We need no preview\n		$pun_pm_msg_preview = false;\n	}\n	elseif ($pun_pm_send_action == ''send'')\n	{\n		// Try to send the message\n		// Inside this function will be a redirect, if everything is ok\n		$pun_pm_errors = pun_pm_send_message($pun_pm_body, $pun_pm_subject, $pun_pm_receiver_username, $pun_pm_message_id);\n		// Remember $pun_pm_message_id = false; inside this function if $pun_pm_message_id is incorrect\n\n		// Well... Go processing errors\n\n		// We need no preview\n		$pun_pm_msg_preview = false;\n	}\n	elseif ($pun_pm_send_action == ''delete'' && $pun_pm_message_id !== false)\n	{\n		pun_pm_delete_from_outbox(array($pun_pm_message_id));\n		redirect(forum_link($forum_url[''pun_pm_outbox'']), $lang_pun_pm[''Message deleted'']);\n	}\n	elseif ($pun_pm_send_action == ''preview'')\n	{\n		// Preview message\n		$pun_pm_errors = array();\n		$pun_pm_msg_preview = pun_pm_preview($pun_pm_receiver_username, $pun_pm_subject, $pun_pm_body, $pun_pm_errors);\n	}\n\n	($hook = get_hook(''pun_pm_new_send_action'')) ? eval($hook) : null;\n\n	$pun_pm_page_text = pun_pm_send_form($pun_pm_receiver_username, $pun_pm_subject, $pun_pm_body, $pun_pm_message_id, false, false, $pun_pm_msg_preview);\n\n	// Setup navigation menu\n	$forum_page[''main_menu''] = array(\n		''inbox''		=> ''<li class="first-item"><a href="''.forum_link($forum_url[''pun_pm_inbox'']).''"><span>''.$lang_pun_pm[''Inbox''].''</span></a></li>'',\n		''outbox''	=> ''<li><a href="''.forum_link($forum_url[''pun_pm_outbox'']).''"><span>''.$lang_pun_pm[''Outbox''].''</span></a></li>'',\n		''write''		=> ''<li class="active"><a href="''.forum_link($forum_url[''pun_pm_write'']).''"><span>''.$lang_pun_pm[''Compose message''].''</span></a></li>'',\n	);\n\n	// Setup breadcrumbs\n	$forum_page[''crumbs''] = array(\n		array($forum_config[''o_board_title''], forum_link($forum_url[''index''])),\n		array($lang_pun_pm[''Private messages''], forum_link($forum_url[''pun_pm''])),\n		array($lang_pun_pm[''Compose message''], forum_link($forum_url[''pun_pm_write'']))\n	);\n\n	($hook = get_hook(''pun_pm_pre_send_output'')) ? eval($hook) : null;\n\n	define(''FORUM_PAGE'', ''pun_pm-write'');\n	require FORUM_ROOT.''header.php'';\n\n	// START SUBST - <!-- forum_main -->\n	ob_start();\n\n	echo $pun_pm_page_text;\n\n	$tpl_temp = trim(ob_get_contents());\n	$tpl_main = str_replace(''<!-- forum_main -->'', $tpl_temp, $tpl_main);\n	ob_end_clean();\n	// END SUBST - <!-- forum_main -->\n\n	require FORUM_ROOT.''footer.php'';\n}\n\n$section = isset($_GET[''section'']) ? $_GET[''section''] : null;\n\nif ($section == ''pun_pm'' && !$forum_user[''is_guest''])\n{\n	if (!isset($lang_pun_pm))\n	{\n		if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php''))\n			include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php'';\n		else\n			include $ext_info[''path''].''/lang/English/''.$ext_info[''id''].''.php'';\n	}\n\n	if (!defined(''PUN_PM_FUNCTIONS_LOADED''))\n		require $ext_info[''path''].''/functions.php'';\n\n	$pun_pm_page = isset($_GET[''pmpage'']) ? $_GET[''pmpage''] : '''';\n\n	($hook = get_hook(''pun_pm_pre_page_building'')) ? eval($hook) : null;\n\n	// pun_pm_get_page() performs everything :)\n	// Remember $pun_pm_page correction inside pun_pm_get_page() if this variable is incorrect\n	$pun_pm_page_text = pun_pm_get_page($pun_pm_page);\n\n	// Setup navigation menu\n	$forum_page[''main_menu''] = array(\n		''inbox''		=> ''<li class="first-item''.($pun_pm_page == ''inbox'' ? '' active'' : '''').''"><a href="''.forum_link($forum_url[''pun_pm_inbox'']).''"><span>''.$lang_pun_pm[''Inbox''].''</span></a></li>'',\n		''outbox''	=> ''<li''.(($pun_pm_page == ''outbox'') ? '' class="active"'' : '''').''><a href="''.forum_link($forum_url[''pun_pm_outbox'']).''"><span>''.$lang_pun_pm[''Outbox''].''</span></a></li>'',\n		''write''		=> ''<li''.(($pun_pm_page == ''write'' || $pun_pm_page == ''compose'') ? '' class="active"'' : '''').''><a href="''.forum_link($forum_url[''pun_pm_write'']).''"><span>''.$lang_pun_pm[''Compose message''].''</span></a></li>'',\n	);\n\n	// Setup breadcrumbs\n	$forum_page[''crumbs''] = array(\n		array($forum_config[''o_board_title''], forum_link($forum_url[''index''])),\n		array($lang_pun_pm[''Private messages''], forum_link($forum_url[''pun_pm'']))\n	);\n\n	if ($pun_pm_page == ''inbox'')\n		$forum_page[''crumbs''][] = array($lang_pun_pm[''Inbox''], forum_link($forum_url[''pun_pm_inbox'']));\n	else if ($pun_pm_page == ''outbox'')\n		$forum_page[''crumbs''][] = array($lang_pun_pm[''Outbox''], forum_link($forum_url[''pun_pm_outbox'']));\n	else if ($pun_pm_page == ''write'' || $pun_pm_page == ''compose'')\n		$forum_page[''crumbs''][] = array($lang_pun_pm[''Compose message''], forum_link($forum_url[''pun_pm_write'']));\n\n	($hook = get_hook(''pun_pm_pre_page_output'')) ? eval($hook) : null;\n\n	define(''FORUM_PAGE'', ''pun_pm-''.$pun_pm_page);\n	require FORUM_ROOT.''header.php'';\n\n	// START SUBST - <!-- forum_main -->\n	ob_start();\n\n	echo $pun_pm_page_text;\n\n	$tpl_temp = trim(ob_get_contents());\n	$tpl_main = str_replace(''<!-- forum_main -->'', $tpl_temp, $tpl_main);\n	ob_end_clean();\n	// END SUBST - <!-- forum_main -->\n\n	require FORUM_ROOT.''footer.php'';\n}', 1375442767, 5),
('aop_features_avatars_fieldset_end', 'pun_pm', '// Admin options\nif (!isset($lang_pun_pm))\n{\n	if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php''))\n		include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php'';\n	else\n		include $ext_info[''path''].''/lang/English/''.$ext_info[''id''].''.php'';\n}\n\n$forum_page[''group_count''] = $forum_page[''item_count''] = 0;\n\n?>\n			<div class="content-head">\n				<h2 class="hn"><span><?php echo $lang_pun_pm[''Features title''] ?></span></h2>\n			</div>\n			<fieldset class="frm-group group<?php echo ++$forum_page[''group_count''] ?>">\n				<legend class="group-legend"><span><?php echo $lang_pun_pm[''PM settings''] ?></span></legend>\n				<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\n					<div class="sf-box text">\n						<label for="fld<?php echo ++$forum_page[''fld_count''] ?>"><span><?php echo $lang_pun_pm[''Inbox limit''] ?></span><small><?php echo $lang_pun_pm[''Inbox limit info''] ?></small></label><br />\n						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[''fld_count''] ?>" name="form[pun_pm_inbox_size]" size="6" maxlength="6" value="<?php echo $forum_config[''o_pun_pm_inbox_size''] ?>" /></span>\n					</div>\n				</div>\n				<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\n					<div class="sf-box text">\n						<label for="fld<?php echo ++$forum_page[''fld_count''] ?>"><span><?php echo $lang_pun_pm[''Outbox limit''] ?></span><small><?php echo $lang_pun_pm[''Outbox limit info''] ?></small></label><br />\n						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[''fld_count''] ?>" name="form[pun_pm_outbox_size]" size="6" maxlength="6" value="<?php echo $forum_config[''o_pun_pm_outbox_size''] ?>" /></span>\n					</div>\n				</div>\n				<fieldset class="mf-set set<?php echo ++$forum_page[''item_count''] ?>">\n					<legend><span><?php echo $lang_pun_pm[''Navigation links''] ?></span></legend>\n					<div class="mf-box">\n						<div class="mf-item">\n							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[''fld_count''] ?>" name="form[pun_pm_show_new_count]" value="1"<?php if ($forum_config[''o_pun_pm_show_new_count''] == ''1'') echo '' checked="checked"'' ?> /></span>\n							<label for="fld<?php echo $forum_page[''fld_count''] ?>"><?php echo $lang_pun_pm[''Snow new count''] ?></label>\n						</div>\n						<div class="mf-item">\n							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[''fld_count''] ?>" name="form[pun_pm_show_global_link]" value="1"<?php if ($forum_config[''o_pun_pm_show_global_link''] == ''1'') echo '' checked="checked"'' ?> /></span>\n							<label for="fld<?php echo $forum_page[''fld_count''] ?>"><?php echo $lang_pun_pm[''Show global link''] ?></label>\n						</div>\n					</div>\n				</fieldset>\n<?php ($hook = get_hook(''pun_pm_aop_features_pre_pm_settings_fieldset_end'')) ? eval($hook) : null; ?>\n			</fieldset>\n<?php', 1375442767, 5),
('aop_features_validation', 'pun_pm', '$form[''pun_pm_inbox_size''] = (!isset($form[''pun_pm_inbox_size'']) || (int) $form[''pun_pm_inbox_size''] <= 0) ? ''0'' : (string)(int) $form[''pun_pm_inbox_size''];\n			$form[''pun_pm_outbox_size''] = (!isset($form[''pun_pm_outbox_size'']) || (int) $form[''pun_pm_outbox_size''] <= 0) ? ''0'' : (string)(int) $form[''pun_pm_outbox_size''];\n\n			if (!isset($form[''pun_pm_show_new_count'']) || $form[''pun_pm_show_new_count''] != ''1'')\n				$form[''pun_pm_show_new_count''] = ''0'';\n\n			if (!isset($form[''pun_pm_show_global_link'']) || $form[''pun_pm_show_global_link''] != ''1'')\n				$form[''pun_pm_show_global_link''] = ''0'';\n\n			($hook = get_hook(''pun_pm_aop_features_validation_end'')) ? eval($hook) : null;', 1375442767, 5),
('fn_delete_user_end', 'pun_pm', '$query = array(\n				''DELETE''	=> ''pun_pm_messages'',\n				''WHERE''		=> ''receiver_id = ''.$user_id.'' AND deleted_by_sender = 1''\n			);\n			$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);\n\n			$query = array(\n				''UPDATE''	=> ''pun_pm_messages'',\n				''SET''		=> ''deleted_by_receiver = 1'',\n				''WHERE''		=> ''receiver_id = ''.$user_id\n			);\n			$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);', 1375442767, 5),
('hd_visit_elements', 'pun_pm', '// ''New messages (N)'' link\n			if (!$forum_user[''is_guest''] && $forum_config[''o_pun_pm_show_new_count''])\n			{\n				global $lang_pun_pm;\n\n				if (!isset($lang_pun_pm))\n				{\n					if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php''))\n						include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php'';\n					else\n						include $ext_info[''path''].''/lang/English/''.$ext_info[''id''].''.php'';\n				}\n\n				// TODO: Do not include all functions, divide them into 2 files\n				if(!defined(''PUN_PM_FUNCTIONS_LOADED''))\n					require $ext_info[''path''].''/functions.php'';\n\n				($hook = get_hook(''pun_pm_hd_visit_elements_pre_change'')) ? eval($hook) : null;\n\n				//$visit_elements[''<!-- forum_visit -->''] = preg_replace(''#(<p id="visit-links" class="options">.*?)(</p>)#'', ''$1 <span><a href="''.forum_link($forum_url[''pun_pm_inbox'']).''">''.pun_pm_unread_messages().''</a></span>$2'', $visit_elements[''<!-- forum_visit -->'']);\n				if ($forum_user[''g_read_board''] == ''1'' && $forum_user[''g_search''] == ''1'')\n				{\n					$visit_links[''pun_pm''] = ''<span id="visit-pun_pm"><a href="''.forum_link($forum_url[''pun_pm_inbox'']).''">''.pun_pm_unread_messages().''</a></span>'';\n				}\n\n				($hook = get_hook(''pun_pm_hd_visit_elements_after_change'')) ? eval($hook) : null;\n			}', 1375442767, 5),
('vt_row_pre_post_contacts_merge', 'pun_pm', 'global $lang_pun_pm;\n\n			if (!isset($lang_pun_pm))\n			{\n				if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php''))\n					include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php'';\n				else\n					include $ext_info[''path''].''/lang/English/''.$ext_info[''id''].''.php'';\n			}\n\n			($hook = get_hook(''pun_pm_pre_post_contacts_add'')) ? eval($hook) : null;\n\n			// Links ''Send PM'' near posts\n			if (!$forum_user[''is_guest''] && $cur_post[''poster_id''] > 1 && $forum_user[''id''] != $cur_post[''poster_id''])\n				$forum_page[''post_contacts''][''PM''] = ''<span class="contact"><a title="''.$lang_pun_pm[''Send PM''].''" href="''.forum_link($forum_url[''pun_pm_post_link''], $cur_post[''poster_id'']).''">''.$lang_pun_pm[''PM''].''</a></span>'';\n\n			($hook = get_hook(''pun_pm_after_post_contacts_add'')) ? eval($hook) : null;', 1375442767, 5),
('fn_generate_navlinks_end', 'pun_pm', '// Link ''PM'' in the main nav menu\n			if (isset($links[''profile'']) && $forum_config[''o_pun_pm_show_global_link''])\n			{\n				global $lang_pun_pm;\n\n				if (!isset($lang_pun_pm))\n				{\n					if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php''))\n						include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php'';\n					else\n						include $ext_info[''path''].''/lang/English/''.$ext_info[''id''].''.php'';\n				}\n\n				if (''pun_pm'' == substr(FORUM_PAGE, 0, 6))\n					$links[''profile''] = str_replace('' class="isactive"'', '''', $links[''profile'']);\n\n				($hook = get_hook(''pun_pm_pre_main_navlinks_add'')) ? eval($hook) : null;\n\n				$links[''profile''] .= "\\n\\t\\t".''<li id="nav_pun_pm"''.(''pun_pm'' == substr(FORUM_PAGE, 0, 6) ? '' class="isactive"'' : '''').''><a href="''.forum_link($forum_url[''pun_pm'']).''"><span>''.$lang_pun_pm[''Private messages''].''</span></a></li>'';\n\n				($hook = get_hook(''pun_pm_after_main_navlinks_add'')) ? eval($hook) : null;\n			}', 1375442767, 5),
('pf_view_details_pre_header_load', 'pun_pm', '// Link in the profile\n			if (!$forum_user[''is_guest''] && $forum_user[''id''] != $user[''id''])\n			{\n				if (!isset($lang_pun_pm))\n				{\n					if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php''))\n						include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php'';\n					else\n						include $ext_info[''path''].''/lang/English/''.$ext_info[''id''].''.php'';\n				}\n\n				($hook = get_hook(''pun_pm_pre_profile_user_contact_add'')) ? eval($hook) : null;\n\n				$forum_page[''user_contact''][''PM''] = ''<li><span>''.$lang_pun_pm[''PM''].'': <a href="''.forum_link($forum_url[''pun_pm_post_link''], $id).''">''.$lang_pun_pm[''Send PM''].''</a></span></li>'';\n\n				($hook = get_hook(''pun_pm_after_profile_user_contact_add'')) ? eval($hook) : null;\n			}', 1375442767, 5),
('pf_change_details_about_pre_header_load', 'pun_pm', '// Link in the profile\n			if (!$forum_user[''is_guest''] && $forum_user[''id''] != $user[''id''])\n			{\n				if (!isset($lang_pun_pm))\n				{\n					if (file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php''))\n						include $ext_info[''path''].''/lang/''.$forum_user[''language''].''/''.$ext_info[''id''].''.php'';\n					else\n						include $ext_info[''path''].''/lang/English/''.$ext_info[''id''].''.php'';\n				}\n\n				($hook = get_hook(''pun_pm_pre_profile_user_contact_add'')) ? eval($hook) : null;\n\n				$forum_page[''user_contact''][''PM''] = ''<li><span>''.$lang_pun_pm[''PM''].'': <a href="''.forum_link($forum_url[''pun_pm_post_link''], $id).''">''.$lang_pun_pm[''Send PM''].''</a></span></li>'';\n\n				($hook = get_hook(''pun_pm_after_profile_user_contact_add'')) ? eval($hook) : null;\n			}', 1375442767, 5),
('co_modify_url_scheme', 'pun_pm', 'if ($forum_config[''o_sef''] != ''Default'' && file_exists($ext_info[''path''].''/url/''.$forum_config[''o_sef''].''.php''))\n				require $ext_info[''path''].''/url/''.$forum_config[''o_sef''].''.php'';\n			else\n				require $ext_info[''path''].''/url/Default.php'';', 1375442767, 5),
('re_rewrite_rules', 'pun_pm', '$forum_rewrite_rules[''/^pun_pm[\\/_-]?send(\\.html?|\\/)?$/i''] = ''misc.php?action=pun_pm_send'';\n			$forum_rewrite_rules[''/^pun_pm[\\/_-]?compose[\\/_-]?([0-9]+)(\\.html?|\\/)?$/i''] = ''misc.php?section=pun_pm&pmpage=compose&receiver_id=$1'';\n			$forum_rewrite_rules[''/^pun_pm(\\.html?|\\/)?$/i''] = ''misc.php?section=pun_pm'';\n			$forum_rewrite_rules[''/^pun_pm[\\/_-]?([0-9a-z]+)(\\.html?|\\/)?$/i''] = ''misc.php?section=pun_pm&pmpage=$1'';\n			$forum_rewrite_rules[''/^pun_pm[\\/_-]?([0-9a-z]+)[\\/_-]?(p|page\\/)([0-9]+)(\\.html?|\\/)?$/i''] = ''misc.php?section=pun_pm&pmpage=$1&p=$3'';\n			$forum_rewrite_rules[''/^pun_pm[\\/_-]?([0-9a-z]+)[\\/_-]?([0-9]+)(\\.html?|\\/)?$/i''] = ''misc.php?section=pun_pm&pmpage=$1&message_id=$2'';\n\n			($hook = get_hook(''pun_pm_after_rewrite_rules_set'')) ? eval($hook) : null;', 1375442767, 5),
('ca_fn_generate_admin_menu_new_sublink', 'logo', 'if (!isset($logo))\r\n{	\r\n	if ($forum_user[''language''] != ''English'' && file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/lang.php''))\r\n	{\r\n		require $ext_info[''path''].''/lang/''.$forum_user[''language''].''/lang.php'';\r\n	}\r\n	else\r\n	{\r\n		require $ext_info[''path''].''/lang/English/lang.php'';\r\n	}\r\n}\r\n\r\n$forum_url[''admin_settings_logo''] = ''admin/settings.php?section=logo'';\r\n\r\nif (FORUM_PAGE_SECTION == ''settings'')\r\n	{\r\n		$forum_page[''admin_submenu''][''settings-logo''] = ''<li class="''.((FORUM_PAGE == ''admin-settings-logo'') ? ''active'' : ''normal'').((empty($forum_page[''admin_submenu''])) ? '' first-item'' : '''').''"><a href="''.forum_link($forum_url[''admin_settings_logo'']).''">''.$logo[''logo''].''</a></li>'';\r\n	}', 1377592481, 5),
('aop_pre_update_configuration', 'logo', 'switch ($section)\r\n	{\r\n		case ''logo'':\r\n		{\r\n	\r\n	if($_FILES[''logo_src''][''tmp_name''])\r\n		{\r\n			$file=$_FILES[''logo_src''];\r\n			$allow = array(\r\n				''types'' => array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF),\r\n				''mime_types'' => array(''image/gif'', ''image/jpeg'', ''image/pjpeg'', ''image/png'', ''image/x-png''),\r\n				''max_width'' => ''1000'',\r\n				''max_height'' => ''200'');\r\n\r\n		if(getimagesize($file[''tmp_name'']) && in_array($file[''type''], $allow[''mime_types'']))\r\n			{\r\n				list($file[''width''],$file[''height''],$file[''image_type''])=getimagesize($file[''tmp_name'']);\r\n\r\n\r\n\r\n				if($file[''width'']<=$allow[''max_width''] && $file[''height'']<=$allow[''max_height''] && in_array($file[''image_type''],$allow[''types'']))\r\n							{\r\n								$pos = strrpos($file[''name''], ''.''); \r\n								$basename = substr($file[''name''], 0, $pos); \r\n								$ext = strtolower(substr($file[''name''], $pos+1));\r\n								if ($forum_config[''o_logo_src''] != ''0'')\r\n									@unlink(''..''.$forum_config[''o_logo_src'']);\r\n								if(!@rename($file[''tmp_name''], ''../img/logo.''.$ext))\r\n									{\r\n										message($logo[''error_chmod'']);\r\n									}\r\n								\r\n								else\r\n									{\r\n								$form[''logo_src'']=''/img/logo.''.$ext;\r\n								@chmod(''..''.$forum_config[''o_logo_src''], 0644);\r\n								$form[''logo_width'']=$file[''width''];\r\n								$form[''logo_height'']=$file[''height''];\r\n									}\r\n							}\r\n							else message($logo[''error_format'']);\r\n					}\r\n					else message($logo[''error_mime_type'']);\r\n		}\r\n		$forum_url[''admin_settings_logo''] = ''admin/settings.php?section=logo'';\r\n		\r\n		if($forum_config[''o_logo_src''] == ''0'') $form[''logo_enable''] = ''0'';\r\n		if (!isset($form[''logo_enable'']) || $form[''logo_enable''] != ''1'') $form[''logo_enable''] = ''0'';\r\n		if (!isset($form[''logo_hide_forum_title'']) || $form[''logo_hide_forum_title''] != ''1'') $form[''logo_hide_forum_title''] = ''0'';\r\n}\r\n\r\ndefault:\r\n{\r\n	break;\r\n}\r\n}', 1377592481, 5),
('co_common', 'logo', 'if (!isset($logo)) {\r\n	if ($forum_user[''language''] != ''English'' && file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/lang.php'')) {\r\n		require $ext_info[''path''].''/lang/''.$forum_user[''language''].''/lang.php'';\r\n	} else {\r\n		require $ext_info[''path''].''/lang/English/lang.php'';\r\n	}\r\n}', 1377592481, 5),
('hd_head', 'logo', 'if($forum_config[''o_logo_src''] != ''0'')\r\n		{\r\n			$check_file=@fopen(FORUM_ROOT.$forum_config[''o_logo_src''], ''r'');\r\n			if($check_file)\r\n			fclose($check_file);\r\n			else $forum_config[''o_logo_enable''] = ''0'';\r\n		}\r\n		if ($forum_config[''o_logo_enable''] == ''1'' )\r\n			{\r\n				$forum_loader->add_css($ext_info[''url''].''/main.css'', array(''type'' => ''url''));\r\n				$forum_loader->add_css(''div.logo{background-image:url(''.$base_url.$forum_config[''o_logo_src''].''); width:''.$forum_config[''o_logo_width''].''px; height:''.$forum_config[''o_logo_height''].''px;}td.logo{width:''.$forum_config[''o_logo_width''].''px; height:''.$forum_config[''o_logo_height''].''px;}'', array(''type'' => ''inline''));\r\n			}', 1377592481, 5),
('hd_gen_elements', 'logo', 'if( $forum_config[''o_logo_enable''] == ''1'' && $forum_config[''o_logo_align''] == ''left'' )\r\n				{\r\n					$gen_elements[''<!-- forum_title -->''] = ''<table class="logo">'';\r\n					$gen_elements[''<!-- forum_title -->''] .= ''<td class="logo" id="left" ><div  id="left" class="logo">'';\r\n					$gen_elements[''<!-- forum_title -->''] .= ($forum_config[''o_logo_link''] != '''') ? ''<a class="logo" href="''.$forum_config[''o_logo_link''].''" title="''.$forum_config[''o_logo_link_title''].''"></a></div></td>'' : ''</div></td>'';\r\n					$gen_elements[''<!-- forum_title -->''] .= ''<td class="title" style="vertical-align:''.$forum_config[''o_logo_title_vertical''].''">'';\r\n					$gen_elements[''<!-- forum_title -->''] .= ($forum_config[''o_logo_hide_forum_title''] != ''1'') ? ''<p id="brd-title" style="text-align:''.$forum_config[''o_logo_title_align''].''"><a href="''.forum_link($forum_url[''index'']).''">''.forum_htmlencode($forum_config[''o_board_title'']).''</a></p>'' : '''';\r\n					$gen_elements[''<!-- forum_desc -->''] = ($forum_config[''o_board_desc''] != '''') ? ''<p id="brd-desc" style="text-align:''.$forum_config[''o_logo_title_align''].''">''.forum_htmlencode($forum_config[''o_board_desc'']).''</p></td>'' : ''</td>'';\r\n					$gen_elements[''<!-- forum_desc -->''] .= (isset($ad)) ? "<td><div style=''float:right;background:#999''>{$ad}</div></td></table>" : ''</table>'';\r\n				}\r\n			if( $forum_config[''o_logo_enable''] == ''1'' && $forum_config[''o_logo_align''] == ''right'' )\r\n				{\r\n					$gen_elements[''<!-- forum_title -->''] = (isset($ad)) ? "<table class=''logo''><td><div style=''background:#999''>{$ad}</div></td>" : ''<table class="logo">'';\r\n					$gen_elements[''<!-- forum_title -->''] .= ''<td class="title" style="vertical-align:''.$forum_config[''o_logo_title_vertical''].''">'';\r\n					$gen_elements[''<!-- forum_title -->''] .= ($forum_config[''o_logo_hide_forum_title''] != ''1'') ? ''<p id="brd-title" style="text-align:''.$forum_config[''o_logo_title_align''].''"><a href="''.forum_link($forum_url[''index'']).''">''.forum_htmlencode($forum_config[''o_board_title'']).''</a></p>'' : '''';\r\n					$gen_elements[''<!-- forum_desc -->''] = ($forum_config[''o_board_desc''] != '''') ? ''<p id="brd-desc" style="text-align:''.$forum_config[''o_logo_title_align''].''">''.forum_htmlencode($forum_config[''o_board_desc'']).''</p></td>'' : ''</td>'';\r\n					$gen_elements[''<!-- forum_desc -->''] .= ''<td class="logo" id="right"><div id="right" class="logo">'';\r\n					$gen_elements[''<!-- forum_desc -->''] .= ($forum_config[''o_logo_link''] != '''') ? ''<a class="logo" href="''.$forum_config[''o_logo_link''].''" title="''.$forum_config[''o_logo_link_title''].''"></a></div></td></table>'' : ''</div></td></table>'';\r\n					\r\n				}', 1377592481, 6);
INSERT INTO `extension_hooks` (`id`, `extension_id`, `code`, `installed`, `priority`) VALUES
('aop_new_section', 'logo', 'if($section == ''logo''){\r\nif (!isset($logo)) {\r\n	if ($forum_user[''language''] != ''English'' && file_exists($ext_info[''path''].''/lang/''.$forum_user[''language''].''/lang.php'')) {\r\n		require $ext_info[''path''].''/lang/''.$forum_user[''language''].''/lang.php'';\r\n	} else {\r\n		require $ext_info[''path''].''/lang/English/lang.php'';\r\n	}\r\n}\r\n\r\n$forum_page[''group_count''] = $forum_page[''item_count''] = $forum_page[''fld_count''] = 0;\r\n$forum_url[''admin_settings_logo''] = ''admin/settings.php?section=logo'';\r\n\r\n	// Setup breadcrumbs\r\n	$forum_page[''crumbs''] = array(\r\n		array($forum_config[''o_board_title''], forum_link($forum_url[''index''])),\r\n		array($lang_admin_common[''Forum administration''], forum_link($forum_url[''admin_index''])),\r\n		array($lang_admin_common[''Settings''], forum_link($forum_url[''admin_settings_setup''])),\r\n		array($logo[''logo''], forum_link($forum_url[''admin_settings_logo'']))\r\n	);\r\n\r\n	define(''FORUM_PAGE_SECTION'', ''settings'');\r\n	define(''FORUM_PAGE'', ''admin-settings-logo'');\r\n	require FORUM_ROOT.''header.php'';\r\n\r\n	// START SUBST - <!-- forum_main -->\r\n	ob_start();\r\n	$check_file=@fopen(''..''.$forum_config[''o_logo_src''], ''r'');\r\n\r\n				?>\r\n					<div class="main-content frm parted">\r\n						<form class="frm-form" enctype="multipart/form-data" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url[''admin_settings_logo'']) ?>">\r\n							<div class="hidden">\r\n								<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url[''admin_settings_logo''])) ?>" />\r\n								<input type="hidden" name="form_sent" value="1" />\r\n								<input type="hidden" name="form[0]" value="0" />\r\n							</div>\r\n\r\n\r\n					<div class="content-head">\r\n						<h2 class="hn"><span><?php echo $logo[''settings''] ?></span></h2>\r\n					</div>\r\n						<fieldset class="frm-group group1">\r\n\r\n						<?\r\n							\r\n							\r\n						if($forum_config[''o_logo_src''] != ''0''){\r\n							?>\r\n\r\n					<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\r\n						<div class="sf-box text required">\r\n						<label for="fld<?php echo ++$forum_page[''fld_count''] ?>"><span><?php echo $logo[''current''] ?></span></label>\r\n							<?if($check_file) echo ''<div style="border: solid 1px #999; margin-left: 10px; height:''.$forum_config[''o_logo_height''].''px; width:''.$forum_config[''o_logo_width''].''px;"><img src="''.$base_url.$forum_config[''o_logo_src''].''"></div>'';\r\n							else echo ''<span style="margin-left: 10px; position: relative; top: 2px;">''.$logo[''file_not_found''].''</span>''; ?>\r\n						</div>\r\n					</div>\r\n					\r\n					<?\r\n				}\r\n				if (!is_writable(FORUM_ROOT.''img/''))\r\n					echo ''<div class="ct-box warn-box"><p class="important">''.$logo[''not_writable''].''</p></div>'';\r\n				else{\r\n					?>\r\n					<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\r\n						<div class="sf-box text required">\r\n							<label for="fld<?php echo ++$forum_page[''fld_count''] ?>"><span><?php echo $logo[''upload_logo''] ?></span><small><?php echo $logo[''upload_logo_desc''] ?></small></label><br />\r\n							<span class="fld-input"><input id="fld<?php echo $forum_page[''fld_count''] ?>" name="logo_src" type="file" size="40" /></span>	\r\n						</div>\r\n					</div>\r\n					<?}\r\n					if($forum_config[''o_logo_src''] != ''0''){\r\n						?>\r\n					<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\r\n						<div class="sf-box checkbox">\r\n						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[''fld_count''] ?>" name="form[logo_enable]" value="1" <?php if ($forum_config[''o_logo_enable''] == ''1'') echo ''checked'' ?> /></span>\r\n							<label for="fld<?php echo $forum_page[''fld_count''] ?>"><?php echo $logo[''enable_logo''] ?>\r\n							</label>\r\n						</div>\r\n					</div>\r\n\r\n					<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\r\n						<div class="sf-box select">\r\n							<label for="fld<?php echo ++$forum_page[''fld_count''] ?>"><span><?php echo $logo[''logo_align''] ?></span></label><br />\r\n							<span class="fld-input"><select id="fld<?php echo $forum_page[''fld_count''] ?>" name="form[logo_align]">\r\n							<option value="left"<?php if ($forum_config[''o_logo_align''] == ''left'') echo '' selected="selected"'' ?>><? echo $logo[''left''] ?></option>\r\n							<option value="right"<?php if ($forum_config[''o_logo_align''] == ''right'') echo '' selected="selected"'' ?>><? echo $logo[''right''] ?></option>\r\n							</select></span>\r\n						</div>\r\n					</div>\r\n\r\n					<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\r\n						<div class="sf-box select">\r\n							<label for="fld<?php echo ++$forum_page[''fld_count''] ?>"><span><?php echo $logo[''title_align''] ?></span><small><?php echo $logo[''horizontal''] ?></small></label><br />\r\n							<span class="fld-input"><select id="fld<?php echo $forum_page[''fld_count''] ?>" name="form[logo_title_align]" >\r\n							<option value="left"<?php if ($forum_config[''o_logo_title_align''] == ''left'') echo '' selected="selected"'' ?>><? echo $logo[''left''] ?></option>\r\n							<option value="center"<?php if ($forum_config[''o_logo_title_align''] == ''center'') echo '' selected="selected"'' ?>><? echo $logo[''center''] ?></option>\r\n							<option value="right"<?php if ($forum_config[''o_logo_title_align''] == ''right'') echo '' selected="selected"'' ?>><? echo $logo[''right''] ?></option>\r\n							</select></span>\r\n						</div>\r\n					</div>\r\n\r\n					<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\r\n						<div class="sf-box select">\r\n							<label for="fld<?php echo ++$forum_page[''fld_count''] ?>"><span><?php echo $logo[''title_vertical''] ?></span><small><?php echo $logo[''vertical''] ?></small></label><br />\r\n							<span class="fld-input"><select id="fld<?php echo $forum_page[''fld_count''] ?>" name="form[logo_title_vertical]">\r\n							<option value="top"<?php if ($forum_config[''o_logo_title_vertical''] == ''top'') echo '' selected="selected"'' ?>><? echo $logo[''top''] ?></option>\r\n							<option value="middle"<?php if ($forum_config[''o_logo_title_vertical''] == ''middle'') echo '' selected="selected"'' ?>><? echo $logo[''middle''] ?></option>\r\n							<option value="bottom"<?php if ($forum_config[''o_logo_title_vertical''] == ''bottom'') echo '' selected="selected"'' ?>><? echo $logo[''bottom''] ?></option>\r\n							</select></span>\r\n						</div>\r\n					</div>\r\n\r\n					<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\r\n						<div class="sf-box checkbox">\r\n						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[''fld_count''] ?>" name="form[logo_hide_forum_title]" value="1" <?php if ($forum_config[''o_logo_hide_forum_title''] == ''1'') echo ''checked'';?> /></span>\r\n							<label for="fld<?php echo $forum_page[''fld_count''] ?>"><?php echo $logo[''hide_board_title''] ?></label>\r\n						</div>\r\n					</div>\r\n\r\n					<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\r\n						<div class="sf-box text">\r\n							<label for="fld<?php echo ++$forum_page[''fld_count''] ?>">\r\n								<span><? echo $logo[''logo_link_url''] ?></span>\r\n								<small><? echo $logo[''logo_link_url_example''] ?></small>\r\n							</label><br />\r\n							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[''fld_count''] ?>" name="form[logo_link]" size="50" maxlength="255" value="<?php echo forum_htmlencode($forum_config[''o_logo_link'']) ?>" /></span>\r\n						</div>\r\n					</div>\r\n\r\n					<div class="sf-set set<?php echo ++$forum_page[''item_count''] ?>">\r\n						<div class="sf-box text">\r\n							<label for="fld<?php echo ++$forum_page[''fld_count''] ?>">\r\n								<span><? echo $logo[''logo_link_title''] ?></span>\r\n							</label><br />\r\n							<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[''fld_count''] ?>" name="form[logo_link_title]" size="50" maxlength="255" value="<?php echo forum_htmlencode($forum_config[''o_logo_link_title'']) ?>" /></span>\r\n						</div>\r\n					</div>\r\n					<?\r\n				}\r\n				?>\r\n			</fieldset>\r\n						<div class="frm-buttons">\r\n							<span class="submit primary"><input type="submit" name="save" value="<?php echo $lang_admin_common[''Save changes''] ?>" /></span>\r\n						</div>\r\n					</form>\r\n				</div><?\r\n}', 1377592481, 5);

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `forum_name` varchar(80) NOT NULL DEFAULT 'New forum',
  `forum_desc` text,
  `redirect_url` varchar(100) DEFAULT NULL,
  `moderators` text,
  `num_topics` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `num_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `last_post` int(10) unsigned DEFAULT NULL,
  `last_post_id` int(10) unsigned DEFAULT NULL,
  `last_poster` varchar(200) DEFAULT NULL,
  `sort_by` tinyint(1) NOT NULL DEFAULT '0',
  `disp_position` int(10) NOT NULL DEFAULT '0',
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`id`, `forum_name`, `forum_desc`, `redirect_url`, `moderators`, `num_topics`, `num_posts`, `last_post`, `last_post_id`, `last_poster`, `sort_by`, `disp_position`, `cat_id`) VALUES
(15, 'Pozostałe', NULL, NULL, 'a:1:{s:9:"chujumuju";i:9;}', 3, 3, 1377689074, 30, 'zdzislaw.dyrma', 0, 7, 2),
(8, 'Narkotyki', NULL, NULL, 'a:1:{s:9:"chujumuju";i:9;}', 0, 0, NULL, NULL, NULL, 0, 0, 2),
(6, 'Aktualne zgloszenia', NULL, NULL, 'a:1:{s:9:"chujumuju";i:9;}', 30, 36, 1377941354, 44, 'TVI', 0, 0, 5),
(7, 'Archiwalne zgloszenia', NULL, NULL, 'a:1:{s:9:"chujumuju";i:9;}', 0, 0, NULL, NULL, NULL, 0, 1, 5),
(9, 'Broń', NULL, NULL, 'a:1:{s:9:"chujumuju";i:9;}', 1, 1, 1377685254, 24, 'Mbank_konsultant', 0, 1, 2),
(10, 'Elektronika', NULL, NULL, 'a:1:{s:9:"chujumuju";i:9;}', 0, 0, NULL, NULL, NULL, 0, 2, 2),
(11, 'Usługi', NULL, NULL, 'a:1:{s:9:"chujumuju";i:9;}', 1, 1, 1377686283, 25, 'Mbank_konsultant', 0, 3, 2),
(12, 'Chemia', NULL, NULL, 'a:1:{s:9:"chujumuju";i:9;}', 0, 0, NULL, NULL, NULL, 0, 4, 2),
(13, 'Edukacja', NULL, NULL, 'a:1:{s:9:"chujumuju";i:9;}', 2, 2, 1377688595, 28, 'Mbank_konsultant', 0, 5, 2),
(14, 'Erotyka', NULL, NULL, 'a:1:{s:9:"chujumuju";i:9;}', 0, 0, NULL, NULL, NULL, 0, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `forum_perms`
--

CREATE TABLE IF NOT EXISTS `forum_perms` (
  `group_id` int(10) NOT NULL DEFAULT '0',
  `forum_id` int(10) NOT NULL DEFAULT '0',
  `read_forum` tinyint(1) NOT NULL DEFAULT '1',
  `post_replies` tinyint(1) NOT NULL DEFAULT '1',
  `post_topics` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`group_id`,`forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_perms`
--

INSERT INTO `forum_perms` (`group_id`, `forum_id`, `read_forum`, `post_replies`, `post_topics`) VALUES
(3, 6, 0, 1, 1),
(2, 6, 0, 0, 0),
(2, 7, 0, 0, 0),
(3, 7, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `forum_subscriptions`
--

CREATE TABLE IF NOT EXISTS `forum_subscriptions` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `g_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `g_title` varchar(50) NOT NULL DEFAULT '',
  `g_user_title` varchar(50) DEFAULT NULL,
  `g_moderator` tinyint(1) NOT NULL DEFAULT '0',
  `g_mod_edit_users` tinyint(1) NOT NULL DEFAULT '0',
  `g_mod_rename_users` tinyint(1) NOT NULL DEFAULT '0',
  `g_mod_change_passwords` tinyint(1) NOT NULL DEFAULT '0',
  `g_mod_ban_users` tinyint(1) NOT NULL DEFAULT '0',
  `g_read_board` tinyint(1) NOT NULL DEFAULT '1',
  `g_view_users` tinyint(1) NOT NULL DEFAULT '1',
  `g_post_replies` tinyint(1) NOT NULL DEFAULT '1',
  `g_post_topics` tinyint(1) NOT NULL DEFAULT '1',
  `g_edit_posts` tinyint(1) NOT NULL DEFAULT '1',
  `g_delete_posts` tinyint(1) NOT NULL DEFAULT '1',
  `g_delete_topics` tinyint(1) NOT NULL DEFAULT '1',
  `g_set_title` tinyint(1) NOT NULL DEFAULT '1',
  `g_search` tinyint(1) NOT NULL DEFAULT '1',
  `g_search_users` tinyint(1) NOT NULL DEFAULT '1',
  `g_send_email` tinyint(1) NOT NULL DEFAULT '1',
  `g_post_flood` smallint(6) NOT NULL DEFAULT '30',
  `g_search_flood` smallint(6) NOT NULL DEFAULT '30',
  `g_email_flood` smallint(6) NOT NULL DEFAULT '60',
  PRIMARY KEY (`g_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`g_id`, `g_title`, `g_user_title`, `g_moderator`, `g_mod_edit_users`, `g_mod_rename_users`, `g_mod_change_passwords`, `g_mod_ban_users`, `g_read_board`, `g_view_users`, `g_post_replies`, `g_post_topics`, `g_edit_posts`, `g_delete_posts`, `g_delete_topics`, `g_set_title`, `g_search`, `g_search_users`, `g_send_email`, `g_post_flood`, `g_search_flood`, `g_email_flood`) VALUES
(1, 'Administrators', 'Administrator', 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0),
(2, 'Guest', NULL, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 60, 30, 0),
(3, 'Members', NULL, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 60, 30, 60),
(4, 'Moderators', 'Moderator', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `moderator_payouts`
--

CREATE TABLE IF NOT EXISTS `moderator_payouts` (
  `id` int(11) NOT NULL,
  `currentpayout` float NOT NULL,
  `totalpayout` float NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `moderator_payouts`
--

INSERT INTO `moderator_payouts` (`id`, `currentpayout`, `totalpayout`) VALUES
(2, 0.015, 0.015),
(9, 0, 0.12);

-- --------------------------------------------------------

--
-- Table structure for table `online`
--

CREATE TABLE IF NOT EXISTS `online` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `ident` varchar(200) NOT NULL DEFAULT '',
  `logged` int(10) unsigned NOT NULL DEFAULT '0',
  `idle` tinyint(1) NOT NULL DEFAULT '0',
  `csrf_token` varchar(40) NOT NULL DEFAULT '',
  `prev_url` varchar(255) DEFAULT NULL,
  `last_post` int(10) unsigned DEFAULT NULL,
  `last_search` int(10) unsigned DEFAULT NULL,
  UNIQUE KEY `online_user_id_ident_idx` (`user_id`,`ident`(25)),
  KEY `online_ident_idx` (`ident`(25)),
  KEY `online_logged_idx` (`logged`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

--
-- Dumping data for table `online`
--

INSERT INTO `online` (`user_id`, `ident`, `logged`, `idle`, `csrf_token`, `prev_url`, `last_post`, `last_search`) VALUES
(11, 'zdzislaw.dyrma', 1377948588, 0, '378d680e6b347ea6b2b10729e6dc4ed83642faa8', 'http://nco5ranerted3nkt.onion/escrow/escrows.php?action=detail&id=27', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

CREATE TABLE IF NOT EXISTS `payouts` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `receiverid` int(11) NOT NULL,
  `amount` float NOT NULL,
  `btcaddress` varchar(34) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `escrowid` int(11) NOT NULL,
  `txhash` varchar(128) NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `payouts`
--

INSERT INTO `payouts` (`index`, `time`, `receiverid`, `amount`, `btcaddress`, `status`, `escrowid`, `txhash`) VALUES
(24, 1377519511, 10, 0.0096, '14kibS1NsYZUjxcZbHLER3PLpLggUku52v', 1, 4, '5a6eb1f03e8415643f0acbfa788dfa0e67072c343f966d4b361bab964336949e');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poster` varchar(200) NOT NULL DEFAULT '',
  `poster_id` int(10) unsigned NOT NULL DEFAULT '1',
  `poster_ip` varchar(39) DEFAULT NULL,
  `poster_email` varchar(80) DEFAULT NULL,
  `message` text,
  `hide_smilies` tinyint(1) NOT NULL DEFAULT '0',
  `posted` int(10) unsigned NOT NULL DEFAULT '0',
  `edited` int(10) unsigned DEFAULT NULL,
  `edited_by` varchar(200) DEFAULT NULL,
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `posts_topic_id_idx` (`topic_id`),
  KEY `posts_multi_idx` (`poster_id`,`topic_id`),
  KEY `posts_posted_idx` (`posted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `poster`, `poster_id`, `poster_ip`, `poster_email`, `message`, `hide_smilies`, `posted`, `edited`, `edited_by`, `topic_id`) VALUES
(2, 'TVI', 8, '192.168.0.10', NULL, 'test problem', 0, 1377074261, NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pun_pm_messages`
--

CREATE TABLE IF NOT EXISTS `pun_pm_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) unsigned NOT NULL DEFAULT '0',
  `receiver_id` int(10) unsigned DEFAULT NULL,
  `lastedited_at` int(10) unsigned NOT NULL DEFAULT '0',
  `read_at` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `status` varchar(9) NOT NULL DEFAULT 'draft',
  `deleted_by_sender` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_by_receiver` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pun_pm_messages_sender_id_idx` (`sender_id`),
  KEY `pun_pm_messages_receiver_id_idx` (`receiver_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=354 ;

--
-- Dumping data for table `pun_pm_messages`
--

INSERT INTO `pun_pm_messages` (`id`, `sender_id`, `receiver_id`, `lastedited_at`, `read_at`, `subject`, `body`, `status`, `deleted_by_sender`, `deleted_by_receiver`) VALUES
(1, 8, 2, 1375446982, 1375447026, 'test', 'test', 'read', 0, 0);
INSERT INTO `pun_pm_messages` (`id`, `sender_id`, `receiver_id`, `lastedited_at`, `read_at`, `subject`, `body`, `status`, `deleted_by_sender`, `deleted_by_receiver`) VALUES
(245, 0, 8, 1377875920, 0, 'You just send 0.1 BTC to zdzislaw.dyrma', 'Hello [b]TVI[/b], we just received a [b]0.1[/b] BTC payment from you for [b]kupie bazem[/b] soled by [b]zdzislaw.dyrma[/b]. Thank you.', 'delivered', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE IF NOT EXISTS `ranks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rank` varchar(50) NOT NULL DEFAULT '',
  `min_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`id`, `rank`, `min_posts`) VALUES
(1, 'New member', 0),
(2, 'Member', 10);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  `reported_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `message` text,
  `zapped` int(10) unsigned DEFAULT NULL,
  `zapped_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reports_zapped_idx` (`zapped`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `search_cache`
--

CREATE TABLE IF NOT EXISTS `search_cache` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `ident` varchar(200) NOT NULL DEFAULT '',
  `search_data` text,
  PRIMARY KEY (`id`),
  KEY `search_cache_ident_idx` (`ident`(8))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `search_cache`
--

INSERT INTO `search_cache` (`id`, `ident`, `search_data`) VALUES
(22882268, 'Mbank_konsultant', 'a:4:{s:14:"search_results";s:5:"18,19";s:7:"sort_by";N;s:8:"sort_dir";s:4:"DESC";s:7:"show_as";s:5:"posts";}');

-- --------------------------------------------------------

--
-- Table structure for table `search_matches`
--

CREATE TABLE IF NOT EXISTS `search_matches` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `word_id` int(10) unsigned NOT NULL DEFAULT '0',
  `subject_match` tinyint(1) NOT NULL DEFAULT '0',
  KEY `search_matches_word_id_idx` (`word_id`),
  KEY `search_matches_post_id_idx` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `search_matches`
--

INSERT INTO `search_matches` (`post_id`, `word_id`, `subject_match`) VALUES
(4, 40, 0);

-- --------------------------------------------------------

--
-- Table structure for table `search_words`
--

CREATE TABLE IF NOT EXISTS `search_words` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`word`),
  KEY `search_words_id_idx` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=176 ;

--
-- Dumping data for table `search_words`
--

INSERT INTO `search_words` (`id`, `word`) VALUES
(175, 'test7');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poster` varchar(200) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `posted` int(10) unsigned NOT NULL DEFAULT '0',
  `first_post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `last_poster` varchar(200) DEFAULT NULL,
  `num_views` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `num_replies` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `sticky` tinyint(1) NOT NULL DEFAULT '0',
  `moved_to` int(10) unsigned DEFAULT NULL,
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `topics_forum_id_idx` (`forum_id`),
  KEY `topics_moved_to_idx` (`moved_to`),
  KEY `topics_last_post_idx` (`last_post`),
  KEY `topics_first_post_id_idx` (`first_post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `poster`, `subject`, `posted`, `first_post_id`, `last_post`, `last_post_id`, `last_poster`, `num_views`, `num_replies`, `closed`, `sticky`, `moved_to`, `forum_id`) VALUES
(2, 'TVI', 'Buyer TVI reported a new problem in transaction papierosy worth 1 BTC', 1377074261, 2, 1377074261, 2, 'TVI', 2, 0, 0, 0, NULL, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL DEFAULT '3',
  `username` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `salt` varchar(12) DEFAULT NULL,
  `email` varchar(80) NOT NULL DEFAULT '',
  `title` varchar(50) DEFAULT NULL,
  `realname` varchar(40) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `linkedin` varchar(100) DEFAULT NULL,
  `skype` varchar(100) DEFAULT NULL,
  `jabber` varchar(80) DEFAULT NULL,
  `icq` varchar(12) DEFAULT NULL,
  `msn` varchar(80) DEFAULT NULL,
  `aim` varchar(30) DEFAULT NULL,
  `yahoo` varchar(30) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL,
  `signature` text,
  `disp_topics` tinyint(3) unsigned DEFAULT NULL,
  `disp_posts` tinyint(3) unsigned DEFAULT NULL,
  `email_setting` tinyint(1) NOT NULL DEFAULT '1',
  `notify_with_post` tinyint(1) NOT NULL DEFAULT '0',
  `auto_notify` tinyint(1) NOT NULL DEFAULT '0',
  `show_smilies` tinyint(1) NOT NULL DEFAULT '1',
  `show_img` tinyint(1) NOT NULL DEFAULT '1',
  `show_img_sig` tinyint(1) NOT NULL DEFAULT '1',
  `show_avatars` tinyint(1) NOT NULL DEFAULT '1',
  `show_sig` tinyint(1) NOT NULL DEFAULT '1',
  `access_keys` tinyint(1) NOT NULL DEFAULT '0',
  `timezone` float NOT NULL DEFAULT '0',
  `dst` tinyint(1) NOT NULL DEFAULT '0',
  `time_format` int(10) unsigned NOT NULL DEFAULT '0',
  `date_format` int(10) unsigned NOT NULL DEFAULT '0',
  `language` varchar(25) NOT NULL DEFAULT 'English',
  `style` varchar(25) NOT NULL DEFAULT 'Oxygen',
  `num_posts` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post` int(10) unsigned DEFAULT NULL,
  `last_search` int(10) unsigned DEFAULT NULL,
  `last_email_sent` int(10) unsigned DEFAULT NULL,
  `registered` int(10) unsigned NOT NULL DEFAULT '0',
  `registration_ip` varchar(39) NOT NULL DEFAULT '0.0.0.0',
  `last_visit` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_note` varchar(30) DEFAULT NULL,
  `activate_string` varchar(80) DEFAULT NULL,
  `activate_key` varchar(8) DEFAULT NULL,
  `avatar` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `avatar_width` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `avatar_height` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pubkey` varchar(3700) NOT NULL,
  `btcaddress` varchar(34) NOT NULL,
  `btcescrowaddress` varchar(34) NOT NULL,
  `pun_pm_new_messages` int(10) DEFAULT NULL,
  `pun_pm_long_subject` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `users_registered_idx` (`registered`),
  KEY `users_username_idx` (`username`(8))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `group_id`, `username`, `password`, `salt`, `email`, `title`, `realname`, `url`, `facebook`, `twitter`, `linkedin`, `skype`, `jabber`, `icq`, `msn`, `aim`, `yahoo`, `location`, `signature`, `disp_topics`, `disp_posts`, `email_setting`, `notify_with_post`, `auto_notify`, `show_smilies`, `show_img`, `show_img_sig`, `show_avatars`, `show_sig`, `access_keys`, `timezone`, `dst`, `time_format`, `date_format`, `language`, `style`, `num_posts`, `last_post`, `last_search`, `last_email_sent`, `registered`, `registration_ip`, `last_visit`, `admin_note`, `activate_string`, `activate_key`, `avatar`, `avatar_width`, `avatar_height`, `pubkey`, `btcaddress`, `btcescrowaddress`, `pun_pm_new_messages`, `pun_pm_long_subject`) VALUES
(1, 2, 'Guest', 'Guest', NULL, 'Guest', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 'English', 'Oxygen', 0, NULL, NULL, NULL, 0, '0.0.0.0', 0, NULL, NULL, NULL, 0, 0, 0, '', '', '', NULL, 1),
(2, 1, 'Mbank_konsultant', '00e6df9e56cba9344f5574634b3d134ceab260fa', 'X+one^BuKp&&', 'bitcoinpower@tormail.org', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 'English', 'Oxygen', 8, 1377688595, 1377687540, 1375447187, 1375269731, '127.0.0.1', 1377942238, NULL, NULL, 'afkafkaf', 0, 0, 0, '-----BEGIN PGP PUBLIC KEY BLOCK-----\r\nVersion: GnuPG v1.4.11 (GNU/Linux)\r\n\r\nmQENBFD5K7MBCADF96fQqkxbKGBBPe6GZlTnJ+PjNnsxK6xgifchuOOYEazLjbSh\r\nWlv+0vCrhxMlZFR9xzZaYXarDHYUAjgkGUyB3KkB8ruiPnoV9ak+isy8+ni496Fe\r\nth8FMwz10Fm0IidBpVifMqm/3DTx+Bvk/yMnAcu2LORmkexK+MUb3T8IJ6BagSww\r\nEXTvH1gED7MpLVLzOrz7xEzZ1FPUCdJVrOv25NYsfUy2XDJES60OvG80BdeAZxj0\r\ny1oMgTqaYTFEsA2oK3RNfGZaMnm7ffAPAYQ95Z6I7+sXsy0SqIHziNTuO2eDOMog\r\nQErU9rLcvdqDfkmPv/7Gc/AGhaM2GzS1ngNHABEBAAG0K0tvbnN1bHRhbnQgKGZ1\r\ndSkgPGJpdGNvaW5wb3dlckB0b3JtYWlsLm9yZz6JATgEEwECACIFAlD5K7MCGwMG\r\nCwkIBwMCBhUIAgkKCwQWAgMBAh4BAheAAAoJEL43RVK+AuyhlrMH/jotJ55u31xZ\r\nNeRqa4ehxPqBGhwAK34wUSvUAU8BbhI/f6kgulzJYKfiEayjTU48yGsfvoThwXl6\r\n6UMqR6sVAQ9cD6sDhUYq160CGR0N/9v0VMRS1GipCgFSJ2q1FKhuOcOL6AxShOPj\r\n6jwRAXntUkqW1zoPvmCSfjEGsx36iFAUd5m5M06MlVvCVJ/Uwzh2VYaSOyKQmS25\r\n8p0NkhEkfLjZ0HWjPUux+S5q4Q39FEkNsd8xopa0IjYw+EvqSASDw2kPyk4BqO7y\r\nS7T8pjLyjxuF/aEC9ugUxAe0w2M6HglpSU51wLJKYioKj5Ff4imI7UzZJaGHBozJ\r\n3Jhc0FRqE225AQ0EUPkrswEIAKFFRw5A4TpC8VDSrZglFI/msqsJUBKo1h+HTZ0z\r\nObZzTOdDiBu34pmlvEvBci2gDbOeUECmrIb9ZdVyUvYRKLGSQVotLWbPoZ2AzbwB\r\n4uHuIlepP+rrN0nv9fWMzUXrtaqSooXihpKMaNpPwuExubEJottfYdbemp9wZbcy\r\niycVyx/G/u3Hegd1LzPyj+J3q7o9TU9NbdwsN4isbEM8sYgYL7svBKJrI4ApNurm\r\nSZ0v0kKrJK7VFHHy7kyOslTa7u2kJTdhMxeUg0e4Nm7XUqcHYabLMewjdIrZsB52\r\n3XqCUSiYh/dS56jPHZhpWu1IsvmIewV8yh7uDYF7Mwbc+YkAEQEAAYkBHwQYAQIA\r\nCQUCUPkrswIbDAAKCRC+N0VSvgLsoTaoB/401Sz1F3Qd7OKpApcCeRhMTFHMGgIC\r\n0dGWvr88G8N8dJAJkxbMLacHDoK8x4T1bCt7nEa9K+uL4+aMTUQ604Ff/GC5gTGu\r\nJ0PqVOE9pXN5ODsI+AkTh7Rgenqt3VxKdwbvqWXUuijs+uA3Xjx6Z+z51u8OBLXI\r\n1M8ZrT/fIAFOF3JdZric1zASLzA3Vt3rlvOhsfvH2I4zDTJtaIMn6XntyyRmdaQB\r\ngtNPXrK8OcUhtqkZ+35x1D5aFHlMeHfMw9A3uRokBRrCClcsrZ5NkPvs4c1qMCQo\r\nfmqvHpAyzTPlJoE38UUlhbmUqJGrSob+DEtgQh9qtGJhNsaCn244iI0f\r\n=caGF\r\n-----END PGP PUBLIC KEY BLOCK-----', '1AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '', 4, 1),
(11, 3, 'zdzislaw.dyrma', '42e48f2c968894de90a12ab2fd1036d879e71277', 'qMniD/Uv6nQ%', 'zdzislaw.dyrma@safe-mail.net', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 'English', 'Oxygen', 3, 1377856029, NULL, NULL, 1377688893, '192.168.0.10', 1377937775, NULL, NULL, NULL, 0, 0, 0, '', '14kibS1NsYZUjxcZbHLER3PLpLggUku52v', '', 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
