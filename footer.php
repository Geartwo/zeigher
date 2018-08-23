</div> <!-- class=list -->

<div class="footer clear line">
<?php $hook->include('footer.php'); ?>
</div>
<div class="footer">
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script src="/zeigher/js/functions.js"></script>
</div>
</div> <!-- class=main -->
</div> <!-- class=wholy -->
</div> <!-- class=whole -->
<?php $hook->include('function.js'); ?>
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
<!-- 
Der Kopierschutz obliegt Geartwo 
E-Mail: geartwo@chrometech.at 
-->
