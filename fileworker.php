<?php
//Rename

if(isset ($_GET['renold']) && isset ($_GET['rennew'])){
	$newfolder = "..".workpath($_GET['newf']);
	if ($isad('edit')) {
		$old =$_GET['renold'];
		$new =$_GET['rennew'];
		rename("$realfolder$old", "$newfolder$new");
        	rename("realfolder/.pic_$old.jpg", "$newfolder/.pic_".$new.".jpg");
                $df = implode('/', explode('%2F', rawurlencode($cmsfolder)));
		$new = $db->real_escape_string($new);
                $newf = $db->real_escape_string($newf);
                $df = $db->real_escape_string($df);
                $old = $db->real_escape_string($old);
                $db->query("UPDATE files SET name = '$new', folder= '$newf' WHERE folder = '$df' AND name = '$old'");
        }else{
                        echo $lang->norenameright;
                }
}elseif(isset ($_GET['newfolder']) && $isad('edit')){
                $new = $_GET['newfolder'];
                mkdir("$realfolder$new", 0755);
}elseif(isset ($_GET['delfile']) && $isad('edit')){
	$del = $_GET['delfile'];
	unlink("$realfolder$del");
	$db->query("DELETE FROM files WHERE folder = '$folder' AND name='$del'");
}elseif(isset($_GET['deldir']) && $isad('deldir')){
	$dbquery = $db->query("SELECT id, name FROM folder WHERE id = '".escape("deldir")."'");
	if($dbquery->num_rows > 0):
		$row = $dbquery->fetch_object();
		$db->query("DELETE FROM folder WHERE id = '$row->id'");
		$folder = ".".folderpath($row->id);
		rmdir($folder);
	endif;
}

//Create a File
function createfile($path, $filename, $force){
	if(file_exists(workpath($path)."/".$filename) && $force != true) exit("File exist use force");;
	
}

//Create a Folder
function createfolder($path, $foldername, $force){
        if(file_exists(workpath($path, ".")."/".$foldername) && $force != true) exit("Folder exist use force");;
	mkdir(workpath($path, ".")."/".$foldername);
}
exit;
?>
