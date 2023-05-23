<?php

require_once('./../src/config.php');
session_start();

use Steampixel\Route;

Route::add('/', function() {
    //strona wyswietlajaca obrazki
    global $twig;
    //pobierz 10 najnowszych postow
    $postArray = Post::getPage();
    $twigData = array("postArray" => $postArray,
                         "pageTitle" => "Strona glowna",
                         );
    if(isset($_SESSION['user']))
         $twigData['user'] = $_SESSION['user'];
    $twig->display("index.html.twig", $twigData);
}); 

Route::add('/upload', function() {
    //strona z formularzem do wgrywania obrazkow
    global $twig;
    $twigData = array ("pageTitle" => "Wgraj meme");
    if(isset($_SESSION['user']))
         $twigData['user'] = $_SESSION['user'];
    $twig->display("upload.html.twig", $twigData);
}); 

Route::add('/upload', function() {
    global $twig;
    if(isset($_POST['submit'])) {
        Post::upload($_FILES['uploadedFile']['tmp_name'], $_POST['title'], $_POST['userId']);
   }
   header("Location: http://localhost/cms/pub");
}, 'post');

Route::add('/register', function() {
   global $twig;
   $twigData = array("pageTitle" => "Zarejestruj uzytkownika");
   $twig->display("register.html.twig", $twigData);
});

Route::add('/register', function() {
    global $twig;
    if(isset($_POST['submit'])) {
       User::register($_POST['email'], $_POST['password']);
       header("Location: http://localhost/cms/pub");
    }
}, 'post');

Route::add('/login', function() {
    global $twig;
    $twigData = array("pageTitle" => "Zaloguj uzytkownika");
    $twig->display("login.html.twig", $twigData);
});

Route::add('/login', function() {
    global $twig;
    if(isset($_POST['submit'])) {
       User::login($_POST['email'], $_POST['password']);
    }
    header("Location: http://localhost/cms/pub");
}, 'post');


Route::run('/cms/pub');
?>