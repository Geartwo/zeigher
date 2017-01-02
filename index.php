<script>
var res = Math.floor(window.innerWidth / 250);
if (res == 1) res = 2;
if (res >= 4) res = 4;
var four = 0;
window.onload = window.onresize = function(event) {
	var winsize = window.innerWidth-1400;
	if( winsize <= 0){
		tbild.style.right = winsize; 
	}else{
		tbild.style.right = winsize/2;
	}
};
</script>
<?php
include '.data/all.php';
include '.data/header.php';
if(!file_exists($folder."/.pic_.bintro.jpg.jpg") && file_exists($folder."/.bintro.jpg")){
	pic_thumb($folder.'/.bintro.jpg', $folder.'/.pic_.bintro.jpg.jpg', '238', '150');
}
$ufolder = implode('/', explode('%2F', rawurlencode($folder)));
if(file_exists($folder ."/.intro.jpg")){
	echo "<img class=\"tbild\" onmousedown=\"return false;\" src=\"".$ufolder."/.intro.jpg\" alt=\"mainpic\">";
}
//Login & Register
if($_SESSION['loggedin'] == false && $settings->use == 'none' && !isset($_GET['register']) && !isset($_GET['reset']) || isset($_GET['login'])){
	include '.data/login.php';
}
if(isset($_GET['register'])){
	include '.data/register.php';
}
if(isset($_GET['reset'])){
        include '.data/reset.php';
}
//QR-Code
if(isset($_GET['qr'])){
	echo "<img src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".$https."://".$hostname.$path."?f=".$folder."'><br>";
}
//Tag
if($mode == 'dmyma'){
	$notsort = "1";
	if(isset($_GET['f'])){
		$file = $_GET['f'];
		$dbquery = $db->query("SELECT *, (pro * 5) - ( con * 5 ) + view as pcv FROM files WHERE name LIKE '%$file%' ORDER BY pcv DESC, date LIMIT 100");
		$tago = $dbquery->fetch_object();
		while ($row = $dbquery->fetch_assoc()){
			$fileid = $row['id'];
			$dat_array[] = $fileid;
		}
	}else{
		echo "<br>". $lang->newfiles .":<br>";
		$dbquery = $db->query("SELECT * FROM files WHERE orfile = 1 ORDER BY id DESC LIMIT 30");
		while ($row = $dbquery->fetch_assoc()){
			$fileid = $row['id'];
			$dat_array[] = $fileid;
		}
	}
}
if(file_exists($folder ."/.intro.txt")){
echo "</a><div id='mainintro' class=\"intro\" style='cursor: s-resize;' onclick='mainmore();'>";
        $docfile=fopen($folder . "/.intro.txt","r+");
        while(!feof($docfile)) {
                $line = htmlentities(fgets($docfile,1000));
                echo $line."<br>";
                $intro = $line."\n";
        }
        fclose($docfile);
        echo "</div>";
        $intro = 'true';
}
if(isset($mainextension)){
    foreach($mainextension as $maex){
        include($maex);
    }
}
echo "<div style='clear: right;'></div>";
//Folder listing
if (!is_dir($folder) && $mode != 'dmyma'| $aleartred == 1){
	echo "<div class=\"\" style='max-height: none;'><h1>".$lang->fourzerofour."</h1>".$lang->dontexists."<br>
	<a href=\"?f=".$tgif."\">".$lang->back."</a></div>";
}elseif (!isset($specialindex) && $_SESSION['loggedin'] == true || $settings->use == 'all'){
	if ($mode != 'dmyma'){
		$oph = opendir($folder);
		while(($file = readdir($oph)) !== false){
			if($file[0] == '.') continue;
			if(!is_dir($folder . "/" . $file)) continue;
            $datnf_array[] = $file;
        }
        if(isset($datnf_array)){
            natsort($datnf_array);
            foreach($datnf_array as $file){
			$dbfile = $db->real_escape_string($file);
			$row = $db->query("SELECT id FROM files WHERE name = '$dbfile'")->fetch_assoc();
			$fileid = $row['id'];
			if(!isset($fileid)){
				$mysqltime = date("Y-m-d H:i:s");
				$entry = $db->query("INSERT INTO files (userid, folder, date, name, orfile) VALUES ('1', '$folder', '$mysqltime', '$dbfile', 1)");
				$row = $db->query("SELECT * FROM files WHERE name = '$dbfile'")->fetch_assoc();
				$fileid = $row['id'];
			}
			if(is_dir($folder . "/" . $file)) $ord_array[] = $fileid;
            }
        }
		if($isad('fileeditor') && $edit == 1){
			echo "<textarea style='display: none;' id='descbox' onsubmit=\"SetNameOrd('".$folder."');\">";
			if(file_exists($folder ."/intro.txt")){
				$docfile=fopen($folder . "/intro.txt","r+");
				while(!feof($docfile)){
					$zeile = htmlentities(fgets($docfile));
					echo $zeile."\n";
				}
				fclose($docfile);
			}
			echo "</textarea>
<a id='descedit' class='ico-edit' onclick=\"SetDesc('".$folder."');\"></a>
			<a id='bintroup' class='ico-up' onclick=\"SetDescUploadBack('".$folder."');\"></a><div/>";
		}
		if(!isset($ord_array)) $ord_array[0] = "xpvkleer";
		$realfirst = "";
		foreach($ord_array as $fileid){
            $row = $db->query("SELECT * FROM files WHERE id = '$fileid'")->fetch_assoc();
            $file = $row['name'];
            if ($mode == 'dmyma')$folder = $row['folder'];
			#Thumbnail Generate
			$endthumb = "";
			$zgif = urldecode($cgif);
			if($mode != 'dmyma' && !file_exists($folder."/".$file."/.pic_.bintro.jpg.jpg") && file_exists($folder."/".$file."/.bintro.jpg")){
				pic_thumb($folder."/".$file.'/.bintro.jpg', $folder."/".$file.'/.pic_.bintro.jpg.jpg', '238', '150');
				$endthumb = $folder."/".$file;
			}elseif($mode != 'dmyma' && file_exists($folder."/".$file."/.pic_.bintro.jpg.jpg")){
				$endthumb = rawurlencode($folder."/".$file);
			}elseif(file_exists(urldecode($cgif.'/.pic_.bintro.jpg.jpg'))){
				$endthumb = $cgif;
			}
			if($file[0] == "-" && $realfirst != "-"){
				echo "<div class=\"alpha\">".$lang->category."</div>";
				$realfirst = "-";
			}
			if($file[0] == "-"){
				$mpf = htmlentities(substr($file, 1));	
			}else{
				if($realfirst == "-"){
					echo "<br><div class='clear' style=\"height: 5px;\"></div>";
					$realfirst = "";
				}
				$filename = $file;
				$mpf = htmlentities($filename);
			}
			if (isset($alpha) && $alpha == "ja"){
				$firstChr = $file[0];
				if($firstChr != $realfirst){
					echo "<div class=\"alpha clear\">".$firstChr."</div>";
					$realfirst = $firstChr;
				}
			}
			if($ord_array[0] != "xpvkleer"){
				echo "<a draggable='false' id=\"".$file."v\" class=\"buo ord\" value=\"".$mpf."\"  href=\"?f=".$cmsfolder."/".$file."\">
				<div class='bigfolder' id='".$file."k' style=\"background: url('".$endthumb."/.pic_.bintro.jpg.jpg') no-repeat; background-size: 100% 100%;\" ondrop=\"drop(event, '".$file."','".$folder."','')\" ondragover='allowDrop(event)' ondragstart=\"drag(event, '".$file."','".$folder."','')\">";
                if($isad('fileeditor') && $edit == 1){
                    $fourpack = 1;
					echo "</a><form style='display: inline-block;' onsubmit=\"SetNameDelOrd('".$file."','".$folder."',''); event.preventDefault();\"><input type='hidden' id='".$file."r' value='".$file."'><input  style='display: none' type='submit'></form><a id='".$file."o' class='ico-edit' onclick=\"SN('".$file."','".$folder."','');\"></a><a id='".$file."n' class='ico-no' onclick=\"SND('".$file."','".$folder."','".$folder."','".$fourpack."');\"></a><a draggable='false' id=\"".$file."v\" class=\"buo ord\" value=\"".$mpf."\"  href=\"?f=".$cmsfolder."/".$file."\">";
				}
				echo "<font class='bigback' id='".$mpf."z'><font class=\"ico-dokfull\"></font> ".$mpf."</font>
				</div></a>";
			}
			$dbfile = $db->real_escape_string($file);
			$dbquery = $db->query("SELECT * FROM files WHERE name = '$dbfile' AND folder = '$ufolder'");
			$row = $dbquery->fetch_assoc();
			$fileid = "";
			$fileid = $row['id'];
			$rawfolder = rawurlencode($folder);
			$rawfile = rawurlencode($file);
			$utf8file = utf8_encode($file);
			$utf8folder = utf8_encode($folder);
		}
		echo "<div style=\"clear: left;\"></div>";
	}
	//Rename
	if(isset($_POST['desc'])){
		if($isad('description')){
			$file = fopen($folder."intro.txt","w");
			echo fwrite($file, $_POST['desc'],0);
			fclose($file);
		}
		echo "<script>self.location.href=\"?f=".$folder."\"</script>";
	}
	//File listing
	$thereAreFiles = false;
	$file = "";
	if($mode != 'dmyma'){
		$opf = opendir($folder);
		while(($file = readdir($opf)) !== false){
			if(is_dir($folder."/".$file) | $file == "" | $file[0] == "." | preg_match("/\.php\z/i", $file) | preg_match("/\.md\z/i", $file) | preg_match("/\.html\z/i", $file)) continue;
			$datn_array[] = $file;
		}
        if(isset($datn_array)){
            natsort($datn_array);
		foreach($datn_array as $file){
			$dbfile = $db->real_escape_string($file);
			$row = $db->query("SELECT id FROM files WHERE name = '$dbfile'")->fetch_assoc();
			$fileid = $row['id'];
			if(!isset($fileid)){
				$mysqltime = date("Y-m-d H:i:s");
				$entry = $db->query("INSERT INTO files (userid, folder, date, name, orfile) VALUES ('0', '$folder', '$mysqltime', '$dbfile', 0)");
				$row = $db->query("SELECT * FROM files WHERE name = '$dbfile'")->fetch_assoc();
				$fileid = $row['id'];
			}
			$dat_array[] = $fileid;
		}
        }
	}
	if(empty($dat_array)){
		if(!isset($intro) && empty($ord_array)){
			echo"<b>".$lang->empty."</b>";
		}
	}else{
		$four = 0;
		$fourpack = 1;
		$idnum = 1;
		$numItems = count($dat_array);
		$i = 0;
		$lastfolder = $folder;
		foreach($dat_array as $fileid){
			$row = $db->query("SELECT * FROM files WHERE id = '$fileid'")->fetch_assoc();
			$file = $row['name'];
			//$folder = $row['folder'];
			if($mode == 'dmyma') $folder = addcslashes($row['folder'], "'");
			$rawfolder = rawurlencode($folder);
			$rawfile = rawurlencode($file);
			$utf8file = utf8_encode($file);
			$utf8folder = utf8_encode($folder);
			if(!isset($fileid)){
				$mysqltime = date("Y-m-d H:i:s");
				$dbfile = $db->real_escape_string($file);
				$entry = $db->query("INSERT INTO files (userid, folder, date, name, orfile) VALUES ('0', '$folder', '$mysqltime', '$dbfile', 1)");
				$row = $db->query("SELECT * FROM files WHERE name = '$dbfile'")->fetch_assoc();
				$fileid = $row['id'];
			}
			$orfile = $row['orfile'];
			if($orfile == 4) $rawfile = ".bintro.jpg";
			#Thumbnail Generate
			$singlbackground = "";
			if(file_exists("./.pic_.bintro.jpg.jpg") && $mode=='dmyma'){
				$endthumb = ".";
			}
			if(!file_exists($folder."/.pic_".$file.".jpg")){
				if(preg_match('/\.mp4\z/i', $file) || preg_match('/\.webm\z/i', $file) || preg_match('/\.mkv\z/i', $file)){
					exec('ffmpeg -i "'.$folder.'/'.$file.'" -y -vcodec mjpeg -vframes 1 -an -f rawvideo -s 238x150 -ss 00:00:05 "'.$folder.'/.pic_'.$file.'.jpg" > /dev/null &');
				}elseif(preg_match('/\.jpg\z/i', $file) || preg_match('/\.png\z/i', $file) || preg_match('/\.gif\z/i', $file)){
					pic_thumb($folder.'/'.$file, $folder.'/.pic_'.$file.'.jpg', '238', '150');
				}elseif(preg_match('/\.mp3\z/i', $file) || preg_match('/\.aac\z/i', $file) || preg_match('/\.rdio\z/i', $file)){
					if(file_exists($folder."/.art_".$file.".jpg")) pic_thumb($folder.'/.art_'.$file.'.jpg', $folder.'/.pic_'.$file.'.jpg', '238', '150');
				}
				if(!file_exists($folder."/.pic_".$file.".jpg")){
					$singlbackground = $endthumb."/.pic_.bintro.jpg.jpg";
				}
			}else{
				$singlbackground = $rawfolder."/.pic_".$rawfile.".jpg";
			}
			$yeslo = implode('/', explode('%2F', rawurlencode($folder . "/" . $file)));
			$mpf = explode('.', $file);
			$mpz = count($mpf);
			if($mpz == 1) $mpr = $file;
			$mpz = implode('.',array_slice($mpf, 0, $mpz - 1));
			$mpf = htmlentities($mpz);
			$yesl = $yeslo;
			if($orfile == 4) $yesl = "";
			$yeslop = $folder . "/" . $mpz;
			$simply = $db->query("SELECT value FROM settings WHERE setting = 'simply' AND userid = '$userid'");
			$row = $simply->fetch_assoc();
			$simply = $row['value'];
			if($simply =="true")$onr = true;
			if(isset($onr))echo"<script>res = 1;</script>";
			$htmlescfile = str_replace("'", "&#39;", $file);
			//Singles
			$pext = substr(strrchr($file, "."), 1);
			echo "<a class='buo ord' id='num".$idnum."' draggable='false' onclick=\"streamer(".$idnum.", '".$fileid."', '".$rawfile."', '".$folder."'); ";
			//File
			$sign = "ico-no";
			if(file_exists($plugextension[$pext])){
				require($plugextension[$pext]);
			}
	if($sign == "ico-no") {
		echo "\">";
		$sign = 'ico-down';
	    }
	    $lastfolder = $folder;
            echo "<div class='bigfolder bigfile' id='".$rawfile."k' style=\"background: url('".$singlbackground."') no-repeat; background-size: 100% 100%;\" ondragstart=\"drag(event, '".$rawfile."','".$folder."','')\"";
	    if ($isad('fileeditor') && $edit == 1) {
	       echo "draggable=true>";
            $extension = substr(strrchr($file, "."), 1);
            echo "</a>
            <form style='display: inline; margin: 0;' onsubmit='\"SND('".$rawfile."','".$folder."','".$folder."','".$fourpack."',1);\">
            <input type='hidden' id='".$rawfile."r' value='".$htmlescfile."' draggable='false'>
            </form>
            <a id='".$rawfile."o' class='ico-edit' onclick=\"SN('".$rawfile."','".$folder."');\"></a>
            <a id='".$rawfile."n' class='ico-no' onclick=\"SND('".$rawfile."','".$folder."','".$folder."','".$fourpack."',1);\"></a>
            <a draggable='false' onclick=\"streamer(".$idnum.", '".$fileid."', '".$rawfile."', '".$folder."');\" >
            ";
	    }else{
		echo ">";
	    }
	    echo "<font id='".$rawfile."z' class='bigback bigfileback'><font class='".$sign."'></font>" . $mpf . "</font>";
	    if ($orfile == 5) {
                echo "<a id='".$mpf."d' href=\".data/downloader.php?file=.".$yesl."\" class='ico-down'></a>";
            }
			echo "</div></a>";
		        if(isset($onr)){
			echo"<style>
			.bigfile { width: 100% !important; max-width: 100% !important; height: auto !important; background: transparent !important; margin-bottom: 2px !important; }
			.bigfileback { height: auto !important; }
			</style>
			<br class='clear'>";}
			$four = $four + 1;
			$idnum = $idnum + 1;
	}
    }
echo "
<script>
function controlblock(){
streamercontrol.style.display = 'block';
clearTimeout(window.controltimeout);
window.controltimeout = setTimeout(function(){streamercontrol.style.display = 'none';}, 1000);
}
</script>
<div id='streamerfild' class='clear' style='display: none;'>
<div id='streamermax'>
<div id='streamerfile' onmousemove='controlblock();'></div>
<div id='streamercontrol' onmousemove='controlblock();'>
<span id='streamerfull' onclick='full();'>Full</span>
</div>
</div>
<div id='streamertext' onmouseover='document.onkeydown = \"\";' onmouseout='document.onkeydown = window.keyuse;'></div>
</div>
<script>window.lastnum = ";
if(isset($idnum)):
	echo $idnum;
else:
	echo 0;
endif;
echo"</script>";
if($isad('fileeditor') && $edit==1 && $mode=='fmyma'){
    echo "<font class='".$color.", buttet' onclick=\"NF('".$folder."','".$lang->newfolder."')\">".$lang->newfolder."</font><br>";
echo "
<div class='fileUpload btn btn-primary'>
    <span>Upload</span>
<input class='fileUpload' id='filebiup' multiple type='file'>
</div>
<input id='fileupfolder' type='hidden' value='".$folder."'>
<input id='fileupmode' type='hidden' value='fb'>
<input class='".$color."' type=submit onclick=\"UploadFile('file')\"><br>
<progress id='fileupg' value='0' max='100' style='margin-top:10px'></progress><br>
<style>
.fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
</style>
";
}
    if ($mode != 'dmyma') {
		closedir($oph);
	}
}
include '.data/footer.php';
?>
