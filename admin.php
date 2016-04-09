<?php
$spsite = "admin";
$spsiten = "Admin";
include '.data/header.php';
if(isset($username))$dbquery = $db->query("SELECT * FROM user WHERE user = '$username'");
while ($row = $dbquery->fetch_assoc()){
$isad = $row['isad'];
}
if(isset($isad) && $isad > 0){
	if($folder != "setting" && $isad >= 8){
		echo "<input type='submit' class='buttet ".$color."' value='".$lang->settings."' onclick=\"self.location.href='?f=setting'\" /><br>";
	}elseif ($isad >= 8){
		echo "<input type='submit' class='buttet ".$color."' value='".$lang->settings."' onclick=\"self.location.href='admin.php'\" /><br>";
		if(isset($_GET['value'])) {
			$setting = $_GET['setting'];
			$value = $_GET['value'];
			$db->query("UPDATE settings SET value = '$value' WHERE setting = '$setting' AND userid = 0");
		}
		echo
		$lang->mode.": ".$mode."<br>";
		if($mode == 'fmyma' | $mode == 'dmyma') {
			echo $lang->dbuser.": ".$dbuser."<br>
			".$lang->dbhost.": ".$dbhost."<br>
			".$lang->dbank.": ".$dbank."<br>";
		}
		$dbquery = $db->query("SELECT * FROM settings WHERE userid = '0'");
		while ($row = $dbquery->fetch_assoc()){
			echo "
			<form>
			".$row['setting'].": 
			<input type='hidden' name='f' value='setting'>
			<input type='hidden' name='setting' value=".$row['setting'].">";
			if ($row['value'] == 'true' | $row['value'] == 'false') {
			echo "
			<select name='value'>
			<option value=true ";
			if ($row['value'] == 'true') echo "selected";
			echo">".$lang->y."</option>
			<option value=false ";
			if ($row['value'] == 'false') echo "selected";
			echo ">".$lang->n."</option>
			</select>
			";
			} else {
				echo "<input name='value' value=".$row['value'].">";
			}
			echo "
			<input type='submit' class='buttet ".$color."'>
			</form>";
		}
		echo "Max-Upload: ".$upload_mb."MB<br>";
        echo "
        ".$lang->translated.": ".$lang->translator."<br>
        <br>
        ";
    }
    if($folder != "news" && $isad >= 2) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->news."\" onclick=\"self.location.href='?f=news'\" /><br>";
    }  elseif ($isad >= 2) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->news."\" onclick=\"self.location.href='admin.php'\" /><br>";
        echo "
        <script type=\"text/javascript\" src=\"data/ckeditor/ckeditor.js\"></script>
        <form action=\"admin.php\" method=\"post\">
        <label for=\"admin.php\"></label>
        <textarea  class=\"ckeditor\" cols=\"80\" rows=\"10\" name=\"editor\" id=\"editor1\" >";
        if (file_exists("news.txt")){
            $datei=fopen("news.txt","r+");
            while(!feof($datei)) { 
                $zeile = fgets($datei,1000); 
                echo $zeile; 
            }
            fclose($datei);
        }
        echo "
        </textarea>
        <input type=\"submit\" class='buttet ".$color."' value=\"&raquo; ".$lang->save."\"/>
        </form>
        ";
    }
    
    if($folder != "imprint" && $isad >= 8) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->imprint."\" onclick=\"self.location.href='?f=imprint'\" /><br>";
    }  elseif ($isad >= 8) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->save."\" onclick=\"self.location.href='admin.php'\" /><br>";
        echo "
        <script type=\"text/javascript\" src=\"data/ckeditor/ckeditor.js\"></script>
        <form action=\"admin.php\" method=\"post\">
        <label for=\"admin.php\"></label>
        <textarea  class=\"ckeditor\" cols=\"80\" rows=\"10\" name=\"impeditor\" id=\"editor1\" >";
        if (file_exists(".settings/imprint.txt")){
            $datei=fopen(".settings/imprint.txt","r+");
            while(!feof($datei)) { 
                $zeile = fgets($datei,1000); 
                echo $zeile; 
            }
            fclose($datei);
        }
        echo "
        </textarea>
        <input type=\"submit\" class='buttet ".$color."' value=\"&raquo; ".$lang->save."\"/>
        </form>
        ";
    }
    
	if($folder != "promotion" && $isad >= 8){
		echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->promotioncode."\" onclick=\"self.location.href='?f=promotion'\" /><br>";
	}elseif ($isad >= 8){
		echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->promotioncode."\" onclick=\"self.location.href='admin.php'\" /><br>";
		if (isset($_POST["lacode"])) {
		$code = $_POST["lacode"];
			$db->query("DELETE FROM aktion WHERE code = '$code'");
			echo "Code wurde gel&ouml;scht.";
		}
		if (isset($_POST["acode"])) {
			if (isset($_POST["exp"])) {
				$exp = $_POST["exp"];
			} elseif (!isset($exp)) {
				$exp = "0000-00-00";
			}
			$length = 5;
			$characters = '123456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			$eintragen = $db->query("INSERT INTO promotion (code, expires) VALUES ('$randomString', '$exp')");
			echo $lang->newcodeready;
		}
		if (isset($_POST["com"])) {
			$com = $_POST["com"];
			$code = $_POST["becode"];
			$db->query("UPDATE promotion SET comment = '$com' WHERE code = '$code'");
			echo $lang->editcode;
		}
		if (isset($_POST["prompoints"])) {
			$prompoints = $_POST["prompoints"];
			$code = $_POST["becode"];
			$db->query("UPDATE promotion SET prompoints = '$prompoints' WHERE code = '$code'");
			echo $lang->editcode;
		}
		$dbakt = $db->query("SELECT id FROM promotion");
		while ($dbid = mysqli_fetch_array ($dbakt)) {
			$promid = $dbid[0];   
			$dbquery = $db->query("SELECT * FROM promotion WHERE id = '$promid'");
			while ($row = $dbquery->fetch_assoc()){
				$promexp = $row['expires'];
				$promcode = $row['code'];
				$promcom = $row['comment'];
				$prompoints = $row['prompoints'];
			}
			echo "<div class=\"boxrow\">
			<form action=\"admin.php\" method=\"post\" style=\"display: inline-block;\">
			<div class=\"boxl boxn\">" .$promcode. "</div>
			<div class=\"boxl boxn\">" .$promexp. "</div>
			<input type=\"hidden\" name=\"becode\" value=\"".$promcode."\">
			<div class=\"boxl boxn\"><input name=\"com\" value=\"".$promcom."\"></div>
			<div class=\"boxl boxn\"><input name=\"prompoints\" value=\"".$prompoints."\"></div>
			<input type=\"submit\" class= 'buttet ".$color."' value=\"".$lang->edit."\">
			</form>
			<form action=\"admin.php?f=promotion\" method=\"post\" style=\"display: inline-block;\">
			<input type=\"hidden\" name=\"lacode\" value=\"".$promcode."\">
			<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->del."\">
			</form>
			</div>";
		}
		echo "<form action=\"admin.php?f=promotion\" method=\"post\">
		<input type=\"hidden\" name=\"acode\" value=\"1\">
		<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->newcode."\">
		</form>
		<br>";
	}
    if($folder != "randc" && $isad >= 1) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->randc."\" onclick=\"self.location.href='?f=randc'\" /><br>";
    }  elseif ($isad >= 1) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->randc."\" onclick=\"self.location.href='admin.php'\" /><br>";
        $dbro = $db->query("SELECT id FROM user ORDER BY user");
        $dum = $dbro->num_rows;
        $dfro = $db->query("SELECT id FROM user WHERE free = 1");
        $dfm = $dfro->num_rows;
        echo "
        B/F<br>
        ".$dum."/".$dfm."
        <div class=\"boxall\">
        <div class=\"boxrow\">
        <div class=\"boxl boxn\">".$lang->user."</div>
        <div class=\"boxl boxm \">".$lang->email."</div>
        <div class=\"boxl boxc \">F</div>
        <div class=\"boxl boxc \">A</div>
        <div class=\"boxl boxa \">".$lang->actions."</div>
        </div>";
        while ($dbid = $dbro->fetch_array ()) {
            $userwahl = $dbid[0];   
            $dbquery = $db->query("SELECT * FROM user WHERE id = '$userwahl'");
            while ($row = $dbquery->fetch_assoc()){
                $usntzr = $row['user'];
                $usisad = $row['isad'];
                $usfree = $row['free'];
                $usmail = $row['email'];
            }
            if ($usfree == 1) $usfree = "checked"; else $usfree = "";
            echo "
            <div class=\"boxrow\">
            <div class=\"boxl boxn\">" .$usntzr. "</div>
            ";
            if ($isad < 6) {
                echo "<div class=\"boxl boxm \">****@****.**</div>";
            } else {
                echo "<div class=\"boxl boxm \">" .$usmail. "</div>";
            }
            echo "
            <div class=\"boxl\"><input type=\"checkbox\" id=\"user".$userwahl."free\" name=\"free\" ".$usfree."></div>
            <div class=\"boxl\"><input type=\"text\" id=\"user".$userwahl."isad\" name=\"isad\" pattern=\"[0-9]*\" value=\"".$usisad."\"></div>
            <div class=\"boxlbut\"><input onclick=\"userwork('".$userwahl."', 'setting');\" class=\"".$color."\" type=\"button\" value=\"".$lang->settings."\" /><input onclick=\"userwork('".$userwahl."', 'mail');\" class=\"".$color."\" type=\"button\" value=\"".$lang->freeplusmail."\" /><input onclick=\"userwork('".$userwahl."', 'delete');\" class=\"".$color."\" type=\"button\" value=\"".$lang->del."\" /></div>
            </div>
            ";
        }
        echo "</div>";
    }
    
    if($folder != "update" && $isad >= 7) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->newversion."\" onclick=\"self.location.href='?f=update'\" /><br>";
    }  elseif ($isad >= 7) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->newversion."\" onclick=\"self.location.href='admin.php'\" /><br>";
        $uversion  = @file_get_contents('http://tekkit.at/data/version.html');
        if ($version < $uversion ) {
            $utdc = "submit";
        } else {
            $utdc = "hidden";
        }
        echo "
        <div class=\"clear\">
        ".$lang->version.": ".$version."<br>
        ".$lang->newversion.": ".$uversion."
        </div>
        <form action=\"admin.php\" method=\"post\">
        <input type=\"".$utdc."\" class='buttet ".$color."' name=\"update\" value=\"&raquo; ".$lang->update."\" />
        </form>
        ";
    }
    
    if($folder != "password" && $isad >= 8) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->getpasshash."\" onclick=\"self.location.href='?f=password'\" /><br>";
    }  elseif ($isad >= 8) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->getpasshash."\" onclick=\"self.location.href='admin.php'\" /><br>";
        echo "
        <div class=\"login\">
        <form action=\"admin.php\" method=\"post\">
        <div class=\"lt\">".$lang->password.":</div><input type=\"password\" name=\"password\" /><br>
        <input type=\"submit\" class='buttet ".$color."' value=\"&raquo; ".$lang->query."\" />
        </form>
        </div class=\"login\">
        ";
    }
  
    if (isset($_POST["password"])){
        $password = $_POST['password'];
        $ph = password_hash($_POST['password'], PASSWORD_DEFAULT);
        echo $password . " = " . $ph;
    }


    
    
    
    if (isset($_POST["update"])) {
        if (!extension_loaded('zip')) {  
        dl('zip.so');  
        }
        $target_url = "https://github.com/Geartwo/H5Extend/archive/0.9.6.zip";
        $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';  
        $file_zip = "update.zip";  
        $file_txt = ".";  
        echo "<br>Starting<br>Target_url: $target_url";  
        echo "<br>Headers stripped out";  
        $ch = curl_init();  
        $fp = fopen("$file_zip", "w");  
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);  
        curl_setopt($ch, CURLOPT_URL,$target_url);  
        curl_setopt($ch, CURLOPT_FAILONERROR, true);  
        curl_setopt($ch, CURLOPT_HEADER,0);  
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);  
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);  
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);   
        curl_setopt($ch, CURLOPT_FILE, $fp);  
        $page = curl_exec($ch);  
        if (!$page) {  
            echo "<br />cURL error:" . curl_error($ch);  
            exit;  
        }  
        curl_close($ch);  
        echo "<br>Downloaded file: $target_url";  
        echo "<br>Saved as file: $file_zip";  
        echo "<br>About to unzip ...";  
        $zip = new ZipArchive;  
        if (! $zip) {  
            echo "<br>Could not make ZipArchive object.";  
            exit;  
        }  
        if($zip->open("$file_zip") != "true") {  
            echo "<br>Die Update datei konnte nicht geöffnet werden.";  
        }  
        $zip->extractTo("$file_txt");  
        $zip->close();  
        unlink ('update.zip');
	    echo "Update.";
    }
    if (isset($_POST["editor"])) {
        $postArray = $_POST["editor"];
        $datei_handle=fopen("news.txt","w"); 
        fwrite($datei_handle,$postArray); 
        fclose($datei_handle);
        echo "Die News wurden Aktualisiert.";
    }
    if (isset($_POST["impeditor"])) {
        $postArray = $_POST["impeditor"];
        $datei_handle=fopen(".settings/imprint.txt","w"); 
        fwrite($datei_handle,$postArray); 
        fclose($datei_handle);
        echo "Das Impressum wurden Aktualisiert.";
    }
} else {
    if (file_exists(".settings/imprint.txt")){
        $datei=fopen(".settings/imprint.txt","r+");
        while(!feof($datei)) { 
            $zeile = fgets($datei,1000);
            echo $zeile; 
        }
        fclose($datei);
    } else {
        echo $lang->noadmin;
    }
}
include '.data/footer.php';
?>
