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
if ($_POST["mode"] == 'cl' && $isad >= 5) {
    $sub = $_POST["sub"];
    if ($isad >= 8 || $usercom == $comus ) {
    $db->query("DELETE FROM comments WHERE sub = '$sub'");   
    }
}
?>
