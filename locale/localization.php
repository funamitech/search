<?php

function printtext($key) {
    echo TEXTS[$key];
}

function printftext() {
    $argv = func_get_args();
    $key = array_shift($argv);
    vprintf(TEXTS[$key], $argv);
}

// default to language "en"
$locale = "en";

$accept_language_header = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

if ($accept_language_header) {
    foreach(explode(",", explode(";", $accept_language_header)[0]) as $header_language) {
        if (file_exists("locale/$header_language.php")) {
            $locale = $header_language;
            break;
        }
    }
}

define("TEXTS", require "locale/$locale.php");
?>
