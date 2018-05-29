<?php
if(isset($_REQUEST['x'])):
	switch($_REQUEST['x']):
	case "plugin":
		include "plugin.php";
		break;
	case "fileinfo":
                include "fileinfo.php";
                break;
        endswitch;
endif;
