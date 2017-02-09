<?php
//Get a Real Path
function workpath($a){
  if($a == "undefined"):
    $a = ".";
  endif;
  $a = str_replace("%20", " ", $a);
  $b=split("/", $a);
  if($a[0] == "/"):
    $c[]="";
    $b = array_slice($b, 1);
  endif;
  foreach($b as $d):
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
  if($e[0] != "/"):
    $e = "/".$e;
  endif;
  if(file_exists(".".$e) == false):
    $e = false;
  elseif(!is_file(".".$e)):
    if(substr($e, -1) != "/") $e = "$e/";
  endif;
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

function getLanguage() {

    if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $langs = " ".$_SERVER['HTTP_ACCEPT_LANGUAGE'];
    } else {
    $langs = 'en';
    }

    //verfuegbare Sprachen in Array
    $languages = array('en',
                       'de',
                       'es',
                       'fr');

    //ermitteln der positionen
    foreach($languages as $code) {
        $pos = strpos($langs, $code);
        if(intval($pos) != 0) {
            $position[$code] = intval($pos);
        }
    }

    //standardsprache festlegen = englisch
    $bestLanguage = 'en';

    //pruefen ob uebereinstimmungen vorhanden
    if(!empty($position)) {
        foreach($languages as $code) {
            if(isset($position[$code]) &&
               $position[$code] == min($position)) {
                    $bestLanguage = $code;
            }
        }
    }

    return $bestLanguage;
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
//Extension class
class ExtendClass
{
    public function __call($method, $args)
    {
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }
}
//Define all classes
$hook = new ExtendClass();
$hook->get_folder = function($a){
        return ".plugins".DIRECTORY_SEPARATOR.$a.DIRECTORY_SEPARATOR;
};
$hook->get_info = function($a, $b){
        include get_folder($a)."info.php";
};
$hook->install = function($a){
	global $db;
        include $hook->get_folder($a)."info.php";
	$plugversion = $version;
	foreach($requirements as $key => $require):
        	$compare = preg_replace("/([0-9\.])+/", '',$require);
        	$require = preg_replace("/([\<\>\=])+/", '',$require);
        	if($key == "core" && version_compare($zeigher_version, $require , $compare) == true):
                	 continue;
        	elseif($key == "core"):
                	echo "Error: Zeigher version $zeigher_version is not compatible with the required version $require";
                	$error = 1;
                	continue;
        	endif;
		$dbkey = $db->real_escape_string($key);
        	$dbquery = $db->query("SELECT active FROM plugins WHERE name = '$dbkey' AND active = 1");
        	if(file_exists($hook->get_folder($key)."info.php")):
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
        $name = $db->real_escape_string($a);
        $db->query("UPDATE plugins SET active = false WHERE name = '$name'");
        include $hook->get_folder($a)."install.php";
};
$hook->uninstall = function($a){
	global $db;
        $name = $db->real_escape_string($a);
        $db->query("UPDATE plugins SET active = false WHERE name = '$name'");
        include $this->get_folder($a)."uninstall.php";
};

$page = new ExtendClass();
$page->admin = function(){
	global $db, $isad, $lang, $color, $hook, $adminextension;
	include '.data/admin.php';
};
$page->usersettings = function(){
        global $db, $color, $isad, $lang, $userextension;
        include '.data/user.php';
};
$fileextension = new ExtendClass();
$icon = new ExtendClass();
$api = new ExtendClass();
//Set Language
if(!isset($theme)) $theme = "default";
$langcode = getLanguage();
include 'lang/' . $langcode;
if(isset($langextension)){
    foreach($langextension as $laex){
        include($laex);
    }
}
include "themes/".$theme."/variable.php";
?>
