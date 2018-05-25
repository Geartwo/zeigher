</div> <!-- class=list -->

<div class="footer clear line">
<a href="?page=imprint"><?php echo $lang->imprint; ?></a>
</div>
<div class="footer">
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.js"></script>
<script src="https://cdn.jsdelivr.net/simplemde/1.8.0/simplemde.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script src="?x=main&file=functions.js&v1.0"></script>
<?php
if(isset($functionsjsextension)):
    foreach($functionsjsextension as $fjex):
        include($fjex);
    endforeach;
endif;
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
    document.getElementById(hash+'-a').click();
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
</script>
<script>
var elements = document.getElementsByTagName('a');
for(var i = 0, len = elements.length; i < len; i++) {
    elements[i].addEventListener("click", function () {
	console.log("Click");
        //event.preventDefault();
	//console.log(elements[i]);
    }, false);
}
</script>
<!-- 
Der Kopierschutz obliegt Geartwo 
E-Mail: geartwo@chrometech.at 
-->
