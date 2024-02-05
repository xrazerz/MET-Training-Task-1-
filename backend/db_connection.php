<?php
    $localhost = 'localhost';
    $db_username = 'root';
    $db_password = ''; 
    $database = 'taskonemet';
    $con = mysqli_connect($localhost, $db_username, $db_password, $database);
    
    if (mysqli_connect_errno()) {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }

?>