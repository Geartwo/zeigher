<?php
echo "<div class=\"intro\">".$lang->morefunctions."<div>";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$hostname = $_SERVER['HTTP_HOST'];
	$path = dirname($_SERVER['PHP_SELF']);

	
	$dbquery = $db->query("SELECT * FROM user WHERE email= '$email'");
	$dbnum = $dbquery->num_rows;
	echo "<div class=\"error\">";
	if(checkEmailAdress($email) != 'true'){
		echo $lang->validemail;
	}elseif($dbnum!=1){	
        echo $lang->wrongpass;
	}else{
		$dbquery = $db->query("SELECT * FROM user WHERE email= '$email'");
		while ($row = $dbquery->fetch_assoc()){
			$dbusername = $row['user'];
			$dbemail = $row['email'];
			$dbpassword = $row['pass'];
			$dbfree = $row['free'];
			$isad = $row['isad'];
			$userid = $row['id'];
		}
		if(password_get_info($dbpassword)['algoName']=='unknown'){
			$salted_password = $secret_salt . $password;
			$ph = hash('sha256', $salted_password);
		}else{
			$ph = " ";
		}
		if($dbemail == $email && $dbpassword == $ph || password_verify($_POST['password'],$dbpassword) == true && $dbfree >= 1){
			if(password_get_info($ph)['algoName']!='bcrypt'){
				$ph = password_hash($_POST['password'], PASSWORD_DEFAULT);
				$db->query("UPDATE user SET pass = '$ph' WHERE email = '$email'");
			}
			$_SESSION['loggedin'] = true;
			echo "<script>location.reload();</script>";
		}elseif($dbpassword != $ph || password_verify($_POST['password'],$dbpassword) == false){
			echo $lang->wrongpass;
		}elseif($dbfree != 1) {
			echo $lang->notfree;
		}else{
			echo "ERROR";
		}
		$_SESSION['userid'] = $userid;
	}
	echo "</div>";
}
echo "<div class=\"login\">
<form action=\"index.php?f=". $folder ."&login\" method=\"post\">
<div class=\"lt\">".$lang->email.":</div><input type=\"email\" name=\"email\" />
<div class=\"lt\">".$lang->password.":</div><input type=\"password\" name=\"password\" autocomplete='off'/>
<br><input type=\"submit\" class=\"buttet ico-key ".$color."\" value=\"".$lang->login."\" />";
if($settings->regist == "true") echo "<br><a onclick=\"self.location.href='?f=". $folder ."&register'\" class=\"buttet ico-edit ".$color."\"> ".$lang->register."</a>";
echo "</form></div class=\"login\">";
?>
