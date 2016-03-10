<?php
$spsite = "user";
$spsiten = "Persöhnliches";
include '.data/header.php';
if($folder != "setting"){
	echo "<input type='submit' class='buttet ".$color."' value='".$lang->settings."' onclick=\"self.location.href='?f=setting'\" /><br>";
}else{
	echo "<input type='submit' class='buttet ".$color."' value='".$lang->settings."' onclick=\"self.location.href='user.php'\" /><br>";
	$dbquery = $db->query("SELECT * FROM user WHERE id = '$userid'");
	while ($row = $dbquery->fetch_assoc()){
		$dbpassword = $row['pass'];
		$dbmail = $row['email'];
	}
	if(!empty($_POST['addset'])) {
		$addset = $_POST['addset'];
		$db->query("INSERT INTO settings (setting, userid) VALUES ('$addset', '$userid')");
	}
	if(isset($_GET['value'])) {
		$setting = $_GET['setting'];
		$value = $_GET['value'];
		$db->query("UPDATE settings SET value = '$value' WHERE setting = '$setting' AND userid = '$userid'");
	}
	if(!empty($_POST['password'])) {
		$password = $_POST['password'];
		$wpassword = $_POST['wpassword'];
		$username = $_POST['username'];
		$mail = $_POST['mail'];
		$dbquery = $db->query("SELECT * FROM user WHERE user = '$username' AND id <> '$userid';");
		$dbnum = $dbquery->num_rows;
		$dbquery = $db->query("SELECT * FROM user WHERE email = '$mail' AND id <> '$userid';");
		$mailnum = $dbquery->num_rows;
		if (isset($_POST['opassword'])) {
			$opassword = $_POST['opassword'];
			if(password_get_info($dbpassword)['algoName']=='unknown'){
				echo "Error - Old Password Encryption";
			}
			if(empty($opassword)){
			echo $lang->pwemp;
			}elseif(empty($username)){
				echo $lang->unemp;
			}elseif(password_verify($opassword,$dbpassword) != true){
				echo $lang->wrongpass;
			}elseif(empty($username)){
				echo $lang->unemp;
			}elseif(empty($mail)){
				echo $lang->ememp;
			}elseif(checkEmailAdress($mail) != 'true'){
				echo $lang->validemail;
			}elseif($mailnum != 0){
				echo $lang->doubleemail;
			}elseif($dbnum != 0){
				echo $lang->doubleuser;
			}else{
				if(!empty($password)){
					if(empty($wpassword)){
						echo $lang->pwreemp;
					}elseif($password != $wpassword){
						echo $lang->pwsame;
					}else{
						$opassword = $password;
					}
				}
				$ph = password_hash($opassword, PASSWORD_DEFAULT);
				echo $lang->datachange."<br>";
				$eintragen = $db->query("UPDATE user Set pass = '$ph', email = '$mail', user = '$username' WHERE id = '$userid'");
				echo "Es wurde eine Mail mit der Passwort &Auml;nderung verschickt.<br>";
				$reg = 1;
				$subject = 'Ihr Passwort auf '.$name.' wurde zurückgesetzt';
				$time = date('j\.n\.Y \u\m G:i:s');
				$message = 'Das Passwort des Users '.$username.' wurde am '.$time.' Uhr zurückgesetzt.
				Falls diese Änderung nicht von ihnen gemacht wurde antworten sie auf diese Mail, sonst können sie die Mail verwerfen.';
				$headers = "Content-type:text/plain;charset=utf-8" . "\n" . 'From: ' . $settings->mainmail . "\r\n" . 'Reply-To: ' . $settings->mainmail . "\r\n" . 'X-Mailer: PHP/' . phpversion();
				mail($mail, $subject, $message, $headers);
			}
		}
	}
	echo "<br>
	<div class=\"login\">
	<form action=\"user.php?f=setting\" method=\"post\">
	<div class=\"lt\">".$lang->user.":</div><input value='".$username."' name='username'/>
	<div class=\"lt\">".$lang->email.":</div><input value='".$dbmail."' name='mail'/>
	<div class=\"lt\">".$lang->password.":</div><input type=\"password\" name=\"opassword\" />
	<div class=\"lt\">Neues Passwort:</div><input type=\"password\" name=\"password\" />
	<div class=\"lt\">Neues Passwort Wiederholen:</div><input type=\"password\" name=\"wpassword\" />
	<br>
	<input type=\"submit\" class=\"buttet\" value=\"Passwort &auml;ndern\" />
	</form>
	</div class=\"login\">";
	$dbquery = $db->query("SELECT * FROM settings WHERE userid = '$userid'");
	while ($row = $dbquery->fetch_assoc()){
		echo "
			<form>
			".$row['setting'].":
			<input type='hidden' name='f' value='setting'>
			<input type='hidden' name='setting' value=".$row['setting'].">";
		if ($row['value'] == 'true' | $row['value'] == 'false') {
			echo "<select name='value'>
			<option value=true ";
			if ($row['value'] == 'true') echo "selected";
			echo">".$lang->y."</option>
			<option value=false ";
			if ($row['value'] == 'false') echo "selected";
			echo ">".$lang->n."</option>
			</select>";
		}elseif($row['setting']=='color'){
			echo "<select name='value'>";
			for($i = 0;$i < count($themecolors);$i ++){
				echo "<option value=".$themecolors[$i];
				if($row['value'] == $themecolors[$i]) echo " selected";
				echo ">".$themecolors[$i]."</option>";
			}
			echo "</select>";
		}else{
			echo "<input name='value' value=".$row['value'].">";
		}
		echo "
		<input type='submit' class='buttet ".$color."'>
		</form>";
	}
	echo "<form action=\"user.php?f=setting\" method=\"post\">
	<select name='addset'>
	<option value='theme'>theme</option>
	<option value='color'>color</option>
	<option value='simply'>simply</option>
	</select><br>
	<input type=\"submit\" class=\"buttet\" value=\"Ad Set\" />
	</form>";
}
if($folder != "owndata"){
	echo "<input type='submit' class='buttet ".$color."' value='".$lang->owndata."' onclick=\"self.location.href='?f=owndata'\" /><br>";
}else{
	echo "<input type='submit' class='buttet ".$color."' value='".$lang->owndata."' onclick=\"self.location.href='user.php'\" /><br>";
	if(isset($_POST['newtag'])){
		$tagname = $_POST['newtag'];
		if($userpoints <= 10){
			echo 'Error Points<br>';
		}else{
			$userpoints = $userpoints - 10;
			$db->query("UPDATE points SET points = '$userpoints' WHERE type = 'user' AND objectid = '$userid'");
			$db->query("INSERT INTO tags (tagname, userid) VALUES ('$tagname', '$userid')");
		}
	}
	echo $lang->uploads.": <br>";
	$dbquery = $db->query("SELECT * FROM files WHERE userid = '$userid'");
	while ($row = $dbquery->fetch_assoc()){
		$data = $row['folder'];
		$date = $row['date'];
		$name = $row['name'];
		echo $name." - ".$date."<br>";
	}
	$dbquery = $db->query("SELECT * FROM files WHERE userid = '$userid'");
	while($row = $dbquery->fetch_assoc()){
		$data = $row['folder'];
		$date = $row['date'];
		$name = $row['name'];
		$path = "./".$row['folder']."/".$name;
		$idata = implode(' - ', array_slice(explode('/', urldecode($data)), 1));
		if(ereg(".mp4", $name)){
			echo $name . '
			<br>
			<video width="250" preload="metadata" controls><source src="' . $path . '" type="video/mp4">Dein Browser unterst&uuml;tzt keine HTML5 Videos.</video>
			<br>';
		}elseif (ereg(".webm", $name)){
			echo $name . '
			<br>
			<video width="250" preload="metadata" controls><source src="' . $path . '" type="video/ogg" />Dein Browser unterst&uuml;tzt keine HTML5 Videos.</video>
			<br>';
		}elseif (ereg(".mp3", $name)){
			echo $name . '
			<br>
			<audio width="250" preload="none" controls><source src="' . $path . '"/>Dein Browser unterst&uuml;tzt keine HTML5 Audio.</audio>
			<br>';
		}
	}
}
if($folder != "upload"){
        echo "<input type='submit' class='buttet ".$color."' value='".$lang->upload."' onclick=\"self.location.href='?f=upload'\" /><br>";
}else{
        echo "<input type='submit' class='buttet ".$color."' value='".$lang->upload."' onclick=\"self.location.href='user.php'\" /><br>";
	if ($userpoints >= 0) {
		$realpoints = $userpoints + $userpremium;
	} else {
		$realpoints = $userpremium;
	}
	echo "
	<input onclick=\"unbut('off')\" onchange=\"showFileSize(".$realpoints.")\" class=\"buttet ".$color."\" type='file' id='filebiup' multiple>
	<input id='upsub' class=\"buttet ".$color."\" type='hidden' value='Upload Files' onclick=\"UploadFile('file')\"><br>
	<input id='fileupfolder' type='hidden' value='.files'>
	<input id='fileupmode' type='hidden' value='1'>
	" . $lang->description . ":<br>
	<textarea name=\"impeditor\" id=\"editor1\"></textarea>
	<div id='points'></div>

	<progress id='fileupg' value='0' max='100' style='margin-top:10px'></progress> <span id='filepercent'></span><br>
	<script>var simplemde = new SimpleMDE({element: document.getElementById('editor1')});</script>";
	//Tags
	if($mode == 'dmyma') {
		echo $lang->tags.": <br>";
		$dbquery = $db->query("SELECT * FROM tags WHERE userid = '$userid'");
		echo "<ul>";
		if($db->error){
			while ($row = $dbquery->fetch_assoc()){
				echo "<li>".$row['tagname']."</li><ul>";
				$tagid = $row['id'];
				$dbquery = $db->query("SELECT parent FROM tagparents WHERE tagid = '$tagid'");
				if($db->error){
					while ($row = $dbquery->fetch_assoc()){
						$parentid = $row['parent'];
						$dbquery = $db->query("SELECT tagname FROM tags WHERE id = '$parentid'");
						while ($row = $dbquery->fetch_assoc()){
							$tagname = $row['tagname'];
							echo "<li>".$row['tagname']."</li>";
						}
					}
				}
			}
			echo "</ul>";
		}
		echo "</ul>";
		echo "<br>
		<form action='user.php?f=owndata' method='post'>
		<input name='newtag'>
		<input class='buttet ".$color."' type=submit value='".$lang->newtags." (-10)'>
		</form>";
	}
}
include '.data/footer.php';
?>
<!--
<script>
document.getElementById("prozent").innerHTML = "0%";
</script>
-->
