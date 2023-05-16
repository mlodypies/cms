<?php
require('./../src/config.php');


?>

<form action="" method="post" enctype="multipart/form-data">
        <label for="uploadedFileInput">
            Wybierz plik do wgrania na serwer:
        </label>
       <input type="file" name="uploadedFile" id="uploadedFileInput" required>
       <input type="submit" value="Wyslij plik" name="submit">
    </form>

    <?php
    if(isset($_POST['submit'])) {
         Post::upload($_FILES['uploadedFile']['tmp_name']);
    }

    ?>