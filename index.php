<?php
include '.data/all.php';
$cmsfolder = workpath($_GET['f']);
if(isset($_GET['x'])):
	include ".data/ajax.php";
	exit;
elseif(!isset($installed) || $installed == false):
        echo "<script>self.location.href='?x=main&file=install.php'</script>";
        exit;
elseif(isset($_GET['watchfile']) | is_file(".$cmsfolder")):
	include ".data/stream.php";
	exit;
elseif(isset($_GET['upload'])):
        include ".data/upload.php";
        exit;
elseif(isset($_GET['logoff'])):
	$_SESSION['loggedin'] = false;
        session_destroy();
	setcookie("Zeigher-ID", "", time() - 3600, "/");
	setcookie("Zeigher-Token", "", time() - 3600, "/");
        echo "<script>self.location.href='.'</script>";
	exit;
elseif(isset($_GET['api'])):
	include ".data/api.php";
	$apiget = $_GET['api'];
	echo $api->$apiget($cmsfolder);
	exit;
endif;

include '.data/header.php';


//Install redirect
if (!isset($installed) || $installed == false):
        include ".data/install.php";
        exit;
endif;


//Background picture thumbnail greator
if(!file_exists(".$cmsfolder.pic_.bintro.jpg.jpg") && file_exists(".$cmsfolder.bintro.jpg")):
	pic_thumb(".$cmsfolder.bintro.jpg", ".$cmsfolder.pic_.bintro.jpg.jpg", '238', '150');
endif;
$ufolder = implode('/', explode('%2F', rawurlencode($folder)));
if(file_exists("$folder/.intro.jpg")):
	echo "<img class='tbild' onmousedown='return false;' src='$ufolder/.intro.jpg' alt='mainpic'>";
endif;


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
echo "<div style='clear: right;'></div>";


//Login redirect
if($_SESSION['loggedin'] == false && $settings->use == 'none' && !isset($_GET['page'])):
	echo "<script>self.location.href='?page=login'</script>";
endif;

//Get IDs of all Folders
$cmsfolder_array = explode("/", $cmsfolder);
array_pop($cmsfolder_array);
foreach($cmsfolder_array as $folder):
        if($folder == ""):
                $lastfolderid = 1;
        else:
                $dbFolder = $db->real_escape_string($folder);
                $lastfolderid = $db->query("SELECT id FROM folder WHERE name = '$dbFolder' AND parentfolderid = '$lastfolderid'")->fetch_object()->id
                or die ("cmsfolderid ERROR");
        endif;
        $cmsFolderId[] = $lastfolderid;
endforeach;

//Static page
if(isset($_GET['page'])):
	$pageget = $_GET['page'];
	if(isset($page->$pageget)):
		$page->$pageget();
	else:
		$fourzerofour = true;
	endif;
endif;


//404 Error
if (isset($fourzerofour)){
	echo "<div class=\"\" style='max-height: none;'><h1>".$lang->fourzerofour."</h1>".$lang->dontexists."<br>";
	if($isad('edit') && $edit==1 && $mode=='fmyma'):
		$newfolder = end(explode("/", $_GET['f']));
        	echo "<span class='$color btn' onclick=\"NF('.".$_GET['f']."/..', '$newfolder')\">$lang->newfolder</span>";
	endif;
	echo "<a class='$color btn' href=\"?f=".$tgif."\">".$lang->back."</a></div>";

}elseif(!isset($_GET['page']) && !isset($specialindex) && $_SESSION['loggedin'] == true || $settings->use == 'all'){

//Folder listing
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
	if ($mode != 'dmyma'){
		$oph = opendir(".$cmsfolder");
		while(($folder = readdir($oph)) !== false){
			if($folder[0] == '.') continue;
			if(!is_dir(".$cmsfolder$folder")) continue;
            $folder_array[] = $folder;
        }

        if(isset($folder_array)){
            natsort($folder_array);
            foreach($folder_array as $folder){
			$dbFolder = $db->real_escape_string($folder);
			$folderId = $db->query("SELECT id FROM folder WHERE name = '$dbFolder' AND parentfolderid = '$lastfolderid'")->fetch_object()->id;
			if(!isset($folderId)){
				$db->query("INSERT INTO folder (name, parentfolderid) VALUES ('$dbFolder', '$lastfolderid')")
				or die("SQL Folder Initialisation ERROR");
				$folderId = $db->query("SELECT id FROM folder WHERE name = '$dbFolder' AND parentfolderid = '$lastfolderid'")->fetch_object()->id;
			}
			$ord_array[] = $folderId;
            }
        }
		if($isad('edit') && $edit == 1){
			echo "<textarea style='display: none;' id='descbox' onsubmit=\"SetNameOrd('".$cmsfolder."');\">";
			if(file_exists(".$cmsfolder"."intro.txt")){
				$docfile=fopen(".$cmsfolder"."intro.txt","r+");
				while(!feof($docfile)){
					$zeile = htmlentities(fgets($docfile));
					echo $zeile."\n";
				}
				fclose($docfile);
			}
			echo "</textarea>
<a id='descedit' class='ico-edit' onclick=\"SetDesc('".$folder."');\"></a>
			<a id='bintroup' class='ico-up' onclick=\"SetDescUploadBack('".$cmsfolder."');\"></a><div/>";
		}
		if(!isset($ord_array)) $ord_array[0] = "xpvkleer";
		$realfirst = "";
		foreach($ord_array as $folderId){
            $row = $db->query("SELECT * FROM folder WHERE id = '$folderId'")->fetch_assoc();
            $folder = $row['name'];


			#Thumbnail Generate
			$endthumb = "";
			$zgif = urldecode($cgif);
			if($mode != 'dmyma' && !file_exists(".$cmsfolder$folder/.pic_.bintro.jpg.jpg") && file_exists(".$cmsfolder$folder/.bintro.jpg")){
				pic_thumb(".$cmsfolder$folder/.bintro.jpg", ".$cmsfolder$folder/.pic_.bintro.jpg.jpg", '238', '150');
				$endthumb = ".$cmsfolder$folder";
			}elseif($mode != 'dmyma' && file_exists(".$cmsfolder$folder/.pic_.bintro.jpg.jpg")){
				$endthumb = rawurlencode(".$cmsfolder$folder");
			}elseif(file_exists(urldecode("$cgif/.pic_.bintro.jpg.jpg"))){
				$endthumb = $cgif;
			}
				$foldername = $folder;
				$mpf = htmlentities($foldername);
			if (isset($alpha) && $alpha == "ja"){
				$firstChr = $file[0];
				if($firstChr != $realfirst){
					echo "<div class=\"alpha clear\">".$firstChr."</div>";
					$realfirst = $firstChr;
				}
			}
			if($ord_array[0] != "xpvkleer"){
				echo "<a draggable='false' id='$folder-v' class=\"buo ord\" value=\"".$mpf."\"  href='$cmsfolder$folder/'>
				<div class='bigfolder $color-2' id='".$folder."k' draggable='true' style=\"background: url('?watchfile=/$endthumb/.pic_.bintro.jpg.jpg') no-repeat; background-size: 100% 100%;\" ondrop=\"drop(event, '".$folder."','".$cmsfolder."','')\" ondragover='allowDrop(event)' ondragstart=\"drag(event, '".$folder."','".$cmsfolder."','')\">";
                if($isad('edit') && $edit == 1){
                    $fourpack = 1;
					echo "</a>
<form style='display: inline-block;' onsubmit=\"SND('$folder','$cmsfolder','$cmsfolder',''); event.preventDefault();\">
<input type='hidden' id='".$folder."r' value='$folder'>
<input  style='display: none' type='submit'></form>
<a id='".$folder."o' onclick=\"SN('$folder','$cmsfolder','');\">";
					icon("tag.svg");
					echo "</a><a id='".$file."n' onclick=\"SND('".$folder."','".$cmsfolder."','".$cmsfolder."','".$fourpack."');\">";
                                        icon("trash.svg");
                                        echo "</a><a draggable='false' id='".$folder."v' class='buo ord' value='$mpf'  href='$cmsfolder$folder/'>";
				}
				echo "<form style='display: inline;' onsubmit=\"SND('$folder','$cmsfolder','$cmsfolder',''); event.preventDefault();\"><input type='hidden' id='".$folder."r' value='$folder'></form>";
				echo "<font class='bigback' id='".$mpf."z'>";
				icon("folder.svg");
				echo " $mpf</font></div></a>";
			}
		}
		echo "<div style=\"clear: left;\"></div>";
	}
//ToDO
$folder = $cmsfolder;


	//Rename
	if(isset($_POST['desc'])){
		if($isad('description')){
			$file = fopen($cmsfolder."intro.txt","w");
			echo fwrite($file, $_POST['desc'],0);
			fclose($file);
		}
		echo "<script>self.location.href='$cmsfolder'</script>";
	}
	//File listing
	$thereAreFiles = false;
	$file = "";
	if($mode != 'dmyma'){
		$opf = opendir(".$cmsfolder");
		while(($file = readdir($opf)) !== false){
			if(is_dir(".$cmsfolder$file") | $file == "" | $file[0] == "." | preg_match("/\.php\z/i", $file) | preg_match("/\.md\z/i", $file) | preg_match("/\.html\z/i", $file)) continue;
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
		$nowfolder = explode("/", explode(workpath("$cmsfolder.."), $cmsfolder)[1])[0];
		$underfolder = scandir(".".workpath("$cmsfolder.."));
		$nowfolderkey = array_search($nowfolder, $underfolder);
		$lastfolder = $underfolder[$nowfolderkey-1];
		$nextfolder = $underfolder[$nowfolderkey+1];
		echo "<a id='num0'  onclick='self.location=\"$cmsfolder../$lastfolder#num1\"'></a>";
		foreach($dat_array as $fileid){
			$row = $db->query("SELECT name, orfile, folder FROM files WHERE id = '$fileid'")->fetch_assoc();
			$file = $row['name'];
			$orfile = $row['orfile'];
			//$folder = $row['folder'];
			if($mode == 'dmyma') $folder = addcslashes($row['folder'], "'");
			$rawfolder = rawurlencode($folder);
			$rawfile = rawurlencode($file);
			if(!isset($fileid)){
				$mysqltime = date("Y-m-d H:i:s");
				$dbfile = $db->real_escape_string($file);
				$entry = $db->query("INSERT INTO files (userid, folder, date, name, orfile) VALUES ('0', '$folder', '$mysqltime', '$dbfile', 1)");
				$row = $db->query("SELECT * FROM files WHERE name = '$dbfile'")->fetch_assoc();
				$fileid = $row['id'];
			}
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
			$filename = explode('.', $file);
			$filename = htmlentities(implode('.',array_slice($filename, 0, count($filename) - 1)));
			$htmlescfile = str_replace("'", "&#39;", $file);
			//Singles
			$pext = substr(strrchr($file, "."), 1);
			if(!$pext) $pext = "standard";
                        if(!$filename) $filename = $file;
			echo "<a class='buo ord' id='num$idnum' draggable='false' href='$cmsfolder$file' onclick=\"event.preventDefault(); streamer($idnum, $fileid); ".$fileextension->$pext($cmsfolder, $file)."\">";
                        $sign = $icon->$pext();
            echo "<div class='bigfolder bigfile $color-2' id='".$rawfile."k' style=\"background: url('?watchfile=$singlbackground') no-repeat; background-size: 100% 100%;\" ondragstart=\"drag(event, '".$rawfile."','".$folder."','')\"";
	    if ($isad('edit') && $edit == 1) {
	       echo "draggable=true>
		<a id='".$rawfile."o' onclick=\"SN('".$rawfile."','".$folder."');\">";
		icon("tag.svg");
		echo "</a><a id='".$rawfile."n' onclick=\"SND('".$rawfile."','".$folder."','".$folder."','".$fourpack."',1);\">";
		icon("trash.svg");
		echo "</a>";
	    }else{
                echo ">";
            }
            echo "</a>
            <form style='display: inline; margin: 0;' onsubmit='\"SND('".$rawfile."','".$folder."','".$folder."','".$fourpack."',1);\">
            <input type='hidden' id='".$rawfile."r' value='$htmlescfile' draggable='false'>
            </form>
            <a draggable='false' target='popup' href='$cmsfolder$file' onclick=\"event.preventDefault(); streamer($idnum, $fileid); ".$fileextension->$pext($cmsfolder, $file)."\" >
            ";
	    echo "<font id='".$rawfile."z' class='bigback bigfileback'>";
	    icon($icon->$pext);
			echo "$filename</font></div></a>";
			$four = $four + 1;
			$idnum = $idnum + 1;
	}
    }
echo "<script>window.lastnum = $idnum;</script><a id='num$idnum'  onclick='self.location=\"$cmsfolder../$nextfolder#num1\"'></a>";
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
		<div id='streamerfile' ondblclick='full();' onmousemove='controlblock();'></div>
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
echo"</script>
<div style='display: table;'>";
if($isad('edit') && $edit==1 && $mode=='fmyma'){
    echo "<span style='display: table-row;' class='$color btn' onclick=\"NF('.$cmsfolder','".$lang->newfolder."')\">".$lang->newfolder."</span>";
echo "
<div style='display: table-row;' class='fileUpload btn btn-primary'>
<input class='".$color." btn' class='fileUpload' id='filebiup' multiple type='file' onchange=\"CheckFile(this.files[0].name);\">
<div id='upload-addon'>
</div>
</div>
<input style='display: table-row;' id='fileupfolder' type='hidden' value='.$cmsfolder'>
<input style='display: table-row;' id='fileupmode' type='hidden' value='fb'>
<input style='display: table-row;' class='$color' type=submit onclick=\"UploadFile('file')\">
<progress style='display: table-row;' id='fileupg' value='0' max='100' style='margin-top:10px'></progress>
</div>
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
}
</style>
";
//Tag
echo "<h2>Tags:</h2>";
$dbquery = $db->query("SELECT tagid FROM tag_folder WHERE folderid='$lastfolderid'");
while($row = $dbquery->fetch_assoc()):
	$row2 = $db->query("SELECT * FROM tag_name WHERE id=".$row['tagid']."")->fetch_assoc();
        echo "<a class='btn $color' href='?page=tag&id=".$row2['id']."'>".$row2['name']."</a>";
        if($isad("upload")):
        	echo "<a class='btn $color' href='?page=tag&untag=".$row2['id']."'>";
                icon("untag.svg");
                echo "</a>";
        endif;
	$usedTags[] = $row2['id'];
        echo "<br>";
endwhile;
$dbquery = $db->query("SELECT * FROM tag_name WHERE id NOT IN ('".implode($usedTags, "', '")."')");
if($dbquery->num_rows > 0):
	echo "<select class='btn $color' id='tagchoice'>";
	while($row = $dbquery->fetch_assoc()):
		echo "<option value='".$row['id']."'>".$row['name']."</option>";
	endwhile;
	echo "</select>
	<a class='btn $color' onclick=\"location.href='?page=tag&tag='+document.getElementById('tagchoice').value\"";
	icon("tag.svg");
	echo "</a>";
endif;
}
    if ($mode != 'dmyma') {
		closedir($oph);
	}
}
include '.data/footer.php';
?>
