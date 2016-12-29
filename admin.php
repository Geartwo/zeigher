<?php
$spsite = "admin";
$spsiten = "Admin";
include '.data/all.php';
include '.data/header.php';
if(isset($username))$dbquery = $db->query("SELECT * FROM user WHERE user = '$username'");
while ($row = $dbquery->fetch_assoc()){
$isad = $row['isad'];
}
$pluginfolder = ".plugins";
$dbquery = $db->query("SELECT name FROM plugins WHERE active = 1");
while($row = $dbquery->fetch_assoc()):
	$longpfolder = $pluginfolder.DIRECTORY_SEPARATOR.$row['name'].DIRECTORY_SEPARATOR;
	if(file_exists($longpfolder."admin.php")):
		$adminextension[$row['name']] = $longpfolder."admin.php";
	endif;
endwhile;
if($isad >= 1):
	$key = "plugins";
	echo "<input type='submit' class='buttet ".$color."' value='".$lang->$key."' onclick=\"self.location.href='";
	if($_GET['d'] == $key):
		echo "admin.php";
	else:
		echo "?d=".$key;
	endif;
	echo "'\" /><br>";
	if($_GET['d'] == $key):
		echo "<div class='boxall'>";
		$plugdir = scandir($pluginfolder);
		foreach($plugdir as $pfolder):
			if($pfolder[0] == ".") continue;
			$dbquery = $db->query("SELECT * FROM plugins WHERE name = '$pfolder'");
			if($dbquery->num_rows == 0):
				$db->query("INSERT INTO plugins (name, active) VALUES ('$pfolder', 0)");
				$dbquery = $db->query("SELECT * FROM plugins WHERE name = '$pfolder'");
			endif;
			$row = $dbquery->fetch_assoc();
			if(isset($lang->$row['name'])):
		 		$row['realname'] = $lang->$row['name'];
			else:
				$row['realname'] = $row['name'];
			endif;
			echo "<div class='boxrow'>
			<div class='boxl boxn'>".$row['realname']."</div><div class='boxl'><input id='check-".$row['name']."' type='checkbox' onclick=\"activatePlugin('".$row['name']."')\"";
			if($row['active'] == 1) echo "checked";
			echo "></div>
			</div>";
		endforeach;
		echo "</div>";
	endif;
endif;
if(isset($adminextension) && $isad > 0){
        foreach($adminextension as $key => $adex){
                echo "<input type='submit' class='buttet ".$color."' value='".$lang->$key."' onclick=\"self.location.href='";
                if($_GET['d'] == $key){
                        echo "admin.php";
                }else{
                        echo "?d=".$key;
                }
                echo "'\" /><br>";
                include $adex;
        }
}
if(isset($isad) && $isad > 0){
        if($folder != "imprint" && $isad >= $sysisad->imprint) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->imprint."\" onclick=\"self.location.href='?f=imprint'\" /><br>";
    }  elseif ($isad >= $sysisad->imprint) {
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
    
	if($folder != "promotion" && $isad >= $sysisad->promotion){
		echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->promotioncode."\" onclick=\"self.location.href='?f=promotion'\" /><br>";
	}elseif ($isad >= $sysisad->promotion){
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
			$db->query("INSERT INTO promotion (code, expires) VALUES ('$randomString', '$exp')");
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

    if($folder != "update" && $isad >= $sysisad->update) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->newversion."\" onclick=\"self.location.href='?f=update'\" /><br>";
    }  elseif ($isad >= $sysisad->update) {
        echo "<input type=\"submit\" class='buttet ".$color."' value=\"".$lang->newversion."\" onclick=\"self.location.href='admin.php'\" /><br>";
        $uversion  = @file_get_contents('http://chrometech.at/zeigher/version.html');
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
