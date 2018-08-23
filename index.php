<?php
//Welcome to Zeigher
$zeigher_version = "0.9.9";

require_once "extendedclass.php";
if(file_exists("config.php")) include "config.php";
//Install redirect
if(!isset($installed) || $installed == false):
        include "install.php";
        exit;
endif;

require_once 'all.php';

$cmsfolder = workpath(explode('?', $_SERVER['REQUEST_URI'])[0]);
$realfolder = "..$cmsfolder";
if(isset($_GET['x'])):
	include "ajax.php";
	exit;
elseif(isset($_GET['watchfile']) || is_file($realfolder)):
	include "stream.php";
elseif(isset($_GET['upload'])):
        include "upload.php";
        exit;
elseif(isset($_GET['logoff'])):
	$_SESSION['loggedin'] = false;
	setcookie("PHPSESSID", "", time() - 3600);
        session_destroy();
	setcookie("Zeigher-ID", "", time() - 3600, "/");
	setcookie("Zeigher-Token", "", time() - 3600, "/");
        echo "<script>self.location.href='.'</script>";
	exit;
elseif(isset($_GET['api'])): //API calls
	include "api.php";
endif;

require_once 'header.php';

echo "<div style='clear: right;'></div>";


//Login redirect
if($_SESSION['loggedin'] == false && $settings->use == 'none' && !isset($_GET['page'])):
	echo "<script>self.location.href='?page=login'</script>";
endif;

// Add  root folderid if not existing
if($cmsfolder == "/"):
	if(!$db->query("SELECT id FROM folder WHERE id = '1'")->num_rows):
		$db->query("INSERT INTO folder (id, name, parentfolderid) VALUES (1, 'root', 0)")
		or trigger_error("SQL Folder Initialisation ERROR");
	endif;
endif;


if(isset($_GET['page'])): //Static page
	$pageget = $_GET['page'];
	if(isset($page->$pageget)):
		$page->$pageget();
		$noitems = 1;
	else:
		$fourzerofour = true;
	endif;
endif;

//404 Error
if(isset($fourzerofour) | !file_exists($realfolder)):
	echo "<div class=\"\" style='max-height: none;'><h1>".$lang->fourzerofour."</h1>";
	if(isset($_GET['page'])):
		echo "$lang->sitedontexists<br>";
	else:
		echo "$lang->dontexists<br>";
		if($isad('edit') && $edit==1):
			$newfolder = end(explode("/", $cmsfolder));
        		echo "<span class='$color btn' onclick=\"NF('$cmsfolder/..', '$newfolder')\">$lang->newfolder</span>";
		endif;
	endif;
	echo "<a class='$color btn' href='$cmsfolder..'>$lang->back</a></div>";
exit;
elseif(!isset($_GET['page']) && !isset($noitems) && $_SESSION['loggedin'] == 1 || $settings->use == 1):

	$folderid = folderid($cmsfolder);

	//Get plugin Intros
	$hook->include('intro.php');
	if($isad('edit') && $edit == 1):
		echo "<textarea style='display: none;' id='descbox' onsubmit=\"SetNameOrd('$cmsfolder');\">";
		echo "</textarea><a id='descedit' class='ico-edit' onclick=\"SetDesc('".$folder."');\"></a>
		<a id='bintroup' class='ico-up' onclick=\"SetDescUploadBack('$cmsfolder');\"></a><div/>";
	endif;

//TMP MV .bintro to new background Folder
if(file_exists("$realfolder/.bintro.jpg") && !file_exists("data/backgrounds/$folderid.jpg")):
	rename("$realfolder/.bintro.jpg", "data/backgrounds/$folderid.jpg");
endif;
//TMP

	//Folder listing
	$oph = opendir($realfolder);
	while(($file = readdir($oph)) !== false):
		if($file[0] == '.' || $file == 'zeigher') continue;
		if(!is_dir("$realfolder$file")):
			$file_array[] = $file;
			continue;
		endif;
        	$folder_array[] = $file;
	endwhile;
	if(isset($folder_array)):
		$folderlist = folderlist($folder_array, $folderid);
	endif;

	//File listing
	$hook->include("extension.php");
        if(isset($file_array)):
            	natsort($file_array);
		foreach($file_array as $file):
			$dat_array[] = childid($folderid, $file, "file", true);
		endforeach;
        endif;
	if(empty($dat_array)):
		if(!isset($intro) && empty($folderlist)):
			echo "<b>$lang->empty</b>";
		endif;
	else:
		$four = 0;
		$fourpack = 1;
		$idnum = 1;
		$i = 0;
		echo "<a id='num0-a'></a>";
		foreach($dat_array as $fileid):
			$file = $db->query("SELECT * FROM file WHERE id = '$fileid'")->fetch_object()->name;
			$rawfile = rawurlencode($file);

			if(background("filethumb", $fileid)):
                                $bgsmallfile = background("filethumb", $fileid);
                        else:
                                $bgsmallfile = $bgsmall;
                        endif;
			#Thumbnail Generate
			#if(file_exists("./.pic_.bintro.jpg.jpg") && $mode=='dmyma') $endthumb = ".";
			
			#if(!file_exists($folder."/.pic_".$file.".jpg")):
			#	if(preg_match('/\.jpg\z/i', $file) || preg_match('/\.png\z/i', $file) || preg_match('/\.gif\z/i', $file)):
			#		pic_thumb($folder.'/'.$file, $folder.'/.pic_'.$file.'.jpg', '238', '150');
			#	elseif(preg_match('/\.mp3\z/i', $file) || preg_match('/\.aac\z/i', $file) || preg_match('/\.rdio\z/i', $file)):
			#		if(file_exists($realfolder."/.art_".$file.".jpg")) pic_thumb($folder.'/.art_'.$file.'.jpg', $folder.'/.pic_'.$file.'.jpg', '238', '150');
			#	endif;
			#	if(!file_exists($folder."/.pic_$file.jpg")):
			#		$singlbackground = $endthumb."/.pic_.bintro.jpg.jpg";
			#	endif;
			#else:
			#	$singlbackground = $cmsfolder."/.pic_".$rawfile.".jpg";
			#endif;
//TMP MV .pic to new background Folder
if(file_exists("$realfolder.pic_$file.jpg") && !file_exists("data/filethumb/$fileid.jpg")):
        rename("$realfolder.pic_$file.jpg", "data/filethumb/$fileid.jpg");
endif;
//TMP
			$filename = explode('.', $file);
			$filename = htmlentities(implode('.',array_slice($filename, 0, count($filename) - 1)));
			$htmlescfile = str_replace("'", "&#39;", $file);
			//Singles
			$pext = substr(strrchr($file, "."), 1);
			if(!$pext) $pext = "standard";
            echo "<div class='bigfolder bigfile $color-2' id='num$idnum' style=\"background: url('$bgsmallfile') no-repeat; background-size: 100% 100%;\" ondragstart=\"drag(event, '$fileid','$folderid','')\"";
	    if ($isad('edit') && $edit == 1):
	       echo "draggable=true>
		<a id='$fileid-o' class='btn $color' onclick=\"SN('$fileid','$folderid');\">";
		echo icon("tag.svg");
		echo "</a><a id='".$rawfile."n' class='btn $color' onclick=\"SND('$fileid','$folderid','$folderid','$fourpack',1);\">";
		echo icon("trash.svg");
		echo "</a>";
	    else:
                echo ">";
            endif;
            echo "<a class='buo ord' id='num$idnum-a' draggable='false' href='$cmsfolder".rawurlencode($file)."' onclick=\"event.preventDefault(); streamer($idnum, $fileid); ".$fileextension->$pext($cmsfolder, $file)."\">
            <form style='display: inline; margin: 0;' onsubmit='\"SND('$fileid','$folderid','$folderid','$fourpack',1);\">
            <input type='hidden' id='$fileid-r' value='$htmlescfile' draggable='false'>
            </form>";
	    echo "<font id='$fileid-z' class='bigback bigfileback'>";
	    echo icon($icon->$pext);
			echo "$filename</font></a></div>";
			$four++;
			$idnum++;
	endforeach;
    endif;
if(isset($idnum)) echo "<div id='num$idnum' style='clear: both;'></div><script>window.lastnum = $idnum;</script><a id='num$idnum-a'></a>";
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
<div style='display: table-row;' class='cssfileUpload btn btn-primary'>
<input class='".$color." btn' class='cssfileUpload' id='filebiup' multiple type='file' onchange=\"CheckFile(this.files[0].name);\">
<div id='upload-addon'>
</div>
</div>
<input style='display: table-row;' id='fileupfolder' type='hidden' value='$realfolder'>
<input style='display: table-row;' id='fileupmode' type='hidden' value='fb'>
<input style='display: table-row;' class='$color' type=submit onclick=\"UploadFile('file')\">
<progress style='display: table-row;' id='fileupg' value='0' max='100' style='margin-top:10px'></progress>
</div>
<style>
.cssfileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
}
.cssfileUpload input.upload {
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
