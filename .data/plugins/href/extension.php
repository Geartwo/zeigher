<?php
$docfile=fopen($yeslop .".href","r+");
$lfile = htmlentities(fgets($docfile));
fclose($docfile);
echo"onclick=\"self.location.href='".$lfile."'\">";
?>
