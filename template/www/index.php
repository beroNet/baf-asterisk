<?php

include(file_exists('/home/admin/lib/php/beroGui.class.php') ? '/home/admin/lib/php/beroGui.class.php' : '/apps/asterisk/lib/php/beroGui.class.php');

function sippeers_table () {

	exec('/apps/asterisk/bin/asterisk -C /apps/asterisk/etc/asterisk/asterisk.conf -rnx "sip show peers"', $asterisk_out, $retval);

	if ($retval != 0) {
		return(false);
	}

	$ret =	'<table id="table" class="contenttoc">' . "\n" .
		'<tr>';
	$table_head = array_filter(explode(' ', $asterisk_out[0]));
	foreach ($table_head as $head) {
		if (in_array($head, array('Dyn', 'Forcerport', 'ACL'))) {
			continue;
		}
		$ret .= '<th style="text-align: center;">' . ucwords(str_replace('/', ' / ', $head)) . '</th>';
	}
	$ret .= '</tr>' . "\n";

	for ($i = 1; $i < (count($asterisk_out) - 1); $i++) {
		$tmp_row = array_merge(array_filter(explode('  ', $asterisk_out[$i])));
		unset($tmp_row[2]);
		if ($tmp_row[3] == ' N') {
			unset($tmp_row[3]);
		}
		$ret .= '<tr>';
		foreach ($tmp_row as $item) {
			$ret .= '<td style="text-align: center;">' . $item . '</td>';
		}
		$ret .= '</tr>' . "\n";
	}

	$ret .= '</table>' . "\n";

	return($ret);
}

$app_name = 'asterisk';
require_once(file_exists('/home/admin/lib/php/session.php') ? '/home/admin/lib/php/session.php' : '/apps/asterisk/lib/php/session.php');

switch ($_GET['action']) {
case 'reload':
	exec('/apps/asterisk/bin/asterisk -C /apps/asterisk/etc/asterisk/asterisk.conf -rnx "core reload"');
	sleep(2);
	break;
case 'restart':
	exec('/apps/asterisk/init/S01asterisk restart');
	sleep(2);
	break;
}

if (($body = sippeers_table()) == false) {
	$body = '<div style="color: red; font-weight: bold; text-align: center;">Warning: Asterisk does not seem to be running!</div>' . "\n";
}

$gui = new beroGUIv2('Asterisk');

$menu = array(	array('url' => '?action=reload', 'id' => 'reload', 'title' => 'Reload Asterisk'),
		array('url' => '?action=restart', 'id' => 'restart', 'title' => 'Restart Asterisk'));

echo	$gui->get_MainHeader($menu, null) .
	$body .
	$gui->get_MainFooter();

?>
