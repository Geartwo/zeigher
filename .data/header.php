<head>
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta charset="utf-8" />
</head>
<html onload="resolution()">
<link rel="icon" type="image/vnd.microsoft.icon" href=".settings/favicon.ico">
<?php
error_reporting();
$isad=0;
include 'all.php';
include 'functions.php';
$regist='';
if (isset($_GET['login'])) $use = 'none';
set_time_limit(0);
$hostname = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['PHP_SELF']);
if (isset($https)) $https = "https"; else $https = "http";
if(isset ($_GET['f'])) $folder = $_GET['f'];
if(!isset($folder)) {$folder ='.';}
$alarm = explode('/', $folder);
$aleartred = in_array('..', $alarm);
$name = ucfirst($_SERVER['HTTP_HOST']);
$url = explode('.', $name);
$url = implode('', array_slice($url, 0, 1));
$parts = explode('/', $folder);
$partz = count($parts);
$f = implode(' ', array_slice($parts, 1, $partz -2));
$lastf = implode('', array_slice($parts, -1, $partz));
$older = implode('/', array_slice($parts, 0, $partz - 1));
$ulastf = htmlentities($lastf);
$siter = array_slice($parts, 1, $partz -2);
$bsiter = array_slice($parts, 1, $partz -1);
$piczahl = 0;
$picrow = 1;
$dbfree = 2;
//Destroy Session
if(isset ($_GET['log'])){
	$_SESSION['loggedin'] = false;
	session_destroy();
	echo "<script>self.location.href='?f=".$folder."'</script>";
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
        $bfooter = $lang->backgroundimage.": ".@file_get_contents($tgif ."/.bintro.html");
        $endpic = $ugif . '/.bintro.jpg';
    } else {
        $bfooter = "";
        $endpic = $ugif . '/.bintro.jpg';
    }
}
foreach($bsiter as $tsiter) {
    $tgif = $tgif . "/" . $tsiter;
    $ugif = $ugif . "/" . rawurlencode ($tsiter);
    if (file_exists($tgif ."/.bintro.jpg")){
        $endgif = $ugif;
        if (file_exists($tgif ."/.bintro.html")){
            $bfooter = $lang->backgroundimage.": ".@file_get_contents($tgif ."/.bintro.html");
        } else {
            $bfooter = "";
        }
	$endpic = $ugif . '/.bintro.jpg';
	$cgif = $ugif;
    }
}
if (isset($_SESSION['lastpic'])) echo "<div id='pic-old' style='display: inline-block;  z-index: -2; position: fixed; height:100%;width:100%; background: url(".$_SESSION['lastpic'].") no-repeat center center fixed; background-size: cover;'></div>";
if (isset ($endgif)) {
	echo "
	<img src='".$endgif."/.bintro.jpg' id='dummy' style='display:none;' alt='' />
	<style>body{margin: 0; background: white;}</style>
	<div id='pic' style='display: inline-block;  z-index: -1; position: fixed; height:100%;width:100%;display:none; background: no-repeat center center fixed; background-size: cover;'></div>
	<script>
	$('#dummy').load(function() {
    $('#pic').css('background-image','url(".$endpic.")');
    $('#pic').fadeIn(1000);
	});
	</script>
	";
	$_SESSION['lastpic'] = $endpic;
}

//Real Header
if(empty($settings->stitel)) $settings->stitel = $url;
if(isset($nnout)){
	echo "<style>
	.wrapper { display: none; }
	.meune { display: none; }
	</style>";
}
echo "
<div class='whole'>
<div class=\"wrapper\"";
echo "><div class=\"logoe\">";
if(!empty($settings->logo) && $settings->logo=='true'){
	echo "<a href='.'><img class=\"logof\" src='.settings/".$settings->stitel."'></a>";
}elseif(!empty($settings->stitel)){
	echo "<a class=\"logof f".$color."\" href='.'>".$settings->stitel."</a>";
}else{
	echo "<a class=\"logof f".$color."\" href='.'>".$hostname."</a>";
}
if($_SESSION['loggedin'] == "true"){

    if(isset($headerextension)){
	   foreach($headerextension as $huex){
            include($huex);
	   }
    }
	if(isset($userpremium)) {$plus = "+";} else {$plus = "";}
	if($settings->points == 'true') echo "<div class=\"logos f".$color."\" onclick=\"\">".$lang->points.": ".$userpoints.$plus.$userpremium."</div>";
	echo "<div class=\"menuer\">
	<div id=\"menu2\">
	<ul>
	<li class=\"usr2 f".$color."\">
	<b><a class=\"ico-set\">".$username."</a></b>
	<ul>
	<li class=\"submenu2 f".$color."\"><b><a href=\"user.php?f=setting\">".$lang->settings."</a></b></li>
	<li class=\"submenu2 f".$color."\"><b><a href=\"user.php?f=owndata\">".$lang->owndata."</a></b></li>";
    if(isset($isad) && $isad > 0) {
        echo "<li class=\"submenu2\"><b><a href=\"admin.php\">".$lang->administration."</a></b></li>";
    }
    if ($edit == 1) { $editn = '<b style="color: green;">'.$lang->on.'</b>'; $editu = 0; } else { $editn = '<b style="color: red;">'.$lang->off.'</b>'; $editu = 1; }
    if(isset($isad) && $isad >= 3) {
        echo "<li class=\"submenu2\"><b><a href=\".?f=".$folder."&edit=" . $editu . "\">".$lang->edit." " . $editn . "</a></b></li>";
    }
    echo "<li class=\"submenu2 f".$color."\"><b><a href=\".?log=".$folder."\">".$lang->logoff."</a></b></li>
    </ul></li></ul></div>
    </div>";
} elseif (!isset($spsite)) {
    echo "
    <div class=\"menuer\">
    <div id=\"menu2\">
    <ul>
    <li class=\"usr2 f".$color."\">
    <b><a class=\"ico-set\" href='.?f=".$folder."&login'>" . $lang->login . "</a></b>
    </li></ul></div></div>
    ";
}
echo "
</div class=\"logoe\">
</div class=\"wrapper\">

<div class=\"wholy clear\">
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
<div class=\"main\">
<div class=\"meune\">";
if($mode != 'dmyma' && $folder != '.'){
    echo "<a href=\".\"><b class=\"sites buttet ".$color."\" ondrop=\"drop(event, '','.','')\" ondragover='allowDrop(event)'>".$lang->home."</b></a>";
    $tgif = ".";
    if (isset($spsite)) {
        if (!isset($spsiten)) $spsiten = $spsite;
        $hpsite = htmlentities($spsiten);
        echo "<a href=\"". $spsite .".php\"><b class=\"sites buttet ".$color."\">".$hpsite."</b></a>";
    }
    foreach($siter as $tsiter) {
        $tgif = $tgif . "/" . $tsiter;
        if ($tsiter[0] == "-") {
            $tsiter = substr($tsiter, 1);
        }
        $tsiter = htmlentities($tsiter);
        echo "<a href=\".?f=". $tgif ."\"><b class=\"sites buttet ".$color."\" ondrop=\"drop(event, '','".$tgif."','')\" ondragover='allowDrop(event)'>".$tsiter."</b></a>";
    }
    $mlastf = htmlentities($lastf);
    if ($ulastf[0] == "-") {
        $ulastf = substr($ulastf, 1);
        $mlastf = substr($lastf, 1);
    }
    echo "<a href=\"?f=". $folder ."\"><b class=\"lastsitese buttet ".$color."\">".$ulastf."</b></a>
    <title>".$mlastf."</title>";
} elseif (isset($spsite)) {
    if (!isset($spsiten)) $spsiten = $spsite;
    $hpsite = htmlentities($spsiten);
    echo "<a href=\".\"><b class=\"sites buttet ".$color."\">".$lang->home."</b></a><a href=\"". $spsite .".php\"><b class=\"lastsitese buttet ".$color."\">".$hpsite."</b></a><title>".htmlentities($spsite)."</title>";
} elseif($mode != 'dmyma') {
    echo "
    <a href=\".\"><b class=\"lastsitese buttet ".$color."\">".$lang->home."</b></a>
    <title>".$lang->home."</title>";
}

echo "
</div id=\"meune\">
<div id=\"list\">";
?>
