<?php				
            					if(isset ($_GET['edit']) && $_GET['edit'] == $yeslop ) {
                                    $edit = $_GET['edit'];
                                    if (isset($_POST["editor"]) && ($isad >= 8)) {
                                        $postArray = utf8_encode($_POST["editor"]);
                                        $datei_handle=fopen($edit.".txt","w"); 
                                        fwrite($datei_handle,$postArray); 
                                        fclose($datei_handle);
                                        echo "<script>self.location.href='?ordner=".$folder."'</script>";
                                    } else {
                                        echo "
                                        <b>".$mpf."</b>
                                        <form method=\"post\">";
                                        if (file_exists($edit.".txt")){
                                            $datei=fopen($edit.".txt","r");
                                            echo "<textarea cols=\"80\" rows=\"10\" name=\"editor\" id=\"editor1\" >";
                                            while(!feof($datei)) { 
                                                $zeile = htmlentities(fgets($datei,1024)); 
                                                echo $zeile; 
                                            }
                                            echo "</textarea>";
                                            fclose($datei);
                                            echo "
                                            <input type=\"submit\" value=\"&raquo; ".$lang->save."\"/>
                                            </form>
                                            ";
                                        }
                                    }
                                } else {
                                    echo "
                                    <div class=\"\">
                                    <b>".$mpf."</b>";
                                    if ($isad >= 8) echo" <a href=\".?ordner=".$folder."&edit=".$yeslop."\">".$lang->save."</a><br>";
                                    echo "<div id=\"".$yeslo."\" style=\"display: block;\">";
                                    $datei=fopen($yeslop .".txt","r+");
                                    while(!feof($datei)) { 
                                        $zeile = htmlentities(fgets($datei)); 
                                        echo $zeile."<br>"; 
                                    }
                                    fclose($datei);
                                    echo "</div><br></div>";
}
