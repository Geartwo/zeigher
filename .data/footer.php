</div> <!-- class=list -->

<div class="footer clear line">
<a href="admin.php"
<?php if (isset($noheader) && !isset($_SESSION['loggedin'])) echo " style='display: none;'"; ?>
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
<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>-->
<!--<script src="jquery.lazyload.js"></script>-->
</div> <!-- class=main -->
</div> <!-- class=wholy -->
</div> <!-- class=whole -->
<!-- 
Der Kopierschutz obliegt Geartwo 
E-Mail: geartwo@chrometech.at 
-->
