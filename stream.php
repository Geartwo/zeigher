<?php
if(isset($_GET['watchfile'])):
	$file = workpath($_GET['watchfile']);
else:
	$file = $cmsfolder;
endif;
if(is_file("..$file")):
	$filenametype = array_pop(explode(".", $file));
	if($mimetype->$filenametype === mime_content_type("..$file")):
		$mimeok = 1;
	else:
		switch($mimetype->$filenametype):
		case("text/css"):
			$mimeok = 1;
			break;
		default:
			$mimeok = 0;
		endswitch;
	endif;
	if($mimeok):
		header("Cache-Control: max-age=2592000");
		header("Content-Type: ".$mimetype->$filenametype);
		header("X-Accel-Redirect:$file");
		header('Access-Control-Allow-Origin: *'); 
		exit;
	else:
		$fourzerofour = 1;
	endif;
		
else:
	$fourzerofour = 1;
endif;

echo $mimetype->$filenametype.mime_content_type("..$file");
exit;
?>
