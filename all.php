<?php
$settings = new ExtendStdClass;
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
	$result = $db->query("SELECT username, admin FROM user WHERE id = '$userid'")->fetch_object();
	$username = $result->username;
	$isadbol = $result->admin;
else:
	$isadbol = 0;
endif;
$isad = function(){
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
        endwhile;
// TODO END
require "functions.php";
$hook->include("function.php");

if(!isset($_SESSION['loggedin'])) $_SESSION['loggedin'] = false;
if(!$_SESSION['loggedin'] && isset($_COOKIE['Zeigher-ID']) && isset($_COOKIE['Zeigher-Token'])):
        $id = $db->real_escape_string($_COOKIE['Zeigher-ID']);
        $row = $db->query("SELECT id, username, password, free, email FROM user WHERE id = '$id'")->fetch_assoc();
        if($row['free'] == true && hash_equals($_COOKIE['Zeigher-Token'], crypt($row['username'].$row['email'], $row['password']))):
                $_SESSION['loggedin'] = true;
                $_SESSION['userid'] = $row['id'];
        endif;
endif;
if (!isset($color)) $color = "green";
?>
