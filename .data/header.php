<!DOCTYPE HTML>
<html onload="resolution()">
<head>
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta charset="utf-8" />
	<link rel='stylesheet' type='text/css' href='?watchfile=/.data/themes/<?php echo $settings->theme ?>/format.css'>
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
$folder = ".".$cmsfolder;
$alarm = explode('/', $folder);
$name = ucfirst($_SERVER['HTTP_HOST']);
$url = explode('.', $name);
$url = implode('', array_slice($url, 0, 1));
$parts = explode('/', $cmsfolder);
if($parts[0] == "") unset($parts[0]);
$partz = count($parts);
$f = implode(' ', array_slice($parts, 1, $partz -2));
$lastf = implode('', array_slice($parts, -1, $partz));
$older = implode('/', array_slice($parts, 0, $partz - 1));
$ulastf = htmlentities($lastf);
$siter = $parts;
$bsiter = $parts;
$piczahl = 0;
$picrow = 1;
$dbfree = 2;

//Destroy Session
if(isset ($_GET['log'])){
	$_SESSION['loggedin'] = false;
	session_destroy();
	echo "<script>self.location.href='$cmsfolder'</script>";
}
if(!isset($_SESSION['loggedin'])) $_SESSION['loggedin'] = false;

//Background
$tgif = ".";
$ugif = ".";
$cgif = "";
$bfooter = "";
if (file_exists($tgif ."/.bintro.jpg")){
    $endgif = $ugif;
    if (file_exists($tgif ."/.bintro.html")){
        $bfooter = $lang->backgroundimage.": ".@file_get_contents($tgif .".bintro.html");
        $endpic = $ugif . '/.bintro.jpg';
    } else {
        $bfooter = "";
        $endpic = "$ugif./bintro.jpg";
    }
}
foreach($bsiter as $tsiter) {
    if($tsiter == "") continue;
    $tgif = $tgif . "/" . $tsiter;
    $ugif = $ugif . "/" . rawurlencode ($tsiter);
    if (file_exists($tgif ."/.bintro.jpg")){
        $endgif = $ugif;
        if (file_exists($tgif ."/.bintro.html")){
            $bfooter = $lang->backgroundimage.": ".@file_get_contents($tgif ."/.bintro.html");
        } else {
            $bfooter = "";
        }
	$endpic = substr("$ugif/.bintro.jpg", 1);
	$cgif = $ugif;
    }
}
if (isset($_SESSION['lastpic'])) echo "<div id='pic-old' style='display: inline-block;  z-index: -2; position: fixed; height:100%;width:100%; background: url(?watchfile=".$_SESSION['lastpic'].") no-repeat center center fixed; background-size: cover;'></div>";
if (isset ($endpic)):
	$_SESSION['lastpic'] = $endpic;
	echo "
	<img src='?watchfile=$endpic' id='dummy' style='display:none;' alt='' />
	<style>body{margin: 0; background: white;}</style>
	<div id='pic' style='display: inline-block;  z-index: -1; position: fixed; height:100%;width:100%;display:none; background: no-repeat center center fixed; background-size: cover;'></div>
";
$rnt = "
	<script>
	$('#dummy').load(function() {
    $('#pic').css('background-image','url(?watchfile=$endpic)');
    $('#pic').fadeIn(1000);
	});
	</script>
	";
endif;;

//Real Header
if(empty($settings->stitel)) $settings->stitel = $url;
if(isset($nnout)){
	echo "<style>
	.wrapper { display: none; }
	.meune { display: none; }
	</style>";
}
echo "
<div class='container'>
<nav class='navbar navbar-inverse'>
<div id='tbild' style='transition: max-width 0.5s linear 0s, margin 0.5s linear 0s; float: right; position: absolute; top: 5; right: 5; overflow: hidden; margin: 5px;'></div>
<div class='navbar-header'>";
if(!empty($settings->logo) && $settings->logo=='true'){
	echo "<a href='/' class='navbar-brand'><img src='.settings/".$settings->stitel."'></a>";
}elseif(!empty($settings->stitel)){
	echo "<a class='navbar-brand f$color' href='/'>".$settings->stitel."</a>";
}else{
	echo "<a class='navbar-brand f$color' href='/'>".$hostname."</a>";
}
echo "</div>
<ul class='nav navbar-nav navbar-left'>";
if($_SESSION['loggedin'] == "true"):
    if(isset($headerextension)):
	   foreach($headerextension as $huex):
            include($huex);
	   endforeach;
    endif;
	if(isset($userpremium)):
		$plus = "+";
	else:
		$plus = "";
	endif;
	if($settings->points == 'true') echo "<li $lang->points: $userpoints$plus$userpremium</div>";
	echo "</ul>";
	echo "<div class='dropdown'>
	<div id='menu2'>
	<ul>
	<li class='usr2 f$color'>
	<b><a class='ico-set'>$username</a></b>
	<ul>
	<li class='submenu2 f$color'><b><a href=\"?page=user&d=setting\">".$lang->settings."</a></b></li>
	<li class=\"submenu2 f".$color."\"><b><a href=\"?page=user&d=owndata\">".$lang->owndata."</a></b></li>";
    if($isad('admside')) {
        echo "<li class=\"submenu2\"><b><a href='?page=admin'>".$lang->administration."</a></b></li>";
    }
    if ($edit == 1) { $editn = '<b style="color: green;">'.$lang->on.'</b>'; $editu = 0; } else { $editn = '<b style="color: red;">'.$lang->off.'</b>'; $editu = 1; }
    if($isad('edit')) {
        echo "<li class='submenu2'><b><a href='$cmsfolder&edit=$editu'>$lang->edit $editn</a></b></li>";
    }
    echo "<li class='submenu2 f$color'><b><a href=".preg_replace('/ /', '+', $cmsfolder)."?logoff>$lang->logoff</a></b></li>
    </ul></li></ul></div>
    </div>";
elseif(!isset($spsite)):
    echo "
    <div class='dropdown'>
    <div id='menu2'>
    <ul>
    <li class='usr2 f$color'>
    <b><a class='ico-set' href='$cmsfolder&login'>$lang->login</a></b>
    </li></ul></div></div>
    ";
endif;
echo "
</nav>

<div class=\"wholy\">
";
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
if($mode != 'dmyma' && $cmsfolder != '/'){
    echo "<a href='/'><span class='sites btn $color' ondrop=\"drop(event, '','.','')\" ondragover='allowDrop(event)'>".$lang->home."</span></a>";
    $tgif = "";
    if (isset($spsite)) {
        if (!isset($spsiten)) $spsiten = $spsite;
        $hpsite = htmlentities($spsiten);
        echo "<a href=$spsite.php'><span class='sites btn $color'>$hpsite</span></a>";
    }
    foreach($siter as $tsiter) {
	if($tsiter == "") continue;
        $tgif = $tgif . "/" . $tsiter;
        if ($tsiter[0] == "-") {
            $tsiter = substr($tsiter, 1);
        }
        $tsiter = htmlentities($tsiter);
        echo "<a href='$tgif'><b class='sites btn $color' ondrop=\"drop(event, '','".$tgif."','')\" ondragover='allowDrop(event)'>".$tsiter."</b></a>";
    }
    if (isset($_GET['page'])) {
        echo "<a href='?page=".$_GET['page']."'><span class='sites btn $color'>".$lang->$_GET['page']."</span></a>";
    }
    $mlastf = htmlentities($lastf);
    //echo "<a href='$cmsfolder'><b class='lastsitese btn $color'>$ulastf</b></a>
    //<title>".$mlastf."</title>";
} elseif (isset($spsite)) {
    if (!isset($spsiten)) $spsiten = $spsite;
    $hpsite = htmlentities($spsiten);
    echo "<a href='/'><b class='sites btn $color'>".$lang->home."</b></a><a href=\"". $spsite .".php\"><b class=\"lastsitese btn ".$color."\">".$hpsite."</b></a><title>".htmlentities($spsite)."</title>";
} elseif($mode != 'dmyma') {
    echo "
    <a href='/'><b class='btn $color'>".$lang->home."</b></a>
    <title>".$lang->home."</title>";
    if (isset($_GET['page'])) {
        echo "<a href='?page=".$_GET['page']."'><span class='sites btn $color'>".$lang->$_GET['page']."</span></a>";
    }
}

echo "
</div>
<div id=\"list\">";
?>
