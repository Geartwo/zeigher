<?php 
if(isset($_GET['watchfile'])) $cmsfolder = workpath($_GET['watchfile']);
if (!isset($_SESSION['loggedin']) && !preg_match('/(\.pic|.bintro)*/', $cmsfolder)):
    exit;
endif;
if (ereg(".pdf", $cmsfolder)){header("Content-Type: application/pdf");
} elseif (ereg(".mp4", $cmsfolder)){header("Content-Type: video/mp4");
header("X-Accel-Buffering: no");
} elseif (ereg(".mp3", $cmsfolder)){header("Content-Type: audio/mp3");
} elseif (ereg(".jpg", $cmsfolder)){header("Content-Type: image/jpeg");
} elseif (ereg(".epub", $cmsfolder)){header("Content-Type: application/epub+zip");
}
header("X-Accel-Redirect:$cmsfolder");
header("Cache-Control: max-age=2592000");
exit;
?>
