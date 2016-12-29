<?php
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
if(isset($db) && $installed == true):
	if(!mysqli_set_charset($db, "utf8")):
    	printf("Error loading character set utf8: %s\n", mysqli_error($db));
    	exit();
	endif;
	//Get Settings
	$dbquery = $db->query("SELECT * FROM settings WHERE userid = '0'");
	while ($row = $dbquery->fetch_assoc()):
		$settings->$row['setting'] = $row['value'];
	endwhile;
	$dbquery = $db->query("SELECT name FROM plugins WHERE active = 1");
	$pluginfolder = ".plugins";
	while($row = $dbquery->fetch_assoc()):
	        $longpfolder = $pluginfolder.DIRECTORY_SEPARATOR.$row['name'].DIRECTORY_SEPARATOR;
	        if(file_exists($longpfolder."admin.php")):
	                $adminextension[$row['name']] = $longpfolder."admin.php";
	        endif;
	        if(file_exists($longpfolder."extension.php")):
	                $plugextension[$row['name']] = $longpfolder."extension.php";
	        endif;
	        if(file_exists($longpfolder."header.php")):
	                $headerextension[$row['name']] = $longpfolder."header.php";
	        endif;
	        if(file_exists($longpfolder."footer.php")):
	                $footerextension[$row['name']] = $longpfolder."footer.php";
	        endif;
	        if(file_exists($longpfolder."voteroom.php")):
	                $voteroomextension[$row['name']] = $longpfolder."voteroom.php";
	        endif;
	        if(file_exists($longpfolder."function.php")):
	                $functionsextension[$row['name']] = $longpfolder."function.php";
	        endif;
	        if(file_exists($longpfolder."main.php")):
	                $mainextension[$row['name']] = $longpfolder."main.php";
	        endif;
	        if(file_exists($longpfolder."lang.php")):
	                $langextension[$row['name']] = $longpfolder."lang.php";
	        endif;
	endwhile;
endif;
?>
