<?php
$fileid = $db->real_escape_string($_GET['id']);
$cmsfolder = workpath($_GET['f']);
$row = $db->query("SELECT * FROM files WHERE id = '$fileid'")->fetch_assoc();
@$filedate = date("d.m.Y G:i", strtotime($row['date']));
$upuserid = $row['userid'];
$file = $row['name'];
$pro = $row['pro'];
$con = $row['con'];
$view = $row['view'];
$description = $row['description'];
if($upuserid == 0):
	$upuser = "System";
else:
	$upuser = $db->query("SELECT user FROM user WHERE id = '$upuserid'")->fetch_object()->user;
endif;
$filename = explode('.', $file);
$filename = htmlentities(implode('.',array_slice($filename, 0, count($filename) - 1)));
$description = str_replace("<", htmlentities("<"), $description);
echo "<span class='votebox'><a class='btn $color' href='?x=main&file=downloader.php&downfile=$cmsfolder$file'>".icon("download.svg")."</a><span class='btn $color'><span class='vote' id='fileup' onclick=\"conpro('up', 'files', '$fileid', 'file');\">$pro</span>".icon("thumbs-up.svg")."</span><span class='btn $color'><span class='vote' id='filedown' onclick=\"conpro('down', 'files', '$fileid', 'file');\">$con</span>".icon("thumbs-down.svg")."</span><br>
$lang->uploaded: $filedate<br>
$lang->user: $upuser<br>
</span>";
echo "<h2>$filename</h2>";
echo $description;
echo "<div class='comments'>";
echo "<br><a name='comment'><b style='font-size: 20px;'>".icon("chat.svg").$lang->comments.":</b></a><br>";
$dbro = $db->query("SELECT id FROM comments WHERE objectid = '$fileid' ORDER BY id ASC");
while ($dbid = $dbro->fetch_array ()) {
    $folderid = $dbid[0];
    $row = $db->query("SELECT * FROM comments WHERE id = '$folderid'")->fetch_assoc();
        $comid = $row['id'];
        $comcom = $row['comment'];
        $comusid = $row['userid'];
	$commentUser = $db->query("SELECT user FROM user WHERE id = '$comusid'")->fetch_object()->user;
    $usercom = $username;
    if (isset($comid)) {
        $comdate = date("d.m.Y - H:i", strToTime($comdate));
        if($row['sub'] =! 0){
                        echo "<div name='coms' id='com".$row['sub']."' class='overcom $color'>$commentUser<div class='datcom'>".$row['date']." <a class='link' href='?f=".$folder."&comment=" . $comsub . "#comment'>".$lang->answer."</a>";
		if ($isad('comment') || $usercom == $comus ) echo " | <a class='link' onclick=\"com('file', '".$fileid."', 'cl', '".$comsub."');\">".$lang->del."</a>";
                        echo "</div><div class=\"undercom\">" . $comcom . "</div></div>";
                        $nowid = $comid;
                } else {
                        echo "<div name='coms' id='com".$comsub."' class=\"overcom ".$color."\">"  . $folderid ." " . $comus . "'s ".$lang->answerto." " . $comnam . " <div class=\"datcom\">" . $comdate .
                        " <a href=\"?ordner=".$folder."&comment=" . $comsub . "#comment\">".$lang->answer."</a></div><div class=\"undercom\">" . $comcom . "</div></div>";
                }
        }
}
if($_SESSION['loggedin'] == true) {
        echo "<div style='display: none;' id='newcom'>
    Neuer Kommentar:
    <textarea cols=\"80\" rows=\"10\" id='comment' name=\"comment\" /></textarea><br>
    <input type=\"submit\" class=\"buttet, ".$color."\" value=\"".$lang->comment."\" onclick=\"com('file', '".$fileid."', 'comment');\"/>
	</div>
        ";
    echo "<input type=\"button\" style=\"clear: both;\" class=\"buttet ".$color."\" value=\"Kommentieren\" onclick=\"document.getElementById('newcom').style.display='block';\">";
} elseif(isset ($_GET['comment']) && isset($_SESSION['loggedin'])) {
                        $sub = $_GET['comment'];
                        echo "
                        <br>
                        Antworten:
                        <form action=\"?f=".$folder."#comment\" method=\"post\">
                        <textarea cols=\"80\" rows=\"10\" name=\"comment\" /></textarea><br>
                        <input type=\"hidden\" name=\"sub\" value=\"" . $sub . "\" />
                        <input type=\"submit\" class=\"buttet, ".$color."\" value=\"Antworten\" />
                        </form>
                        ";
}
?>
<select id='comsort' onchange='comsort();'>
<option value='timeline'>Time</option>
<option value='chrono'>Chrono</option>
</select>
