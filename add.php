<!DOCTYPE HTML>
<html>
    <head>
        <title>Jackowski z5</title>
    </head>
    <body>
        <?php
            session_start();
            $time = date('H:i:s', time());
            $user = $_SESSION['username'];
            $user = htmlentities ($user, ENT_QUOTES, "UTF-8");
            $post = $_POST['post'];
            $post = htmlentities ($post, ENT_QUOTES, "UTF-8");
            if (IsSet($_POST['post']))
            { $dbhost=""; $dbuser=""; $dbpassword=""; $dbname="";
            $connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
            if (!$connection)
            {
            echo " MySQL Connection error." . PHP_EOL;
            echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Error: " . mysqli_connect_error() . PHP_EOL;
            exit;
            }
            $result = mysqli_query($connection, "INSERT INTO messages (message, user) VALUES ('$post', '$user');") or die ("DB error: $dbname");
            mysqli_close($connection);
            }
            header ('Location: index2.php');
        ?>
    </body>
</html>