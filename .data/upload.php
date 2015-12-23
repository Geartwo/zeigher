<?php
include 'all.php';
$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
if(isset($_POST['bintro'])) {
	$bintro = true;
	$rn = '.bintro.jpg';
}elseif(isset($_POST['fb'])) {
        $fb = true;
}else{
        $bintro = false;
}
$folder = "../" . $_GET['folder'] . "/";
$realfolder = $_GET['folder'];
@$text = $db->real_escape_string($_POST['impeditor']);
if ($settings->points == 'true' && $bintro == false && $fb == false) {
	$points = $_POST['points'];
	if ($points > $upload_mb) { 
		echo "Error: File to Big<br><a href='..'>Back</a>";
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
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
	foreach ($_FILES['fileselect']['name'] as $f => $name) {     
	    if ($_FILES['fileselect']['error'][$f] == 4) {
	        continue; // Skip file if any error found
	    }	       
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
	            if(move_uploaded_file($_FILES["fileselect"]["tmp_name"][$f], $folder.$name))
	            $count++; // Number of successfully uploaded file
                       @$mysqltime = date("Y-m-d G:i:s");
                       $db->query("INSERT INTO files (userid, folder, date, name, orfile, description) VALUES ('$userid', '$realfolder', '$mysqltime', '$name', 1, '$text')");
                       $row = $db->query("SELECT id FROM tags WHERE tagname='$name'")->fetch_assoc();
                       $id = $row['id'];
                       $db->query("INSERT INTO tagparents (parent, type, objectid) VALUES ('1', 'file', '$id')");
	        #}
	    }
	}
	if ($bintro == true | $fb==true) echo "<script>self.location.href='..?f=".$realfolder."'</script>";
	echo "<script>self.location.href='../'</script>";
}
