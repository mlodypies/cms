<?php

require_once('./../src/config.php');

use Steampixel\Route;

Route::add('/', function() {
    echo "Strona glowna";
}); 

Route::run('/cms/pub');
?>