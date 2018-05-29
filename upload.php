<?php
//if($_SESSION['logedin'] != true) exit;
if(isset($_GET['check'])):
	echo "Test";
	exit;
endif;
$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
if($_POST['mode'] == "bintro") {
	$bintro = true;
	$rn = '.bintro.jpg';
}elseif($_POST['mode'] == "fb") {
        $fb = true;
}
$folder = $realfolder;
@$text = $db->real_escape_string($_POST['impeditor']);
if(!empty($_FILES['fileselect'])){
	foreach ($_FILES['fileselect']['name'] as $f => $name) {
	    if(isset($_POST['bintro'])){
		$name = ".bintro.jpg";
		unlink(".pic.bintro.jpg.jpg");
	    }
	    if ($_FILES['fileselect']['error'][$f] == 4) {
	        continue; // Skip file if any error found
	    }
	    $finfo = new finfo(FILEINFO_MIME_TYPE);
if(false):
	    if (false === $ext = array_search($finfo->file($_FILES['fileselect']['tmp_name'][$f]),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'mp3' => 'audio/mpeg',
            'pdf' => 'application/pdf',
	    'epub' => 'application/epub',
	    'zip' => 'application/zip',
	    'zip' => 'application/octet-stream',
	    '7z' => 'application/x-7z-compressed',
	    'iso' => 'application/octet-stream',
	    'iso' => 'application/x-zip-compressed',
	    'txt' => 'text/plain',
        ), true)){
        	throw new RuntimeException('Invalid file format.');
    	    }
endif;
	    if ($_FILES['fileselect']['error'][$f] == 0) {			
	        #if ($_FILES['fileselect']['size'][$f] > $max_file_size) {
	        #    $message[] = "$name is too large!.";
	        #    continue; // Skip large files
	        #}
			#	elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
			#		$message[] = "$name is not a valid format";
			#		continue; // Skip invalid file formats
			#	}
	        #else{ // No error found! Move uploaded files 
			if ($settings->points == 'true' && !isset($bintro) && !isset($fb)) {
				$points = ceil($_FILES['fileselect']['size'][$f]/1048576);
				if ($points > $upload_mb) { 
					echo "Error: File to Big<br><a href='.'>Back</a>";
					exit;
				} else {
					if ($userpoints < $points) {
						if ($userpoints > 0) {
							$points = $points - $userpoints;
							$userpoints = 0;
						}
						$userpremium = $userpremium - $points;
					} else {
						$userpoints = $userpoints - $points;
					}
					$eintragen = $db->query("UPDATE points SET points = '$userpoints' WHERE type = 'user' AND objectid = '$userid'");
					$eintragen = $db->query("UPDATE user SET premium = '$userpremium' WHERE id = '$userid'");
				}
			}
	        if(move_uploaded_file($_FILES["fileselect"]["tmp_name"][$f], $folder.$name)){
			if (isset($bintro)){
				echo "<script>self.location.href='.?f=".$folder."'</script>";
				exit;
			}
                       @$mysqltime = date("Y-m-d G:i:s");
			$name = $db->real_escape_string($name);
                       $db->query("INSERT INTO files (userid, folder, date, name, orfile, description) VALUES ('$userid', '$folder', '$mysqltime', '$name', 0, '$text')");
                       #$row = $db->query("SELECT id FROM tags WHERE tagname='$name'")->fetch_assoc();
                       #$id = $row['id'];
                       #$db->query("INSERT INTO tagparents (parent, type, objectid) VALUES ('1', 'file', '$id')");
	        }
	    }
	}
	if (isset($fb)){
        	echo "<script>self.location.href='.?f=".$folder."'</script>";
        	exit;
        }
}else{echo"Wrong";}
