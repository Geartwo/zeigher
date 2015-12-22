<?php 
session_start();
if (!isset($_SESSION['loggedin'])) {
    exit();
}
$file = $_GET['file'];
$file = substr($file,1);
if (ereg(".pdf", $file)){header("Content-Type: application/pdf");
} elseif (ereg(".mp4", $file)){header("Content-Type: video/mp4");
} elseif (ereg(".mp3", $file)){header("Content-Type: audio/mp3");
}
header("X-Accel-Redirect:".$file);
header("X-Accel-Buffering: no");
?>
