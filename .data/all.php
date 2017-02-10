<?php
//Check dependencys
if(!function_exists('mysqli')):
	echo "MYSQLI dont exist";
endif;
include '.settings/config.php';
ini_set("session.cookie_lifetime", 2592000);
ini_set("session.gc_maxlifetime", 2592000);
$settings = new stdClass();
$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$upload_mb = min($max_upload, $max_post, $memory_limit);
session_start();
include 'sql.php';
//Setting
if (isset($_SESSION['userid'])) {$userid = $_SESSION['userid'];} else {$userid = '-1';}
if (isset($_SESSION['edit'])) {$edit = $_SESSION['edit'];} else {$edit = 0;}
if (isset($_GET['edit'])) { $edit = $_GET['edit']; }
if ($edit == 1) { $_SESSION['edit'] = 1; } else { unset($_SESSION['edit']); }
//SQL Ready
if(isset($db)):
	$numtheme = $db->query("SELECT value FROM settings WHERE setting = 'theme' AND userid = '$userid'");
	if($numtheme->num_rows == 1):
		$themeid = $userid;
	else:
		$themeid = 0;
	endif;
	$theme = $db->query("SELECT value FROM settings WHERE setting = 'theme' AND userid = '$themeid'")->fetch_object()->value;
	$username = $db->query("SELECT user FROM user WHERE id = '$userid'")->fetch_object()->user;
	$isadbol = false;
	$isadbol = $db->query("SELECT isad FROM user WHERE id = '$userid'")->fetch_object()->isad;
	$isad = function($isadright){
		global $isadbol;
		return $isadbol;
	};
	if($settings->points == 'true'):
		//Get Points
		$userpoints = $db->query("SELECT points FROM points WHERE objectid = '$userid'")->fetch_object()->points;
		//Get Premium Points
		$userpremium = $db->query("SELECT premium FROM user WHERE id = '$userid'")->fetch_object()->premium;
	endif;
	//Get Color
	$numcolor = $db->query("SELECT value FROM settings WHERE setting = 'color' AND userid = '$userid'");
	if($numcolor->num_rows == 1):
		$colorid = $userid;
	else:
		$colorid = 0;
	endif;
	$color = $db->query("SELECT value FROM settings WHERE setting = 'color' AND userid = '$colorid'")->fetch_object()->value;
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
		if(file_exists($longpfolder."function.js")):
                        $functionsjsextension[$row['name']] = $longpfolder."function.js";
                endif;
                if(file_exists($longpfolder."main.php")):
                        $mainextension[$row['name']] = $longpfolder."main.php";
                endif;
                if(file_exists($longpfolder."lang.php")):
                        $langextension[$row['name']] = $longpfolder."lang.php";
                endif;
        endwhile;
endif;
include ".data/functions.php";
if(isset($functionsextension)):
    foreach($functionsextension as $fuex):
        include($fuex);
    endforeach;
endif;
if (!isset($color)) $color = "green";
?>
