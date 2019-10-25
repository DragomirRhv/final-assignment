<?php 
    require_once 'config/session.php';
    require_once 'config/db.php';
    require_once 'config/settings.php';
    require_once 'functions.php';

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

    if(isset($_POST['addtask'])) {
        $name = '';
        if(isset($_POST['task_name'])){
            $name = trim($_POST['task_name']);
        }

        $description = $_POST['task_description'];
        /* if(isset($_POST['task_description'])){
            $description = trim();
        } */

        $date = $_POST['due_date'];
        /* if(isset($_POST['due_date'])) {
            $date = trim();
        } */

        $taskPlace = 0;
        if(isset($_POST['place'])) {
            $taskPlace = trim($_POST['place']);
        }

        $taskStatus = 0;
        if(isset($_POST['status'])) {
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

        /* Validation for due date */
        /* if(!mb_strlen($date)){
            $errors['date'] = 'Please enter a due date!';
        }elseif(ctype_alpha($date)) {
            $errors['date'] = 'There should be only numbers in due date field !';
        }elseif(mb_strlen($date) !== 10) {
            $errors['date'] = 'There should be exactly 10 symbols for due date!';
        } */
        

        /* Validation for task place */
        if($taskPlace < 1) {
            $errors['place'] = 'You did not choose a task place!';
        }

        /* Validation for task status */
        if($taskStatus < 1) {
            $errors['status'] = 'You did not choose a task status!';
        }

        if(!count($errors)) {
            $sql = " INSERT INTO `".TABLE_TASKS."` (
                        `user_id`,
                        `status_id`,
                        `place_id`,
                        `name`,
                        `task_description`,
                        `due_date`,
                        `created_at`,
                        `updated_at`
                ) VALUES (
                        '".mysqli_real_escape_string($conn, $_SESSION['user']['id'])."',
                        '".mysqli_real_escape_string($conn, $taskStatus)."',
                        '".mysqli_real_escape_string($conn, $taskPlace)."',
                        '".mysqli_real_escape_string($conn, $name)."',
                        '".mysqli_real_escape_string($conn, $description)."',
                        '".mysqli_real_escape_string($conn, $date)."',
                        NOW(),
                        NOW()
                )
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
        <link rel="shortcut icon" href="assets/img/logo/list.png">
        <title>Add Tasks</title>
        <link rel="stylesheet" href="assets/css/addtask.css">
    </head>
    <body>
        <div class="frame">
            <div class="addtask">
                <div>
                    <h2>Add Task</h2>
                </div>
                <?php if(isset($errors)) : ?>
                    <ul>
                        <?php foreach($errors as $error) :?>
                        <li><?=$error?></li>
                        <?php endforeach?>
                    </ul>
                <?php endif ?>
                <form action="#" method="POST">
                    <input type="text" name="task_name" placeholder="Name of the task">
                    <input type="text" name="task_description" placeholder="Description of the task">
                    <div class="hold">
                        <input class="date" type="date" name="due_date" placeholder="YYYY-MM-DD" required="required">
                        <select name="place" id="">
                            <option value="0">Place</option>
                            <?php foreach($places as $place) : ?>
                            <option value="<?=$place['id']?>">
                                    <?=$place['task_place']?>
                            </option>
                            <?php endforeach ?>
                        </select>
                        <select name="status" id="status">Status
                            <option value="0">Status
                                <?php foreach($status as $stat) : ?>
                                <option value="<?=$stat['id']?>">
                                    <?=$stat['status_name']?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <input id="sbm_btn_three" type="submit" name="addtask" value="Add Task">
                </form>
            </div>
        </div>
    </body>
</html>