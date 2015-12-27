<?php
include 'all.php';
//Rename
$folder = $_GET['f'];
if(isset ($_GET['renold']) && isset ($_GET['rennew'])){
$newf = $_GET['newf'];
if ($isad >=3) {
$old =$_GET['renold'];
$new =$_GET['rennew'];
                        rename(".".$folder."/".$old, ".".$newf."/".$new);
                        rename(".".$folder."/.pic_".$old.".jpg", ".".$newf."/.pic_".$new.".jpg");
                        $df = implode('/', explode('%2F', rawurlencode($folder)));
			$new = $db->real_escape_string($new);
                        $newf = $db->real_escape_string($newf);
                        $df = $db->real_escape_string($df);
                        $old = $db->real_escape_string($old);
                        $db->query("UPDATE files SET name = '$new', folder= '$newf' WHERE folder = '$df' AND name = '$old'");
                }else{
                        echo $lang->norenameright;
                }
}elseif (isset ($_GET['newfolder']) && $isad >=3){
                $new = 'New Folder';
                mkdir(".".$folder."/".$new, 0755);
                echo "<script>self.location.href=\"?f=.".$folder."\"</script>";
}elseif (isset ($_GET['delfile']) && $isad >=8){
	unlink(".".$folder."/".$_GET['delfile']);
}elseif (isset ($_GET['deldir']) && $isad >=8){
        rmdir(".".$folder."/".$_GET['deldir']);
}
?>
