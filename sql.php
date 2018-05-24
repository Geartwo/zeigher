<?php
//SQL connect
$db = new mysqli($dbhost, $dbuser, $dbwd, $dbank);
if($db->connect_error):
	echo "<b style='color: red'>Fatal Error: Connection failed - $conn->connect_error</b>";
	exit;
endif;

if(!$db->set_charset("utf8")):
    	echo "<b style='color: red'>Fatal Error: Loading character set utf8 - $db->error</b>";
    	exit();
endif;
?>
