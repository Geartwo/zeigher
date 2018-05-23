<?php
if(isset($_GET['mail'])):
	$dbmail = $db->real_escape_string($_GET['mail']);
	$dbquery = $db->query("SELECT * FROM user WHERE email = '$dbmail'");
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
