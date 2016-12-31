<?php
@include '../../../.settings/config.php';
@include '../.settings/config.php';
@include '.settings/config.php';
if (!isset($installed)) {
	echo "<script>self.location.href='install.php'</script>";
	exit;
}
if(!isset($folder)) $folder = ".";
$wish = "";
ini_set("session.cookie_lifetime","2592000");
ini_set("session.gc_maxlifetime", "2592000");
$settings = new stdClass();
$sysisad = new stdClass();
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
if (isset($db) && $installed == true) {
	$numtheme = $db->query("SELECT value FROM settings WHERE setting = 'theme' AND userid = '$userid'");
	if($numtheme->num_rows == 1){
		$themeid = $userid;
	}else{
		$themeid = 0;
	}
	$theme = $db->query("SELECT value FROM settings WHERE setting = 'theme' AND userid = '$themeid'");
	$theme = $theme->fetch_object()->value;
	$dbquery = $db->query("SELECT isad, user FROM user WHERE id = '$userid'");
	$row = $dbquery->fetch_object();
	$isad = $row->isad;
	$username = $row->user;
	if ($settings->points == 'true') {
		//Get Points
		$dbquery = $db->query("SELECT * FROM points WHERE objectid = '$userid'");
		while ($row = $dbquery->fetch_assoc()){
			$userpoints = $row['points'];
		}
		//Get Premium Points
		$dbquery = $db->query("SELECT * FROM user WHERE id = '$userid'");
		while ($row = $dbquery->fetch_assoc()){
			$userpremium = $row['premium'];
		}
	}
	//Get Color
	$numcolor = $db->query("SELECT value FROM settings WHERE setting = 'color' AND userid = '$userid'");
	if($numcolor->num_rows == 1){
		$colorid = $userid;
	}else{
		$colorid = 0;
	}
	$dbquery = $db->query("SELECT value FROM settings WHERE setting = 'color'AND userid = '$colorid'");
	while ($row = $dbquery->fetch_assoc()){
		$color = $row['value'];
	}
}
if (!isset($color)) $color = "green";
?>
