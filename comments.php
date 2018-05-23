<?php
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != "true") exit;
if ($_REQUEST["mode"] == 'comment') {
        $result = $db->query("SHOW TABLE STATUS LIKE 'comments'");
        $row = $result->fetch_array();
        $nextId = $row['Auto_increment'];
        if (isset($_REQUEST["sub"])) $nextId = $_REQUEST["sub"];
        $db->query("INSERT INTO comments (userid, comment, objectid, date, sub) VALUES ('$userid', '".escape('comment')."', '".escape('oid')."', '".date("Y-m-d H:i:s")."', '$nextId')");
}
if ($_REQUEST["mode"] == 'cl' && $isad >= 5) {
    $sub = $_REQUEST["sub"];
    if ($isad >= 8 || $usercom == $comus ) {
    $db->query("DELETE FROM comments WHERE sub = '$sub'");   
    }
}
?>
