<meta name=viewport content="width=device-width, initial-scale=1">
<meta charset="utf-8" />
<script>
var res = Math.floor(window.innerWidth / 250);
if (res == 1) res = 2;
if (res >= 4) res = 4;
var four = 0;
</script>
<?php
include '.data/header.php';
$ufolder = implode('/', explode('%2F', rawurlencode($folder)));
if(file_exists($folder ."/intro.jpg")){
	echo "<img class=\"tbild\" onmousedown=\"return false;\" src=\"".$ufolder."/intro.jpg\" alt=\"mainpic\">";
}
if(file_exists($folder ."/intro.txt")){
	echo "</a><div id='intro' class=\"intro\">";
	$docfile=fopen($folder . "/intro.txt","r+");
	while(!feof($docfile)) { 
		$line = htmlentities(fgets($docfile,1000)); 
		echo $line."<br>";
		$intro = $line."\n"; 
	}
	fclose($docfile);
	echo "</div>
	<br>";
	$intro = 'true';
}else{
	echo "<div id='intro' class=\"intro\"></div>";
}
//Login & Register
if(!isset($_SESSION['loggedin']) && $settings->use == 'none' && !isset($_GET['register']) || isset($_GET['login'])){
	include '.data/login.php';
}
if(isset($_GET['register'])){
	include '.data/register.php';
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
if(isset($_GET['wish'])){
	include ".data/wish.php";
}elseif (!is_dir($folder) && $mode != 'dmyma'| $aleartred == 1){
	echo "<div class=\"intro\"><h1>".$lang->fourzerofour."</h1>".$lang->dontexists."<br>
	<a href=\"?f=".$tgif."\">".$lang->back."</a></div>";
}elseif (isset($_SESSION['loggedin']) || $settings->use == 'all'){
	//Folder listing
	if ($mode != 'dmyma'){
		$oph = opendir($folder);
		while(($file = readdir($oph)) !== false){
			if($file[0] == '.') continue;
			$sufile = $db->real_escape_string($file);
			$row = $db->query("SELECT id FROM files WHERE name = '$sufile'")->fetch_assoc();
			$fileid = $row['id'];
			if(!isset($fileid)){
				$mysqltime = date("Y-m-d H:i:s");
				$dbfile = $db->real_escape_string($file);
				$entry = $db->query("INSERT INTO files (userid, folder, date, name, orfile) VALUES ('0', '$folder', '$mysqltime', '$dbfile', 1)");
				$row = $db->query("SELECT * FROM files WHERE name = '$dbfile'")->fetch_assoc();
				$fileid = $row['id'];
			}
			if(is_dir($folder . "/" . $file)) $ord_array[] = $fileid;
		}
		if($isad >=3 && $edit == 1){
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
			<form id='biupform' style='display: inline-block;' action='.data/upload.php?folder=".$folder."' method='POST' enctype='multipart/form-data'>
			<input id='biup' name='fileselect[]' type='file'>
			<input name='bintro' type='hidden'>
			</form>
			<a id='descedit' class='ico-edit' onclick=\"SetDesc('".$folder."');\"></a>
			<a id='bintroup' class='ico-up' onclick=\"SetDescUploadBack('".$folder."');\"></a><div/>";
		}
		if(!isset($ord_array)) $ord_array[0] = "xpvkleer";
		natsort($ord_array);
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
			}elseif(file_exists($zgif."/.pic_.bintro.jpg.jpg")){
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
				echo "<a draggable='false' id=\"".$file."v\" class=\"buo ord\" value=\"".$mpf."\"  href=\"?f=".$folder."/".$file."\">
				<div class='bigfolder' id='".$file."k' style=\"background: url('".$endthumb."/.pic_.bintro.jpg.jpg') no-repeat; background-size: 100% 100%;\" ondrop=\"drop(event, '".$file."','".$folder."','".$fourpack."')\" ondragover='allowDrop(event)' ondragstart=\"drag(event, '".$file."','".$folder."','')\">
				<font class='bigback' id='".$mpf."z'><font class=\"ico-dokfull\"></font> ".$mpf."</font>";
				if($isad >=3 && $edit == 1){
					echo "</a><form style='display: inline-block' onsubmit=\"SetNameDelOrd('".$file."','".$folder."',''); event.preventDefault();\"><input type='hidden' id='".$file."r' value='".$file."'><input  style='display: none' type='submit'></form><a id='".$file."o' class='ico-edit' onclick=\"SN('".$file."','".$folder."','');\"></a><a id='".$file."n' class='ico-no' onclick=\"SND('".$file."','".$folder."','".$folder."','".$fourpack."');\"></a>";
				}
				echo "</div></a>";
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
			if(!isset($fileid)){
				$mysqltime = date("Y-m-d H:i:s");
				$entry = $db->query("INSERT INTO files (userid, folder, date, name, orfile) VALUES ('0', '$folder', '$mysqltime', '$dbfile', 2)");
				$row = $db->query("SELECT * FROM files WHERE name = '$dbfile' AND folder = '$ufolder'")->fetch_assoc();
				$fileid = $row['id'];
			}
		}
		echo "<div style=\"clear: left;\"></div>";
	}
	//Rename
	if(isset($_POST['desc'])){
		if($isad >=8){
			$file = fopen($folder."intro.txt","w");
			echo fwrite($file, $_POST['desc'],0);
			fclose($file);
		}
		echo "<script>self.location.href=\"?f=".$folder."\"</script>";
	}
	//File listing
	$thereAreFiles = false;
	if($mode != 'dmyma'){
		$oph = opendir($folder);
		while(($file = readdir($oph)) !== false){
			if(is_dir($folder."/".$file) | $file[0] == "." | ereg(".php", $file) | ereg(".md", $file) | ereg(".html", $file)) continue;
			$datn_array[] = $file;
		}
		if(!isset($notsort)) natsort($datn_array);
		foreach($datn_array as $file){
			$sufile = $db->real_escape_string($file);
			$row = $db->query("SELECT id FROM files WHERE name = '$sufile'")->fetch_assoc();
			$fileid = $row['id'];
			if(!isset($fileid)){
				$mysqltime = date("Y-m-d H:i:s");
				$entry = $db->query("INSERT INTO files (userid, folder, date, name, orfile) VALUES ('0', '$folder', '$mysqltime', '$dbfile', 1)");
				$row = $db->query("SELECT * FROM files WHERE name = '$dbfile'")->fetch_assoc();
				$fileid = $row['id'];
			}
			$dat_array[] = $fileid;
		}
	}
	if(empty($dat_array)){
		if(!isset($intro) && empty($ord_array)){
			echo"<b>".$lang->empty."</b>";
		}
	}else{
		//if(!isset($notsort)) natsort($dat_array);
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
			if(!file_exists($folder."/.pic_".$file.".jpg")){
				if(ereg('.mp4', $file) || ereg('.webm', $file) || ereg('.mkv', $file)){
					exec('/opt/ffmpeg/ffmpeg -i "'.$folder.'/'.$file.'" -y -vcodec mjpeg -vframes 1 -an -f rawvideo -s 238x150 -ss 00:00:05 "'.$folder.'/.pic_'.$file.'.jpg" > /dev/null &');
				}elseif(ereg('.jpg', $file) || ereg('.png', $file) || ereg('.gif', $file)){
					pic_thumb($folder.'/'.$file, $folder.'/.pic_'.$file.'.jpg', '238', '150');
				}elseif(ereg('.mp3', $file) || ereg('.aac', $file) || ereg('.rdio', $file)){
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
			//Singles
			echo "<a class='buo ord' id='num".$fourpack."' draggable='false' onclick=\"streamer(Math.ceil(".$fourpack." / res) * res, '".$fileid."', '".$idnum."', '".$file."', '".$folder."'); ";
			//File
			if(ereg(".href", $file)){
				$docfile=fopen($yeslop .".href","r+");
				$lfile = htmlentities(fgets($docfile));
				fclose($docfile);
				echo"onclick=\"self.location.href='".$lfile."'\">";
			}elseif(preg_match("/\.mp4/", $file) || preg_match("/\.webm/", $file)){
				echo "streamit('".$folder."', '".$rawfile."', Math.ceil(".$fourpack." / res) * res);\"><script>next('".$lastfolder."', '".$fileid."', '".$rawfile."', Math.ceil(".$fourpack." / res) * res, '".$idnum."', '".$file."');</script>";
				$sign = 'ico-film';
			}elseif (ereg(".mp3", $file) || ereg(".aac", $file)){
               	echo "hearit('".$folder."', '".$rawfile."', Math.ceil(".$fourpack." / res) * res);\">
		<script>next('".$lastfolder."', '".$fileid."', '".$rawfile."', Math.ceil(".$fourpack." / res) * res, '".$idnum."', '".$file."');</script>";
		$sign = 'ico-music';
        	} elseif (ereg(".yt", $file)){
                $rawfile = implode(array_slice(explode('?v=',fgets(fopen($yeslop.".yt","r+"))), 1));
                echo "ytit('".$rawfile."', Math.ceil(".$fourpack." / res) * res);\">";
		$singlbackground = "https://img.youtube.com/vi/".$rawfile."/sddefault.jpg";
		$sign = 'ico-yt';
            }elseif (ereg(".mkv", $file)){
                $yeskv = $https ."://". $_SERVER["HTTP_HOST"]."/".$yeslop.".mkv";
                echo "streamkv('".$yeskv."');\">";
		$sign = 'ico-vlc';
			}elseif (ereg('.jpg', $file) || ereg('.png', $file) || ereg('.gif', $file)) {
            	echo "pikern('".$yeslo."', Math.ceil(".$fourpack." / res) * res);\">";
		$sign = 'ico-pic';
            } elseif (ereg(".zip", $file) || ereg('.rar', $file) || ereg('.iso', $file) || ereg('.exe', $file) || ereg('.apk', $file)){
            	echo "\">";
		$sign = 'ico-down';
            } elseif (ereg(".rdio", $file)){
                $file = trim(fgets(fopen($yeslop .".rdio", 'r')));
		echo "rdit('".$file."', Math.ceil(".$fourpack." / res) * res);\">";
		$sign = 'ico-radio';
            } elseif (ereg(".txt", $file)){
            	echo "showit('".$yeslo."', Math.ceil(".$fourpack." / res) * res, '".$color."');\">";
	        $sign = 'ico-pap';
	    } elseif (ereg(".epub", $file)){
		echo "readit('".$yeslo."', Math.ceil(".$fourpack." / res) * res, '".$color."');\">";
		$sign = 'ico-book';
		include '.data/book.php';
	    } elseif (ereg('.pdf', $file)){
                echo "seeit('".$yeslo."', Math.ceil(".$fourpack." / res) * res, '".$color."');\">";
                $sign = 'ico-book';
            } else {
		echo "\">";
		$sign = "ico-no";
	    }
	    $lastfolder = $folder;
            echo "<div class='bigfolder bigfile' id='".$file."k' style=\"background: url('".$singlbackground."') no-repeat; background-size: 100% 100%;\" ondragstart=\"drag(event, '".$file."','".$folder."','')\" draggable='false'>
	    <form style='display: inline-block' id='".$file."z' onsubmit=\"SND('".$file."','".$folder."','".$folder."','".$fourpack."',1); event.preventDefault();\"><font class='bigback bigfileback'><font class='".$sign."'></font>" . $mpf . "</font></form>";
	    if ($orfile == 5) {
                echo "<a id='".$mpf."d' href=\".data/downloader.php?file=.".$yesl."\" class='ico-down'></a>";
            }
	    if ($isad >=3 && $edit == 1) {
	    $extension = substr(strrchr($file, "."), 1);
	    echo "</a>
            <input type='hidden' id='".$file."r' value='".$file."' draggable='false'><br>
            <a id='".$file."o' class='ico-edit' onclick=\"SN('".$file."','".$folder."');\"></a>
            <a id='".$file."n' class='ico-no' onclick=\"SND('".$file."','".$folder."','".$folder."','".$fourpack."',1);\"></a>
            ";
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
			echo "<div class='fourpack' style='display: none;' id='".$fourpack."'>
			</div>
			<div class='fourpack' id='".$fourpack."v'>
			</div>";
			$fourpack = $fourpack + 1;
	}
    	$thereAreFiles = true;
    }
    if($thereAreFiles == true) {
    $fourdiv = $fourpack / 4;
    for(;;) {
        echo "<div class='clear'></div>
	<div class='fourpack' style='display: none;' id='".$fourpack."'>
       	</div>
        <div class='fourpack' id='".$fourpack."v'>
 	</div>";
	if(is_integer($fourdiv) == true) break;
    	$fourpack = $fourpack + 1;
	$fourdiv = $fourpack / 4;
	}
    }
    echo "<font class='".$color.", buttet' onclick=\"NF('".$folder."','New Folder')\">New Folder</font>";
echo "<form id='biupform' style='display: inline-block;' action='.data/upload.php?folder=".$folder."' method='POST' enctype='multipart/form-data'>
                        <input id='biup' name='fileselect[]' multiple='multiple' type='file'>
                        <input name='fb' type='hidden'>
			<input class='".$color."' type=submit>
                        </form>";
    echo "<script>
	newload = true;
	if(window.location.hash) {
        var hash = location.hash.replace(/^.*?#/, '');
	var pairs = hash.split('&');
	hash = pairs[0];
	if(pairs[1]){
	playtime = pairs[1];
	} else {
	playtime = 0;
	}
    	document.getElementById(hash).click();
    }
    </script>";
    if ($mode != 'dmyma') {
		closedir($oph);
	}
} elseif(isset($_GET['wish'])) {
	include ".data/wish.php";
}
include '.data/footer.php';
?>
