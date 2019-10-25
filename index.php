<?php
    require_once 'config/session.php';
    require_once 'config/db.php';
    require_once 'config/settings.php';
    require_once 'functions.php';

    if(isset($_POST['signup'])) {
        
        $firstName = '';
        if(isset($_POST['first_name'])) {
            $firstName = trim($_POST['first_name']);
        }

        $lastName = '';
        if(isset($_POST['last_name'])) {
            $lastName = trim($_POST['last_name']);
        }

        $email = '';
        if(isset($_POST['email'])){
            $email = trim($_POST['email']);
        }

        $age = 0;
        if(isset($_POST['age'])){
            $age = trim($_POST['age']);
        }

        $pass = '';
        if(isset($_POST['password'])) {
            $pass = trim($_POST['password']);
        }

        $rePass = '';
        if(isset($_POST['re_password'])) {
            $rePass = trim($_POST['re_password']);
        }

        $errors = [];
        
        /* Validation for First Name */
        if(!mb_strlen($firstName)){
            $errors['first_name'] = 'First Name field cannot be empty!';
        }elseif (mb_strlen($firstName) > 32) {
            $errors['first_name'] = 'First Name cannot exceed 32 symbols!';
        }elseif(preg_match('/[0-9]+/', $firstName)) {
            $errors['first_name'] = 'First Name cannot contain a number!';
        }

        /* Validation for surname */
        if(!mb_strlen($lastName)) {
            $errors['last_name'] = 'Last Name field cannot be empty!';
        }elseif (mb_strlen($lastName) > 32) {
            $errors['last_name'] = 'Last Name cannot exceed 32 symbols!';
        }elseif(preg_match('/[0-9]+/', $lastName)){
            $errors['last_name'] = 'Last Name cannot contain a number!';
        }

        /* Validation for email */
        if(!mb_strlen($email)) {
            $errors['email'] = 'Please enter an Email Address!';
        }elseif (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid Email Address!';
        }else {
            $sql = "SELECT
                    `id`
                FROM `".TABLE_USERS."`
                WHERE `email` = '".mysqli_real_escape_string($conn, $email)."'
            ";
            if($result = mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result)) {
                    $errors['email'] = 'This Email Address already exists!';
                }
            }
        }

        /* Validation for age */
        if($age < 1) {
            $errors['age'] = 'Please enter a valid value for age!';
        }elseif($age > 100) {
            $errors['age'] = 'Unfortunatelly our web site is not allowed for users over 100 years!';
        }

        /* Validation for password */
        if(mb_strlen($pass) === 0) {
            $errors['password'] = "Please enter a password!";
        }elseif(mb_strlen($pass) < 8) {
            $errors['password'] = 'Your password must contain at least 8 symbols!';
        }elseif($pass !== $rePass) {
            $errors['password'] = 'Your passwords did not match!';
        }

        /* For successfull registration */
        if(!count($errors)){
            $password = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `".TABLE_USERS."` (
                        `first_name`,
                        `last_name`,
                        `email`,
                        `age`,
                        `password`,
                        `created_at`,
                        `updated_at`
            ) VALUES (
                        '".mysqli_real_escape_string($conn, $firstName)."',
                        '".mysqli_real_escape_string($conn, $lastName)."',
                        '".mysqli_real_escape_string($conn, $email)."',
                        '".mysqli_real_escape_string($conn, $age)."',
                        '".mysqli_real_escape_string($conn, $password)."',
                        NOW(),
                        NOW()
                )
            ";
            if(mysqli_query($conn,$sql)) {
                header('Location: signin.php');
            }else {
                echo "Something went wrong!";
            }
        }
        /* showArray($errors); */
    }

    if(isset($_POST['signin'])) {

        $email = '';
        if(isset($_POST['email'])) {
            $email = trim($_POST['email']);
        }

        $pass = '';
        if(isset($_POST['password'])) {
            $pass = trim($_POST['password']);
        }

        $errors = [];
        $user = [];

        if(!mb_strlen($email)) {
            $errors['email'] = 'Please enter an Email Address!';
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid Email Address!';
        }else {
            $sql = "SELECT 
                    `id`,
                    `first_name`,
                    `last_name`,
                    `email`,
                    `age`,
                    `password`,
                    `created_at`,
                    `updated_at`
                FROM `".TABLE_USERS."`
                WHERE `email` = '".mysqli_real_escape_string($conn, $email)."'
            ";
            if($result = mysqli_query($conn, $sql)){
                $user = mysqli_fetch_assoc($result);
            }
        }

        if(!mb_strlen($pass)) {
            $errors['password'] = 'Please enter a password!';
        }elseif(mb_strlen($pass) < 8){
            $errors['password'] = 'Password must contain at least 8 symbols!';
        }

        if(empty($user)){
            $errors['user'] = 'There is no such a user!';
        }

        if(!count($errors)) {
            if(password_verify($pass, $user['password'])) {
                $_SESSION['user'] = $user;
                unset($_SESSION['user']['password']);
                header('Location: task_manager.php');
            }else {
                $errors['password'] = 'Incorrect Password!';
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
        <title>Login Page</title>
        <link rel="stylesheet" href="assets/css/app.css">
    </head>
    <body>
        <header>
            <div>
                <div>
                    <span class="invsible">Welcome To</span>
                    <p class="clickIT">Task Manager</p>
                </div>
            </div>
        </header>
        <section class="signin-hole">
            <?php if(isset($errors)) : ?>
                <ol>
                    <?php foreach($errors as $error) : ?>
                    <li><?=$error?></li>
                    <?php endforeach ?>
                </ol>
            <?php endif ?>
            <div class="frame">
                <div class="triangle"></div>
                <div class="signin">
                    <form action="#" method="POST">
                        <p>Sign In</p>
                        <input type="text" name="email" placeholder="Email Address">
                        <input type="password" name="password" placeholder="Password">
                        <input id="submit_btn_one" type="submit" name="signin" value="Sign In">
                        <a href="">Forgot password?</a>
                    </form>
                    <div id="line1"></div>
                    <p id="social-p">Or Sign in With</p>
                    <div id="line2"></div>
                    <div class="social_icons">
                        <div class="holders">
                            <div class="twtr-holder"><a href="#"><img src="assets/img/twitter.png" alt="Twitter Logo" title="Twitter"><span id="twit">Twitter</span></a></div>
                        </div>
                        <div class="holders">
                            <div class="face-holder"><a href="#"><img src="assets/img/facebook.png" alt="Facebook Logo" title="Facebook"><span id="face">Facebook</span></a></div>
                        </div>
                        <div class="holders">
                            <div class="googl-holder"><a href="#"><img src="assets/img/google.png" alt="Google Logo" title="Google"><span id="goog">Google</span></a></div>
                        </div>
                    </div>
                </div>
                <div class="signup">
                    <form action="" method="POST">
                        <p>Sign Up</p>
                        <input type="text" name="first_name" placeholder="First Name">
                        <input type="text" name="last_name" placeholder="Last Name">
                        <input type="text" name="email" placeholder="Email Address">
                        <input type="text" name="age" placeholder="Age">
                        <input type="password" name="password" placeholder="Password">
                        <input type="password" name="re_password" placeholder="Confirm Password">
                        <input class="form_two" id="submit_btn_two" type="submit" name="signup" value="Sign Up">
                        <p>By creating an account, you agree with our <a href="">terms</a>!</p>
                    </form>
                </div>
            </div>
        </section>

        <script type="text/javascript" src="assets/js/jquery-3.4.1.js"></script>
		<script type="text/javascript" src="assets/js/app.js"></script>
    </body>
</html>