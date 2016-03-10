<?php
include 'all.php';
if($_SESSION['loggedin'] != true) exit;
if ($_POST["mode"] == 'comment') {
        $date = date("Y-m-d H:i:s");
        $comment = $_POST["comment"];
	$type = $_POST["type"];
	$oid = $_POST["oid"];
        $result = $db->query("SHOW TABLE STATUS LIKE 'comments'");
        $row = $result->fetch_array();
        $nextId = $row['Auto_increment'];
        if (isset($_POST["sub"])) $nextId = $_POST["sub"];
        $db->query("INSERT INTO comments (userid, comment, objectid, date, sub, type) VALUES ('$userid', '$comment', '$oid', '$date', '$nextId', '$type')");
}
if ($_POST["mode"] == 'comment' && $isad >= 5) {
    if (isset($_POST["sub"])) $comus = $_POST["sub"];
    if (isset($_POST["comsub"])) $comsub = $_POST["comsub"];
    if (isset($_POST["comid"])) $comid = $_POST["comid"];
    if ($isad >= 8 || $usercom == $comus ) {
        if ($comsub == $comid) {
            $db->query("DELETE FROM comments WHERE sub = '$comsub'");   
        } else{
		$db->query("DELETE FROM comments WHERE id = '$comid'");
        }
    }
}
?>
