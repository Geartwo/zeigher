<?php 
if(isset($_GET['watchfile'])) $cmsfolder = workpath($_GET['watchfile']);
if (!isset($_SESSION['loggedin']) && !preg_match('/(\.pic|.bintro)*/', $cmsfolder)):
    exit;
endif;
if (preg_match("/\.pdf/", $cmsfolder)){header("Content-Type: application/pdf");
} elseif (preg_match("/\.css/", $cmsfolder)){header("Content-Type: text/css");
} elseif (preg_match("/\.mp4/", $cmsfolder)){header("Content-Type: video/mp4");
header("X-Accel-Buffering: no");
} elseif (preg_match("/\.mp3/", $cmsfolder)){header("Content-Type: audio/mp3");
} elseif (preg_match("/\.jpg/", $cmsfolder)){header("Content-Type: image/jpeg");
} elseif (preg_match("/\.epub/", $cmsfolder)){header("Content-Type: application/epub+zip");
} elseif (preg_match("/\.svg/", $cmsfolder)){header("Content-Type: image/svg+xml");
}
header("X-Accel-Redirect:$cmsfolder");
header("Cache-Control: max-age=2592000");
header('Access-Control-Allow-Origin: *'); 
exit;
?>
