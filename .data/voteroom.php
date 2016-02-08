<?php
include 'all.php';
include 'functions.php';
foreach($voteroomextension as $voex){
	include($voex);
}
$fileid = $_POST['file'];
$folder = $_POST['folder'];
$row = $db->query("SELECT * FROM files WHERE id = '$fileid'")->fetch_assoc();
@$filedate = date("d.m.Y G:i", strtotime($row['date']));
$upuserid = $row['userid'];
$filename = $row['name'];
$pro = $row['pro'];
$con = $row['con'];
$view = $row['view'];
$description = $row['description'];
$row = $db->query("SELECT * FROM points WHERE type = 'user' AND objectid = '$userid'")->fetch_assoc();
$row = $db->query("SELECT * FROM user WHERE id = '$upuserid'")->fetch_assoc();
$upuser = $row['user'];
echo $filename;
$description = str_replace("<", htmlentities("<"), $description);
$my_html = \Michelf\Markdown::defaultTransform($description);
echo "<a class='ico-down' href='.data/downloader.php?file=.".$folder."/".$filename."'></a><br>";
echo $lang->uploaded.": ".$filedate."<b class='vote' id='fileup' onclick=\"conpro('up', 'files', '".$fileid."', 'file');\">".$pro."</b><b class='vote'>+</b> <b class='vote' id='filedown' onclick=\"conpro('down', 'files', '".$fileid."', 'file');\">".$con."</b><b class='vote'>-</b> ".$lang->user.": ".$upuser."<br>".
$my_html."<br>";
echo "<div class=\"comments\">";
echo "<br><a name=\"comment\"><b style=\"font-size: 20px;\"><font class='ico-kom'></font>".$lang->comments.":</b></a><br>";
$dbro = $db->query("SELECT id FROM comments WHERE objectid = '$fileid' ORDER BY sub ASC, id ASC");
while ($dbid = $dbro->fetch_array ()) {
    $folderid = $dbid[0];
    $dbquery = $db->query("SELECT * FROM comments WHERE id = '$fileid'");
    while ($row = $dbquery->fetch_assoc()){
        $comid = $row['id'];
        $comcom = $row['comment'];
        $comus = $row['user'];
        $comdate = $row['date'];
                $comsub = $row['sub'];
    }
    $usercom = ucfirst($username);
    if (isset($comid)) {
        $comnam = $db->fetch_array ($db->query("SELECT userid FROM comments WHERE id = '$comsub'"));
        $comnam = ucfirst($comnam[0]);
        $comus = ucfirst($comus);
                $comdate = date("d.m.Y - H:i", strToTime($comdate));
                if ($comid == $comsub) {
                        echo "<div class=\"overcom ".$color."\">" . $comus . " <div class=\"datcom\">" . $comdate . " <a href=\"?f=".$folder."&comment=" . $comsub . "#comment\">".$lang->answer."</a>
                        </div><div class=\"undercom\">" . $comcom . "</div></div>";
                        $nowid = $comid;
                } else {
                        echo "<div class=\"overcom anscom ".$color."\">" . $comus . "'s ".$lang->answerto." " . $comnam . " <div class=\"datcom\">" . $comdate .
                        " <a href=\"?ordner=".$folder."&comment=" . $comsub . "#comment\">".$lang->answer."</a></div><div class=\"undercom\">" . $comcom . "</div></div>";
                }
                if ($isad >= 8 || $usercom == $comus ) {
                        echo "
                        <form action=\"?ordner=".$folder."#comment\" method=\"post\">
                        <input type=\"hidden\" name=\"sub\" value=\"" . $comus . "\">
                        <input type=\"hidden\" name=\"comid\" value=\"" . $comid . "\">
                        <input type=\"hidden\" name=\"comsub\" value=\"" . $comsub . "\">
                        <input type=\"submit\" class=\"buttet, ".$color."\" value=\"".$lang->del."\" name=\"cl\">
                        </form>";
                 }
        }
}
if(isset($_SESSION['loggedin'])) {
        echo "<div style='display: none;' id='newcom'>
    Neuer Kommentar:
    <form>
    <textarea cols=\"80\" rows=\"10\" name=\"comment\" /></textarea><br>
    <input type=\"submit\" class=\"buttet, ".$color."\" value=\"".$lang->comment."\" />
        </form>
	</div>
        ";
#'$userid', '$comment', '$folder', '$date', '$nextId', 'folder'
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
include 'comments.php';
?>
