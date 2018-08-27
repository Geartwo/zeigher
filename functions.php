<?php
//Get a Real Path
function workpath($a, $pre = false){
  if($a === "undefined"):
    $a = ".";
  endif;
  $a = str_replace("%20", " ", $a);
  $b = explode("/", $a);
  $c[]="";
  if($a[0] === "/"):
    $b = array_slice($b, 1);
  endif;
  foreach($b as $d):
    $d = rawurldecode($d);
    switch($d):
    case ".":
      continue;
      break;
    case "..":
      $c = array_slice($c, 0, -1);
      break;
    default:
      $c[] = $d;
    endswitch;
  endforeach;
  $e = implode("/", $c);
  if(empty($e)) $e = "/";
  if($e[0] != "/") $e = "/".$e;
  if(is_dir("..".$e)) if(substr($e, -1) != "/") $e = "$e/";
  if($pre) $e = $pre.$e;
  return $e;
}
function checkEmailAdress($email_address) {
$s = '/^[A-Z0-9._-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z.]{2,6}$/i';
if(preg_match($s, $email_address))        return true;
return FALSE;
}
function plusalph($filez) {
    $firstChr = $filez[0];
    if ($firstChr != $realfirst) {
        echo "".$firstChr."";
        $realfirst = $firstChr;
    }
    echo "".$firstChr."No";
}
function sendit($usmail) {
    $subject = 'Sie wurden auf '.$url.' freigeschalten';
    $message = 'hello';
    $headers = 'From: ' . $sender . "\r\n" . 'Reply-To: ' . $sender . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    mail($usmail, $subject, $message, $headers);
}

function background($size, $id) {
    global $mainfolder;
    switch($size):
    case "normal":
	$size = "backgrounds";
	break;
    case "small":
	$size = "smalbackground";
    	break;
    case "filethumb":
        $size = "filethumb";
        break;
    endswitch;
    if(file_exists("data/$size/$id.jpg")) $bgname = "$id.jpg";
    if(file_exists("data/$size/$id.png")) $bgname = "$id.png";
    if(isset($bgname)):
        return $mainfolder."zeigher/data/$size/$bgname";
    else:
	return false;
    endif;
}
//Thumbnail Creator
function pic_thumb($image, $target, $max_width, $max_height) {
    $picsize     = getimagesize($image);
    if(($picsize[2]==1)OR($picsize[2]==2)OR($picsize[2]==3)) {
    if($picsize[2] == 1) {
      $src_img     = imagecreatefromgif($image);
    }
    if($picsize[2] == 2) {
      $quality=100;
      $src_img     = imagecreatefromjpeg($image);
    }
    if($picsize[2] == 3) {
      $quality=9;
      $src_img     = imagecreatefrompng($image);
    }
    $src_width   = $picsize[0];
    $src_height  = $picsize[1];
    $skal_vert = $max_height/$src_height;
    $skal_hor = $max_width/$src_width;
    $skal = min($skal_vert, $skal_hor);
    if ($skal > 1) {
     $skal = 1;
    }
    $dest_height = $src_height*$skal;
    $dest_width = $src_width*$skal;
    $dst_img = imagecreatetruecolor($dest_width,$dest_height);
    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
    if($picsize[2] == 1) {
      imagegif($dst_img, "$target");
    }
    if($picsize[2] == 2) {
      imagejpeg($dst_img, "$target", $quality);
    }
    if($picsize[2] == 3) {
      imagepng($dst_img, "$target", $quality);
    }
    }
}


//Mysqli simple POST Escape
function escape($req, $key=false){
	global $_REQUEST, $db;
	if($key): return $db->real_escape_string($req);
	else: return $db->real_escape_string($_REQUEST[$req]);
	endif;
}

//Icon Function
function icon($svg, $path = false){
        global $settings;
        if(!$path) $path = "themes/".$settings->theme."/icons/";
        return file_get_contents($path.$svg);
}

//Get Folderpath by ID
function folderpath($id){
	if(empty($id)):
		trigger_error("ERROR: No ID set");
		return false;
	endif;
	global $db;
	$dbquerry = $db->query("SELECT name, parentfolderid FROM folder WHERE id='$id'")->fetch_object()
	or die("Unknown folderId $id");
        $foldersearch[] = $dbquerry->name;
	$i = 0;
        while($dbquerry->parentfolderid != 1 && $i <= 50):
        	$dbquerry = $db->query("SELECT name, parentfolderid FROM folder WHERE id='$dbquerry->parentfolderid'")->fetch_object()
		or trigger_error("ERROR: Path fail");
                $foldersearch[] = $dbquerry->name;
		$i++;
       endwhile;
       $foldersearch[] = "";
       return implode(array_reverse($foldersearch), "/");
}
//Get ID by Folderpatch
function allfolderids($path){
	global $db;
	$path_array = explode("/", $path);
        if($path[-1] == "/") array_pop($path_array);
        foreach($path_array as $folder):
                if($folder == ""):
                        $folderid = 1;
                else:
                        $dbFolder = $db->real_escape_string(rawurldecode($folder));
                        $folderid = $db->query("SELECT id FROM folder WHERE name = '$dbFolder' AND parentfolderid = '$folderid'")->fetch_object()->id
			or trigger_error("ERROR: Path noth found $path");
                endif;
		$folderids[] = $folderid;
        endforeach;
	return $folderids;
}
function folderid($path){
	$all = allfolderids($path);
	return array_pop($all);
}
function childid($parentid, $name, $type, $create=false){
	global $db;
	$dbName = $db->real_escape_string(rawurldecode($name));
	if($type !== "file" && $type !== "folder"):
		trigger_error("ERROR: Not supported filetype: $type");
		return false;
	endif;
	$id = $db->query("SELECT id FROM $type WHERE name = '$dbName' AND parentfolderid = '$parentid'");
	if($id->num_rows === 0 && $create === true):
		$db->query("INSERT INTO $type (name, parentfolderid) VALUES ('$dbName', '$parentid')")
        	or trigger_error("ERROR: SQL Initialisation $dbName/$parentid");
		$id = $db->query("SELECT id FROM $type WHERE name = '$dbName' AND parentfolderid = '$parentid'");
	endif;
	if($id->num_rows === 1):
		return $id->fetch_object()->id;
	else:
		trigger_error("ERROR: SQL ID $dbName/$parentid");
		return false;
	endif;
}

function foldername($id){
	global $db;
	if(empty($id)):
		return false;
	endif;
	$folder = $db->query("SELECT name FROM folder WHERE id = '$id'")
	or trigger_error("ERROR: $id not found");
	return $folder->fetch_object()->name;
}
function fileid($parentid, $file, $create=false){
        global $db;
        $dbFolder = $db->real_escape_string(rawurldecode($folder));
        $folderid = $db->query("SELECT id FROM folder WHERE name = '$dbFolder' AND parentfolderid = '$parentid'");
        if($folderid->num_rows === 0 && $create === true):
                $db->query("INSERT INTO folder (name, parentfolderid) VALUES ('$dbFolder', '$parentid')")
                or trigger_error("ERROR: SQL Folder Initialisation $dbFolder/$parentid");
                $folderid = $db->query("SELECT id FROM folder WHERE name = '$dbFolder' AND parentfolderid = '$parentid'");
        endif;
        if($folderid->num_rows === 1):
                return $folderid->fetch_object()->id;
        else:
                return false;
        endif;
}
//Define all classes
$hook = new ExtendClass();
$hook->get_folder = function($a){
        return "plugins".DIRECTORY_SEPARATOR.$a.DIRECTORY_SEPARATOR;
};
$hook->get_info = function($a, $b){
	global $hook;
	if(file_exists($hook->get_folder($a)."info.php")):
        	include $hook->get_folder($a)."info.php";
		if(isset($$b)) return $$b;
	endif;
};
$hook->install = function($a){
	global $db, $hook, $zeigher_version;
        include $hook->get_folder($a)."info.php";
	$plugversion = $version;
	foreach($requirements as $key => $require):
        	$compare = preg_replace("/([0-9\.])+/", '',$require);
        	$require = preg_replace("/([\<\>\=])+/", '',$require);
        	if($key == "core" && version_compare($zeigher_version, $require , $compare) == true):
                	 continue;
        	elseif($key == "core"):
                	echo "Error: Zeigher version $zeigher_version is not compatible with the required version $require";
                	exit;
        	endif;
        	if(file_exists($hook->get_folder($key)."info.php")):
			$dbkey = escape($key, true);
        		$dbquery = $db->query("SELECT active FROM plugins WHERE name = '$dbkey' AND active = 1");
                	include $hook->get_folder($key)."info.php";
                	if(version_compare($version, $require, $compare) == false):
                        	echo "Error: Required plugin $key $version is not compatible with $key $compare$require";
                        	$error = 1;
                	elseif($dbquery->num_rows != 1):
                        	echo "Error: Required plugin $key $compare$require is not installed";
                        	$error = 1;
                	endif;
        	else:
                	echo "Error: Required plugin $key $compare$require is not present";
                	$error = 1;
        	endif;
	endforeach;
	if(isset($error)) exit;
        $name = escape($a, true);
        $db->query("UPDATE plugins SET active = 1 WHERE name = '$name'");
        include $hook->get_folder($a)."install.php";
	echo "OK";
};
$hook->uninstall = function($a){
	global $db, $hook;
        $name = $db->real_escape_string($a);
        $db->query("UPDATE plugins SET active = 0 WHERE name = '$name'");
        include $hook->get_folder($a)."uninstall.php";
	echo "OK";
};
$hook->include = function($a, $b = false){
	global $db, $hook;
	if(is_array($b)):
		foreach($b as $b_var):
			global $$b_var;
		endforeach;
	endif;
	$dbquery = $db->query("SELECT name FROM plugins WHERE active = 1");
	while($i = $dbquery->fetch_object()):
		if(file_exists($hook->get_folder($i->name).$a)):
                	include $hook->get_folder($i->name).$a;
        	endif;
	endwhile;
};

function fileright($file){
	return true;
}

//Page register
$page = new ExtendClass();
$page->admin = function(){
	global $db, $isad, $lang, $color, $hook, $adminextension;
	include 'admin.php';
};
$page->usersettings = function(){
        global $db, $color, $isad, $lang, $userextension;
        include 'user.php';
};
$page->login = function(){
        global $db, $color, $lang, $settings, $_POST;
        include 'login.php';
};
$page->register = function(){
	global $db, $color, $lang, $settings, $_POST;
        include 'register.php';
};
$page->reset = function(){
	global $db, $color, $lang, $settings, $_POST;
        include 'reset.php';
};
$page->tag = function(){
        global $db, $isad, $color, $lang, $lastfolderid, $_POST;
	if($_SESSION['loggedin']) include 'tag.php';
};

$fileextension = new ExtendClass();
$fileextension->standard = function(){
};
$icon = new ExtendStdClass();
$icon->standard = "bug.svg";

//Set mimetypes
$mimetype = new ExtendStdClass();

$uploadcheck = new ExtendStdClass();
$api = new ExtendClass();
//Set Language
if(!isset($theme)) $theme = "default";
include "themes/".$theme."/variable.php";
?>
