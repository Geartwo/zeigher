<?php 
//if (!isset($_SESSION['loggedin'])) {
//    exit();
//}
$file = urldecode($_GET['watchfile']);
$file = substr($file,1);
if (ereg(".pdf", $file)){header("Content-Type: application/pdf");
} elseif (ereg(".mp4", $file)){header("Content-Type: video/mp4");
} elseif (ereg(".mp3", $file)){header("Content-Type: audio/mp3");
} elseif (ereg(".jpg", $file)){header("Content-Type: image/jpeg");
} elseif (ereg(".epub", $file)){header("Content-Type: application/epub+zip");
}
header("X-Accel-Redirect:".$file);
header("X-Accel-Buffering: no");
?>
