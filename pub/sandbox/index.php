<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="uploadedFileInput">
            Wybierz plik do wgrania na serwer:
        </label>
       <input type="file" name="uploadedFile" id="uploadedFileInput">
       <input type="submit" value="Wyslij plik" name="submit">
    </form>

    <?php
    if(isset($_POST['submit']))
    {
        //zdefiniuj folder do ktorego trafia pliki 
        $targetDir = "img/";

        //pobierz pierwotna naze plisku z tablicy 

        $sourceFileName = $_FILES['uploadedFile']['name'];

        //pobierz tymczasowa sciezke do pliku na serwerze 
        $tempURL = $_FILES['uploadedFile']['tmp_name'];

        //sprawdz czyx mamy doczynienia z obrazem
        $imgInfo = getimagesize($tempURL);
        if(!is_array($imgInfo)) {
            die("BLAD: Przekazany plik nie jest obrazem");
        }

        //zbuduj docelowy URL pliku na serwerze

        $targetURL = $targetDir . $sourceFileName;
        if(file_exists($targetURL)) {
            die("BLAD: podany plik juz istnieje");
        }
        //przesun plik do docelowej lokalizacji 
        move_uploaded_file($tempURL, $targetURL);
        echo "Plik zostal poprawnie wgrany na serwer";
    }
    ?>
</body>
</html>