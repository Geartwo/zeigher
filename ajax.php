<?php
if(isset($_REQUEST['x']) && isset($_REQUEST['file'])):
	if(preg_match("/\.\.\z/i", $_REQUEST['file'])) exit;
	if($_REQUEST['x'] === 'main'):
		include $_REQUEST['file'];
	elseif($_REQUEST['x'] === 'plugin'):
		include 'plugins/'.$_REQUEST['file'];
	elseif($_REQUEST['x'] === 'setting'):
                include $_REQUEST['file'];
	endif;
endif;
