<?php
$spsite = "admin";
$spsiten = "Admin";
include '.data/all.php';
include '.data/header.php';
if(isset($username))$dbquery = $db->query("SELECT * FROM user WHERE user = '$username'");
if($isad):
	$key = "plugins";
	echo "<input type='submit' class='buttet ".$color."' value='".$lang->$key."' onclick=\"self.location.href='";
	if(isset($_GET['d']) && $_GET['d'] == $key):
		echo "admin.php";
	else:
		echo "?d=".$key;
	endif;
	echo "'\" /><br>";
	if(isset($_GET['d']) && $_GET['d']  == $key):
		echo "<div id='pluginresponse'></div>";
		echo "<div class='boxall'>";
		$plugdir = scandir($pluginfolder);
		foreach($plugdir as $pfolder):
			if($pfolder[0] == ".") continue;
			$dbquery = $db->query("SELECT * FROM plugins WHERE name = '$pfolder'");
			if($dbquery->num_rows == 0):
				$db->query("INSERT INTO plugins (name, active) VALUES ('$pfolder', 0)");
				$dbquery = $db->query("SELECT * FROM plugins WHERE name = '$pfolder'");
			endif;
			$row = $dbquery->fetch_assoc();
			if(isset($lang->$row['name'])):
		 		$row['realname'] = $lang->$row['name'];
			else:
				$row['realname'] = $row['name'];
			endif;
			echo "<div class='boxrow'>
			<div class='boxl boxn'>".$row['realname']."</div><div class='boxl'><input id='check-".$row['name']."' type='checkbox' onclick=\"activatePlugin('".$row['name']."')\"";
			if($row['active'] == 1) echo "checked";
			echo "></div>
			</div>";
		endforeach;
		if($plugdir == ""):
			echo $lang->nopluginsfound;
		endif;
		echo "</div>";
	endif;
endif;
if(isset($adminextension)):
        foreach($adminextension as $key => $adex):
		if(!isset($lang->$key)) $lang->$key = $key;
                echo "<input type='submit' class='buttet ".$color."' value='".$lang->$key."' onclick=\"self.location.href='";
                if(isset($_GET['d']) && $_GET['d'] == $key):
                        echo "admin.php";
                else:
                        echo "?d=".$key;
                endif;
                echo "'\" /><br>";
                include $adex;
        endforeach;
endif;
include '.data/footer.php';
?>
