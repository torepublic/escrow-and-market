<?php

if (!defined('FORUM')) exit;
define('FORUM_QJ_LOADED', 1);
$forum_id = isset($forum_id) ? $forum_id : 0;

?><form id="qjump" method="get" accept-charset="utf-8" action="http://nco5ranerted3nkt.onion/escrow/viewforum.php">
	<div class="frm-fld frm-select">
		<label for="qjump-select"><span><?php echo $lang_common['Jump to'] ?></span></label><br />
		<span class="frm-input"><select id="qjump-select" name="id">
			<optgroup label="Tablica.TOR.pl">
				<option value="8"<?php echo ($forum_id == 8) ? ' selected="selected"' : '' ?>>Narkotyki</option>
				<option value="9"<?php echo ($forum_id == 9) ? ' selected="selected"' : '' ?>>Broń</option>
				<option value="10"<?php echo ($forum_id == 10) ? ' selected="selected"' : '' ?>>Elektronika</option>
				<option value="11"<?php echo ($forum_id == 11) ? ' selected="selected"' : '' ?>>Usługi</option>
				<option value="12"<?php echo ($forum_id == 12) ? ' selected="selected"' : '' ?>>Chemia</option>
				<option value="13"<?php echo ($forum_id == 13) ? ' selected="selected"' : '' ?>>Edukacja</option>
				<option value="14"<?php echo ($forum_id == 14) ? ' selected="selected"' : '' ?>>Erotyka</option>
				<option value="15"<?php echo ($forum_id == 15) ? ' selected="selected"' : '' ?>>Pozostałe</option>
			</optgroup>
			<optgroup label="Reported problems">
				<option value="6"<?php echo ($forum_id == 6) ? ' selected="selected"' : '' ?>>Aktualne zgloszenia</option>
				<option value="7"<?php echo ($forum_id == 7) ? ' selected="selected"' : '' ?>>Archiwalne zgloszenia</option>
			</optgroup>
		</select>
		<input type="submit" id="qjump-submit" value="<?php echo $lang_common['Go'] ?>" /></span>
	</div>
</form>
<?php

$forum_javascript_quickjump_code = <<<EOL
(function () {
	var forum_quickjump_url = "http://nco5ranerted3nkt.onion/escrow/viewforum.php?id=$1";
	var sef_friendly_url_array = new Array(10);
	sef_friendly_url_array[8] = "narkotyki";
	sef_friendly_url_array[9] = "bron";
	sef_friendly_url_array[10] = "elektronika";
	sef_friendly_url_array[11] = "uslugi";
	sef_friendly_url_array[12] = "chemia";
	sef_friendly_url_array[13] = "edukacja";
	sef_friendly_url_array[14] = "erotyka";
	sef_friendly_url_array[15] = "pozostale";
	sef_friendly_url_array[6] = "aktualne-zgloszenia";
	sef_friendly_url_array[7] = "archiwalne-zgloszenia";

	PUNBB.common.addDOMReadyEvent(function () { PUNBB.common.attachQuickjumpRedirect(forum_quickjump_url, sef_friendly_url_array); });
}());
EOL;

$forum_loader->add_js($forum_javascript_quickjump_code, array('type' => 'inline', 'weight' => 60, 'group' => FORUM_JS_GROUP_SYSTEM));
?>
