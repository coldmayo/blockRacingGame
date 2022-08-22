<?php 
session_start();
    include("connection.php");
    include("functions.php");

    if($_SERVER['REQUEST_METHOD']=="POST") {
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];

        if(!empty($user_name) && !empty($password)) {
            $user_id = random_num(20);
            $query = "insert into users (user_id,user_name,password) values ('$user_id','$user_name','$password')";
            
            mysqli_query($con, $query);
            header("Location: login.php");
            die;
        }
        else {
            echo("Please enter something dude I dont have all day");
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Sign up</title>
    </head>
    <body>
        <style type ="text/css">

        </style>
        <div id = "box">
            <form method="post">
                <input id = "text" type = "text" name = "user_name"><br><br>
                <input id = "text" type = "password" name = "password"><br><br>
                <input id = "button" type = "submit" value = "Signup"><br><br>
                <a href = "login.php">Click to Sign In</a><br><br>
            </form>
        </div>
    </body>
</html>