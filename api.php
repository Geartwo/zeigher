<?php
if($_SESSION['loggedin'] == false):
	$return['error'] = true;
	$return['descriptor'] = "unauthenticated";
	return $return = json_encode($return);
	exit;
endif;
header('Content-Type: application/json');
$api->standard = function(){
        $return['error'] = true;
	$return['descriptor'] = "Undefined Command";
	return $return;
};
$api->ping = function(){
	$return['error'] = false;
	$return['descriptor'] = "Page Works";
	return $return;
};
$api->ls = function($cmsfolder){
	$return['error'] = false;
	$dir = scandir(".$cmsfolder");
	foreach($dir as $key):
		if(preg_match("/\.php\z/i", $key) | preg_match("/\.md\z/i", $key) | preg_match("/\.html\z/i", $key) | $key[0] == ".") continue;
		if(is_dir(".$cmsfolder$key")):
			$return['folder'][] = $key;
		else:
			$return['file'][] = $key;
		endif;
	endforeach;
	return $return;
};
echo json_encode($api->$_REQUEST['api']($cmsfolder));
exit;
?>
