<?php
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
			echo "<div class=\"overcom ".$color."\">" . $comus . " <div class=\"datcom\">" . $comdate . " <a href=\"?ordner=".$folder."&comment=" . $comsub . "#comment\">".$lang->answer."</a>
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
//require_once("captcha/ayah.php");
//$ayah = new AYAH();
if(isset ($_GET['comment']) && $_GET['comment'] == "new" && isset($_SESSION['loggedin'])) {
	echo "
    Neuer Kommentar:
    <form action=\"?ordner=".$folder."#comment\" method=\"post\">
    <textarea cols=\"80\" rows=\"10\" name=\"comment\" /></textarea><br>
    " . # $ayah->getPublisherHTML() . "
    "<input type=\"submit\" class=\"buttet, ".$color."\" value=\"".$lang->comment."\" />
	</form>
	";
} elseif(isset ($_GET['comment']) && isset($_SESSION['loggedin'])) {
                        $sub = $_GET['comment'];
                        echo "
                        <br>
                        Antworten:
                        <form action=\"?ordner=".$folder."#comment\" method=\"post\">
                        <textarea cols=\"80\" rows=\"10\" name=\"comment\" /></textarea><br>
                        <input type=\"hidden\" name=\"sub\" value=\"" . $sub . "\" />
                        " . $ayah->getPublisherHTML() . "
                        <input type=\"submit\" class=\"buttet, ".$color."\" value=\"Antworten\" />
                        </form>
                        ";
} elseif (isset($_SESSION['loggedin'])) {
    echo "<input type=\"button\" style=\"clear: both;\" class=\"buttet ".$color."\" value=\"Kommentieren\" onclick=\"self.location.href='?ordner=".$folder."&comment=new#comment'\">";
}
if (array_key_exists('comment', $_POST)) {
    $score = $ayah->scoreResult();
    if ($score) {
        $date = date("Y-m-d H:i:s");
        $comment = $_POST["comment"];
        $result = $db->query("SHOW TABLE STATUS LIKE 'comments'");
        $row = $result->fetch_array();
        $nextId = $row['Auto_increment'];
        if (isset($_POST["sub"])) $nextId = $_POST["sub"];
        $eintragen = $db->query("INSERT INTO comments (userid, comment, objectid, date, sub, type) VALUES ('$userid', '$comment', '$folder', '$date', '$nextId', 'folder')");
        echo "<script>window.location.href='?ordner=".$folder."#comment'</script>";
    } else {
        echo $lang->nohuman;
    }
}
if (array_key_exists('cl', $_POST)) {
    if (isset($_POST["sub"])) $comus = $_POST["sub"];
    if (isset($_POST["comsub"])) $comsub = $_POST["comsub"];
    if (isset($_POST["comid"])) $comid = $_POST["comid"];
    if ($isad >= 8 || $usercom == $comus ) {
        if ($comsub == $comid) {
            $loesch = $db->query("DELETE FROM comments WHERE sub = '$comsub'");   
        } else {
			$loesch = $db->query("DELETE FROM comments WHERE id = '$comid'");
        }
		echo "<script>window.location = '?ordner=".$folder."#comment'</script>";
    }
}
?>
