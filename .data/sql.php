<?php
@include '../.settings/config.php';
@include '.settings/config.php';
if(!isset($theme))$theme = "default";
//SQL connect
if ($mode == 'fmyma' | $mode == 'dmyma' && $installed == true) {
    $db = new mysqli($dbhost, $dbuser, $dbwd, $dbank);
	if (mysqli_connect_errno()) {
		printf("Can't connect to MySQL Server. Errorcode: %s\n",mysqli_connect_error());
		exit;
	}
} elseif ($mode == 'fsql') { 
    $db = new SQLite3($sqlitefolder . 'mysqlitedb.db');
} elseif($installed == true) {
	printf("No Mode Selectet");
        exit;
}

if (isset($db) && $installed == true) {
	//Get Settings
	$settings = new stdClass();
	$dbquery = $db->query("SELECT * FROM settings WHERE userid = '0'");
	while ($row = $dbquery->fetch_assoc()){
		$settings->$row['setting'] = $row['value'];
	}
}
?>