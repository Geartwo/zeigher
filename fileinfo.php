<?php
$fileid = escape('id');
$filename = $db->query("SELECT name FROM files WHERE id = '$fileid'")->fetch_object()->name;
$filearray = explode('.',$filename);
array_pop($filearray);
$fileshowname = htmlentities(implode('.', $filearray));
echo "<h2>$fileshowname</h2>";
echo "<span class='votebox'><a class='btn $color' href='?x=main&file=downloader.php&downfile=$cmsfolder$filename'>".icon("download.svg")."</a></span>";
//Hook - Fileinfo
        $hook->include('fileinfo.php');
