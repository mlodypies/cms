<?php

require_once('./../src/config.php');

use Steampixel\Route;

Route::add('/', function() {
    //strona wyswietlajaca obrazki
    global $twig;
    //pobierz 10 najnowszych postow
    $postArray = Post::getPage();
    $twigData = array("postArray" => $postArray,
                         "pageTitle" => "Strona glowna");
    $twig->display("index.html.twig", $twigData);
}); 

Route::add('/upload', function() {
    //strona z formularzem do wgrywania obrazkow
    global $twig;
    $twigData = array ("pageTitle" => "Wgraj meme");
    $twig->display("upload.html.twig", $twigData);
}); 

Route::add('/upload', function() {
    global $twig;
    if(isset($_POST['submit'])) {
        Post::upload($_FILES['uploadedFile']['tmp_name']);
   }
   header("Location: http://localhost/cms/pub");
}, 'post');

Route::run('/cms/pub');
?>