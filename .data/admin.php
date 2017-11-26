<?php
if($isad()):
	$key = "plugins";
	echo "<input type='submit' class='btn $color' value='".$lang->$key."' onclick=\"self.location.href='";
	if(isset($_GET['d']) && $_GET['d'] == $key):
		echo "?page=admin";
	else:
		echo "?page=admin&d=$key";
	endif;
	echo "'\" /><br>";
	if(isset($_GET['d']) && $_GET['d']  == $key):
		echo "<div id='pluginresponse'></div>";
		echo "<div class='boxall'>";
		$plugdir = scandir('.plugins');
		foreach($plugdir as $pfolder):
			if($pfolder[0] == ".") continue;
			$dbquery = $db->query("SELECT * FROM plugins WHERE name = '$pfolder'");
			if($dbquery->num_rows == 0):
				$db->query("INSERT INTO plugins (name, active) VALUES ('$pfolder', 0)");
				$dbquery = $db->query("SELECT * FROM plugins WHERE name = '$pfolder'");
			endif;
			$row = $dbquery->fetch_assoc();
			if(!$pluginname = $hook->get_info($row['name'], 'name')) $pluginname = $row['name'];
			echo "<div class='boxrow'>
			<div class='boxl boxn'>$pluginname</div><div class='boxl'><input id='check-".$row['name']."' type='checkbox' onclick=\"activatePlugin('".$row['name']."')\"";
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
if(isset($adminextension) && $isad()):
        foreach($adminextension as $key => $adex):
		if(!isset($lang->$key)) $lang->$key = $key;
                echo "<input type='submit' class='btn $color' value='".$lang->$key."' onclick=\"self.location.href='";
                if(isset($_GET['d']) && $_GET['d'] == $key):
                        echo "?page=admin";
                else:
                        echo "?page=admin&d=".$key;
                endif;
                echo "'\" /><br>";
                include $adex;
        endforeach;
endif;
?>
