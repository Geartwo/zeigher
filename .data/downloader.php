<?php
session_start();
set_time_limit(0);
if ($_SESSION['loggedin'] == false && $use == 'none') {
    echo "<script>self.location.href='..'</script>";
    exit();
}
$file = $_GET['file'];
$yeslo = explode('/', $file);
$yeslo = implode('', array_slice($yeslo, -1));
header ('Content-type: octet/stream');
header ('Content-disposition: attachment; filename='.$yeslo.';');
header('Content-Length: '.filesize($file));
readfile($file);
exit;
?>