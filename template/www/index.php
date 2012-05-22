<?php

$app_name	= 'asterisk';
$base_path	= '/apps/' . $app_name;
$conf_path	= $base_path . '/etc';

$redir_login = '/app/berogui/includes/login.php';

# check if session is still active
@session_start();
if (!isset($_SESSION['beroari_time'])) {
	header('Location:' . $redir_login . '?userapp=' . $app_name);
	exit();
} elseif ((isset($_SESSION['beroari_time'])) && (($_SESSION['beroari_time'] + 1200) < time())) {
	@session_unset();
	@session_destroy();
	header('Location:' . $redir_login . '?reason=sess_expd&userapp=' . $app_name);
	exit();
}

# reset session time
$_SESSION['beroari_time'] = time();

if ($_GET['action'] == "reload" ) {
    exec($base_path . '/bin/asterisk -C ' . $conf_path . '/asterisk/asterisk.conf -rnx "core reload"');
}

$ast_exec = $base_path . '/bin/asterisk';
$ast_opts = '-C ' . $conf_path . '/asterisk/asterisk.conf -rnx "sip show peers"';

$sed_exec = 	'sed "s/Dyn Forcerport ACL//" | ' .
		'sed "s/ D //" | ' .
		'sed "s/OK (\(.*\) ms)/OK\&nbsp;(\1\&nbsp;ms)/" | ' .
		'grep -v "Monitored:" | ' .
		'sed "s/[ ]*/<\/td><td>/g" | '.
		'sed "s/^<td>//" | ' .
		'sed "s/^<\/td>//" | '.
		'sed "s/<td>$//"';
exec($ast_exec . ' ' . $ast_opts . ' | ' . $sed_exec, $peers_tmp);

$peers_sip = "<table style=\"width: 100%\">\n<tr>" . implode("</tr>\n<tr>", $peers_tmp) . "</tr>\n</table>\n";

unset($peers_tmp);

echo	"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" .
	"\t<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n" .
	"<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\n" .
	"\t<head>\n" .
	"\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n" .
	"\t\t<link type=\"text/css\" href=\"/userapp/css/beroApp.css\" rel=\"Stylesheet\" />\n" .
	"\t\t<link rel=\"icon\" href=\"/app/berogui/includes/images/favicon.ico\" type=\"image/x-icon\" />\n" .
	"\t\t<title>" . $app_name . "</title>\n" .
	"\t</head>\n" .
	"\t<body>\n" .
	"\t\t<div class=\"main\">\n" .
	"\t\t\t<div class=\"top\"><img src=\"/app/berogui/includes/images/bg_top.png\"/></div>\n" .
	"\t\t\t<div class=\"left\">\n" .
	"\t\t\t\t<h1>" . $app_name . "</h1>\n" .
	"\t\t\t\t<div>\n" .
	"\t\t\t\t\tMenu: \n" .
	"\t\t\t\t\t\t<a href=\"/app/berogui/\">berogui</a> | \n" .
	"\t\t\t\t\t\t<a href=\"/userapp/\">UserApp Management</a> | \n" .
	"\t\t\t\t\t\t<a href=\"filemanager.php\">Asterisk Configuration</a> | \n" .
	"\t\t\t\t\t\t<a href=\"?action=reload\">Reload</a>\n" .
	"\t\t\t\t</div>\n" .
	"\t\t\t\t<h2>Status</h2>\n" .
	"\t\t\t\t<div>\n" .
	$peers_sip .
	"\t\t\t\t</div>\n" .
	"\t\t\t</div>\n" .
	"\t\t\t<div class='bottom'><img src=\"/app/berogui/includes/images/bg_bottom.png\"></div>\n" .
	"\t\t</div>\n" .
	"\t</body>\n" .
	"</html>";
?>
