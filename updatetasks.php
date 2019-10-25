<?php   
    require_once 'config/session.php';
    require_once 'config/db.php';
    require_once 'config/settings.php';
    require_once 'functions.php';

    /* Just want to added some comment */
    
    $sql = "SELECT 
                `id`,
                `task_place`
            FROM `".TABLE_TASK_PLACES."`
    ";
    $places = [];
    if($result=mysqli_query($conn, $sql)) {
        while($row=mysqli_fetch_assoc($result)){
            $places[] = $row;
        }
    }

    $status = [];
    $sql = "SELECT
            `id`,
            `status_name`
        FROM `".TABLE_TASK_STATUS."`
    ";
    if($result=mysqli_query($conn, $sql)){
        while($row=mysqli_fetch_assoc($result)){
            $status[] = $row;
        }
    }

    $sql = " SELECT  
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
                WHERE tasks.`id` = '".mysqli_real_escape_string($conn, $_GET['row_id'])."'
    ";
    $taskInfo = '';
    if($result = mysqli_query($conn, $sql)) {
        $taskInfo = mysqli_fetch_assoc($result);
    }

    if(isset($_POST['update_task'])){
        $name = '';
        if(isset($_POST['task_name'])){
            $name = trim($_POST['task_name']);
        }

        $description = $_POST['task_description'];
        

        $date = $_POST['due_date'];

        $taskPlace = 0;
        if(isset($_POST['place'])) {
            $taskPlace = trim($_POST['place']);
        }

        $taskStatus = 0;
        if(isset($_POST['status'])){
            $taskStatus = trim($_POST['status']);
        }

        $errors = [];
        /* Validation for task name */
        if(!mb_strlen($name)) {
            $errors['name'] = 'Please write down a task name';
        }elseif(mb_strlen($name) > 32) {
            $errors['name'] = 'Task name field cannot contain more than 32 symbols!';
        }

        /* Validation for task description */
        if(!mb_strlen($description)) {
            $errors['description'] = 'Please write down description for your task!';
        }elseif(mb_strlen($description) > 300) {
            $errors['description'] = 'Description field cannot exceed 300 symbols!';
        }

        if($taskPlace < 1) {
            $errors['place'] = 'You did not choose a task place!';
        }

        /* Validation for task status */
        if($taskStatus < 1) {
            $errors['status'] = 'You did not choose a task status!';
        }

        /* Notification for changes */
        $notification = [];
        if($name !== $taskInfo['name']) {
            $notification[] = 'Task name has been changed.';
        }
        if ($description !== $taskInfo['task_description']){
            $notification[] = 'Task description has been changed.';
        }
        if ($date !== $taskInfo['due_date']) {
            $notification[] = 'Due date has been changed.';
        }
        if ($taskPlace !== $taskInfo['task_place']){
            $notification[] = 'Place field has been changed.';
        }
        if ($taskStatus !== $taskInfo['status_name']){
            $notification[] = 'Task status has been changed.';
        }
        
        if(!count($errors)) {
            $sql = " UPDATE `".TABLE_TASKS."`
                        SET
                        `status_id` = '".mysqli_real_escape_string($conn, $taskStatus)."',
                        `place_id` = '".mysqli_real_escape_string($conn, $taskPlace)."',
                        `name` = '".mysqli_real_escape_string($conn, $name)."',
                        `task_description` = '".mysqli_real_escape_string($conn, $description)."',
                        `due_date` = '".mysqli_real_escape_string($conn, $date)."',
                        `updated_at` = NOW()
                    WHERE `id` = '".mysqli_real_escape_string($conn, $_GET['row_id'])."'
            ";
            if(mysqli_query($conn, $sql)) {
                header( "Refresh:2; url=http://localhost/Practical%20Assignment/task_manager.php", true, 303);
           }else {
               echo "Something went wrong!";
           }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="assets/css/update.css">
        <link rel="shortcut icon" href="assets/img/logo/list.png">
        <title>Update Task</title>
    </head>
    <body>
        <div class="frame">
            <div class="updatetask">
                <div>
                    <h2>Update Task</h2>
                </div>
                <?php if(isset($notification)) : ?>
                    <ol>
                        <?php foreach($notification as $notify) : ?>
                        <li><?=$notify?></li>
                        <?php endforeach ?>
                    </ol>
                <?php endif ?>
                <form action="#" method="POST">
                    <input type="text" name="task_name" value="<?=$taskInfo['name']?>">
                    <input type="text" name="task_description" value="<?=$taskInfo['task_description']?>">
                    <div class="hold">
                        <input class="date" type="date" name="due_date" placeholder="YYYY-MM-DD" required="required" value="<?=$taskInfo['due_date']?>">
                        <select name="place" id="">
                            <?php foreach($places as $place) :?>
                            <option 
                            <?php if($place['task_place'] === $taskInfo['task_place']) : ?>
                            selected="selected"
                            <?php endif ?> 
                            value="<?=$place['id']?>"><?=$place['task_place']?></option>
                            <?php endforeach ?>
                        </select>
                        <select name="status" id="status_up">
                            <?php foreach($status as $stat) :?>
                            <option 
                            <?php if($stat['status_name'] === $taskInfo['status_name']) : ?> 
                            selected="selected"
                            <?php endif ?> 
                            value="<?=$stat['id']?>"><?=$stat['status_name']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>          
                    <input class="sbmt_btn_four" type="submit" name="update_task" value="Update Task">
                </form>
            </div>
        </div>
    </body>
</html>