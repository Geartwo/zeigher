<?php
function showfolder($subfolderid, $folder){
global $bgsmall, $cmsfolder, $color,$db, $edit, $isad, $lastChr;
$subfolder = $db->query("SELECT name FROM folder WHERE id = '$subfolderid'")->fetch_object();
$folderid = $folder->id;
if($folder->dropcap):
	$currentChr = $subfolder->name[0];
	if(empty($lastChr) || $currentChr != $lastChr):
		$lastChr = $currentChr;
		echo "<div class='dropcap clear'>$currentChr<span>$currentChr</span></div>";
	endif;
endif;
if(background("small", $subfolderid)):
	$bgsmallfolder = background("small", $subfolderid);
else:
	$bgsmallfolder = $bgsmall;
endif;

echo "<div
class='bigfolder $color-2'
id='$subfolderid-k'
draggable='true'
style=\"background: url('$bgsmallfolder') no-repeat; background-size: 100% 100%;\"
ondrop=\"drop(event, '$subfolderid','$folderid','')\"
ondragover='allowDrop(event)'
ondragstart=\"drag(event, '$subfolderid','$folderid','')\">";

if($isad('edit') && $edit == 1):
	echo "</a><a id='$subfolderid-o' class='btn $color' onclick=\"SN('$subfolderid','$folderid','');\">";
	echo icon("pencil.svg");
	echo "</a><a id='$subfolderid-n' class='btn $color' onclick=\"deletefolder('$subfolderid');\">";
	echo icon("trash.svg");
	echo "</a>";
	echo "<form style='display: none;' id='$subfolderid-changenameform' onsubmit=\"SND('$subfolderid','$folderid','$folderid',''); event.preventDefault();\">";
	echo "<input id='$subfolderid-r' value='$subfolder'><input  style='display: none' type='submit'></form>";
endif;

echo "<form style='display: inline;' onsubmit=\"SND('$subfolderid','$folderid','$folderid',''); event.preventDefault();\"><input type='hidden' id='$subfolderid-r' value='$subfolder->name'></form>";
echo "<a draggable='false' id='$subfolderid-v' class='buo ord' value='".htmlentities($subfolder->name)."'  href='$cmsfolder$subfolder->name/'>
<font class='bigback' id='$subfolderid-z'>";
echo icon("folder.svg");
echo " ".htmlentities($subfolder->name)."</font></div></a>";
}


function folderlist($folder_array, $folderid){
global $db;
natsort($folder_array);
foreach($folder_array as $subfolder):
        $ord_array[] = childid($folderid, $subfolder, "folder", true);
endforeach;
$lastChr = "";
$folder = $db->query("SELECT id, dropcap FROM folder WHERE id = '$folderid'")->fetch_object();
foreach($ord_array as $subfolderid):
	showfolder($subfolderid, $folder);
endforeach;
return true;
}
