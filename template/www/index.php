<?php


function get_userapp_name () {

	$ret = "Unknown UserAppFS";

	if (($fp = fopen("/apps/asterisk/VERSION", "r"))) {
		$buf = fread($fp, 1024);
		fclose($fp);

		if (preg_match("/NAME=.+\n/", $buf, $matches)) {
			$ret = trim(substr($matches[0], strpos($matches[0], '=') + 1), "\"\n");
		}
	}

	return($ret);
}

$userapp_n	= get_userapp_name();

if ($_GET['action'] == "reload" ) {
    exec('/apps/asterisk/bin/asterisk -C /apps/asterisk/etc/asterisk/asterisk.conf -rnx "core reload"');
}


exec('/apps/asterisk/bin/asterisk -C /apps/asterisk/etc/asterisk/asterisk.conf -rnx "sip show peers" | sed "s/Dyn Forcerport ACL//" | sed "s/ D //" | sed "s/OK (\(.*\) ms)/OK-(\1_ms)/"  | grep -v "Monitored:" | sed "s/[ ]*/<\/td><td>/g"  | sed "s/^<td>//" | sed "s/^<\/td>//" | sed "s/<td>$//"', $tmppeers);
$sippeers=implode("<tr></tr>", $tmppeers);


$ret			=	"<h1>" . $userapp_n. " </h1>\n
				<hr noshade/>" .
                                "<div>Go to: 
                                <table><tr>
                                <td><a href=\"/app/berogui/\">berogui</a></td>
                                <td><a href=\"filemanager.php\">Asterisk Configuration</a></td>
                                <td><a href=\"index.php?action=reload\">Reload Configuration</a></td>
                                </tr></table>
                                </div>\n" .

                                "<h2>Asterisk</h2>" .
                                "<br><br><b>Stauts:</b><br>".
                                "<table width=100%>".
                                $sippeers.
                                "</table>";

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n
	<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n
	<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\n
		<head>\n
			<link type=\"text/css\" href=\"./include/css/berofog.css\" rel=\"Stylesheet\" />\n
			<title>" . $userapp_n. " </title>\n
		</head>\n
		<body>\n
			<div class='main'>\n
				<div class='top'><img src=\"./include/images/bg_top.png\"/></div>\n
				<div class='left'>\n"
					. $ret .
				"</div>\n
				<div class='bottom'><img src=\"./include/images/bg_bottom.png\"></div>\n
			</div>\n
		</body>\n
	</html>\n";

?>
