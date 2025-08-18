<?php
    
    include('config.local.php');

    $link = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname);

    if (!$link) {
        die('Connection Issue Present');
    } else {
    echo "Connected to database successfully<br>";
}

//Otherwise success - database commands can be processed


?>