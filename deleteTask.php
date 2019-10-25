<?php 
    require_once 'config/session.php';
    require_once 'config/db.php';
    require_once 'config/settings.php';
    require_once 'functions.php';


    if($_REQUEST['empid']) {
        $sql = "DELETE FROM 
                    `".TABLE_TASKS."`
         WHERE `id`= '".$_REQUEST['empid']."' LIMIT 1
         ";
        $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
        if($resultset) {
            echo "Record Deleted";   
            }
        }

?>