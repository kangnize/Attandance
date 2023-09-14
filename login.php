<?php
    
    session_start();
    require 'server.php';
    $db = new db();
    
    $db->redirect_user();
    
    if(isset($_POST["submit"])){
        $email = $_POST['user'];
        $password = $_POST['pass'];
        
        $email = $db->clean_input($email);
        $password = $db->clean_input($password);
       
        $password = md5($password);

        if($db->user_exists($email, $password)){
            $user_id = $db->get_userid($email);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;

            $db->redirect_user($email);
            
        } else{
            echo('<script> alert("Login failed. Invalid username or password!!!")</script>');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="styles/index.css">
    <style>
        body {
            background-image: url("images/bri.jpg");
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <span class="icon-close"><ion-icon name="close"></ion-icon>
        </span>
        <div class="form-box login">
            <h2>Admin Login</h2>
            <form name="form" action="" method="POST">
                <div class="input-box">
                    <span class="icon"></span>
                    <input type="email" id="user" name="user" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon"></span>
                    <input type="password" id="pass" name="pass" required>
                    <label>Password</label>
                </div>
                <button type="submit" name="submit" class="btn">Login</button>
            </form>

        </div>

    </div>
</body>
</html>