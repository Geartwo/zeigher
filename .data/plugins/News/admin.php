<?php

if($folder != "news" && $isad >= 2) {
    echo "<input type=\"submit\" class=\"buttet\" value=\"News\" onclick=\"self.location.href='?ordner=news'\" /><br>";
}  elseif ($isad >= 2) {
    echo "<input type=\"submit\" class=\"buttet\" value=\"News\" onclick=\"self.location.href='admin.php'\" /><br>";
    echo "
    <script type=\"text/javascript\" src=\"data/ckeditor/ckeditor.js\"></script>
    <form action=\"admin.php\" method=\"post\">
    <label for=\"admin.php\"></label>
    <textarea  class=\"ckeditor\" cols=\"80\" rows=\"10\" name=\"news\" id=\"neditor1\" >
    ";
    if (file_exists("news.txt")){
        $datei=fopen("news.txt","r+");
        while(!feof($datei)) { 
            $zeile = fgets($datei,1000); 
            echo $zeile; 
        }
        fclose($datei);
    }
    echo "
    </textarea>
    <input type=\"submit\" value=\"&raquo; Speichern\"/>
    </form>
    ";
}

if (isset($_POST["news"])) {
    $postArray = $_POST["editor"];
    $datei_handle=fopen("news.txt","w"); 
            fwrite($datei_handle,$postArray); 
    fclose($datei_handle);
    echo "Die News wurden Aktualisiert.";
}
        
?>