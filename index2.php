<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mariusz Jackowski z5</title>
</script>
</HEAD>
<BODY>
    <?php    
        session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
        if (!$_SESSION ['loggedin'] == "true")
        {
            $_SESSION ['error'] = "Nie zalogowano";
            header('Location: index.php');
            exit();
        }
        echo "<div style=\"position:absolute;right:10px;top:10px;font-size:18px;font-weight:bold;\"><a href=\"logout.php\">Log out</a></div>";
        print "Username: ".$_SESSION['username'];
    ?>
    <form method="POST" action="add.php">
        Post:<br><input type="text" name="post" maxlength="90" size="90"><br>
        <input type="submit" value="Send"/>
    </form>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <?php echo "<p style=\"color:red;font-size:15px;\">".$_SESSION['error']."</p>"; $_SESSION['error']="";?>
        Select file to upload (max 25 MB):<input type="file" name="fileToUpload" id="fileToUpload"><br> 
        <input type="submit" value="Upload" name="submit"> 
    </form>
    <?php
        session_start();
        $dbhost=""; $dbuser=""; $dbpassword=""; $dbname="";
        $connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
        if (!$connection)
        {
            echo " MySQL Connection error." . PHP_EOL;
            echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        $result = mysqli_query($connection, "Select * from messages ORDER BY datetime DESC LIMIT 5 ") or die ("DB error: $dbname");
        print "<TABLE CELLPADDING=5 BORDER=1>";
        print "<TR><TD>Date/Time</TD><TD>User</TD><TD>Message</TD></TR>\n";
        while ($row = mysqli_fetch_array ($result))
        {
            $id = $row[0];
            $user = $row[3];
            $date = $row[1];
            $message= $row[2];
            $file = $row[4];
            print "<TR>";
            print "<TD>$date</TD>";
            print "<TD>$user</TD>";
            print "<TD>";
            if($file){
                $extension = pathinfo($message, PATHINFO_EXTENSION);
                $filename = pathinfo($message, PATHINFO_FILENAME);
                $filenameafterconv = $filename."_CONV_TO_mp4";
                $dirname = pathinfo($message, PATHINFO_DIRNAME);
                $target_file_after_conv = $dirname."/".$filenameafterconv.".mp4";
                switch ($extension){
                    case "jpg":
                    case "gif":
                    case "png":
                        print "<img src=\"$message\" alt=\"Zdjęcie\" style=\"min-width:180px;max-width:360px;min-height:180px;max-height:640px;\">";
                        break;
                    case "mp3":
                    case "wav":
                        print "<audio id=\"audio\" muted autoplay controls>
                        <source src=$message>
                        Your browser does not support the audio element.
                      </audio>";
                        break;
                    case "mp4":
                        print "<video controls muted autoplay style=\"background:black;min-width:180px;max-width:360px;min-height:180px;max-height:640px;\">
                        <source src=\"$message\" type=\"video/mp4\">
                      Your browser does not support the video tag.
                      </video>";
                        break;
                    case "avi":
                        if(file_exists($target_file_after_conv)){
                            print "<video controls muted autoplay style=\"background:black;min-width:180px;max-width:360px;min-height:180px;max-height:640px;\">
                            <source src=\"$target_file_after_conv\" type=\"video/mp4\">
                          Your browser does not support the video tag.
                          </video>";
                        }else{
                            exec("ffmpeg -i $message -c:a copy -c:v vp9 -b:v 100K $filenameafterconv");
                            print "<video controls muted autoplay style=\"background:black;min-width:180px;max-width:360px;min-height:180px;max-height:640px;\">
                            <source src=\"$target_file_after_conv\" type=\"video/mp4\">
                          Your browser does not support the video tag.
                          </video>";
                        }
                        break;
                }
            }else{
                print "$message";
            }
            // print "$filename";
            print "</TD></TR>\n";
        }
        print "</TABLE>";
        mysqli_close($connection);
    ?>
    <script>document.getElementById("audio").play();</script>
    <br><br>
    <form method="POST" action="testconv.php">
        <input type="submit" value="textconv" name="submit"> 
    </form>
</BODY>
</HTML>