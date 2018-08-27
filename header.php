<!DOCTYPE HTML>
<html onload="resolution()">
<head>
	<meta name=viewport content="width=device-width, initial-scale=1" charset="utf-8" />
	<link rel='stylesheet' type='text/css' href='/zeigher/themes/<?php echo $settings->theme ?>/format.css'>
</head>
<body>
<script>
var res = Math.floor(window.innerWidth / 250);
if (res == 1) res = 2;
if (res >= 4) res = 4;
var four = 0;
window.onload = window.onresize = function(event) {
        var winsize = window.innerWidth-1400;
        if( winsize <= 0){
                tbild.style.right = winsize; 
        }else{
                tbild.style.right = winsize/2;
        }
};
</script>
<?php
$regist='';
if (isset($_GET['login'])) $use = 'none';
set_time_limit(0);
$hostname = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['PHP_SELF']);
if (isset($https)) $https = "https"; else $https = "http";
//Zeigher Settings
if($cmsfolder == false):
	$fourzerofour = true;
	http_response_code (404);
else:
	http_response_code (200);
endif;

//Background
$folderids = allfolderids($cmsfolder);
$bgfolderids = $folderids;
$bgpath = 0;
$i = 0;
while(!$bgpath && $i < 50):
	$bgfolderid = array_pop($bgfolderids);
	if(file_exists("data/backgrounds/$bgfolderid.jpg")) $bgname = "$bgfolderid.jpg";
	if(file_exists("data/backgrounds/$bgfolderid.png")) $bgname = "$bgfolderid.png";
	if(isset($bgname)):
		$bgpath = $mainfolder."zeigher/data/backgrounds/$bgname";
		$bgsmall = $mainfolder."zeigher/data/smalbackground/$bgname";
	endif;
	$i++;
endwhile;

//Background picture thumbnail greator
if($bgpath && !file_exists("data/smalbackground/$bgname")):
        pic_thumb("data/backgrounds/$bgname", "data/smalbackground/$bgname", '238', '150');
endif;

echo "<div id='background' style='display: inline-block;  z-index: -2; position: fixed; height:100%;width:100%; background: url($bgpath) no-repeat center center fixed; background-size: cover;'></div>";

//Real Header
if(empty($settings->stitel)) $settings->stitel = $url;
echo "<div class='container'>
<nav class='navbar navbar-inverse'>
<div id='tbild' style='transition: max-width 0.5s linear 0s, margin 0.5s linear 0s; float: right; position: absolute; top: 5; right: 5; overflow: hidden; margin: 5px;'></div>
<div class='navbar-header'>";
if(!empty($settings->logo) && $settings->logo!=='false'){
#	echo "<a href='/' class='navbar-brand f$color'>";
#	echo icon($settings->logo, "data/");
#	echo "</a>";
#	echo "<a href='/' class='navbar-brand $color'><img class='navbar-brand' src='/zeigher/data/".$settings->logo."'></a>";
	echo "<a href='/' class='navbar-brand'><img class='navbar-brand' src='/zeigher/data/".$settings->logo."'></a>";
}elseif(!empty($settings->stitel)){
	echo "<a class='navbar-brand f$color' href='/'>".$settings->stitel."</a>";
}else{
	echo "<a class='navbar-brand f$color' href='/'>".$hostname."</a>";
}
echo "</div>";
if($_SESSION['loggedin'] == "true"):
	echo "<span id='burger-logo' class='usr2 f$color' onclick='menu();'>";
        echo icon("menu.svg");
        echo "</span>
	<div id='menu3' style='display: none;'>
	<ul>
	<li class='submenu2 f$color'><b><a href=\"?page=tag\"  title='$lang->tag'>".icon('tag.svg')."</a></b></li>
	<li class='submenu2 f$color'><b><a href=\"?page=usersettings\"  title='$lang->settings'>".icon('tools.svg')."</a></b></li>
	<li class=\"submenu2 f".$color."\"><b><a href=\"?page=user&d=owndata\" title='$lang->owndata'>".icon('cloud.svg')."</a></b></li>";
    if($isad('admside')) {
        echo "<li class=\"submenu2 f$color\"><b><a href='?page=admin' title='$lang->admin'>".icon('shield.svg')."</a></b></li>";
    }
    if($edit == 0): $editn = "<b class='f$color'>".icon('eye.svg')."</b>"; $editu = 1; else: $editn = "<b style='color: red;'>".icon('eye-with-line.svg')."</b>"; $editu = 0; endif;
    if($isad('edit')) echo "<li class='submenu2'><b><a href='?edit=$editu' title='$lang->edit'> $editn</a></b></li>";
    echo "<li class='submenu2 f$color'><a href='".preg_replace('/ /', '+', $cmsfolder)."?logoff'  title='$lang->logoff'>".icon('log-out.svg')."</a></li>
    </ul></li></ul></div>";
    $hook->include('header.php');
    echo "</div>";
else:
	echo "<span id='burger-logo' class='usr2 f$color' onclick='location.href=\"?page=login\";'><span id='lock'>".icon("lock.svg")."</span><span id='unlock'>".icon("lock-open.svg")."</span></span>";
endif;
echo "</nav><div class='wholy'>";
if($_SESSION['loggedin'] == true) {
    echo "<div class=\"news\">";
    if (file_exists("news.txt") && isset($news)){
        echo "</a><div class=\"newslist\">";
        $docfile=fopen("news.txt","r+");
        while(!feof($docfile)) { 
            $zeile = fgets($docfile,1000); 
            echo $zeile; 
        }
        fclose($docfile);
        echo "</div>";
    }
    echo "</div>";
}
if (isset($news)) {$news="newson";} else {$news="newsoff";}
echo "
<div class='main'>
<div class='btn-group'>";
echo "<a href='/'><span class='sites btn $color' ondrop=\"drop(event, '','1','')\" ondragover='allowDrop(event)'>".icon("home.svg")."</span></a>";
if($cmsfolder != '/'):
    foreach($folderids as $folderid):
	if($folderid === 1) continue;
        echo "<a href='".folderpath($folderid)."'><span class='sites btn $color' ondrop=\"drop(event, '','$folderid','')\" ondragover='allowDrop(event)'>".foldername($folderid)."</span></a>";
    endforeach;
endif;
echo "<title>$settings->stitel</title>";
if(isset($_GET['page'])) echo "<a href='?page=".$_GET['page']."'><span class='sites btn $color'>".$lang->$_GET['page']."</span></a>";
echo "</div><div id='list'>";
?>
