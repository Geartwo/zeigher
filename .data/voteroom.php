<?php
include 'all.php';
include 'functions.php';
$fileid = $_POST['file'];
$folder = $_POST['folder'];
$row = $db->query("SELECT * FROM files WHERE id = '$fileid'")->fetch_assoc();
@$filedate = date("d.m.Y G:i", strtotime($row['date']));
$upuserid = $row['userid'];
$filename = $row['name'];
$pro = $row['pro'];
$con = $row['con'];
$view = $row['view'];
$description = $row['description'];
$row = $db->query("SELECT * FROM points WHERE type = 'user' AND objectid = '$userid'")->fetch_assoc();
$row = $db->query("SELECT * FROM user WHERE id = '$upuserid'")->fetch_assoc();
$upuser = $row['user'];
echo $filename;
include 'php-markdown/Michelf/Markdown.inc.php';
$description = str_replace("<", htmlentities("<"), $description);
$my_html = \Michelf\Markdown::defaultTransform($description);
echo "<a class='ico-down' href='.data/downloader.php?file=.".$folder."/".$filename."'></a><br>";
echo $lang->uploaded.": ".$filedate."<b class='vote' id='fileup' onclick=\"conpro('up', 'files', '".$fileid."', 'file');\">".$pro."</b><b class='vote'>+</b> <b class='vote' id='filedown' onclick=\"conpro('down', 'files', '".$fileid."', 'file');\">".$con."</b><b class='vote'>-</b> ".$lang->user.": ".$upuser."<br>".
$my_html."<br>";
include 'comments.php';
?>
