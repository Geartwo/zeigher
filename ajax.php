<?php
include '.data/all.php';
if(preg_match("/\.\.\z/i", $_GET['x'])) exit;
if(isset($_GET['x']) && isset($_GET['file'])):
	if($_GET['x'] == 'main'):
		include '.data/'.$_GET['file'];
	elseif($_GET['x'] == 'plugin'):
		include '.plugins/'.$_GET['file'];
	elseif($_GET['x'] == 'setting'):
                include '.settings/'.$_GET['file'];
	endif;
endif;
