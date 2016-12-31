<?php
include 'all.php';
$check = $_GET['check'];
$name = $_GET['name'];
$pluginfolder = ".plugins";
$longpfolder = $pluginfolder.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR;
include $longpfolder."info.php";
$plugversion = $version;
$error = 0;
foreach($requirements as $key => $require):
	$compare = preg_replace("/([0-9\.])+/", '',$require);
	$require = preg_replace("/([\<\>\=])+/", '',$require);
	if($key == "core" && version_compare($zeigher_version, $require , $compare) == true):
		 continue;
	elseif($key == "core"):
		echo "Error: Zeigher version $zeigher_version is not compatible with the required version $require";
		$error = 1;
		continue;
	endif;
	$dbquery = $db->query("SELECT active FROM plugins WHERE name = '$key' AND active = 1");
	if(file_exists($pluginfolder.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR."info.php")):
		include $pluginfolder.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR."info.php";
		if(version_compare($version, $require, $compare) == false):
			echo "Error: Required plugin $key $version is not compatible with $key $compare$require";
			$error = 1;
		elseif($dbquery->num_rows != 1):
echo $dbquery->num_rows;
			echo "Error: Required plugin $key $compare$require is not installed";
                        $error = 1;
		endif;
	else:
		echo "Error: Required plugin $key $compare$require is not present";
		$error = 1;
	endif;
endforeach;
if($error == 1) exit;
$db->query("UPDATE plugins SET active = $check WHERE name = '$name'");
if($check == 'true' && file_exists($longpfolder."install.php")):
        include $longpfolder."install.php";
elseif($check == 'false' && file_exists($longpfolder."uninstall.php")):
	include $longpfolder."uninstall.php";
endif;
echo "OK";
