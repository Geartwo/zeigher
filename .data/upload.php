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
if ($fn) {

	// AJAX call
	file_put_contents(
		$folder . $fn,
		file_get_contents('php://input')
	);
	echo "$fn uploaded";
	exit();
	
} else {

	// form submit
	$files = $_FILES['fileselect'];

	foreach ($files['error'] as $id => $err) {
		if ($err == UPLOAD_ERR_OK) {
			$fn = $files['name'][$id];
			if ($bintro == true) $fn = $rn;
			move_uploaded_file(
				$files['tmp_name'][$id],
				$folder . $fn
			);	
			$folder = $_GET['folder'];
			if ($bintro == true | $fb==true) echo "<script>self.location.href='..?f=".$folder."'</script>";
			@$mysqltime = date("Y-m-d G:i:s");
			$eintragen = $db->query("INSERT INTO files (userid, folder, date, name, orfile, description) VALUES ('$userid', '$folder', '$mysqltime', '$fn', 1, '$text')");
			$row = $db->query("SELECT id FROM tags WHERE tagname='$fn'")->fetch_assoc();
			$id = $row['id'];
			$eintragen = $db->query("INSERT INTO tagparents (parent, type, objectid) VALUES ('1', 'file', '$id')");
			echo "<script>self.location.href='../'</script>";
		}
	}

}
