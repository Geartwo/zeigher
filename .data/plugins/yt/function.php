<script>
function ytit(kk, cc) {
    var sty = document.getElementById(cc);
    if(sty.style.display == "block"){
        sty.innerHTML = '<iframe width="400" height="255" src="https://www.youtube-nocookie.com/embed/' + kk +'?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>';
    } else {
        sty.innerHTML = 'leer';
    }
};
</script>
