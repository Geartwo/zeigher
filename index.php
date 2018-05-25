<?php
if(file_exists("config.php")) include "config.php";
//Install redirect
if(!isset($installed) || $installed == false):
        include "install.php";
        exit;
endif;

require_once 'all.php';
$cmsfolder = workpath($_GET['f']);
$realfolder = "..$cmsfolder";
if(isset($_GET['x'])):
	include "ajax.php";
	exit;
elseif(isset($_GET['watchfile']) | is_file("$realfolder")):
	include "stream.php";
	exit;
elseif(isset($_GET['upload'])):
        include "upload.php";
        exit;
elseif(isset($_GET['logoff'])):
	$_SESSION['loggedin'] = false;
        session_destroy();
	setcookie("Zeigher-ID", "", time() - 3600, "/");
	setcookie("Zeigher-Token", "", time() - 3600, "/");
        echo "<script>self.location.href='.'</script>";
	exit;
elseif(isset($_GET['api'])):
	include "api.php";
endif;

require_once 'header.php';


//Background picture thumbnail greator
if(!file_exists("$realfolder.pic_.bintro.jpg.jpg") && file_exists("$realfolder.bintro.jpg")):
	pic_thumb("$realfolder.bintro.jpg", "$realfolder.pic_.bintro.jpg.jpg", '238', '150');
endif;
$ufolder = implode('/', explode('%2F', rawurlencode($folder)));
if(file_exists("$folder/.intro.jpg")):
	echo "<img class='tbild' onmousedown='return false;' src='$ufolder/.intro.jpg' alt='mainpic'>";
endif;


echo "<div style='clear: right;'></div>";


//Login redirect
if($_SESSION['loggedin'] == false && $settings->use == 'none' && !isset($_GET['page'])):
	echo "<script>self.location.href='?page=login'</script>";
endif;

//Get IDs of all Folders
if($cmsfolder == "/"):
	if(!$db->query("SELECT id FROM folder WHERE id = '1'")->num_rows):
		$db->query("INSERT INTO folder (id, name, parentfolderid) VALUES (1, 'root', 0)")
		or die("SQL Folder Initialisation ERROR");
	endif;
endif;
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
		$noitems = 1;
	else:
		$fourzerofour = true;
	endif;
endif;

//404 Error
if (isset($fourzerofour)):
	echo "<div class=\"\" style='max-height: none;'><h1>".$lang->fourzerofour."</h1>".$lang->dontexists."<br>";
	if($isad('edit') && $edit==1 && $mode=='fmyma'):
		$newfolder = end(explode("/", $_GET['f']));
        	echo "<span class='$color btn' onclick=\"NF('.".$_GET['f']."/..', '$newfolder')\">$lang->newfolder</span>";
	endif;
	echo "<a class='$color btn' href=\"?f=".$tgif."\">".$lang->back."</a></div>";

elseif(isset($noitems)):
elseif(!isset($_GET['page']) && !isset($specialindex) && $_SESSION['loggedin'] == 1 || $settings->use == 1):

//Folder listing
	$hook->include('intro.php');
	if(file_exists($folder ."/.intro.txt")):
		echo "</a><div id='mainintro' class=\"intro\" style='cursor: s-resize;' onclick='mainmore();'>";
        	$docfile=fopen($folder . "/.intro.txt","r+");
        	while(!feof($docfile)):
        	        $line = htmlentities(fgets($docfile,1000));
        	        echo $line."<br>";
        	        $intro = $line."\n";
        	endwhile;
        	fclose($docfile);
        	echo "</div>";
        	$intro = 'true';
	endif;
		$oph = opendir("$realfolder");
		while(($file = readdir($oph)) !== false):
			if($file[0] == '.' || $file == 'zeigher') continue;
			if(!is_dir("$realfolder$file")):
				$file_array[] = $file;
				continue;
			endif;
            		$folder_array[] = $file;
		endwhile;

        if(isset($folder_array)):
            natsort($folder_array);
            foreach($folder_array as $folder):
			$dbFolder = $db->real_escape_string($folder);
			$folderId = $db->query("SELECT id FROM folder WHERE name = '$dbFolder' AND parentfolderid = '$lastfolderid'")->fetch_object()->id;
			if(!isset($folderId)):
				$db->query("INSERT INTO folder (name, parentfolderid) VALUES ('$dbFolder', '$lastfolderid')")
				or die("SQL Folder Initialisation ERROR");
				$folderId = $db->query("SELECT id FROM folder WHERE name = '$dbFolder' AND parentfolderid = '$lastfolderid'")->fetch_object()->id;
			endif;
			$ord_array[] = $folderId;
            endforeach;
        endif;
		if($isad('edit') && $edit == 1):
			echo "<textarea style='display: none;' id='descbox' onsubmit=\"SetNameOrd('$cmsfolder');\">";
			if(file_exists("$realfolder"."intro.txt")):
				$docfile=fopen("$realfolder"."intro.txt","r+");
				while(!feof($docfile)):
					$zeile = htmlentities(fgets($docfile));
					echo $zeile."\n";
				endwhile;
				fclose($docfile);
			endif;
			echo "</textarea>
<a id='descedit' class='ico-edit' onclick=\"SetDesc('".$folder."');\"></a>
			<a id='bintroup' class='ico-up' onclick=\"SetDescUploadBack('$cmsfolder');\"></a><div/>";
		endif;
		if(!isset($ord_array)) $ord_array[0] = "xpvkleer";
		$realfirst = "";
		foreach($ord_array as $folderId):
            $row = $db->query("SELECT * FROM folder WHERE id = '$folderId'")->fetch_assoc();
            $folder = $row['name'];


			#Thumbnail Generate
			$endthumb = "";
			$zgif = urldecode($cgif);
			if(!file_exists("$realfolder$folder/.pic_.bintro.jpg.jpg") && file_exists("$realfolder$folder/.bintro.jpg")):
				pic_thumb("$realfolder$folder/.bintro.jpg", "$realfolder$folder/.pic_.bintro.jpg.jpg", '238', '150');
				$endthumb = "$realfolder$folder";
			elseif(file_exists("$realfolder$folder/.pic_.bintro.jpg.jpg")):
				$endthumb = rawurlencode("$realfolder$folder");
			elseif(file_exists(urldecode("$cgif/.pic_.bintro.jpg.jpg"))):
				$endthumb = $cgif;
			endif;
				$foldername = $folder;
				$mpf = htmlentities($foldername);
			if (isset($alpha) && $alpha == "ja"):
				$firstChr = $file[0];
				if($firstChr != $realfirst):
					echo "<div class=\"alpha clear\">".$firstChr."</div>";
					$realfirst = $firstChr;
				endif;
			endif;
			if($ord_array[0] != "xpvkleer"):
				echo "<div class='bigfolder $color-2' id='".$folder."k' draggable='true' style=\"background: url('?watchfile=/$endthumb/.pic_.bintro.jpg.jpg') no-repeat; background-size: 100% 100%;\" ondrop=\"drop(event, '$folder','$cmsfolder','')\" ondragover='allowDrop(event)' ondragstart=\"drag(event, '$folder','$cmsfolder','')\">";
                if($isad('edit') && $edit == 1):
                    $fourpack = 1;
					echo "</a>
<form style='display: inline-block;' onsubmit=\"SND('$folder','$cmsfolder','$cmsfolder',''); event.preventDefault();\">
<input type='hidden' id='".$folder."r' value='$folder'>
<input  style='display: none' type='submit'></form>
<a id='".$folder."o' class='btn $color' onclick=\"SN('$folder','$cmsfolder','');\">";
					echo icon("pencil.svg");
					echo "</a><a id='".$file."n' class='btn $color' onclick=\"deletefolder('$folderId');\">";
                                        echo icon("trash.svg");
                                        echo "</a>";
				endif;
				echo "<form style='display: inline;' onsubmit=\"SND('$folder','$cmsfolder','$cmsfolder',''); event.preventDefault();\"><input type='hidden' id='".$folder."r' value='$folder'></form>";
				echo "<a draggable='false' id='".$folder."v' class='buo ord' value='$mpf'  href='$cmsfolder$folder/'>
				<font class='bigback' id='".$mpf."z'>";
				echo icon("folder.svg");
				echo " $mpf</font></div></a>";
			endif;
		endforeach;
		echo "<div style=\"clear: left;\"></div>";
//ToDO
$folder = "$realfolder";


	//Rename
	if(isset($_POST['desc'])):
		if($isad('description')):
			$file = fopen($cmsfolder."intro.txt","w");
			echo fwrite($file, $_POST['desc'],0);
			fclose($file);
		endif;
		echo "<script>self.location.href='$cmsfolder'</script>";
	endif;
	//File listing
	$thereAreFiles = false;
        if(isset($file_array)):
            	natsort($file_array);
		foreach($file_array as $file):
			$dbfile = $db->real_escape_string($file);
			if(!$db->query("SELECT id FROM files WHERE name = '$dbfile' AND folderid = '$lastfolderid'")->num_rows):
				$db->query("INSERT INTO files (name, folderid) VALUES ('$dbfile', $lastfolderid)");
			endif;
			$dat_array[$file] = $db->query("SELECT id FROM files WHERE name = '$dbfile' AND folderid = '$lastfolderid'")->fetch_object()->id;
		endforeach;
        endif;
	if(empty($dat_array)):
		if(!isset($intro) && empty($ord_array)):
			echo "<b>$lang->empty</b>";
		endif;
	else:
		$four = 0;
		$fourpack = 1;
		$idnum = 1;
		$numItems = count($dat_array);
		$i = 0;
		$lastfolder = $folder;
		echo "<a id='num0-a'></a>";
		foreach($dat_array as $file=>$fileid):
			$rawfile = rawurlencode($file);
			
			#Thumbnail Generate
			$singlbackground = "";
			if(file_exists("./.pic_.bintro.jpg.jpg") && $mode=='dmyma') $endthumb = ".";
			
			if(!file_exists($folder."/.pic_".$file.".jpg")):
				if(preg_match('/\.mp4\z/i', $file) || preg_match('/\.webm\z/i', $file) || preg_match('/\.mkv\z/i', $file)):
					exec('ffmpeg -i "'.$folder.'/'.$file.'" -y -vcodec mjpeg -vframes 1 -an -f rawvideo -s 238x150 -ss 00:00:05 "'.$folder.'/.pic_'.$file.'.jpg" > /dev/null &');
				elseif(preg_match('/\.jpg\z/i', $file) || preg_match('/\.png\z/i', $file) || preg_match('/\.gif\z/i', $file)):
					pic_thumb($folder.'/'.$file, $folder.'/.pic_'.$file.'.jpg', '238', '150');
				elseif(preg_match('/\.mp3\z/i', $file) || preg_match('/\.aac\z/i', $file) || preg_match('/\.rdio\z/i', $file)):
					if(file_exists($folder."/.art_".$file.".jpg")) pic_thumb($folder.'/.art_'.$file.'.jpg', $folder.'/.pic_'.$file.'.jpg', '238', '150');
				endif;
				if(!file_exists($folder."/.pic_$file.jpg")):
					$singlbackground = $endthumb."/.pic_.bintro.jpg.jpg";
				endif;
			else:
				$singlbackground = $cmsfolder."/.pic_".$rawfile.".jpg";
			endif;
			$filename = explode('.', $file);
			$filename = htmlentities(implode('.',array_slice($filename, 0, count($filename) - 1)));
			$htmlescfile = str_replace("'", "&#39;", $file);
			//Singles
			$pext = substr(strrchr($file, "."), 1);
			if(!$pext) $pext = "standard";
                        if(!$filename) $filename = $file;
            echo "<div class='bigfolder bigfile $color-2' id='num$idnum' style=\"background: url('?watchfile=$singlbackground') no-repeat; background-size: 100% 100%;\" ondragstart=\"drag(event, '$rawfile','$cmsfolder','')\"";
	    if ($isad('edit') && $edit == 1):
	       echo "draggable=true>
		<a id='$rawfileo' class='btn $color' onclick=\"SN('$rawfile','$cmsfolder');\">";
		echo icon("tag.svg");
		echo "</a><a id='".$rawfile."n' class='btn $color' onclick=\"SND('$rawfile','$cmsfolder','$cmsfolder','$fourpack',1);\">";
		echo icon("trash.svg");
		echo "</a>";
	    else:
                echo ">";
            endif;
            echo "<a class='buo ord' id='num$idnum-a' draggable='false' href='$cmsfolder$file' onclick=\"event.preventDefault(); streamer($idnum, $fileid); ".$fileextension->$pext($cmsfolder, $file)."\">
            <form style='display: inline; margin: 0;' onsubmit='\"SND('".$rawfile."','".$folder."','".$folder."','".$fourpack."',1);\">
            <input type='hidden' id='".$rawfile."r' value='$htmlescfile' draggable='false'>
            </form>";
	    echo "<font id='".$rawfile."z' class='bigback bigfileback'>";
	    echo icon($icon->$pext);
			echo "$filename</font></a></div>";
			$four = $four + 1;
			$idnum = $idnum + 1;
	endforeach;
    endif;
if(isset($idnum)) echo "<div id='num$idnum' style='clear: both;'></div><script>window.lastnum = $idnum;</script><a id='num$idnum-a'  onclick='self.location=\"$cmsfolder../$nextfolder#num1\"'></a>";
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
if($isad('edit') && $edit==1):
    echo "<span style='display: table-row;' class='$color btn' onclick=\"NF('$realfolder','".$lang->newfolder."')\">".$lang->newfolder."</span>";
echo "
<div style='display: table-row;' class='fileUpload btn btn-primary'>
<input class='".$color." btn' class='fileUpload' id='filebiup' multiple type='file' onchange=\"CheckFile(this.files[0].name);\">
<div id='upload-addon'>
</div>
</div>
<input style='display: table-row;' id='fileupfolder' type='hidden' value='$realfolder'>
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
endif;
$hook->include('intro.php');
closedir($oph);
endif;
include 'footer.php';
?>
