<?php
if(isset($_REQUEST['id'])):
	$dbquery = $db->query("SELECT folderid FROM tag_folder WHERE tagid='".escape("id")."'");
	while($row = $dbquery->fetch_assoc()):
		$row = $db->query("SELECT name, id, parentfolderid FROM folder WHERE id='".$row['folderid']."'")->fetch_assoc();	
		$name = $row['name'];
		$foldersearch[] = $row['name'];
		while($row['parentfolderid'] != 1):
			$row = $db->query("SELECT * FROM folder WHERE id='".$row['parentfolderid']."'")->fetch_assoc();
			$foldersearch[] = $row['name'];
		endwhile;
		$foldersearch[] = "";
		$folder = implode(array_reverse($foldersearch), "/");
		if(file_exists(".$folder/.pic_.bintro.jpg.jpg")) $endthumb = "$folder/.pic_.bintro.jpg.jpg";
		echo "<a draggable='false' class='buo ord' value='$name'  href='$folder/'>
                <div class='bigfolder $color-2' id='".$name."k' draggable='true' style=\"background: url('?watchfile=$endthumb') no-repeat; background-size: 100% 100%;\">";
		echo "<font class='bigback'>";
		echo icon("folder.svg");
                echo " $name</font></div></a>";
		unset($foldersearch);
	endwhile;
	echo "<script>
	document.getElementsByClassName('btn-group')[0].innerHTML += \"<a href='?page=".$_REQUEST['page']."&id=".$_REQUEST['id']."'><span class='sites btn $color'>".$db->query("SELECT name FROM tag_name WHERE id='".escape("id")."'")->fetch_object()->name."</span></a>\";
	</script>";
elseif(isset($_REQUEST['tag'])):
	$db->query("INSERT INTO tag_folder (tagid, folderid) VALUES ('".escape("tag")."', '$lastfolderid')");
	echo "<script>location.href='.'</script>";
elseif(isset($_REQUEST['untag'])):
	$db->query("DELETE FROM tag_folder WHERE tagid = '".escape("untag")."' AND folderid = '$lastfolderid'");
	echo "<script>location.href='.'</script>";
else:
	if(isset($_REQUEST['new'])):
		$db->query("INSERT INTO tag_name (name) VALUES ('".escape("new")."')");
	elseif(isset($_REQUEST['delete']) && $isad("edit")):
		$db->query("DELETE FROM tag_name WHERE id = '".escape("delete")."'");
		$db->query("DELETE FROM tag_folder WHERE tagid = '".escape("delete")."'");
	endif;
	$dbquery = $db->query("SELECT * FROM tag_name");
	while($row = $dbquery->fetch_assoc()):
		echo "<a class='btn $color' href='?page=tag&id=".$row['id']."'>".$row['name']."</a>";
		if($isad("edit")):
			echo "<a class='btn $color' href='?page=tag&delete=".$row['id']."'>";
			echo icon("trash.svg");
			echo "</a>";
		endif;
		echo "<br>";
	endwhile;
	echo "<form action='?page=tag' method='post'>
	<br><input name='new'>
	<br><input type='submit' class='btn $color' value='".$lang->newtag."' />
	</form>";
endif;
?>
