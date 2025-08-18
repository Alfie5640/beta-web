<?php
    
    include('config.local.php');

    $link = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname);

    if (!$link) {
        die('Connection Issue Present');
    }

//Otherwise success - database commands can be processed


?>