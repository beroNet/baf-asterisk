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

exec('/apps/asterisk/bin/asterisk -C /apps/asterisk/etc/asterisk/asterisk.conf -rnx "sip show peers" | sed "s/Dyn Forcerport ACL//" | sed "s/ D //" | sed "s/ N //" | sed "s/OK (\(.*\) ms)/OK-(\1_ms)/"  | grep -v "Monitored:" | sed "s/[ ]*/<\/td><td>/g"  | sed "s/^<td>//" | sed "s/^<\/td>//" | sed "s/<td>$//"', $tmppeers);
$sippeers=implode("<tr></tr>", $tmppeers);

?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
 <head>
  <link type="text/css" href="./include/css/berofog.css" rel="Stylesheet" />
  <title><?php echo $userapp_n ?> </title>
 </head>

 <body>
  <div class='main'>
  <div class='top'><img src="./include/images/bg_top.png"/></div>
  <div class='left'>

  <h1> <?php echo $userapp_n ?>  </h1>
  <hr noshade/>
  <div>Go to: 
   <table><tr>
    <td><a href="/app/berogui/">berogui</a></td>
    <td><a href="filemanager.php">Asterisk Configuration</a></td>
    <td><a href="index.php?action=reload">Reload Configuration</a></td>
    </tr>
   </table>
  </div>

  <h2>Asterisk</h2>

  <br><b>SIP Status:</b><br>
  <table width=100%>
   <?php echo $sippeers ?>
  </table>
 </div>
 <div class='bottom'><img src="./include/images/bg_bottom.png"></div>
 </div>
 </body>
</html>
