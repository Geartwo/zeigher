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
<script src=".data/epub/epub.min.js"></script><script src=".data/epub/libs/zip.min.js"></script>
<?php
echo "
<script>
var Book = ePub('".$yeslo."', { width: 600, height: 800, spreads: false });
Book.renderTo('".$mpf."');
</script>
";
?>
