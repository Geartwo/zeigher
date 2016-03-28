<?php
if (isset($_POST["wish"])) {
        $date = date("Y-m-d H:i:s");
        $wish = $_POST["wish"];
        $kat = $_POST["cat"];
        $subject = 'Neuer Wunsch wurde auf '.$name.' eingereicht';
        $message = $lang->user.': ' .$username.'
'.$lang->category.': '.$kat.'
'.$lang->wishes.': '.$wish.'

Webseite besuchen: '.$https.'://'.$name;
        $headers = "Content-type:text/plain;charset=utf-8" . "\n" . 'From: ' . $settings->mainmail . "\r\n" . 'Reply-To: ' . $settings->mainmail . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        mail($settings->mainmail, $subject, $message, $headers);
        echo "
        <h2>Dein vorschlag wurde abgeschickt. </h2>
        <input type=\"submit\" class=\"buttet\" value=\"Mehr W&uuml;nsche?\" onclick=\"self.location.href='formular.php'\" />
        ";
} else {
    echo '<h2>'.$lang->wishes.'</h2>
    <form action=".?wish" method="post">
    <select name="cat">
        <option>Film</option>
        <option>Serie</option>
        <option>Musik</option>
        <option>Radio</option>
        <option>Sonstiges</option>
    </select>
    <input name="wish" />
    <br><input type="submit" class="buttet, '.$color.'" value="Abschicken" />
    </form>';
}
?>
