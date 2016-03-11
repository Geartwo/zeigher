</div> <!-- class=list -->

<div class="footer clear line">
<a href="admin.php"
><?php echo $lang->imprint; ?></a>
</div>
<div class="footer">
<?php
echo $bfooter;
if(isset($footerextension)){
	foreach($footerextension as $foex){
        include($foex);
	}
}
?>
</div>
</div> <!-- class=main -->
</div> <!-- class=wholy -->
</div> <!-- class=whole -->
<!-- 
Der Kopierschutz obliegt Geartwo 
E-Mail: geartwo@chrometech.at 
-->
