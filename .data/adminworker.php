<?php
include 'all.php';
$id = $_POST['id'];
if (isset($_POST["user"])){
    if (!isset($_POST['isad'])) $poisad = 0; else $poisad = $_POST['isad'];
    $row = $db->query("SELECT isad FROM user WHERE id = '$id'")->fetch_assoc();
    if($isad < $row['isad']) exit;
    if($_POST['action'] == "setting"){
        if ($_POST['free'] == "false") $pofree = 0; else $pofree = 1;
        if (!isset($_POST['oisad'])) $oisad = 0; else $oisad = $_POST['oisad'];
        $db->query("UPDATE user SET isad = '$poisad' WHERE id = '$id'");
        $db->query("UPDATE user SET free = '$pofree' WHERE id = '$id'");
    }elseif($_POST['action'] == "delete"){
        if($userid == $id){
            exit;
        }else{
            $db->query("DELETE FROM user WHERE id = '$id'");
        }
    }elseif($_POST["action"] == "mail"){
        $subject = 'Sie wurden auf '.$name.'/'.$path.' freigeschalten';
        $message = 'Sie wurden auf '.$name.' freigeschalten und können diese Seite jetzt uneingeschränkt benutzen.
        '.$https.'://'.$hostname.'/'.$path;
        $headers = "Content-type:text/plain;charset=utf-8" . "\n" . 'From: ' . $sender . "\r\n" . 'Reply-To: ' . $sender . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        $mail = $row['mail'];
        $ouser = $row['user'];
	    mail($mail, $subject, $message, $headers);
    	$db->query("UPDATE user Set free = '1' WHERE user = '$ouser'");
    }
}
if (isset($_POST["sysisad"])){
    if($isad <= $sysisad->isad) exit;
    $value = $_POST['value'];
    $db->query("UPDATE isad SET ivalue = '$value' WHERE id = '$id'");
}
