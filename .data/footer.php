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
<script>
window.winfild = 0;
var res = Math.floor(window.innerWidth / 250);
if (res == 1) res = 2;
if (res >= 4) res = 4;
window.winres = res;
newload = true;
if(window.location.hash) {
    var hash = location.hash.replace(/^.*?#/, '');
    var pairs = hash.split('&');
    hash = pairs[0];
    if(pairs[1]){
        playtime = pairs[1];
    } else {
        playtime = 0;
    }
    document.getElementById(hash).click();
}
window.onresize = function(event){
var res = Math.floor(window.innerWidth / 250);
if (res == 1) res = 2;
if (res >= 4) res = 4;
window.winres = res;
var real = (Math.ceil(window.winfild/window.winres))*window.winres;
if(real != 0){
    kind(real);
}
}
        //$('#dummy').load(function() {
    $('#pic').css('background-image','url("<?php echo $endpic; ?>")');
    $('#pic').fadeIn(2500);
        //});
</script>
<!-- 
Der Kopierschutz obliegt Geartwo 
E-Mail: geartwo@chrometech.at 
-->
