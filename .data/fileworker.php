<?php
//Rename
$folder = $_GET['f'];
if(isset ($_GET['renold']) && isset ($_GET['rennew'])){
$newf = $_GET['newf'];
if ($isad('fileworker')) {
$old =$_GET['renold'];
$new =$_GET['rennew'];
	if(preg_match("/\.mp3\z/i", $old) && !preg_match("/\.mp3\z/i", $new)):
		exec('ffmpeg -i "'.$folder.'/'.$old.'" "'.$folder.'/'.$new.'" > /dev/null');
		unlink($folder."/".$old);
	elseif(preg_match("/\.wma\z/i", $old) && !preg_match("/\.wma\z/i", $new)):
		exec('ffmpeg -i "'.$folder.'/'.$old.'" "'.$folder.'/'.$new.'" > /dev/null');
		unlink($folder."/".$old);
	else:
		rename($folder."/".$old, $newf."/".$new);
	endif;
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
}elseif (isset ($_GET['newfolder']) && $isad('newfolder')){
                $new = $_GET['newfolder'];
                mkdir($folder."/".$new, 0755);
}elseif (isset ($_GET['delfile']) && $isad('deletefolder')){
	$del = $_GET['delfile'];
	unlink($folder."/".$del);
	$db->query("DELETE FROM files WHERE folder = '$folder' AND name='$del'");
}elseif (isset ($_GET['deldir']) && $isad('deletefile')){
	$del = $_GET['deldir'];
        rmdir($folder."/".$del);
	$db->query("DELETE FROM files WHERE folder = '$folder' AND name='$del'");
}
?>
