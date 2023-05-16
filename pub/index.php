<?php

require_once('./../src/config.php');

use Steampixel\Route;

Route::add('/', function() {
    //strona wyswietlajaca obrazki
    global $twig;
    $twig->display("index.html.twig");
}); 

Route::add('/upload', function() {
    //strona z formularzem do wgrywania obrazkow
    global $twig;
    $twig->display("upload.html.twig");
}); 

Route::run('/cms/pub');
?>