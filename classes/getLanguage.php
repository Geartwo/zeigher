<?php
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
