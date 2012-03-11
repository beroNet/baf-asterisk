<?php

if ($_GET['cmd'] == "connect" ) {
    
    $source=$_GET['source'];
    $destination=$_GET['destination'];

    $callfile="
Channel: SIP/$source\n
Application: Dial\n
Data: Local/$destination@default,,r\n
";


    $file="/apps/asterisk/var/spool/asterisk/outgoing/test.call";
    file_put_contents($file, $callfile);
    chmod($file, 0666);
    
    echo "result: success<br>source=$source;destination=$destination";
}

?>
