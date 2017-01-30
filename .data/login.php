<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
	$email = $db->real_escape_string($_POST['email']);
	$password = $db->real_escape_string($_POST['password']);
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
		if(password_verify($_POST['password'],$dbpassword) == true && $dbfree >= 1){
			$_SESSION['loggedin'] = true;
			echo "<script>location.href='$cmsfolder'</script>";
		}elseif($dbpassword != $ph || password_verify($_POST['password'],$dbpassword) == false){
			echo $lang->wrongpass."<br>";
			echo "<a href='?reset'>".$lang->resetpwd."</a><br><br>";
		}elseif($dbfree != 1) {
			echo $lang->notfree;
		}else{
			echo "ERROR";
		}
		$_SESSION['userid'] = $userid;
	}
	echo "</div>";
}else{
	echo "<div class=\"\">".$lang->morefunctions."<div><br>";
}
echo "<div class=\"login\">
<form action='?login' method=\"post\">
<div class=\"lt\">".$lang->email.":</div><input type=\"email\" name=\"email\" />
<div class=\"lt\">".$lang->password.":</div><input type=\"password\" name=\"password\" autocomplete='off'/>
<br><input type=\"submit\" class=\"buttet ico-key ".$color."\" value=\"".$lang->login."\" />";
if($settings->regist == "true") echo "<br><a onclick=\"self.location.href='?register'\" class=\"buttet ico-edit ".$color."\"> ".$lang->register."</a>";
echo "</form></div class=\"login\">";
?>
