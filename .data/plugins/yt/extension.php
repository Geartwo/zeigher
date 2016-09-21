<?php
$rawfile = implode(array_slice(explode('?v=',fgets(fopen($yeslop.".yt","r"))), 1));
$rawfile = str_replace("\n", "", $rawfile); 
echo "ytit('".$rawfile."', Math.ceil(".$fourpack." / res) * res);\">";
$singlbackground = "https://i.ytimg.com/vi/".$rawfile."/hqdefault.jpg";
#$singlbackground = "https://img.youtube.com/vi/".$rawfile."/sddefault.jpg";
$sign = 'ico-yt';
?>
