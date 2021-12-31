<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mariusz Jackowski z5</title>
</HEAD>
<BODY>
    <?php
        $dbhost=""; $dbuser=""; $dbpassword=""; $dbname="";
        $link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
        if(!$link) { echo "Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
        mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków

        session_start();
        $target_dir = $_SESSION['username']; 
        $target_file = "users/".$target_dir."/".basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1; 
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); 
        
        // Check if file already exists 
        if (file_exists($target_file)) { 
            echo "Plik już istnieje.";
            $_SESSION['error'] = "Plik juz istnieje";
            $uploadOk = 0; 
        } 
        
        // Check file size 
        if ($_FILES["fileToUpload"]["size"] > (1048576*25)) {
            echo "Plik jest za duży.";
            $_SESSION['error'] = "Plik jest za duży.";
            $uploadOk = 0;
        } 
        
        // Allow certain file formats 
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" && $imageFileType != "mp3" && $imageFileType != "wav" && $imageFileType != "mp4" && $imageFileType != "avi") 
        { 
            echo "Tylko pliki jpg, png, gif, mp3, wav, mp4, avi.";
            $_SESSION['error'] = "Tylko pliki jpg, png, gif, mp3, wav, mp4, avi.";
            $uploadOk = 0; 
        }

        
        // Check if $uploadOk is set to 0 by an error 
        if ($uploadOk == 0) { 
            echo "Nie przesłano";
            header('Location: index2.php');
            exit();
        } else// if everything is ok, try to upload file 
        { 
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
            {
                echo "Plik ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " przesłano.";
                $_SESSION ['error'] = "Plik ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " przesłano. "/*.$target_file*/;
                $result = mysqli_query($link, "INSERT INTO messages (message, user , File) VALUES ('$target_file', '$target_dir', 1);") or die ("DB error: $dbname");
                mysqli_close($link);
                header('Location: index2.php');
                exit();
            } else { 
                echo "Doszło do błędu przy przesyłaniu.";
                $_SESSION ['error'] = "Doszło do błędu przy przesyłaniu.";

                header('Location: index2.php');
                exit();
            }
        } 
    ?>
</BODY>
</HTML>