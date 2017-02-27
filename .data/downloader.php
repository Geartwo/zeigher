<?php
if ($_SESSION['loggedin'] == false && $use == 'none') {
    exit();
}
if($_GET['type'] == 'zip'):
	$zip = new ZipArchive();
	$filename = $_GET['downfile'].'/.folder.zip';
	if ($zip->open($filename, ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE)!==TRUE):
		exit("cannot open <$filename>\n");
	endif;
	$zip->addFile($_GET['downfile'].'/*', 'Test.zip');
	$zip->close();
	$file = $_GET['downfile'].'/.folder.zip';
	$yeslo = explode('/', $_GET['downfile']);
	$yeslo = implode('', array_slice($yeslo, -1)).'.zip';
else:
	$file = workpath($_GET['downfile']);
	$yeslo = explode('/', $file);
	$yeslo = implode('', array_slice($yeslo, -1));
endif;
$yeslo = str_replace(',', '', $yeslo);
if($_GET['type'] == ''):
	header ('Content-type: octet/stream');
elseif($_GET['type'] == 'zip'):
	header("Content-Type: application/zip");
endif;
header('Content-disposition: attachment; filename='.$yeslo.';');
header('Content-Length: '.filesize(".$file"));
readfile(".$file");
exit;
?>
