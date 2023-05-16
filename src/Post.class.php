<?php
class Post {
    static function upload(string $tempFileName) {
        //deklarujemy folder do ktorego beda zaczytywane obrazy
        $targetDir = "img/";
        //sprawdz czyx mamy doczynienia z obrazem
        $imgInfo = getimagesize($tempFileName);
        //jezeli imginfo nie jest tablica to nie jest to obraz
        if(!is_array($imgInfo)) {
            die("BLAD: Przekazany plik nie jest obrazem");
        }
        //generujemy losowa liczbe w formie 5 losowych cyfr
        $randomNumber = rand(10000, 99999) . hrtime(true);

        //wygeneruj hash - nowa nazwe pliku
        $hash = hash("sha256", $randomNumber);

        //tworzymy docelowy url pliku graficznego 
        $newFileName = $targetDir . $hash . ".webp";

        //sprawdzy czy plik juz nie istnieje
        if(file_exists($newFileName)) {
            die("BLAD: podany plik juz istnieje");
        }
        //zaczytyjemy caly obraz z folderu tymczasowego do stringa                    
        $imageString = file_get_contents($tempFileName);
        //generujemy obraz jako obiekt klasy do GDImage
        $gdImage = @imagecreatefromstring($imageString);
        //zapisujemy w foramie webp
        imagewebp($gdImage, $newFileName);
    }
}



?>