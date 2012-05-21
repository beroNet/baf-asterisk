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
		'sed "s/OK (\(.*    \) ms)/OK-(\1_ms)/" | ' .
		'grep -v "Monitored:" | ' .
		'sed "s/[ ]*/<\/td><td>/g" | '.
		'sed "s/^<td>//" | ' .
		'sed "s/^<\/td>//" | '.
		'sed "s/<td>$//"';
exec($ast_exec . ' ' . $ast_opts . ' | ' . $sed_exec, $peers_tmp);

$peers_sip = implode('<tr></tr>', $peers_tmp);

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
	"\t\t\t\t<hr noshade />\n" .
	"\t\t\t\t<div>\n" .
	"\t\t\t\t\tMenu: <a href=\"/app/berogui/\">berogui</a> | <a href=\"/userapp/\">UserApp Management</a> | <a href=\"?action=reload\">Reload</a>\n" .
	"\t\t\t\t</div>\n" .
	"\t\t\t\t<h2>Asterisk</h2>\n" .
	"\t\t\t\t<div>\n" .
	"\t\t\t\t\tYou can use SIP Phones to register at Asterisk with SIP Port 25060.<br />\n" .
	"\t\t\t\t\tThere are 10 SIP Users: Username=10..20 Secret=10..20<br /><br />\n" .
	"\t\t\t\t\t<span style=\"font-weight: bold;\">Example SIP Phone Configuration:</span><br /><br />\n" .
	"\t\t\t\t\tSIP Registrar: "	. $_SERVER['SERVER_NAME']	. ":25060<br />\n" .
	"\t\t\t\t\tSIP Server: "	. $_SERVER['SERVER_NAME']	. ":25060<br />\n" .
	"\t\t\t\t\tSIP Proxy: "		. $_SERVER['SERVER_NAME']	. ":25060<br />\n" .
	"\t\t\t\t\tSIP Server Port: 25060<br />\n" .
	"\t\t\t\t\tUsername = 10<br />\n" .
	"\t\t\t\t\tSecret = 10<br /><br />\n" .
	"\t\t\t\t\t<span style=\"font-weight: bold;\">HowTo Dial</span><br /><br />\n" .
	"\t\t\t\t\tEvery extensions can reach each other, by just dialing the extensions.\n" .
	"\t\t\t\t\t<span style=\"font-weight: bold;\">Example:</span>\n" .
	"\t\t\t\t\tDial 11 to reach Phone 11<br />\n".
	"\t\t\t\t\tThe beroFix can be reached by prefixing the call with a 0.\n" .
	"\t\t\t\t\t<span style=\"font-weight: bold;\">Example:</span>\n" .
	"\t\t\t\t\tDial 01234 to dial 1234 to berofix.<br /><br /><br />\n" .
	"\t\t\t\t\t<span style=\"font-weight: bold;\">Status:</span><br />\n" .
	"\t\t\t\t\t<table width=100%>\n" .
	$peers_sip .
	"\t\t\t\t\t</table>\n" .
	"\t\t\t\t</div>\n" .
	"\t\t\t</div>\n" .
	"\t\t\t<div class='bottom'><img src=\"/app/berogui/includes/images/bg_bottom.png\"></div>\n" .
	"\t\t</div>\n" .
	"\t</body>\n" .
	"</html>";
?>
