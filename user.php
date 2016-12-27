<?php
$spsite = "user";
$spsiten = "Persöhnliches";
include '.data/all.php';
include '.data/header.php';
if (isset($installed)) {
        $pluginfolder = ".plugins";
        $plugdir = scandir($pluginfolder);
        foreach($plugdir as $pfolder) {
	        if($pfolder[0] == ".") continue;
		$longpfolder = $pluginfolder.DIRECTORY_SEPARATOR.$pfolder.DIRECTORY_SEPARATOR;
                if(file_exists($longpfolder."user.php")) {
                        $userextension[$pfolder] = $longpfolder."user.php";
		}
        }
}
if(isset($userextension)){
        foreach($userextension as $key => $usex){
		echo "<input type='submit' class='buttet ".$color."' value='".$lang->$key."' onclick=\"self.location.href='";
		if($_GET['d'] == $key){
			echo "user.php";
		}else{
			echo "?d=".$key;
		}
		echo "'\" /><br>";
		include $usex;
	}
}

include '.data/footer.php';
?>
