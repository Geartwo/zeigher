<?php
if(isset($_COOKIE['Zeigher-ID']) && isset($_COOKIE['Zeigher-Token'])):
	$id = $db->real_escape_string($_COOKIE['Zeigher-ID']);
	$row = $db->query("SELECT id, user, pass, free FROM user WHERE id = '$id'")->fetch_assoc();
	if($row['free'] == true && hash_equals($_COOKIE['Zeigher-Token'], crypt($row['user'].$row['mail'], $row['pass']))):
		$_SESSION['loggedin'] = true;
		$_SESSION['userid'] = $row['id'];
		echo "<script>location.href='.'</script>";
	else:
		echo "Cooky Error";
	endif;
elseif (isset($_POST['cred']) && isset($_POST['password'])):
	$cred = $db->real_escape_string($_POST['cred']);
	$password = $db->real_escape_string($_POST['password']);
	$hostname = $_SERVER['HTTP_HOST'];
	$path = dirname($_SERVER['PHP_SELF']);
	$dbnum = $db->query("SELECT user FROM user WHERE user = '$cred' OR email= '$email'")->num_rows;
	if($dbnum != 1):
        	echo $lang->wrongpass;
	else:
		$row = $db->query("SELECT id, user, pass, free FROM user WHERE user = '$cred' OR email= '$cred'")->fetch_assoc();
		if(password_verify($_POST['password'], $row['pass']) == true && $row['free'] == true):
			$_SESSION['loggedin'] = true;
			$_SESSION['userid'] = $row['id'];
			setcookie('Zeigher-ID', $row['id'], time() + (86400 * 30), "/");
			setcookie('Zeigher-Token', crypt($row['user'].$row['mail'], $row['pass']), time() + (86400 * 30), "/");
			echo "<script>location.href='.'</script>";
		elseif(password_verify($_POST['password'], $row['pass']) == false):
			echo $lang->wrongpass."<br>";
			echo "<a href='?page=reset'>".$lang->resetpwd."</a><br><br>";
		elseif($row['free'] == false):
			echo $lang->notfree;
		else:
			echo "ERROR";
		endif;
	endif;
else:
	echo "<div>".$lang->morefunctions."<div>";
endif;
echo "<div class='login'>
<form action='?page=login' method='post'>
<div class='lt'>".$lang->usernameoremail.":</div><input name='cred'>
<div class='lt'>".$lang->password.":</div><input type='password' name='password' autocomplete='off'>
<br><input type='submit' class='btn ico-key $color' value='".$lang->login."' />";
if($settings->regist == "true") echo "<br><a onclick=\"self.location.href='?page=register'\" class='btn ico-edit $color'> ".$lang->register."</a>";
echo "</form></div>";
?>
