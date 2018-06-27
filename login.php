<?php
if(isset($_COOKIE['Zeigher-ID']) && isset($_COOKIE['Zeigher-Token'])):
	$id = $db->real_escape_string($_COOKIE['Zeigher-ID']);
	$row = $db->query("SELECT id, username, password, free FROM user WHERE id = '$id'")->fetch_assoc();
	if($row['free'] == true && hash_equals($_COOKIE['Zeigher-Token'], crypt($row['username'].$row['email'], $row['password']))):
		$_SESSION['loggedin'] = true;
		$_SESSION['userid'] = $row['id'];
		echo "<script>location.href='.'</script>";
	else:
		echo "<script>document.cookie = 'Zeigher-Token=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';</script>";
	endif;
elseif (isset($_POST['cred']) && isset($_POST['password'])):
	$cred = $db->real_escape_string($_POST['cred']);
	$password = $db->real_escape_string($_POST['password']);
	$hostname = $_SERVER['HTTP_HOST'];
	$path = dirname($_SERVER['PHP_SELF']);
	if($db->query("SELECT username FROM user WHERE UPPER(username) = UPPER('$cred') OR UPPER(email) = UPPER('$cred')")->num_rows != 1):
        	echo $lang->wrongpass;
	else:
		$row = $db->query("SELECT id, username, password, free FROM user WHERE UPPER(username) = UPPER('$cred') OR UPPER(email) = UPPER('$cred')")->fetch_assoc();
		if(password_verify($_POST['password'], $row['password']) == true && $row['free'] == true):
			setcookie('Zeigher-ID', $row['id'], time() + (86400 * 30), "/");
			setcookie('Zeigher-Token', crypt($row['username'].$row['email'], $row['password']), time() + (86400 * 30), "/");
			echo "<script>location.href='.'</script>";
		elseif(password_verify($_POST['password'], $row['password']) == false):
			echo "$lang->wrongpass<br>";
			echo "<a href='?page=reset'>$lang->resetpwd</a><br><br>";
		elseif($row['free'] == false):
			echo $lang->notfree;
		else:
			echo "ERROR";
		endif;
	endif;
else:
	echo "<div>$lang->morefunctions<div>";
endif;
echo "<div class='login'>
<form class='table center' action='?page=login' method='post'>
<span class='row'><div class='lt cell'>$lang->usernameoremail</div><input class='cell' name='cred'></span>
<span class='row'><div class='lt cell'>$lang->password</div><input type='password' class='cell' name='password' autocomplete='off'></span>
<span class='row'><input type='submit' class='btn ico-key $color cell' value='$lang->login' />";
if($settings->regist == "true") echo "<a onclick=\"self.location.href='?page=register'\" class='btn ico-edit $color cell'> $lang->register</a>";
echo "</span></form></div>";
?>
