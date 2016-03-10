<?php
echo "readepub('".$yeslo."', Math.ceil(".$fourpack." / res) * res, '".$color."');\">";
$sign = 'ico-book';
echo "
<script>
document.onkeydown = function(event) {
    if (event.keyCode == 37) {
        Book.prevPage();
    }
    if (event.keyCode == 39) {
        Book.nextPage();
    }
    return event.returnValue;
}
</script>
<script src='.data/plugins/epub/epub.min.js'></script><script src='.data/plugins/epub/zip.min.js'></script>
<script>
var Book = ePub('".$yeslo."', { width: 600, height: 800, spreads: false });
Book.renderTo('".$mpf."');
</script>
";
?>
