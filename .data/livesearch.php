<?php
include 'sql.php';
echo '<hl>';
$q=$_GET["q"];
$lf = 0;
$or = 0;
if ($mode == "dmyma") {
        $search = $db->query("SELECT * FROM tags WHERE tagname LIKE '%$q%' ORDER BY tagname LIMIT 10");
        while ($row = @mysqli_fetch_assoc($search)){
                $name = $row['tagname'];
                $id = $row['id'];
                if ($or == 0 || $or == 2) { $or = 2; echo "Tags:"; }
                echo "<li><a href=\".?f=". $id . "\">" . $name . "</a></li>";
        }
        $search2 = $db->query("SELECT * FROM files WHERE name LIKE '%$q%' AND folder = './.files' ORDER BY name LIMIT 10");
        while ($row = @mysqli_fetch_assoc($search2)){
		$lf = 1;
                $name = $row['name'];
                $folder = $row['folder'];
                $orfile = $row['orfile'];
                $lf = 1;
                if ($orfile == 1) {
                        if ($or == 0 || $or == 2) { $or = 1; echo "File:"; }
                        echo "<li><a href=\".?f=". $name . "\">" . $name . "</a></li>";
                }
        }
}else {
        $search = $db->query("SELECT * FROM files WHERE name LIKE '%$q%' ORDER BY orfile DESC, name LIMIT 10");
	while ($row = @mysqli_fetch_assoc($search)){
    		$name = $row['name'];
    		$folder = $row['folder'];
    		$orfile = $row['orfile'];
    		$lf = 1;
		if ($orfile == 2) {
			if ($or == 0) { $or = 2; echo "Ordner:"; }
			echo "<li><a href=\".?f=". $folder . "/" . $name . "\">" . $name . "</a></li>";
		}
    		if ($orfile == 1) {
			if ($or == 0 || $or == 2) { $or = 1; echo "Folder:"; }
			echo "<li><a href=\".?f=". $folder . "\">" . $name . "</a></li>";
		}
	}
}
if ($lf == 0) echo"Es tut uns leid, aber \"" . $q . "\" wurde nicht gefunden.";


echo "</hl>";
?>
