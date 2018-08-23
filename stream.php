<?php
//Pre Defined Mimetypes
$mimetype->standard = "text/plain";
$mimetype->css = "text/css";


if(isset($_GET['watchfile'])):
	$file = workpath($_GET['watchfile']);
else:
	$file = $cmsfolder;
endif;
if(is_file("..$file") && preg_match("/\/zeigher\/(data|themes|js)\//", $file) | $_SESSION['loggedin']):
	$filenametype = array_pop(explode(".", $file));
	if($mimetype->$filenametype === mime_content_type("..$file") || $mimetype->standard === mime_content_type("..$file")):
		header("Cache-Control: max-age=2592000");
		if(isset($_GET['download'])):
			header ('Content-type: application/force-download');
		else:
			header("X-Accel-Redirect:$file");
                	header("Access-Control-Allow-Origin: *");
			header("Content-Type: ".$mimetype->$filenametype);
		endif;
                exit;
	else:
		error_log("Mimetype not OK - ".$mimetype->$filenametype."/".mime_content_type("..$file")."($file)", 0);
	endif;
endif;
if($_SESSION['loggedin']):
	http_response_code (404);
	$fourzerofour = 1;
endif;
?>
