<?php
if($_SESSION['loggedin'] == false):
	echo "unauthenticated";
	exit;
endif;
$api->ping = function(){
	return "pong";
};
$api->ls = function($cmsfolder){
	$dir = scandir(".$cmsfolder");
	foreach($dir as $key):
		if($key[0] == ".") continue;
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
