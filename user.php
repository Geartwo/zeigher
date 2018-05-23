<?php
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
?>
