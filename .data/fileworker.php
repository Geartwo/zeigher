<?php
//Rename
$folder = workpath($_GET['f']);
if(isset ($_GET['renold']) && isset ($_GET['rennew'])){
	$newf = workpath($_GET['newf']);
	if ($isad('edit')) {
		$old =$_GET['renold'];
		$new =$_GET['rennew'];
		rename(".$folder$old", ".$newf$new");
        	rename($folder."/.pic_".$old.".jpg", $newf."/.pic_".$new.".jpg");
                $df = implode('/', explode('%2F', rawurlencode($folder)));
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
		error_log(".$folder$new");
                mkdir(".$folder$new", 0755);
}elseif(isset ($_GET['delfile']) && $isad('edit')){
	$del = $_GET['delfile'];
	unlink(".$folder$del");
	$db->query("DELETE FROM files WHERE folder = '$folder' AND name='$del'");
}elseif(isset ($_GET['deldir']) && $isad('deldir')){
	$del = $_GET['deldir'];
        rmdir(".$folder$del");
	$db->query("DELETE FROM files WHERE folder = '$folder' AND name='$del'");
}
?>
