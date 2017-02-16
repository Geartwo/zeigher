<?php
if($_SESSION['loggedin'] == false):
	echo "unauthenticated";
	exit;
endif;
$api->standard = function(){
        return "undefined_command";
};
$api->ping = function(){
	return "pong";
};
$api->ls = function($cmsfolder){
	$dir = scandir(".$cmsfolder");
	foreach($dir as $key):
		if(preg_match("/\.php\z/i", $file) | preg_match("/\.md\z/i", $file) | preg_match("/\.html\z/i", $file) | $key[0] == ".") continue;
		if(is_dir(".$cmsfolder$key")):
			$return['folder'][] = $key;
		else:
			$return['file'][] = $key;
		endif;
	endforeach;
	$return = json_encode($return);
        return $return;
};
$api->show = function($cmsfolder){
	include '.data/streamer.php';
};
?>
