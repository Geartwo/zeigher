<?php
$settings = new stdClass();
$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$upload_mb = min($max_upload, $max_post, $memory_limit);
session_start();
require 'sql.php';
//Get Settings
$dbquery = $db->query("SELECT * FROM settings WHERE userid = '0'");
while ($row = $dbquery->fetch_assoc()):
	$setting = $row['name'];
	$settings->$setting = $row['value'];
endwhile;
//Setting
if (isset($_SESSION['userid'])) {$userid = $_SESSION['userid'];} else {$userid = '-1';}
if (isset($_SESSION['edit'])) {$edit = $_SESSION['edit'];} else {$edit = 0;}
if (isset($_GET['edit'])) { $edit = $_GET['edit']; }
if ($edit == 1) { $_SESSION['edit'] = 1; } else { unset($_SESSION['edit']); }
//SQL Ready
$numtheme = $db->query("SELECT value FROM settings WHERE name = 'theme' AND userid = '$userid'");
if($numtheme->num_rows == 1):
	$themeid = $userid;
else:
	$themeid = 0;
endif;
$theme = $db->query("SELECT value FROM settings WHERE name = 'theme' AND userid = '$themeid'")->fetch_object()->value;
if($userid > 0):
	$isadbol = false;
	$result = $db->query("SELECT username FROM user WHERE id = '$userid'")->fetch_object()->user;
	$username = $result->user;
	$isadbol = $result->admin;
else:
	$isadbol = 0;
endif;
$isad = function($isadright){
	global $isadbol;
	return $isadbol;
};
//Get Color
$numcolor = $db->query("SELECT value FROM settings WHERE name = 'color' AND userid = '$userid'");
if($numcolor->num_rows == 1):
	$colorid = $userid;
else:
	$colorid = 0;
endif;
$color = $db->query("SELECT value FROM settings WHERE name = 'color' AND userid = '$colorid'")->fetch_object()->value;
$dbquery = $db->query("SELECT name FROM plugins WHERE active = 1");


//TODO - Hook
        $pluginfolder = "plugins";
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
                if(file_exists($longpfolder."lang.php")):
                        $langextension[$row['name']] = $longpfolder."lang.php";
                endif;
        endwhile;
require "functions.php";
if(isset($functionsextension)):
    foreach($functionsextension as $fuex):
        include($fuex);
    endforeach;
endif;
// TODO END


if(!$_SESSION['loggedin'] && isset($_COOKIE['Zeigher-ID']) && isset($_COOKIE['Zeigher-Token'])):
        $id = $db->real_escape_string($_COOKIE['Zeigher-ID']);
        $row = $db->query("SELECT id, user, password, free FROM user WHERE id = '$id'")->fetch_assoc();
        if($row['free'] == true && hash_equals($_COOKIE['Zeigher-Token'], crypt($row['user'].$row['mail'], $row['password']))):
                $_SESSION['loggedin'] = true;
                $_SESSION['userid'] = $row['id'];
        endif;
endif;
if (!isset($color)) $color = "green";
?>
