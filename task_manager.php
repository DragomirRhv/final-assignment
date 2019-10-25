<?php 
    require_once 'config/session.php';
    require_once 'config/db.php';
    require_once 'config/settings.php';
    require_once 'functions.php';

    $output = '';
    if(isset($_POST['search'])) {
        $nameSearch = $_POST['nameSearch'];
        $query = "SELECT 
                    tasks.id,
                    tasks.user_id,
                    tasks.name,
                    tasks.task_description,
                    tasks.due_date,
                    task_status.status_name,
                    task_places.task_place,
                    users.first_name,
                    users.last_name
                    FROM task_status 
                    JOIN tasks 
                    ON tasks.status_id = task_status.id
                    JOIN task_places
                    ON tasks.place_id = task_places.id
                    JOIN users
                    ON tasks.user_id = users.id
                WHERE tasks.`user_id` = '".mysqli_real_escape_string($conn, $_SESSION['user']['id'])."'
                AND `name` LIKE '%".$nameSearch."%'        
        ";
        if($result = mysqli_query($conn, $query)){
            $count = mysqli_num_rows($result);
        }
        if($count === 0 ){
            $noMatch = 'There are no matching results!';
        }
        $searchShow = nameSearch($query);
        
    }else {
        $query = " SELECT 
                tasks.id,
                tasks.user_id,
                tasks.name,
                tasks.task_description,
                tasks.due_date,
                task_status.status_name,
                task_places.task_place,
                users.first_name,
                users.last_name
                FROM task_status 
                JOIN tasks 
                ON tasks.status_id = task_status.id
                JOIN task_places
                ON tasks.place_id = task_places.id
                JOIN users
                ON tasks.user_id = users.id
                WHERE tasks.`user_id` = '".mysqli_real_escape_string($conn, $_SESSION['user']['id'])."'
        ";
        $searchShow = nameSearch($query);
    }

    function nameSearch($query) {
        $server = 'localhost';
        $user = 'manager';
        $userPass = 'manager';
        $dbName= 'task_manager';
        
        $conn = mysqli_connect($server,$user,$userPass,$dbName);
        $searchResult = mysqli_query($conn, $query);
        return $searchResult;
    }
    
    /* if(isset($_REQUEST['delete'])) {
        $id = $_REQUEST['delete'];
        $sql = "DELETE FROM `".TABLE_TASKS."` 
                WHERE `id` = $id
        ";
        if($result = mysqli_query($conn, $sql)) {
            header('Location: task_manager.php');
        }
    } */
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="shortcut icon" href="assets/img/logo/list.png">
        <title>Task Manager</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/task_manager.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    </head>
    <body class="taskm">
        <div class="heading">
            <div class="logout">
                <a href="logout.php" title=""><img src="assets/img/logout.png" alt="Logout Icon" Title="Exit"><span>EXIT</span></a>
            </div>
            <h2 class="text"> 
                <span> <span class="btext">Welcome</span> <?=$_SESSION['user']['first_name']?> <?=$_SESSION['user']['last_name']?> To Your <span class="btext">Task Manager</span> !</span>
            </h2>
        </div>
        
        <form action="#" method="POST">
        <div class="wrap">
            <div class="search">
                <input type="text" name="nameSearch" class="searchTerm" placeholder="Search for Task Name">
                <button type="submit" name="search" class="searchButton">
                    <img src="assets/img/search.png" alt="Magnify Glass" title="Search Icon">
                </button>
            </div>
        </div>            
            <?php if(isset($noMatch)) :?>
                <div class="noMatch">
                    <p><?=$noMatch?></p>
                </div>
            <?php endif ?>
            <table>
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Task Name</th>
                        <th>Task Description</th>
                        <th>Due_date</th>
                        <th>Status</th>
                        <th>Place</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($row=mysqli_fetch_array($searchShow)) :?>
                        <tr>
                            <td class="num"><?=$i?></td>
                            <td><?=$row['name']?></td>
                            <td><?=$row['task_description']?></td>
                            <td class="dueD"><?=$row['due_date']?></td>
                            <td class="statn"><?=$row['status_name']?></td>
                            <td class="placet"><?=$row['task_place']?></td>
                            <td>
                                <a class="delete_task" title="Delete" data-emp-id="<?php echo $row['id']; ?>" href="javascript:void(0)">
                                <i class="glyphicon glyphicon-trash"></i>
                                </a>
                                <a href="updatetasks.php?row_id=<?php echo $row['id']; ?>"><img src="assets/img/updated.png" alt="update icon" title="Update"></a>
                            </td>                        
                            <!-- <td class="update">
                                <a href="updatetasks.php?row_id=<?php echo $row['id']; ?>"><img src="assets/img/updated.png" alt="update icon" title="Update"></a>
                            </td> -->
                        </tr>
                    <?php $i++; endwhile; ?>
                </tbody>
            </table>
        </form>
        <div class="addtask">
            <a href="addtasks.php" title="Add Task"><img src="assets/img/calendar.png" alt="Calendar icon" title="Add Task"><span>Add Task</span></a>
        </div>
        

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/bootbox.min.js"></script>
        <script type="text/javascript" src="assets/js/deleteRecords.js"></script>
    </body>
</html>