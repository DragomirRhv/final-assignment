<?php 
    $server = 'localhost';
    $user = 'manager';
    $userPass = 'manager';
    $dbName= 'task_manager';

    $conn = mysqli_connect($server,$user,$userPass,$dbName);

    if(!$conn) {
        die('db failed');
    }


?>