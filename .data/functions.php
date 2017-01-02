<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.js"></script>
<script src="https://cdn.jsdelivr.net/simplemde/1.8.0/simplemde.min.js"></script>
<?php
if(isset($functionsjsextension)):
    foreach($functionsjsextension as $fjex):
        include($fjex);
    endforeach;
endif;
?>
<script>
playtime = 0;
//Main Feutures
function allowDrop(ev) {
    ev.preventDefault();
}
function drag(ev, file, folder, test) {
    ev.dataTransfer.setData("fitext", file);
    ev.dataTransfer.setData("fotext", folder);
}
function drop(ev, file, folder, id) {
    ev.preventDefault();
    var oldfi = ev.dataTransfer.getData("fitext");
    var oldfo = ev.dataTransfer.getData("fotext");
    SND(oldfi, oldfo, folder+"/"+file, id, '', 'd');
}
function resolution() {
    var w1 = window.innerWidth;
    var h1 = window.innerHeight;
    document.getElementById("innerX").innerHTML = w1;
    document.getElementById("innerY").innerHTML = h1;
 
    var w2 = window.outerWidth;
    var h2 = window.outerHeight;
    document.getElementById("outerX").innerHTML = w2;
    document.getElementById("outerY").innerHTML = h2;

}
function pikern(id, kk) {
    if(streamerfild.style.display == 'block'){
        streamerfile.innerHTML = '<img id="1" onmousedown="return false" style="width: 100%; max-width: 800px; height: auto;" src="ajax.php?x=main&file=newstream.php&watchfile=' + id + '">';
    }
};
function streamcontent(num, kk, folder){
    var hash = location.hash.replace(/^.*?#/, '');
    var pairs = hash.split('&');
    hash = pairs[0];
    if(hash != 'num' + num || newload == true) {
    	newload = false;
    	history.pushState(null, null, '#num' + num);
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                streamertext.innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","ajax.php?x=main&file=voteroom.php&watchfile=" + kk + "&folder=" + folder,true);
        xmlhttp.send();
    } else {
    history.pushState(null, null, ' ');
    }
};
function insertAfter(newNode, referenceNode) {
    if(document.fullscreenElement === null || document.webkitFullscreenElement === null){
    	referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }
}
function kind(num){
    var paused = true;
    if(typeof(video) != 'undefined' && video != null){
	paused = video.paused;
    }
    insertAfter(streamerfild, document.getElementById('num'+num));
    if(paused == false){	
	video.play();
    }
}
window.keyuse = checkKey;
document.onkeydown = window.keyuse;
window.tabnum=0;
function checkKey(e) {
    e = e || window.event;
    if (e.keyCode == '37'|e.keyCode == '412') {
    	window.tabnum=window.tabnum-1;
    }
    else if (e.keyCode == '39'|e.keyCode == '417') {
    	window.tabnum=window.tabnum+1;
    }
}
function streamerCheckKey(e) {
    e = e || window.event;
    var num = window.winfild;
    if (e.keyCode == '37'|e.keyCode == '412') {
        num = num-1;
        document.getElementById('num'+num).click();
    }
    else if (e.keyCode == '39'|e.keyCode == '417') {
        num = num+1;
        document.getElementById('num'+num).click();
    }
}
function streamer(num, kk, mpf, folder, userid){
    if(streamerfild.style.display == 'none' || window.winfild != num){
        num = parseInt(num);
	window.winfild = num;
        streamerfild.style.display = 'block';
        var real = (Math.ceil(window.winfild/window.winres))*window.winres;
	var lastnum =  window.lastnum-1;
	if(real > lastnum){
	    real = lastnum
	}
        if(real != 0){
            kind(real);
	    window.keyuse = streamerCheckKey;
	    document.onkeydown = window.keyuse;
            streamcontent(num, kk, folder);
	}
    }else{
        window.winfild = 0;
        streamerfild.style.display = 'none';
	history.pushState(null, null, ' ');
	streamerfile.innerHTML = '';
	streamertext.innerHTML = '';
	window.keyuse = checkKey;
        document.onkeydown = window.keyuse;
    } 
}
function com(type, oid, mode, comsub){
    var comment = document.getElementById('comment').value;
    xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        location.reload();
                }
        }
                xmlhttp.open("GET","ajax.php?x=main&file=comments.php&comment=" + comment + "&type=" + type + "&oid=" + oid + "&mode=" + mode + "&sub=" + comsub,true);
                xmlhttp.send();
};
function isad(id){
    var value = document.getElementById('isad'+id+'value').value;
    xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            location.reload();
        }
    }
    xmlhttp.open("GET","ajax.php?x=main&file=adminworker.php&sysisad&id=" + id + "&value=" + value,true);
    xmlhttp.send();
};
function mainmore(){
	var mainintro = document.getElementById('mainintro');
        if(mainintro.style.cursor == 's-resize'){
                mainintro.style.cursor = 'n-resize';
                mainintro.style.maxHeight = 'none';
                mainintro.style.whiteSpace = 'normal';
        }else{
                mainintro.style.cursor = 's-resize';
                mainintro.style.maxHeight = '1.3em';
                mainintro.style.whiteSpace = 'nowrap';
        }
}
function comsort(){
var comsort = document.getElementById('comsort');
var allElements = document.getElementsByName("coms");
for (var i = 0, n = allElements.length; i < n; ++i) {
  alert(allElements[i]);
}
};
function full(){
if(document.fullscreenElement === null || document.webkitFullscreenElement === null || document.mozFullScreenElement === null){
if (streamermax.requestFullscreen) {
    streamermax.requestFullscreen();
} else if (streamermax.webkitRequestFullscreen){
    streamermax.webkitRequestFullscreen();
} else if (streamermax.mozRequestFullScreen){
    streamermax.mozRequestFullScreen();
}
streamerfull.innerHTML = 'Min';
}else{
if (document.exitFullscreen) {
    document.exitFullscreen();
} else if (document.webkitExitFullscreen){
    document.webkitExitFullscreen();
} else if (document.mozCancelFullScreen){
    document.mozCancelFullScreen();
}
streamerfull.innerHTML = 'Full';
}
}
function rdit(kk, cc) {
    var sty = document.getElementById(cc); 
    if(sty.style.display == "block"){
        var rd = document.getElementById('rd-' + kk);
        if(rd == null) {
            sty.innerHTML = '<audio id="rd-' + kk + '" controls class="rdio" autoplay src="' + kk + '"></audio>';
            var rd = document.getElementById('rd-' + kk);
        }
        rd.play();
    } else {
        var rd = document.getElementById('rd-' + kk);
        rd.pause();
    } 
};
function readit(kk, cc, color) {
    var sty = document.getElementById(cc); 
    if(sty.style.display == "block"){
    Book = ePub(kk, { width: 600, height: 800, spreads: false });
        sty.innerHTML =  '<a class="buttet ' + color + '" onclick="Book.displayChapter(Book.spinePos - 1, false);">&lArr;</a><a class="buttet ' + color + '" onclick="Book.prevPage();readchange();">&larr;</a><span id="nowpage"></span><span id="page"></span><a class="buttet ' + color + '" onclick="Book.nextPage();readchange();">&rarr;</a><a class="buttet ' + color + '" onclick="Book.nextChapter();">&rArr;</a><div style="background: #FFFFEE; padding: 15px; width: 600px;" id="' + cc + 'b"></div><a class="buttet ' + color + '" onclick="Book.displayChapter(Book.spinePos - 1, false);">&lArr;</a><a class="buttet ' + color + '" onclick="Book.prevPage();">&larr;</a><a class="buttet ' + color + '" onclick="Book.nextPage();">&rarr;</a><a class="buttet ' + color + '" onclick="Book.nextChapter();">&rArr;</a>';
    var bindcc = cc + 'b';
    Book.renderTo(bindcc);
    //document.getElementById("nowpage").innerHTML = Book.spinePos;
    //document.getElementById("page").innerHTML = Book.spine.length;
Book.on('Book:pageChanged', function(location){
    console.log(location.anchorPage, location.pageRange)
});
    } else {
        sty.innerHTML = 'leer';
    } 
};
function readchange() {
//document.getElementById("nowpage").innerHTML = Book.spinePos;
}
var pdfpage = 0;
function seeit(kk, cc, color) {
    var sty = document.getElementById(cc);
    if(sty.style.display == "block"){
    sty.innerHTML = '<a class="buttet ' + color + '" onclick="pdf(\'prev\', \'' + cc + '\', \'' + kk + '\');">&larr;</a><span id="nowpage"></span>/<span id="page"></span><a class="buttet ' + color + '" onclick="pdf(\'next\', \'' + cc + '\', \'' + kk + '\');">&rarr;</a><br><canvas id="' + cc + 'b"></canvas><br><a class="buttet ' + color + '" onclick="pdf(\'prev\', \'' + cc + '\', \'' + kk + '\');">&larr;</a><a class="buttet ' + color + '" onclick="pdf(\'next\', \'' + cc + '\', \'' + kk + '\');">&rarr;</a>';
    var canvas = document.getElementById(cc + 'b');
        pdfpage = 1;
    pdf(1, cc, kk);
    } else {
        sty.innerHTML = 'leer';
    }
};
function pdf(num, cc, kk) {
if (num == 'prev') {
    num = pdfpage - 1;
    if (num < 1) {
      return;
    }
    pdfpage = num;
}
if (num == 'next') {
    num = pdfpage + 1;
    if (num > docpages) {
      return;
    }
    pdfpage = num;
}
if (num == 'next') { pdfpage = pdfpage + 1; num = pdfpage;}
var canvas = document.getElementById(cc + 'b');
        PDFJS.getDocument('.data/stream.php?file='+kk).then(function(pdf) {
    docpages = pdf.numPages;
        pdf.getPage(num).then(function(page) {
    document.getElementById('page').innerHTML = docpages;
    document.getElementById('nowpage').innerHTML = num;
        var scale = 1.5;
        var viewport = page.getViewport(scale);

        var context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        var renderContext = {
          canvasContext: context,
          viewport: viewport
        };
        page.render(renderContext);
        });
        });
}
function ChangeType(kk, cc, file) {
    var sty = document.getElementById(kk)
    if(sty.type == "text"){
        if(sty.value == kk) {
            sty.type = "button";
        } else {
            self.location.href=".?f=" + cc +"&old=" + kk + file + "&new=" + sty.value + file + "";
        }
    } else {
        sty.type = "text"
        sty.select()
        sty.onclick = event.preventDefault()
    }
    window.event.returnValue = false;
}
function SetName(kk, cc, file) {
    var sty = document.getElementById(kk + "z")
    var ok = document.getElementById(kk + "o")
    var no = document.getElementById(kk + "n")
    var vc = document.getElementById(kk + "vc")
    if(vc.type != "hidden") {
        //sty.style.display = 'inline-block';
        vc.type = "hidden";
        ok.className = "ico-edit";
        no.className = "ico-no";
    } else {
        //sty.style.display = 'none';
        vc.type = "";
        ok.className = "ico-no";
        no.className = "ico-ok";
    }
}
function SetNameDel(kk, cc, file, newcc) {
    var sty = document.getElementById(kk + "z")
    var dow = document.getElementById(kk + "d") 
    var ok = document.getElementById(kk + "o")
    var no = document.getElementById(kk + "n")
    var vc = document.getElementById(kk + "vc")
    if(vc.type != "hidden") {
    xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        sty.innerHTML = vc.value;
                        SetName(kk, cc);
                }
        }
        xmlhttp.open("GET","ajax.php?x=main&file=fileworker.php&f="+ cc +"&newf="+ newcc +"&renold="+ kk + file+"&rennew="+ vc.value + file,true);
        xmlhttp.send();
    } else {
        var r = confirm("Willst du die Datei \"" + kk + unescape("\" wirklich L%F6schen?"));
        if (r == true) {
        xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                               location.reload(); 
                        }
                }
                xmlhttp.open("GET","ajax.php?x=main&file=fileworker.php&f="+ cc +"&deldir="+ kk,true);
                xmlhttp.send();
    }
    }
}
function SetDesc (kk) {
        var sty = document.getElementById("intro")
        var ok = document.getElementById("descedit")
        var no = document.getElementById("bintroup")
        var vc = document.getElementById("descbox")
        var bi = document.getElementById("biup")
    if(sty.style.display == "none") {
        sty.style.display = 'inline-block';
        vc.style.display = "none";
        ok.className = "ico-edit";
        no.className = "ico-up";
    bi.style.display = '';
    } else {
        sty.style.display = 'none';
        vc.style.display = "inline-block";
        ok.className = "ico-no";
        no.className = "ico-ok";
    bi.style.display = 'none';
    }
}
function SetDescUploadBack () {
    var sty = document.getElementById("intro")
    if(sty.style.display == "none") {
    //$.ajax({
    //itype: "POST",
    $.post( "index.php", { desc: sty.innerHTML} );
    } else {
    document.getElementById("biupform").submit();   
    }
}
function NF (cc, name) {
xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            location.reload();
                }
        }
        xmlhttp.open("GET","ajax.php?x=main&file=fileworker.php&f="+ cc +"&newfolder="+ name,true);
        xmlhttp.send();
}
function activatePlugin (name) {
var check = document.getElementById("check-" + name).checked;
xmlhttp=new XMLHttpRequest();
if(check == false) {
	window.confirm("Data could be get lost. Are you sure?");
}
xmlhttp.onreadystatechange=function() {
if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	if(xmlhttp.responseText != 'OK'){
		document.getElementById("check-" + name).checked = !check;
	}
	document.getElementById('pluginresponse').innerHTML = xmlhttp.responseText;
}
}
xmlhttp.open("GET","ajax.php?x=main&file=plugin.php&check="+ check +"&name="+ name,true);
xmlhttp.send();
}
function SN (kk, cc, id) {
    var ok = document.getElementById(kk + "o")
    var no = document.getElementById(kk + "n")
    var str = document.getElementById(kk + "r")
    var stk = document.getElementById(kk + "k")
    var stb = document.getElementById(kk + "z")
        var sty = document.getElementById("num"+ id)
    if(str.type != "hidden") {
    stk.draggable=true;
        str.type = "hidden";
    stb.style.display = "inline-block";
        ok.className = "ico-edit";
        no.className = "ico-no";
    } else {
    stk.draggable=false;
        str.type = "";
    stb.style.display = "none";
        ok.className = "ico-no";
        no.className = "ico-ok";
    }
}
function SND (kk, cc, newcc, id, nodir, dr) {
    var sty = document.getElementById("num"+ id)
    var ok = document.getElementById(kk + "o")
    var no = document.getElementById(kk + "n")
    var str = document.getElementById(kk + "r")
    var z = document.getElementById(kk + "z")
    if(str.type != "hidden" || dr=='d') {
    xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            location.reload();
                }
        }
        xmlhttp.open("GET","ajax.php?x=main&file=fileworker.php&f="+ cc +"&newf="+ newcc +"&renold="+ kk +"&rennew="+ str.value,true);
        xmlhttp.send();
    }else{
    if(nodir == 1){
        var r = confirm("Willst du das File \"" + kk + unescape("\" wirklich L%F6schen?"));
        if (r == true) {
                xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                location.reload();
                        }
                }
                xmlhttp.open("GET","ajax.php?x=main&file=fileworker.php&f="+ cc +"&delfile="+ kk,true);
                xmlhttp.send();
    }
        }else{
        var r = confirm("Willst du den Ordner \"" + kk + unescape("\" wirklich L%F6schen?"));
        if (r == true) {
            xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    location.reload();
                }
        }
        xmlhttp.open("GET","ajax.php?x=main&file=fileworker.php&f="+ cc +"&deldir="+ kk,true);
        xmlhttp.send();
    }
    }
    }
}
function UpIt() {
    var sty = document.getElementById('upit')
    sty.className = "upit";
}
// file selection
function FileSelectHandler(e) {

    // cancel event and hover styling
    FileDragHover(e);

    // fetch FileList object
    var files = e.target.files || e.dataTransfer.files;

    // process all File objects
    for (var i = 0, f; f = files[i]; i++) {
        ParseFile(f);
        UploadFile(f);
    }

}
function UploadFile(upid) {
    var fileInput = document.getElementById(upid + 'biup').files;
    var folder = document.getElementById(upid + 'upfolder').value;
    var mode = document.getElementById(upid + 'upmode').value;
    var data = new FormData();
    for (var i = 0, file; file = fileInput[i]; ++i) {
        data.append('fileselect[]', file);
    }
    data.append('mode',mode);
    var xhr = new XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(e) {
              var pc = parseInt(e.loaded / e.total * 100);
              document.getElementById(upid + "upg").value = pc;
        document.getElementById(upid + "percent").innerHTML = pc+'%';
      }, false);
    xhr.onreadystatechange=function() {
        if (xhr.readyState==4 && xhr.status==200) {
            console.log(xhr.responseText) 
                               location.reload(); 
                        }
                }
    xhr.onerror = function(e) {
        alert("onError");
    };
//  xhr.upload.addEventListener("progress", function(e) {
//      var pc = parseInt(100 - (e.loaded / e.total * 100));
//      prog.value = pc;
//  }, false);
//    xhr.onload = function(e) {
//        document.getElementById("prozent").innerHTML = "100%";
//        prog.value = prog.max;
//    };

            // start upload
        xhr.open("POST", 'upload.php?folder='+folder, true);
        xhr.setRequestHeader('Cache-Control','no-cache');
        xhr.send(data);
}
function showFileSize(mompoints) {
    var input, file, point, returner, ok;

    if (!window.FileReader) {
        alert("The file API isn't supported on this browser yet.");
        return;
    }

    input = document.getElementById('filebiup');
    returner = document.getElementById('points');
    if (!input) {
        returner("p", "Um, couldn't find the fileinput element.");
    }
    else if (!input.files) {
        returner("p", "This browser doesn't seem to support the `files` property of file inputs.");
    }
    else if (!input.files[0]) {
        returner("p", "Please select a file before clicking 'Load'");
    }
    else {
        file = input.files[0]; console.log(file);
        point = Math.ceil(file.size/1048576);
        mompoints = mompoints-point;
        if (mompoints > 0) {
            unbut('ok');
            ok = " - Ok";
        } else { 
            ok = " - Zu wenig Punkte" 
        }
        returner.innerHTML = "Es kostet " + point + " Punkt/e<br>Restpunkte: " + mompoints + ok;
    }
}
function unbut(kk) {
    var sty = document.getElementById('upsub');
    if(kk == "off") {
        sty.type = 'hidden';
    } else {
        sty.type = 'submit';
    }
}

function conpro(cp, type, objectid, fid) {
xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
    var udres=xmlhttp.responseText;
    var udsplit=udres.split(" ");
    document.getElementById(fid + 'up').innerHTML=udsplit[0];
        document.getElementById(fid + 'down').innerHTML=udsplit[1];
    }
}
xmlhttp.open("GET","ajax.php?x=main&file=updown.php&updown=" + cp + "&type=" + type + "&objectid=" + objectid,true);
xmlhttp.send();
}
$(function() {
    $("div.bigfolder").lazyload({
        effect : "fadeIn"
    });
});
</script>

<?php
function checkEmailAdress($email_address) {
$s = '/^[A-Z0-9._-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z.]{2,6}$/i';
if(preg_match($s, $email_address))        return true;
return FALSE;
}
function plusalph($filez) {
    $firstChr = $filez[0];
    if ($firstChr != $realfirst) {
        echo "".$firstChr."";
        $realfirst = $firstChr;
    }
    echo "".$firstChr."No";
}
function sendit($usmail) {
    $subject = 'Sie wurden auf '.$url.' freigeschalten';
    $message = 'hello';
    $headers = 'From: ' . $sender . "\r\n" . 'Reply-To: ' . $sender . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    mail($usmail, $subject, $message, $headers);
}

function getLanguage() {

    if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $langs = " ".$_SERVER['HTTP_ACCEPT_LANGUAGE'];
    } else {
    $langs = 'en';
    }

    //verfuegbare Sprachen in Array
    $languages = array('en',
                       'de',
                       'es',
                       'fr');

    //ermitteln der positionen
    foreach($languages as $code) {
        $pos = strpos($langs, $code);
        if(intval($pos) != 0) {
            $position[$code] = intval($pos);
        }
    }

    //standardsprache festlegen = englisch
    $bestLanguage = 'en';

    //pruefen ob uebereinstimmungen vorhanden
    if(!empty($position)) {
        foreach($languages as $code) {
            if(isset($position[$code]) &&
               $position[$code] == min($position)) {
                    $bestLanguage = $code;
            }
        }
    }

    return $bestLanguage;
}

function pic_thumb($image, $target, $max_width, $max_height) {
    $picsize     = getimagesize($image);
    if(($picsize[2]==1)OR($picsize[2]==2)OR($picsize[2]==3)) {
    if($picsize[2] == 1) {
      $src_img     = imagecreatefromgif($image);
    }
    if($picsize[2] == 2) {
      $quality=100;
      $src_img     = imagecreatefromjpeg($image);
    }
    if($picsize[2] == 3) {
      $quality=9;
      $src_img     = imagecreatefrompng($image);
    }
    $src_width   = $picsize[0];
    $src_height  = $picsize[1];
    $skal_vert = $max_height/$src_height;
    $skal_hor = $max_width/$src_width;
    $skal = min($skal_vert, $skal_hor);
    if ($skal > 1) {
     $skal = 1;
    }
    $dest_height = $src_height*$skal;
    $dest_width = $src_width*$skal;
    $dst_img = imagecreatetruecolor($dest_width,$dest_height);
    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
    if($picsize[2] == 1) {
      imagegif($dst_img, "$target");
    }
    if($picsize[2] == 2) {
      imagejpeg($dst_img, "$target", $quality);
    }
    if($picsize[2] == 3) {
      imagepng($dst_img, "$target", $quality);
    }
    }
}
if(!isset($theme)) $theme = "default";
$langcode = getLanguage();
include 'lang/' . $langcode;
if(isset($langextension)){
    foreach($langextension as $laex){
        include($laex);
    }
}
include "themes/".$theme."/variable.php";
echo "<link rel='stylesheet' type='text/css' href='.data/themes/".$theme."/format.css'>";
?>
