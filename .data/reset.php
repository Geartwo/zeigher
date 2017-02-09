<?php
if(isset($_GET['mail'])):
	$mail = $_GET['mail'];
	$dbquery = $db->query("SELECT * FROM user WHERE email = '$mail'");
        $mailnum = $dbquery->num_rows;
	if($settings->pwdreset == "true" && $mailnum == 1):
	elseif($settings->pwdreset == "false"):
		$lang->nopwdreset;
	elseif($mailnum == 0):
		$lang->mailunknown;
	endif;
else:
	echo "E-Mail:<br>
	<input type='email'>";
endif;
?>
