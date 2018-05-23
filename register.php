<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$username = $db->real_escape_string($_POST['username']);
	$password = $db->real_escape_string($_POST['password']);
	$wpassword = $db->real_escape_string($_POST['wpassword']);
	$mail = $db->real_escape_string($_POST['mail']);
	$code = $db->real_escape_string($_POST['code']);
	$dbquery = $db->query("SELECT * FROM user WHERE user = '$username'");
	$dbnum = $dbquery->num_rows;
	$dbquery = $db->query("SELECT * FROM user WHERE email = '$mail'");
	$mailnum = $dbquery->num_rows;
	$dbquery = $db->query("SELECT * FROM promotion WHERE code = '$code'");
	$aktnum = $dbquery->num_rows;
	if($username == ''){
		echo $lang->unemp;
	}elseif ($dbnum != 0){
		echo $lang->doubleuser;
	}elseif ($password == ''){
		echo $lang->pwemp;
	}elseif ($wpassword == ''){
		echo $lang->pwreemp;
	}elseif ($password != $wpassword){
		echo $lang->pwsame;
	}elseif ($mail == ''){
		echo $lang->ememp;
	}elseif (checkEmailAdress($mail) != 'true'){
		echo $lang->validemail;
	}elseif ($mailnum != 0){
		echo $lang->doubleemail;
	}elseif ($aktnum == 1){
		$prompoints = $db->query("SELECT prompoints FROM promotion WHERE code = '$code'")->fetch_object()->prompoints;
		$db->query("DELETE FROM promotion WHERE code = '$code'");
		$reg = 1;
		$free = 1;
		$emassage = "Der User hat sich mit dem Code ".$code." selbst Freigestalten";
		echo $lang->finreggo;
	} elseif ($settings->regnow == "true") {
        $reg = 1;
		$prompoints = 0;
		$free = 1;
		$emassage = "Der User wurde Freigestalten.";
		echo $lang->finreggo;
	}else{
	    $prompoints = 0;
        	$reg = 1;
		$free = 0;
		$emassage = "Freischalten: http://".$name."/admin.php?f=randc";
		echo $lang->finregwait;
	}
	if($reg == 1){
    	$ph = password_hash($_POST['password'], PASSWORD_DEFAULT);
    	$db->query("INSERT INTO user (user, pass, email, free, premium) VALUES ('$username', '$ph', '$mail', '$free', '$prompoints')");
    	$id = $db->query("SELECT id FROM user WHERE user='$username'")->fetch_object()->id;
    	$db->query("INSERT INTO points (type, objectid, points) VALUES ('user', '$id', '60')");
    	$subject = 'Neuer User auf '.$name.' registriert';
    	$message = 'Der User '.$username.' hat sich auf '.$name.' registriert.
    	'.$emassage;
    	$headers = "Content-type:text/plain;charset=utf-8" . "\n" . 'From: ' . $settings->mainmail . "\r\n" . 'Reply-To: ' . $settings->mainmail . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    	mail($settings->mainmail, $subject, $message, $headers);
	echo "<script>location.href='.'</script>";
    }
}
if(!isset($reg)){
	echo "<div class='login'>
	<form action='?page=register' method='post'>
	<div class='lt'>*".$lang->user.":</div><input type='text' name='username'>
	<div class='lt'>*".$lang->password.":</div><input type='password' name='password'>
	<div class='lt'>*".$lang->repass.":</div><input type='password' name='wpassword'>
	<div class='lt'>*".$lang->email.":</div><input type='text' name='mail'>
	<div class='lt'>".$lang->promotioncode.":</div><input type='text' name='code'><br>
	<input type='submit' class='btn $color' value='".$lang->register."'>
	</form>
	</div>";
}
?>
