<?php
if (array_key_exists('comment', $_POST)) {
        $date = date("Y-m-d H:i:s");
        $comment = $_POST["comment"];
        $result = $db->query("SHOW TABLE STATUS LIKE 'comments'");
        $row = $result->fetch_array();
        $nextId = $row['Auto_increment'];
        if (isset($_POST["sub"])) $nextId = $_POST["sub"];
        $db->query("INSERT INTO comments (userid, comment, objectid, date, sub, type) VALUES ('$userid', '$comment', '$folder', '$date', '$nextId', 'folder')");
}
if (array_key_exists('cl', $_POST)) {
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
