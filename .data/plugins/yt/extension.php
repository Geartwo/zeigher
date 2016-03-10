<?php
$rawfile = implode(array_slice(explode('?v=',fgets(fopen($yeslop.".yt","r+"))), 1));
echo "ytit('".$rawfile."', Math.ceil(".$fourpack." / res) * res);\">";
$singlbackground = "https://img.youtube.com/vi/".$rawfile."/sddefault.jpg";
$sign = 'ico-yt';
?>
