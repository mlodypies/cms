<?php
class Post {
    private int $id;
    private string $filename;
    private string $timestamp;

    function __construct(int $i, string $f, string $t)
    {
        $this->id = $i;
        $this->filename = $f;
        $this->timestamp = $t;
    }

    public function getFilename() : string {
        return $this->filename;
    }
    public function getTimestamp() : string {
        return $this->timestamp;
    }

    //funkcja zwraca ostatnio dodany obrazek 
    static function getLast() : Post {
        //odwoluje sie do bazy danych
        global $db;
        //przygotuj kwerende do bazy danych
        $query = $db->prepare("SELECT * FROM post ORDER BY timestamp DESC LIMIT 1");
        //wykonaj kwerende
        $query->execute();
        //pobierz wynik
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        $p = new Post($row['id'], $row['filename'], $row['timestamp']);
        return $p;
    }

      //funkcja zwraca jedna stronę obrazków
      static function getPage(int $pageNumber = 1, int $postsPerPage = 10) : array {
        //połączenie z bazą
        global $db;
        //kwerenda
        $query = $db->prepare("SELECT * FROM post ORDER BY timestamp DESC LIMIT ? OFFSET ?");
        //oblicz przesunięcie - numer strony * ilość zdjęć na stronie
        $offset = ($pageNumber-1)*$postsPerPage;
        //podstaw do kwerendy
        $query->bind_param('ii', $postsPerPage, $offset);
        //wywołaj kwerendę
        $query->execute();
        //odbierz wyniki
        $result = $query->get_result();
        //stwórz tablicę na obiekty
        $postsArray = array();
        //pobieraj wiersz po wierszu jako tablicę asocjacyjną indeksowaną nazwami kolumn z mysql
        while($row = $result->fetch_assoc()) {
            $post = new Post($row['id'],$row['filename'],$row['timestamp']);
            array_push($postsArray, $post);
        }
        return $postsArray;
    }

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

        //uzyj globalnego polaczenia
        global $db;
        //stworz kwerende
        $query = $db->prepare("INSERT INTO post VALUES(NULL, ?, ?)");
        //przygotuj znacznik czasu dla bazy danych 
        $dbTimestamp = date("Y-m-d H:i:s");
        //zapisz dane do bazy
        $query->bind_param("ss", $dbTimestamp, $newFileName);
        if(!$query->execute())
            die("Blad zapisu do bazy danych");

    }
}



?>