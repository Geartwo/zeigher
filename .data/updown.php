<?php
include 'all.php';
if($_POST['updown']=='up'){
    $updown = 1;
    $conpro = 'pro';
    $cpother = 'con';
}elseif($_POST['updown']=='down'){
    $updown = -1;
    $conpro = 'con';
    $cpother = 'pro';
}
$downup = $updown * -1;
$objectid = $_POST['objectid'];
$type = $_POST['type'];

$dbquery = $db->query("SELECT * FROM votes WHERE userid = '$userid' AND objectid = '$objectid' AND type = '$type'");
if($dbquery->num_rows >= 1) {
    $row = @mysqli_fetch_assoc($dbquery);
    if($row['conpro'] == $updown) {
	$db->query("DELETE FROM votes WHERE userid = '$userid' AND objectid = '$objectid' AND type = '$type'");
	$db->query("UPDATE $type SET $conpro = $conpro-1 WHERE id = '$objectid'");
    } else {
	$db->query("UPDATE votes SET conpro = $updown WHERE userid = '$userid' AND objectid = '$objectid' AND type = '$type'");
	$db->query("UPDATE $type SET $conpro = $conpro+1, $cpother = $cpother-1 WHERE id = '$objectid'");
    }
} else {
    $db->query("INSERT INTO votes (type, objectid, userid, conpro) VALUES ('$type', '$objectid', '$userid', '$updown')");
    $db->query("UPDATE $type SET $conpro = $conpro+1 WHERE id = '$objectid'");
}
$search = @$db->query("SELECT * FROM $type WHERE id = '$objectid'");
$row = @mysqli_fetch_assoc($search);
echo $row['pro']." ".$row['con'];
?>
