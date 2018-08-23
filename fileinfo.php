<?php
$fileid = escape('id');
$filename = $db->query("SELECT name FROM file WHERE id = '$fileid'")->fetch_object()->name;
$filearray = explode('.',$filename);
array_pop($filearray);
$fileshowname = htmlentities(implode('.', $filearray));
echo "<h2>$fileshowname";
echo "<span class='votebox'><a class='btn $color' href='$cmsfolder$filename?download'>".icon("download.svg")."</a></span>";
echo "</h2>";
//Hook - Fileinfo
        $hook->include('fileinfo.php');
